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
    
    salt=$(echo "$(date +%s)$RANDOM" | md5 | cut -c 1-20)
    new_name="${name}_${salt}.${ext}"
    new_name_without_ext="${name}_${salt}"
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
    
    # Загрузка в фоне
    scp -P "$REMOTE_PORT" -i "$SSH_KEY" "$new_path" "${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_DIR}" > /dev/null 2>&1 &
}

# Мониторинг через fswatch
fswatch -0 "$WATCH_DIR" | while read -d "" event; do
    # fswatch может выдавать события на директорию, проверяем что это файл и он существует
    if [ -f "$event" ]; then
        process_file "$event"
    fi
done
