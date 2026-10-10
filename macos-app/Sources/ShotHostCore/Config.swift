import Foundation

/// Настройки, которые раньше правились в начале shothost.sh.
public struct Config: Codable, Equatable {
    /// Папка, в которую делаются скриншоты.
    public var watchDir = ""
    /// IP или домен сервера.
    public var host = ""
    public var port = 22
    public var user = ""
    /// Путь на сервере к папке shothost/files/.
    public var remoteDir = "/var/www/html/shothost/files/"
    /// Приватный SSH-ключ (без .pub).
    public var keyPath = ""
    /// Адрес для ссылок: домен или IP с папкой /shothost/.
    public var baseURL = ""

    public init() {}

    public var isComplete: Bool {
        ![watchDir, host, user, remoteDir, keyPath, baseURL].contains { $0.trimmingCharacters(in: .whitespaces).isEmpty }
    }

    public func link(forStem stem: String) -> String {
        baseURL.hasSuffix("/") ? baseURL + stem : baseURL + "/" + stem
    }

    func remoteTarget(fileName: String) -> String {
        let dir = remoteDir.hasSuffix("/") ? remoteDir : remoteDir + "/"
        return "\(user)@\(host):\(dir)\(fileName)"
    }

    private static let defaultsKey = "config"

    public static func load(from defaults: UserDefaults = .standard) -> Config {
        guard let data = defaults.data(forKey: defaultsKey),
              let config = try? JSONDecoder().decode(Config.self, from: data) else { return Config() }
        return config
    }

    public func save(to defaults: UserDefaults = .standard) {
        if let data = try? JSONEncoder().encode(self) {
            defaults.set(data, forKey: Self.defaultsKey)
        }
    }
}
