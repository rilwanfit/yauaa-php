<?php

declare(strict_types=1);

namespace Rilwanfit\YauaaPhp\Tests;

use PHPUnit\Framework\TestCase;
use Rilwanfit\YauaaPhp\Analyzer;

/**
 * @internal
 *
 * @coversNothing
 */
final class AnalyzerTest extends TestCase
{
    private Analyzer $analyzer;

    protected function setUp(): void
    {
        $this->analyzer = new Analyzer(__DIR__.'/../resources/patterns.yaml');
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
        $result = $this->analyzer->analyze('Mozilla/5.0 (Linux; Android 10; SM-G975F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Mobile Safari/537.36');

        $this->assertEquals('browser', $result['agent']['type']);
        $this->assertEquals('Chrome', $result['agent']['name']);
        $this->assertEquals('117.0.0.0', $result['agent']['version']);

        // Device assertions
        $this->assertArrayHasKey('device', $result);
        $this->assertEquals('smartphone', $result['device']['class']);
        $this->assertEquals('Samsung', $result['device']['brand']);
        $this->assertEquals('Galaxy SM-G975F', $result['device']['name']);
    }

    /**
     * @dataProvider botProvider
     */
    public function testBotDetection(string $ua, array $expected)
    {
        $result = $this->analyzer->analyze($ua);

        $this->assertEquals($expected['type'], $result['agent']['type']);
        $this->assertEquals($expected['name'], $result['agent']['name']);
        $this->assertSame($expected['version'], $result['agent']['version']);
    }

    public function botProvider(): array
    {
        return [
            'Googlebot' => [
                'ua' => 'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.7049.95 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
                'expected' => ['type' => 'bot', 'name' => 'Googlebot', 'version' => null],
            ],
            'Bingbot' => [
                'ua' => 'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)',
                'expected' => ['type' => 'bot', 'name' => 'Bingbot', 'version' => null],
            ],
        ];
    }

    /**
     * @dataProvider hackerToolProvider
     */
    public function testHackerToolDetection(string $ua, array $expected)
    {
        $result = $this->analyzer->analyze($ua);

        $this->assertEquals($expected['type'], $result['agent']['type']);
        $this->assertEquals($expected['name'], $result['agent']['name']);
        $this->assertSame($expected['version'], $result['agent']['version']);
    }

    public function hackerToolProvider(): array
    {
        return [
            'Curl' => [
                'ua' => 'curl/7.79.1',
                'expected' => ['type' => 'hacker', 'name' => 'curl', 'version' => null],
            ],
            'Wget' => [
                'ua' => 'Wget/1.21.1',
                'expected' => ['type' => 'hacker', 'name' => 'Wget', 'version' => null],
            ],
            'BrowserKit' => [
                'ua' => 'Symfony BrowserKit',
                'expected' => ['type' => 'hacker', 'name' => 'BrowserKit', 'version' => null],
            ],
            'PhantomJS' => [
                'ua' => 'PhantomJS/2.1.1',
                'expected' => ['type' => 'hacker', 'name' => 'PhantomJS', 'version' => null],
            ],
        ];
    }

    public function testFirefoxDetection()
    {
        $result = $this->analyzer->analyze('Mozilla/5.0 (iPhone; CPU iPhone OS 15_4 like Mac OS X) Gecko/20100101 Firefox/112.0');

        $this->assertEquals('browser', $result['agent']['type']);
        $this->assertEquals('Firefox', $result['agent']['name']);
        $this->assertEquals('112.0', $result['agent']['version']);

        // Device assertions
        $this->assertArrayHasKey('device', $result);
        $this->assertEquals('smartphone', $result['device']['class']);
        $this->assertEquals('Apple', $result['device']['brand']);
        $this->assertEquals('iPhone', $result['device']['name']);
    }

    public function testEmptyUserAgent()
    {
        $result = $this->analyzer->analyze('');

        $this->assertEquals('unknown', $result['agent']['type']);
        $this->assertNull($result['agent']['name']);
        $this->assertNull($result['agent']['version']);
    }

    public function testShortUserAgent()
    {
        $result = $this->analyzer->analyze('Short');

        $this->assertEquals('unknown', $result['agent']['type']);
        $this->assertNull($result['agent']['name']);
    }

    public function testUnrecognizedUserAgent()
    {
        $result = $this->analyzer->analyze('CompletelyUnknownAgent/1.0');

        $this->assertEquals('unknown', $result['agent']['type']);
        $this->assertNull($result['agent']['name']);
        $this->assertNull($result['agent']['version']);
    }

    public function testDeviceOnlyUserAgent()
    {
        $result = $this->analyzer->analyze('Mozilla/5.0 (iPad; CPU OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1');

        // Should detect browser and device
        $this->assertEquals('browser', $result['agent']['type']);
        $this->assertEquals('Safari', $result['agent']['name']);

        $this->assertArrayHasKey('device', $result);
        $this->assertEquals('tablet', $result['device']['class']);
        $this->assertEquals('Apple', $result['device']['brand']);
        $this->assertEquals('iPad', $result['device']['name']);
    }

    /**
     * @dataProvider browserAndDeviceProvider
     */
    public function testBrowserAndDeviceDetection(string $ua, array $expectedAgent, array $expectedDevice)
    {
        $result = $this->analyzer->analyze($ua);

        // Agent assertions
        $this->assertEquals($expectedAgent['type'], $result['agent']['type']);
        $this->assertEquals($expectedAgent['name'], $result['agent']['name']);
        if (isset($expectedAgent['version'])) {
            $this->assertEquals($expectedAgent['version'], $result['agent']['version']);
        }

        // Device assertions
        $this->assertArrayHasKey('device', $result);
        $this->assertEquals($expectedDevice['class'], $result['device']['class']);
        $this->assertEquals($expectedDevice['brand'], $result['device']['brand']);
        $this->assertEquals($expectedDevice['name'], $result['device']['name']);
    }

    public function browserAndDeviceProvider(): array
    {
        return [
            'iPhone Safari' => [
                'ua' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 Mobile/15E148 Safari/604.1',
                'expectedAgent' => ['type' => 'browser', 'name' => 'Safari'],
                'expectedDevice' => ['class' => 'smartphone', 'brand' => 'Apple', 'name' => 'iPhone'],
            ],
            'Samsung Chrome' => [
                'ua' => 'Mozilla/5.0 (Linux; Android 10; SM-G975F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Mobile Safari/537.36',
                'expectedAgent' => ['type' => 'browser', 'name' => 'Chrome', 'version' => '117.0.0.0'],
                'expectedDevice' => ['class' => 'smartphone', 'brand' => 'Samsung', 'name' => 'Galaxy SM-G975F'],
            ],
            'iPad Safari' => [
                'ua' => 'Mozilla/5.0 (iPad; CPU OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
                'expectedAgent' => ['type' => 'browser', 'name' => 'Safari'],
                'expectedDevice' => ['class' => 'tablet', 'brand' => 'Apple', 'name' => 'iPad'],
            ],
        ];
    }
}
