import XCTest
@testable import ShotHostCore

final class NamingTests: XCTestCase {
    func testTransliteratesCyrillic() {
        XCTAssertEqual(Naming.cleanName("Снимок экрана"), "Snimok_ekrana")
        XCTAssertEqual(Naming.cleanName("Щука Ёж"), "SHHuka_Ezh")
    }

    func testTransliteratesDecomposedCyrillic() {
        // Finder хранит имена в NFD: «й» = «и» + combining breve.
        XCTAssertEqual(Naming.cleanName("Мой".decomposedStringWithCanonicalMapping), "Moj")
    }

    func testReplacesOtherCharacters() {
        XCTAssertEqual(Naming.cleanName("a b(1)"), "a_b_1_")
        XCTAssertEqual(Naming.cleanName("10.10.2026 в 12.00"), "10.10.2026_v_12.00")
    }

    func testSalt() {
        let salt = Naming.makeSalt()
        XCTAssertEqual(salt.count, 20)
        XCTAssertTrue(salt.allSatisfy { "0123456789abcdef".contains($0) })
    }

    func testUploadNameIsRecognizedAsProcessed() throws {
        let name = try XCTUnwrap(Naming.uploadName(for: "Снимок экрана.png", salt: "0123456789abcdef0123"))
        XCTAssertEqual(name.stem, "Snimok_ekrana_0123456789abcdef0123")
        XCTAssertEqual(name.fileName, "Snimok_ekrana_0123456789abcdef0123.png")
        XCTAssertTrue(Naming.isProcessed(name.fileName))
        XCTAssertFalse(Naming.isProcessed("Снимок экрана.png"))
    }

    func testSkipsFilesWithoutExtension() {
        XCTAssertNil(Naming.uploadName(for: "README"))
    }

    func testLink() {
        var config = Config()
        config.baseURL = "https://example.com/shothost"
        XCTAssertEqual(config.link(forStem: "a_1"), "https://example.com/shothost/a_1")
        config.baseURL = "https://example.com/shothost/"
        XCTAssertEqual(config.link(forStem: "a_1"), "https://example.com/shothost/a_1")
    }
}
