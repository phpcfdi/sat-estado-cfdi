<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Utils;

use ArrayObject;
use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientResponseInterface;

/**
 * This is a generic implementation of ConsumerClientResponseInterface
 * You can use it, or you can create your own implementation as your convenience.
 */
final readonly class ConsumerClientResponse implements ConsumerClientResponseInterface
{
    /** @var ArrayObject<string, string> */
    private ArrayObject $map;

    /** @param array<string, string> $values */
    public function __construct(array $values = [])
    {
        $this->map = new ArrayObject($values);
    }

    public function set(string $keyword, string $content): void
    {
        $this->map->offsetSet($keyword, $content);
    }

    public function get(string $keyword): string
    {
        return $this->map->offsetExists($keyword) ? strval($this->map->offsetGet($keyword)) : '';
    }
}
