<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests;

use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientInterface;
use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientResponseInterface;
use PhpCfdi\SatEstadoCfdi\Utils\ConsumerClientResponse;

final class FakeConsumerClient implements ConsumerClientInterface
{
    /** consume method will return this variable when invoked */
    private ConsumerClientResponseInterface $consumeResponse;

    /** consume method will populate this variable with $uri input */
    public string $lastUri = '';

    /** consume method will populate this variable with $expression input */
    public string $lastExpression = '';

    /** @param array<string, string> $predefined */
    public function __construct(array $predefined = [])
    {
        $this->setClientResponse($predefined);
    }

    /** @param array<string, string> $predefined */
    public function setClientResponse(array $predefined): void
    {
        $this->consumeResponse = self::consumerClientResponseFromArray($predefined);
    }

    /** @param array<string, string> $input */
    public static function consumerClientResponseFromArray(array $input): ConsumerClientResponseInterface
    {
        $consumeResponse = new ConsumerClientResponse();
        foreach ($input as $key => $value) {
            $consumeResponse->set($key, $value);
        }
        return $consumeResponse;
    }

    public function consume(string $uri, string $expression): ConsumerClientResponseInterface
    {
        $this->lastUri = $uri;
        $this->lastExpression = $expression;
        return $this->consumeResponse;
    }
}
