#!/bin/bash
# Собирает ShotHost.app (универсальный: Apple Silicon и Intel) в macos-app/build/.
# Нужны Xcode или Command Line Tools: xcode-select --install
set -euo pipefail
cd "$(dirname "$0")"

swift build -c release --arch arm64 --arch x86_64
BIN="$(swift build -c release --arch arm64 --arch x86_64 --show-bin-path)/ShotHost"

APP="build/ShotHost.app"
rm -rf "$APP"
mkdir -p "$APP/Contents/MacOS" "$APP/Contents/Resources"
cp "$BIN" "$APP/Contents/MacOS/ShotHost"
cp Resources/Info.plist "$APP/Contents/Info.plist"

# Иконка: из AppIcon.png (1024×1024, исходник в AppIcon.svg) собираем AppIcon.icns.
ICONSET="build/AppIcon.iconset"
rm -rf "$ICONSET"
mkdir -p "$ICONSET"
for size in 16 32 128 256 512; do
    sips -z "$size" "$size" Resources/AppIcon.png --out "$ICONSET/icon_${size}x${size}.png" >/dev/null
    sips -z "$((size * 2))" "$((size * 2))" Resources/AppIcon.png --out "$ICONSET/icon_${size}x${size}@2x.png" >/dev/null
done
iconutil -c icns "$ICONSET" -o "$APP/Contents/Resources/AppIcon.icns"

# Подпись без сертификата разработчика (ad-hoc), чтобы приложение запускалось локально.
codesign --force --deep --sign - "$APP"

ditto -c -k --keepParent "$APP" build/ShotHost.zip
echo "Готово: $(pwd)/$APP"
