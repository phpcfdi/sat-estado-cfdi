<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests;

use PhpCfdi\SatEstadoCfdi\ConsumerClientResponse;
use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientInterface;
use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientResponseInterface;

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

    public function __construct(array $predefined = [])
    {
        $this->setClientResponse($predefined);
    }

    public function setClientResponse(array $predefined): void
    {
        $this->consumeResponse = $this->consumerClientResponseFromArray($predefined);
    }

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
