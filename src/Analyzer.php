<?php

declare(strict_types=1);

namespace Rilwanfit\YauaaPhp;

use Rilwanfit\YauaaPhp\Contracts\DetectorInterface;
use Rilwanfit\YauaaPhp\Detector\BotDetector;
use Rilwanfit\YauaaPhp\Detector\BrowserDetector;
use Rilwanfit\YauaaPhp\Detector\DeviceDetector;
use Rilwanfit\YauaaPhp\Detector\HackerToolDetector;
use Rilwanfit\YauaaPhp\Detector\UnknownDetector;
use Rilwanfit\YauaaPhp\Loader\PatternLoader;

final class Analyzer
{
    /** @var DetectorInterface[] */
    private array $detectors;

    private array $lastResult = [];

    public function __construct(?string $patternFile = null)
    {
        if (null === $patternFile) {
            // Resolve to this package's internal resource path
            $patternFile = __DIR__.'/../resources/patterns.yaml';
        }

        $patterns = (new PatternLoader($patternFile))->load();

        $this->detectors = [
            new HackerToolDetector(),
            new BotDetector($patterns['bots'] ?? []),
            new BrowserDetector($patterns['browsers'] ?? []),
            new DeviceDetector($patterns['devices'] ?? []),
            new UnknownDetector(),
        ];
    }

    public function analyze(string $userAgent): array
    {
        $userAgent = trim($userAgent);

        if ('' === $userAgent || strlen($userAgent) < 10) {
            $this->lastResult = $this->normalizeResult([]);
            return $this->lastResult;
        }

        $agent = null;
        $device = null;

        foreach ($this->detectors as $detector) {
            $result = $detector->detect($userAgent);

            if (null === $result) {
                continue;
            }

            if (isset($result['agent']) && !$agent) {
                $agent = $result['agent'];
            }

            if (isset($result['device']) && !$device) {
                $device = $result['device'];
            }


            // Stop early if we have both
            if ($agent && $device) {
                break;
            }
        }

        $this->lastResult = $this->normalizeResult([
            'agent' => $agent,
            'device' => $device,
        ]);

        return $this->lastResult;
    }

    public function isMobile(): bool
    {
        return $this->lastResult['device']['class'] === 'smartphone';
    }

    public function isTablet(): bool
    {
        return $this->lastResult['device']['class'] === 'tablet';
    }

    public function isDesktop(): bool
    {
        return $this->lastResult['device']['class'] === 'desktop';
    }

    public function getDeviceClass(): string
    {
        return $this->lastResult['device']['class'];
    }

    public function getAgentType(): string
    {
        return $this->lastResult['agent']['type'];
    }

    private function normalizeResult(array $result): array
    {
        return [
            'agent' => [
                'type' => $result['agent']['type'] ?? 'unknown',
                'name' => $result['agent']['name'] ?? null,
                'version' => $result['agent']['version'] ?? null,
            ],
            'device' => [
                'class' => $result['device']['class'] ?? 'unknown',
                'brand' => $result['device']['brand'] ?? 'unknown',
                'name' => $result['device']['name'] ?? 'unknown',
            ]
        ];
    }
}
