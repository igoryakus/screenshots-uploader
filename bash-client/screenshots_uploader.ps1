# Настройки
$WATCH_DIR = "C:\path\to\your\Screenshots"
$REMOTE_USER = "<vps_sudo_user>"
$REMOTE_HOST = "<vps_ip_address>"
$REMOTE_PORT = "22"
$REMOTE_DIR = "/var/www/html/screenshots-uploader/files/"
$SSH_KEY = "C:\path\to\your\private_key"
$BASE_URL = "http://<your_domain_or_ip>/screenshots-uploader/"

# Функция обработки файла
function Process-File($filePath) {
    if (-not (Test-Path $filePath)) { return }
    
    # Ждем немного, чтобы файл дозаписался
    Start-Sleep -Milliseconds 500
    
    $file = Get-Item $filePath
    $base = $file.Name
    $ext = $file.Extension
    $name = $file.BaseName
    
    # ЗАЩИТА ОТ ДУБЛИРОВАНИЯ (проверка на наличие хеша в имени)
    if ($name -match "_[a-f0-9]{20}$") {
        return
    }

    # Генерация соли (аналог md5 | cut -c 1-20)
    $bytes = [System.Text.Encoding]::UTF8.GetBytes((Get-Date).Ticks.ToString() + (Get-Random).ToString())
    $hash = [System.Security.Cryptography.MD5]::Create().ComputeHash($bytes)
    $salt = ([System.BitConverter]::ToString($hash).Replace("-", "").ToLower()).Substring(0, 20)
    
    $newName = "${name}_${salt}${ext}"
    $newNameWithoutExt = "${name}_${salt}"
    $newPath = Join-Path $file.DirectoryName $newName
    
    try {
        Move-Item -Path $filePath -Destination $newPath -ErrorAction Stop
    } catch {
        # Если файл занят другим процессом, просто выходим
        return
    }
    
    $url = "${BASE_URL}${newNameWithoutExt}"
    Set-Clipboard -Value $url
    
    # Уведомление (Windows Notification)
    $wshell = New-Object -ComObject WScript.Shell
    $wshell.Popup("Ссылка скопирована в буфер обмена`n$url", 10, "Скриншот загружен", 64) | Out-Null

    # Загрузка через scp (OpenSSH обычно предустановлен в Windows 10/11)
    Start-Process -FilePath "scp" -ArgumentList "-P $REMOTE_PORT", "-i `"$SSH_KEY`"", "`"$newPath`"", "${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_DIR}" -WindowStyle Hidden
}

Write-Host "Monitoring $WATCH_DIR..."

# Мониторинг папки
$fsw = New-Object System.IO.FileSystemWatcher
$fsw.Path = $WATCH_DIR
$fsw.Filter = "*.*"
$fsw.IncludeSubdirectories = $false
$fsw.EnableRaisingEvents = $true

$action = {
    $path = $Event.SourceEventArgs.FullPath
    $changeType = $Event.SourceEventArgs.ChangeType
    if ($changeType -eq "Created") {
        Process-File $path
    }
}

Register-ObjectEvent $fsw Created -Action $action

while ($true) {
    Start-Sleep -Seconds 1
}
