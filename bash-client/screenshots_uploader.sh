#!/bin/bash

# Настройка PATH для корректной работы fswatch и других утилит через LaunchAgent
export PATH="/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin"

# Настройки
# Папка в которую делаются скриншоты
WATCH_DIR="/path/to/your/Screenshots"
# IP сервера (облака) на который будут заливаться файлы
REMOTE_HOST="<vps_ip_address>"
# SSH порт (по умолчанию 22)
REMOTE_PORT="22"
# Пользователь vps с правами sudo, например, root
REMOTE_USER="<vps_sudo_user>"
# Путь на сервере к папке screenshots-uploader/files/
REMOTE_DIR="/var/www/html/screenshots-uploader/files/"
# Путь на Вашем компьютере к ключу, тот что в папке ~/.ssh/ без расширения .pub
SSH_KEY="/path/to/your/private_key"
# Адрес для создания ссылки. Домен или IP с папкой /screenshots-uploader/
BASE_URL="http://<your_domain_or_ip>/screenshots-uploader/"

# Функция обработки файла
process_file() {
    local f="$1"
    
    # Ждем, пока файл допишется (Flameshot может сохранять его не мгновенно)
    sleep 0.2
    
    [ -f "$f" ] || return

    base=$(basename "$f")

    # ЗАЩИТА ОТ ДУБЛИРОВАНИЯ
    if [[ "$base" =~ _[a-f0-9]{20}\.[a-zA-Z0-9]+$ ]]; then
        return
    fi

    dir=$(dirname "$f")
    ext="${base##*.}"
    name="${base%.*}"
    
    # Транслитерация кириллицы в латиницу
    clean_name=$(echo "$name" | sed '
        s/а/a/g; s/б/b/g; s/в/v/g; s/г/g/g; s/д/d/g; s/е/e/g; s/ё/e/g; s/ж/zh/g; s/з/z/g; s/и/i/g; s/й/j/g; s/к/k/g; s/л/l/g; s/м/m/g; s/н/n/g; s/о/o/g; s/п/p/g; s/р/r/g; s/с/s/g; s/т/t/g; s/у/u/g; s/ф/f/g; s/х/h/g; s/ц/c/g; s/ч/ch/g; s/ш/sh/g; s/щ/shh/g; s/ъ//g; s/ы/y/g; s/ь//g; s/э/e/g; s/ю/yu/g; s/я/ya/g;
        s/А/A/g; s/Б/B/g; s/В/V/g; s/Г/G/g; s/Д/D/g; s/Е/E/g; s/Ё/E/g; s/Ж/ZH/g; s/З/Z/g; s/И/I/g; s/Й/J/g; s/К/K/g; s/Л/L/g; s/М/M/g; s/Н/N/g; s/О/O/g; s/П/P/g; s/Р/R/g; s/С/S/g; s/Т/T/g; s/У/U/g; s/Ф/F/g; s/Х/H/g; s/Ц/C/g; s/Ч/CH/g; s/Ш/SH/g; s/Щ/SHH/g; s/Ъ//g; s/Ы/Y/g; s/Ь//g; s/Э/E/g; s/Ю/YU/g; s/Я/YA/g;
        s/[^a-zA-Z0-9._-]/_/g
    ')
    
    salt=$(echo "$(date +%s)$RANDOM" | md5 | cut -c 1-20)
    new_name="${clean_name}_${salt}.${ext}"
    new_name_without_ext="${clean_name}_${salt}"
    new_path="${dir}/${new_name}"
    
    mv "$f" "$new_path"
    
    url="${BASE_URL}${new_name_without_ext}"
    echo -n "$url" | pbcopy
    
    # Модальное окно
    osascript -e "
        tell application \"System Events\"
            activate
            try
                set dialog_result to display dialog \"Ссылка скопирована в буфер обмена\" with title \"Скриншот загружен\" buttons {\"ОК\", \"Показать\"} default button \"Показать\" giving up after 10
                if button returned of dialog_result is \"Показать\" then
                    open location \"$url\"
                end if
            on error
                return
            end try
        end tell" &
    
    # Загрузка
    scp -P "$REMOTE_PORT" -i "$SSH_KEY" "$new_path" "${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_DIR}" 2>/dev/null
    scp_status=$?
    
    if [ $scp_status -eq 0 ]; then
        echo "$(date): Успешно загружено: $new_name"
    else
        echo "$(date): ОШИБКА ЗАГРУЗКИ ($scp_status): $new_name"
    fi
}

# Мониторинг через fswatch
# Используем poll_monitor для надежности на macOS
fswatch -x -m poll_monitor --latency 1 "$WATCH_DIR" | while read -r event_data; do
    # fswatch с флагом -x выдает "путь флаги" разделенные пробелом
    event=$(echo "$event_data" | awk '{print $1}')
    flags=$(echo "$event_data" | cut -d' ' -f2-)
    
    # Если событие произошло в папке, а не с конкретным файлом
    if [ "$event" == "$WATCH_DIR" ] || [ "$event" == "${WATCH_DIR}/" ]; then
        # Находим файлы, которые НЕ содержат паттерн хеша и не являются скрытыми
        found_files=$(find "$WATCH_DIR" -maxdepth 1 -type f ! -name ".*")
        if [ -n "$found_files" ]; then
            echo "$found_files" | while read -r new_file; do
                 base=$(basename "$new_file")
                 # Игнорируем системные файлы и .DS_Store
                 if [[ "$base" == .* || "$base" == *".DS_Store"* ]]; then
                     continue
                 fi
                 # Обрабатываем только те, в которых НЕТ хеша
                 if [[ ! "$base" =~ _[a-f0-9]{20}\.[a-zA-Z0-9]+$ ]]; then
                     process_file "$new_file"
                 fi
            done
        fi
        continue
    fi

    # Игнорируем события удаления
    if [[ "$flags" == *"Removed"* || "$flags" == *"Deleted"* ]]; then
        # Но проверяем, не является ли это одновременно созданием/перемещением
        if [[ "$flags" != *"Created"* && "$flags" != *"MovedTo"* && "$flags" != *"Renamed"* ]]; then
            continue
        fi
    fi

    # Для Renamed или MovedTo проверяем существование
    if [[ "$flags" == *"Renamed"* || "$flags" == *"MovedTo"* ]]; then
        if [ ! -f "$event" ]; then
            continue
        fi
    fi

    # Игнорируем скрытые файлы и .DS_Store
    base=$(basename "$event")
    if [[ "$base" == .* || "$base" == *".DS_Store"* ]]; then
        continue
    fi

    # Если это файл, обрабатываем его
    if [ -f "$event" ]; then
        process_file "$event"
    fi
done
