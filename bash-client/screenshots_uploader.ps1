# Настройки
# Папка в которую делаются скриншоты
$WATCH_DIR = "C:\path\to\your\Screenshots"
# IP сервера (облака) на который будут заливаться файлы
$REMOTE_HOST = "<vps_ip_address>"
# SSH порт (по умолчанию 22)
$REMOTE_PORT = "22"
# Пользователь vps с правами sudo, например, root
$REMOTE_USER = "<vps_sudo_user>"
# Путь на сервере к папке screenshots-uploader/files/
$REMOTE_DIR = "/var/www/html/screenshots-uploader/files/"
# Путь на Вашем компьютере к ключу
$SSH_KEY = "C:\path\to\your\private_key"
# Адрес для создания ссылки. Домен или IP с папкой /screenshots-uploader/
$BASE_URL = "http://<your_domain_or_ip>/screenshots-uploader/"

# Транслитерация
$translitTable = @{
    'а'='a'; 'б'='b'; 'в'='v'; 'г'='g'; 'д'='d'; 'е'='e'; 'ё'='e'; 'ж'='zh'; 'з'='z'; 'и'='i'; 'й'='j'; 'к'='k'; 'л'='l'; 'м'='m'; 'н'='n'; 'о'='o'; 'п'='p'; 'р'='r'; 'с'='s'; 'т'='t'; 'у'='u'; 'ф'='f'; 'х'='h'; 'ц'='c'; 'ч'='ch'; 'ш'='sh'; 'щ'='shh'; 'ъ'=''; 'ы'='y'; 'ь'=''; 'э'='e'; 'ю'='yu'; 'я'='ya';
    'А'='A'; 'Б'='B'; 'В'='V'; 'Г'='G'; 'Д'='D'; 'Е'='E'; 'Ё'='E'; 'Ж'='ZH'; 'З'='Z'; 'И'='I'; 'Й'='J'; 'К'='K'; 'Л'='L'; 'М'='M'; 'Н'='N'; 'О'='O'; 'П'='P'; 'Р'='R'; 'С'='S'; 'Т'='T'; 'У'='U'; 'Ф'='F'; 'Х'='H'; 'Ц'='C'; 'Ч'='CH'; 'Ш'='SH'; 'Щ'='SHH'; 'Ъ'=''; 'Ы'='Y'; 'Ь'=''; 'Э'='E'; 'Ю'='YU'; 'Я'='YA'
}

function Get-CleanName ($name) {
    $result = ""
    foreach ($char in $name.ToCharArray()) {
        if ($translitTable.ContainsKey($char.ToString())) {
            $result += $translitTable[$char.ToString()]
        } elseif ($char -match '[a-zA-Z0-9._-]') {
            $result += $char
        } else {
            $result += '_'
        }
    }
    return $result
}

function Process-File ($filePath) {
    # Ждем, пока файл допишется
    Start-Sleep -Milliseconds 500
    
    if (-not (Test-Path $filePath)) { return }

    $file = Get-Item $filePath
    $baseName = $file.Name
    $dir = $file.DirectoryName
    $ext = $file.Extension # Включает точку (например .png)
    $nameOnly = $file.BaseName

    # ЗАЩИТА ОТ ДУБЛИРОВАНИЯ
    if ($baseName -match '_[a-f0-9]{20}\.[a-zA-Z0-9]+$') {
        return
    }

    $cleanName = Get-CleanName $nameOnly
    
    # Генерация соли (20 символов md5)
    $random = New-Object System.Random
    $seed = [string](Get-Date -UFormat %s) + [string]$random.Next(0, 10000)
    $md5 = [System.Security.Cryptography.MD5]::Create()
    $hashBytes = $md5.ComputeHash([System.Text.Encoding]::UTF8.GetBytes($seed))
    $salt = [System.BitConverter]::ToString($hashBytes).Replace("-", "").ToLower().Substring(0, 20)

    $newNameWithoutExt = "${cleanName}_${salt}"
    $newName = "${newNameWithoutExt}${ext}"
    $newPath = Join-Path $dir $newName

    try {
        Move-Item -Path $filePath -Destination $newPath -ErrorAction Stop
    } catch {
        Write-Host "$(Get-Date): Ошибка переименования: $($_.Exception.Message)"
        return
    }

    $url = "${BASE_URL}${newNameWithoutExt}"
    Set-Clipboard -Value $url

    # Уведомление (Windows Popup)
    $wshell = New-Object -ComObject WScript.Shell
    $button = $wshell.Popup("Ссылка скопирована в буфер обмена`n$url", 10, "Скриншот загружен", 1 + 64)
    # 1 = OK and Cancel, 64 = Information icon. If Cancel (2) or OK (1)
    if ($button -eq 1) {
        # В Windows сложно просто "открыть локацию" как в macOS, но можно запустить браузер
        Start-Process $url
    }

    # Загрузка через scp
    # В Windows scp обычно есть в составе OpenSSH
    $scpArgs = @("-P", $REMOTE_PORT, "-i", $SSH_KEY, $newPath, "${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_DIR}")
    
    & scp @scpArgs 2>$null
    if ($LASTEXITCODE -eq 0) {
        Write-Host "$(Get-Date): Успешно загружено: $newName"
    } else {
        Write-Host "$(Get-Date): ОШИБКА ЗАГРУЗКИ ($LASTEXITCODE): $newName"
    }
}

Write-Host "Мониторинг папки: $WATCH_DIR"

$watcher = New-Object System.IO.FileSystemWatcher
$watcher.Path = $WATCH_DIR
$watcher.Filter = "*.*"
$watcher.EnableRaisingEvents = $true

$action = {
    $path = $Event.SourceEventArgs.FullPath
    $changeType = $Event.SourceEventArgs.ChangeType
    Write-Host "$(Get-Date): Событие $changeType: $path"
    Process-File $path
}

# Регистрируем события
$handlers = @()
$handlers += Register-ObjectEvent $watcher "Created" -Action $action
$handlers += Register-ObjectEvent $watcher "Changed" -Action $action
$handlers += Register-ObjectEvent $watcher "Renamed" -Action $action

# Бесконечный цикл для работы скрипта
try {
    while ($true) {
        Start-Sleep -Seconds 1
    }
} finally {
    # Очистка при выходе
    $watcher.EnableRaisingEvents = $false
    foreach ($h in $handlers) {
        Unregister-Event -SourceIdentifier $h.Name
    }
    $watcher.Dispose()
}
