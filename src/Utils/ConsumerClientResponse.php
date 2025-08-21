<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Utils;

use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientResponseInterface;

/**
 * This is a generic implementation of ConsumerClientResponseInterface
 * You can use it, or you can create your own implementation as your convenience.
 */
final readonly class ConsumerClientResponse implements ConsumerClientResponseInterface
{
    /** @param array<string, string> $values */
    public function __construct(
        private array $values,
    ) {
    }

    public static function createFromValues(mixed $values): self
    {
        if (is_array($values)) {
            $values = (object) $values;
        } elseif ($values instanceof \Traversable) {
            $values = (object) iterator_to_array($values);
        }
        if (! is_object($values)) {
            $values = (object) [];
        }

        $final = [];
        foreach (get_object_vars($values) as $key => $value) {
            if (is_scalar($value)) {
                $final[strval($key)] = strval($value);
            }
        }

        return new self($final);
    }

    public function get(string $keyword): string
    {
        return $this->values[$keyword] ?? '';
    }
}
