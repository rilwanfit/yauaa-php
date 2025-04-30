<?php

declare(strict_types=1);

namespace Rilwanfit\YauaaPhp;

use Rilwanfit\YauaaPhp\Contracts\DetectorInterface;
use Rilwanfit\YauaaPhp\Detector\BotDetector;
use Rilwanfit\YauaaPhp\Detector\BrowserDetector;
use Rilwanfit\YauaaPhp\Detector\HackerToolDetector;
use Rilwanfit\YauaaPhp\Detector\UnknownDetector;
use Rilwanfit\YauaaPhp\Loader\PatternLoader;

final class Analyzer
{
    /** @var DetectorInterface[] */
    private array $detectors;

    public function __construct(string $patternFile)
    {
        $patterns = (new PatternLoader($patternFile))->load();

        $this->detectors = [
            new HackerToolDetector(),
            new BotDetector($patterns['bots'] ?? []),
            new BrowserDetector($patterns['browsers'] ?? []),
            new UnknownDetector(),
        ];
    }

    public function analyze(string $userAgent): array
    {
        $userAgent = trim($userAgent);

        if ($userAgent === '' || strlen($userAgent) < 10) {
            return [
                'agent' => [
                    'type' => 'unknown',
                    'name' => 'Empty or invalid user agent',
                    'version' => null,
                ]
            ];
        }

        foreach ($this->detectors as $detector) {
            $result = $detector->detect($userAgent);
            if ($result !== null) {
                return ['agent' => $result];
            }
        }

        return [
            'agent' => [
                'type' => 'unknown',
                'name' => null,
                'version' => null,
            ]
        ];
    }
}
