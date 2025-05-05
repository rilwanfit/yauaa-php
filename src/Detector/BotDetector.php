<?php

declare(strict_types=1);

namespace Rilwanfit\YauaaPhp\Detector;

use Rilwanfit\YauaaPhp\Contracts\DetectorInterface;

final class BotDetector implements DetectorInterface
{
    public function __construct(private array $patterns) {}

    public function detect(string $userAgent): ?array
    {
        foreach ($this->patterns as $bot) {
            if (
                isset($bot['pattern'], $bot['name']) &&
                false !== stripos($userAgent, $bot['pattern'])
            ) {
                return [
                    'agent' => [
                        'type' => 'bot',
                        'name' => $bot['name'],
                        'version' => null,
                    ],
                ];
            }
        }

        return null;
    }
}
