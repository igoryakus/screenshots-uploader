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

# Подпись без сертификата разработчика (ad-hoc), чтобы приложение запускалось локально.
codesign --force --deep --sign - "$APP"

ditto -c -k --keepParent "$APP" build/ShotHost.zip
echo "Готово: $(pwd)/$APP"
