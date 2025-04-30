<?php

namespace Rilwanfit\YauaaPhp\Contracts;

interface DetectorInterface
{
    public function detect(string $userAgent): ?array;
}
