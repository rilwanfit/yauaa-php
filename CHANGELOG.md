# Changelog

All notable changes to **YAUAA-PHP** will be documented in this file.

This project follows [Semantic Versioning](https://semver.org/).

---

## [V1.1.0] - 2025-05-05
### Added
- Device detection support (class, brand, name) using YAML-driven pattern matching 
- DeviceDetector class with dynamic token replacement (e.g. ${model})
- Analyzer now always returns both agent and device keys in a normalized structure 
- Analyzer::isMobile(), isTablet(), and isDesktop() helper methods 
- Data-driven PHPUnit tests for browsers and devices 
- Improved consistency of detection results from all detectors (agent key structure)

### Fixed
- Samsung model pattern now correctly returns "Galaxy SM-${model}"
- Ensured UnknownDetector, BotDetector, HackerToolDetector return properly structured agent responses

## [0.0.1] - 2025-04-30
### Added
- Initial release of YAUAA-PHP
- Support for browser detection with version parsing
- Support for basic bot detection
- YAML-driven pattern matching system
- PHPUnit tests for core functionality
