<?php

declare(strict_types=1);


namespace Rilwanfit\YauaaPhp\Detector;

use Rilwanfit\YauaaPhp\Contracts\DetectorInterface;

final class BrowserDetector implements DetectorInterface
{
    public function __construct(private array $patterns) {}

    public function detect(string $userAgent): ?array
    {
        foreach ($this->patterns as $browser) {
            if (preg_match($browser['pattern'], $userAgent, $matches)) {
                return [
                    'type' => 'browser',
                    'name' => $browser['name'],
                    'version' => $matches['version'] ?? null,
                ];
            }
        }

        return null;
    }
}
