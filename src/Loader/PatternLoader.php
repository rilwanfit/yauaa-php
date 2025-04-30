<?php

declare(strict_types=1);


namespace Rilwanfit\YauaaPhp\Loader;

use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

final class PatternLoader
{
    public function __construct(private string $filePath) {}

    public function load(): array
    {
        if (!file_exists($this->filePath)) {
            throw new InvalidArgumentException("Pattern file not found: {$this->filePath}");
        }

        return Yaml::parseFile($this->filePath) ?? [];
    }
}
