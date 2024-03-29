<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Clients\Http;

use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientResponseInterface;
use PhpCfdi\SatEstadoCfdi\Utils\ConsumerClientResponse;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * Use this HttpConsumerFactory as a helper to
 * store http client (psr-18)
 * store request factory and stream factory (psr-17)
 * and create the ConsumerClientResponse
 */
final readonly class HttpConsumerFactory implements HttpConsumerFactoryInterface
{
    public function __construct(
        private ClientInterface $httpClient,
        private RequestFactoryInterface $requestFactory,
        private StreamFactoryInterface $streamFactory,
    ) {
    }

    public function httpClient(): ClientInterface
    {
        return $this->httpClient;
    }

    public function requestFactory(): RequestFactoryInterface
    {
        return $this->requestFactory;
    }

    public function streamFactory(): StreamFactoryInterface
    {
        return $this->streamFactory;
    }

    /** @param array<string, string> $values */
    public function newConsumerClientResponse(array $values = []): ConsumerClientResponseInterface
    {
        return ConsumerClientResponse::createFromValues($values);
    }
}
