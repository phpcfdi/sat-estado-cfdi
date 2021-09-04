<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Utils;

use ArrayObject;
use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientResponseInterface;

/**
 * This is a generic implementation of ConsumerClientResponseInterface
 * You can use it, or you can create your own implementation as your convenience.
 */
class ConsumerClientResponse implements ConsumerClientResponseInterface
{
    /** @var ArrayObject<string, string> */
    private $map;

    public function __construct()
    {
        $this->map = new ArrayObject([]);
    }

    public function set(string $keyword, string $content): void
    {
        $this->map[$keyword] = $content;
    }

    public function get(string $keyword): string
    {
        return $this->map[$keyword] ?? '';
    }
}
