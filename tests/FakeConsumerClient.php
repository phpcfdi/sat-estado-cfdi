<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests;

use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientInterface;
use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientResponseInterface;
use PhpCfdi\SatEstadoCfdi\Utils\ConsumerClientResponse;

class FakeConsumerClient implements ConsumerClientInterface
{
    /**
     * consume method will return this variable when invoked
     *
     * @var ConsumerClientResponseInterface
     */
    private $consumeResponse;

    /**
     * consume method will populate this variable with $uri input
     *
     * @var string
     */
    public $lastUri = '';

    /**
     * consume method will populate this variable with $expression input
     *
     * @var string
     */
    public $lastExpression = '';

    /**
     * FakeConsumerClient constructor.
     *
     * @param array<string, string> $predefined
     */
    public function __construct(array $predefined = [])
    {
        $this->setClientResponse($predefined);
    }

    /**
     * @param array<string, string> $predefined
     */
    public function setClientResponse(array $predefined): void
    {
        $this->consumeResponse = $this->consumerClientResponseFromArray($predefined);
    }

    /**
     * @param array<string, string> $input
     * @return ConsumerClientResponseInterface
     */
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
