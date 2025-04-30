<?php

declare(strict_types=1);


namespace Rilwanfit\YauaaPhp\Detector;

use Rilwanfit\YauaaPhp\Contracts\DetectorInterface;

final class UnknownDetector implements DetectorInterface
{
    public function detect(string $userAgent): ?array
    {
        return [
            'type' => 'unknown',
            'name' => null,
            'version' => null,
        ];
    }
}
