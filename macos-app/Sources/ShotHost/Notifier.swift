import AppKit
import UserNotifications

/// Системные уведомления вместо модального окна из скрипта.
final class Notifier: NSObject, UNUserNotificationCenterDelegate {
    private static let uploadedCategory = "uploaded"
    private static let openAction = "open"

    /// UNUserNotificationCenter работает только внутри собранного .app.
    private var center: UNUserNotificationCenter? {
        Bundle.main.bundleIdentifier == nil ? nil : UNUserNotificationCenter.current()
    }

    func requestAuthorization() {
        guard let center else { return }
        center.delegate = self
        let open = UNNotificationAction(identifier: Self.openAction, title: "Открыть", options: [.foreground])
        center.setNotificationCategories([
            UNNotificationCategory(identifier: Self.uploadedCategory, actions: [open], intentIdentifiers: []),
        ])
        center.requestAuthorization(options: [.alert, .sound]) { _, _ in }
    }

    func showUploaded(_ link: String) {
        let content = UNMutableNotificationContent()
        content.title = "Скриншот загружен"
        content.body = "Ссылка скопирована в буфер обмена\n\(link)"
        content.categoryIdentifier = Self.uploadedCategory
        content.userInfo = ["url": link]
        post(content)
    }

    func showError(_ message: String) {
        let content = UNMutableNotificationContent()
        content.title = "Ошибка ShotHost"
        content.body = message
        content.sound = .default
        post(content)
    }

    private func post(_ content: UNNotificationContent) {
        guard let center else {
            print("\(content.title): \(content.body)")
            return
        }
        center.add(UNNotificationRequest(identifier: UUID().uuidString, content: content, trigger: nil))
    }

    func userNotificationCenter(_ center: UNUserNotificationCenter, willPresent notification: UNNotification,
                                withCompletionHandler completionHandler: @escaping (UNNotificationPresentationOptions) -> Void) {
        completionHandler([.banner, .sound])
    }

    func userNotificationCenter(_ center: UNUserNotificationCenter, didReceive response: UNNotificationResponse,
                                withCompletionHandler completionHandler: @escaping () -> Void) {
        let isOpen = response.actionIdentifier == Self.openAction || response.actionIdentifier == UNNotificationDefaultActionIdentifier
        if isOpen, let link = response.notification.request.content.userInfo["url"] as? String, let url = URL(string: link) {
            NSWorkspace.shared.open(url)
        }
        completionHandler()
    }
}
