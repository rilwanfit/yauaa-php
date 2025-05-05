<?php

declare(strict_types=1);

namespace Rilwanfit\YauaaPhp\Detector;

use Rilwanfit\YauaaPhp\Contracts\DetectorInterface;

final class DeviceDetector implements DetectorInterface
{
    public function __construct(private array $patterns) {}

    public function detect(string $userAgent): ?array
    {
        foreach ($this->patterns as $patternDef) {
            if (!isset($patternDef['pattern'], $patternDef['class'], $patternDef['brand'], $patternDef['name'])) {
                continue;
            }

            if (preg_match($patternDef['pattern'], $userAgent, $matches)) {
                $name = $patternDef['name'];

                // Replace placeholders like ${model}
                foreach ($matches as $key => $value) {
                    if (!is_int($key)) {
                        $name = str_replace('${' . $key . '}', $value, $name);
                    }
                }

                return [
                    'device' => [
                        'class' => $patternDef['class'],
                        'brand' => $patternDef['brand'],
                        'name'  => $name,
                    ],
                ];
            }
        }

        return null;
    }
}
