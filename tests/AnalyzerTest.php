<?php

declare(strict_types=1);

namespace Rilwanfit\YauaaPhp\Tests;

use PHPUnit\Framework\TestCase;
use Rilwanfit\YauaaPhp\Analyzer;

final class AnalyzerTest extends TestCase
{
    private Analyzer $analyzer;

    protected function setUp(): void
    {
        $this->analyzer = new Analyzer(__DIR__ . '/../resources/patterns.yaml');
    }

    public function testWithDefaultPatternFile()
    {
        $analyzer = new Analyzer();
        $result = $analyzer->analyze('Mozilla/5.0 Chrome/117.0.0.0 Safari/537.36');

        $this->assertEquals('browser', $result['agent']['type']);
        $this->assertEquals('Chrome', $result['agent']['name']);
    }

    public function testChromeDetection()
    {
        $result = $this->analyzer->analyze('Mozilla/5.0 Chrome/117.0.0.0 Safari/537.36');

        $this->assertEquals('browser', $result['agent']['type']);
        $this->assertEquals('Chrome', $result['agent']['name']);
        $this->assertEquals('117.0.0.0', $result['agent']['version']);
    }

    public function testBotDetection()
    {
        $result = $this->analyzer->analyze('Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.7049.95 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');
        $this->assertEquals('bot', $result['agent']['type']);
        $this->assertEquals('Googlebot', $result['agent']['name']);
    }

    public function testFirefoxDetection()
    {
        $result = $this->analyzer->analyze('Mozilla/5.0 Firefox/112.0');
        $this->assertEquals('browser', $result['agent']['type']);
        $this->assertEquals('Firefox', $result['agent']['name']);
        $this->assertEquals('112.0', $result['agent']['version']);
    }

    public function testEmptyUserAgent()
    {
        $result = $this->analyzer->analyze('');

        $this->assertEquals('unknown', $result['agent']['type']);
        $this->assertEquals('Empty or invalid user agent', $result['agent']['name']);
        $this->assertNull($result['agent']['version']);
    }

    public function testShortUserAgent()
    {
        $result = $this->analyzer->analyze('Short');

        $this->assertEquals('unknown', $result['agent']['type']);
        $this->assertEquals('Empty or invalid user agent', $result['agent']['name']);
    }

    public function testHackerTools()
    {
        $tools = [
            'Symfony BrowserKit' => 'BrowserKit',
            'curl/7.79.1' => 'curl',
            'Wget/1.21.1' => 'Wget',
            'HeadlessChrome/115.0.0.0' => 'HeadlessChrome',
            'PhantomJS/2.1.1' => 'PhantomJS',
            'python-urllib/3.8' => 'python-urllib',
            'Java/1.8.0_181' => 'Java',
            'Go-http-client/2.0' => 'Go-http-client',
        ];

        foreach ($tools as $ua => $expectedTool) {
            $result = $this->analyzer->analyze($ua);
            $this->assertEquals('hacker', $result['agent']['type'], "Failed for UA: $ua");
            $this->assertEquals($expectedTool, $result['agent']['name'], "Failed to match name for: $ua");
            $this->assertNull($result['agent']['version'], "Expected null version for: $ua");
        }
    }

    public function testUnrecognizedUserAgent()
    {
        $result = $this->analyzer->analyze('CompletelyUnknownAgent/1.0');

        $this->assertEquals('unknown', $result['agent']['type']);
        $this->assertNull($result['agent']['name']);
        $this->assertNull($result['agent']['version']);
    }
}
