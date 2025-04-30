# YAUAA-PHP

**Yet Another User Agent Analyzer – PHP Edition**  
A lightweight and extensible PHP library for parsing and analyzing User-Agent strings, inspired by the original [YAUAA](https://github.com/nielsbasjes/yauaa) (Java).

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

---

## 🙏 Special Thanks

This project is heavily inspired by the amazing work of [Niels Basjes](https://github.com/nielsbasjes), author of the original [Yet Another UserAgent Analyzer (YAUAA)](https://github.com/nielsbasjes/yauaa).  
Without YAUAA, this PHP version wouldn't exist.

---

## 🚀 Features

- Detect browser (agent) name and version
- Detect layout engine [ToDo]
- Detect operating system and version [ToDo]
- Detect device class, brand, and name [ToDo]
- Basic bot detection support 
- Pattern-driven and fully customizable (via YAML) 
- No external dependencies required (except Symfony YAML parser)

---

## 🧰 Installation

```bash
composer require rilwanfit/yauaa-php
```

---

## 🧪 Example Usage

```php
use Rilwanfit\YauaaPhp\Analyzer;

$analyzer = new Analyzer(__DIR__ . '/resources/patterns.yaml');

$result = $analyzer->analyze('Mozilla/5.0 Chrome/117.0.0.0 Safari/537.36');

print_r($result);
```

### 🔍 Example Output

```php
[
    'agent' => [
        'class' => 'Browser',
        'name' => 'Chrome',
        'version' => '117.0.0.0',
        'version_major' => '117',
        'name_version' => 'Chrome 117.0.0.0',
        'name_version_major' => 'Chrome 117'
    ],
    'layout_engine' => [ ... ],
    'os' => [ ... ],
    'device' => [ ... ]
]
```

---

## 📦 Custom Patterns

Patterns are loaded from a YAML file (`resources/patterns.yaml`). Example format:

```yaml
browsers:
  - name: "Chrome"
    class: "Browser"
    pattern: "~Chrome/(?<version>[\\d\\.]+)~i"

os:
  - name: "Windows"
    class: "Operating System"
    pattern: "~Windows NT (?<version>[\\d\\.]+)~i"

device:
  - name: "Desktop"
    class: "Desktop"
    pattern: "~Windows NT~i"
```

---

## ✅ Testing

```bash
composer install
./vendor/bin/phpunit
```

---

## 🧱 Directory Structure

```
src/
  Analyzer.php

resources/
  patterns.yaml

tests/
  AnalyzerTest.php
```

---

## 📄 License

This project is licensed under the [MIT License](LICENSE).

---

## 👨‍💻 Author

Built by [Rilwan Fit](https://github.com/rilwanfit)
