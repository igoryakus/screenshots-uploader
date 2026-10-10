// swift-tools-version:5.9
import PackageDescription

let package = Package(
    name: "ShotHost",
    platforms: [.macOS(.v13)],
    targets: [
        .target(name: "ShotHostCore", path: "Sources/ShotHostCore"),
        .executableTarget(name: "ShotHost", dependencies: ["ShotHostCore"], path: "Sources/ShotHost"),
        .testTarget(name: "ShotHostCoreTests", dependencies: ["ShotHostCore"], path: "Tests/ShotHostCoreTests"),
    ]
)
