<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi;

use ArrayObject;
use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientResponseInterface;

/**
 * This is a generic implementation of ConsumerClientResponse, feel free to use your own.
 */
class ConsumerClientResponse implements ConsumerClientResponseInterface
{
    /** @var ArrayObject */
    private $map;

    public function __construct()
    {
        $this->map = new ArrayObject();
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
