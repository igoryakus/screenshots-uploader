import Foundation

/// Следит за изменениями в папке (без fswatch) и сообщает о новых файлах,
/// которые ещё не переименованы ShotHost. Файлы, лежавшие в папке до запуска, не трогает.
public final class FolderWatcher {
    private let directory: URL
    private let onNewFile: (URL) -> Void
    private let queue = DispatchQueue(label: "shothost.watcher")
    private var source: DispatchSourceFileSystemObject?
    private var seen = Set<String>()
    private var pendingScan: DispatchWorkItem?

    public init(directory: URL, onNewFile: @escaping (URL) -> Void) {
        self.directory = directory
        self.onNewFile = onNewFile
    }

    deinit {
        stop()
    }

    public func start() throws {
        let fd = open(directory.path, O_EVTONLY)
        guard fd >= 0 else {
            throw UploadError(message: "Не удалось открыть папку \(directory.path)")
        }
        queue.sync {
            seen = Set(candidates().map(\.lastPathComponent))
        }
        let source = DispatchSource.makeFileSystemObjectSource(fileDescriptor: fd, eventMask: [.write, .extend, .rename], queue: queue)
        source.setEventHandler { [weak self] in self?.scheduleScan() }
        source.setCancelHandler { close(fd) }
        source.resume()
        self.source = source
    }

    public func stop() {
        source?.cancel()
        source = nil
    }

    private func scheduleScan() {
        pendingScan?.cancel()
        let work = DispatchWorkItem { [weak self] in self?.scan() }
        pendingScan = work
        queue.asyncAfter(deadline: .now() + 0.3, execute: work)
    }

    private func scan() {
        let current = candidates()
        let names = Set(current.map(\.lastPathComponent))
        // Забываем удалённые файлы, чтобы файл с тем же именем снова загрузился.
        seen.formIntersection(names)
        for url in current where !seen.contains(url.lastPathComponent) {
            seen.insert(url.lastPathComponent)
            onNewFile(url)
        }
    }

    private func candidates() -> [URL] {
        let urls = (try? FileManager.default.contentsOfDirectory(
            at: directory,
            includingPropertiesForKeys: [.isRegularFileKey],
            options: [.skipsHiddenFiles]
        )) ?? []
        return urls.filter { url in
            let isFile = (try? url.resourceValues(forKeys: [.isRegularFileKey]).isRegularFile) ?? false
            return isFile && !Naming.isProcessed(url.lastPathComponent) && !url.pathExtension.isEmpty
        }
    }
}
