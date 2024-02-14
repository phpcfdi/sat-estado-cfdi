<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests;

use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientInterface;
use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientResponseInterface;
use PhpCfdi\SatEstadoCfdi\Utils\ConsumerClientResponse;

final class FakeConsumerClient implements ConsumerClientInterface
{
    /** consume method will return this variable when invoked */
    private readonly ConsumerClientResponseInterface $consumeResponse;

    /** consume method will populate this variable with $uri input */
    public string $lastUri = '';

    /** consume method will populate this variable with $expression input */
    public string $lastExpression = '';

    /** @param array<string, string> $values */
    public function __construct(array $values = [])
    {
        $this->consumeResponse = new ConsumerClientResponse($values);
    }

    public function consume(string $uri, string $expression): ConsumerClientResponseInterface
    {
        $this->lastUri = $uri;
        $this->lastExpression = $expression;
        return $this->consumeResponse;
    }
}
