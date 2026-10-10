import Foundation

/// Правила именования файлов, те же, что в shothost.sh и shothost.ps1:
/// транслитерация кириллицы, замена прочих символов на "_" и соль из 20 hex-символов.
public enum Naming {
    static let translit: [Character: String] = [
        "а": "a", "б": "b", "в": "v", "г": "g", "д": "d", "е": "e", "ё": "e", "ж": "zh", "з": "z", "и": "i",
        "й": "j", "к": "k", "л": "l", "м": "m", "н": "n", "о": "o", "п": "p", "р": "r", "с": "s", "т": "t",
        "у": "u", "ф": "f", "х": "h", "ц": "c", "ч": "ch", "ш": "sh", "щ": "shh", "ъ": "", "ы": "y", "ь": "",
        "э": "e", "ю": "yu", "я": "ya",
        "А": "A", "Б": "B", "В": "V", "Г": "G", "Д": "D", "Е": "E", "Ё": "E", "Ж": "ZH", "З": "Z", "И": "I",
        "Й": "J", "К": "K", "Л": "L", "М": "M", "Н": "N", "О": "O", "П": "P", "Р": "R", "С": "S", "Т": "T",
        "У": "U", "Ф": "F", "Х": "H", "Ц": "C", "Ч": "CH", "Ш": "SH", "Щ": "SHH", "Ъ": "", "Ы": "Y", "Ь": "",
        "Э": "E", "Ю": "YU", "Я": "YA",
    ]

    private static let processedPattern = try! NSRegularExpression(pattern: "_[a-f0-9]{20}\\.[a-zA-Z0-9]+$")

    /// Имя файла без расширения, пригодное для ссылки.
    /// Swift сравнивает символы с учётом канонической эквивалентности,
    /// поэтому «й» в NFD-форме из имён файлов macOS тоже транслитерируется.
    public static func cleanName(_ name: String) -> String {
        var result = ""
        for ch in name {
            if let latin = translit[ch] {
                result += latin
            } else if ch.isASCII && (ch.isLetter || ch.isNumber || ch == "." || ch == "_" || ch == "-") {
                result.append(ch)
            } else {
                result += "_"
            }
        }
        return result
    }

    public static func makeSalt() -> String {
        let hex = Array("0123456789abcdef")
        return String((0..<20).map { _ in hex.randomElement()! })
    }

    /// Файл уже переименован ShotHost (есть соль перед расширением).
    public static func isProcessed(_ fileName: String) -> Bool {
        let range = NSRange(fileName.startIndex..., in: fileName)
        return processedPattern.firstMatch(in: fileName, range: range) != nil
    }

    /// Новое имя для загрузки: `stem` идёт в ссылку, `fileName` — на сервер.
    /// Возвращает nil для файлов без расширения: их не загружаем.
    public static func uploadName(for fileName: String, salt: String = makeSalt()) -> (stem: String, fileName: String)? {
        let url = URL(fileURLWithPath: fileName)
        let ext = url.pathExtension
        guard !ext.isEmpty else { return nil }
        let stem = "\(cleanName(url.deletingPathExtension().lastPathComponent))_\(salt)"
        return (stem, "\(stem).\(cleanName(ext))")
    }
}
