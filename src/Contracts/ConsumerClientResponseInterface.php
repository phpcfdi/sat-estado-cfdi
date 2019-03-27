<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Contracts;

interface ConsumerClientResponseInterface
{
    /**
     * Store a pair of keyword value
     *
     * @param string $keyword
     * @param string $content
     */
    public function set(string $keyword, string $content): void;

    /**
     * Retrieve a value from a given keyword
     * This method sould not throw any exception, if keyword was not set previously it must return an empty string
     *
     * @param string $keyword
     * @return string
     */
    public function get(string $keyword): string;
}
