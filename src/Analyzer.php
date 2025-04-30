<?php

declare(strict_types=1);

namespace Rilwanfit\YauaaPhp;

use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

final class Analyzer
{
    private array $patterns;

    public function __construct(string $patternFile)
    {
        if (!file_exists($patternFile)) {
            throw new InvalidArgumentException("Pattern file not found: $patternFile");
        }

        $this->patterns = Yaml::parseFile($patternFile) ?? [];
    }

    public function analyze(string $userAgent): array
    {
        foreach ($this->patterns['bots'] ?? [] as $bot) {
            if (stripos($userAgent, $bot['pattern']) !== false) {
                return [
                    'agent' => [
                        'type' => 'bot',
                        'name' => $bot['name'],
                        'version' => null,
                    ],
                ];
            }
        }

        foreach ($this->patterns['browsers'] ?? [] as $browser) {
            if (preg_match($browser['pattern'], $userAgent, $matches)) {
                return [
                    'agent' => [
                        'type' => 'browser',
                        'name' => $browser['name'],
                        'version' => $matches['version'] ?? null,
                    ],
                ];
            }
        }

        return [
            'agent' => [
                'type' => 'unknown', 'name' => null, 'version' => null,
            ],
        ];
    }
}
