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
            if (stripos($userAgent, $bot['pattern']) !== false) {
                return [
                    'type' => 'bot',
                    'name' => $bot['name'],
                    'version' => null,
                ];
            }
        }

        return null;
    }
}
