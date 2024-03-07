<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Contracts;

interface ConsumerClientResponseInterface
{
    /**
     * Retrieve a value from a given keyword
     * This method should not throw any exception,
     * if keyword was not set previously it must return an empty string
     */
    public function get(string $keyword): string;
}
