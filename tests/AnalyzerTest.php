<?php

declare(strict_types=1);

namespace Rilwanfit\YauaaPhp\Tests;

use PHPUnit\Framework\TestCase;
use Rilwanfit\YauaaPhp\Analyzer;

final class AnalyzerTest extends TestCase
{
    public function testChromeDetection()
    {
        $analyzer = new Analyzer(__DIR__ . '/../resources/patterns.yaml');
        $result = $analyzer->analyze('Mozilla/5.0 Chrome/117.0.0.0 Safari/537.36');

        $this->assertEquals('browser', $result['agent']['type']);
        $this->assertEquals('Chrome', $result['agent']['name']);
        $this->assertEquals('117.0.0.0', $result['agent']['version']);
    }

    public function testBotDetection()
    {
        $analyzer = new Analyzer(__DIR__ . '/../resources/patterns.yaml');
        $result = $analyzer->analyze('Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.7049.95 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');
        $this->assertEquals('bot', $result['agent']['type']);
        $this->assertEquals('Googlebot', $result['agent']['name']);
    }
}
