<?php

declare(strict_types=1);


namespace Rilwanfit\YauaaPhp\Detector;

use Rilwanfit\YauaaPhp\Contracts\DetectorInterface;

final class HackerToolDetector implements DetectorInterface
{
    private array $tools = [
        'BrowserKit', 'curl', 'Wget', 'HeadlessChrome',
        'PhantomJS', 'python-urllib', 'Java', 'Go-http-client'
    ];

    public function detect(string $userAgent): ?array
    {
        foreach ($this->tools as $tool) {
            if (stripos($userAgent, $tool) !== false) {
                return [
                    'type' => 'hacker',
                    'name' => $tool,
                    'version' => null,
                ];
            }
        }

        return null;
    }
}
