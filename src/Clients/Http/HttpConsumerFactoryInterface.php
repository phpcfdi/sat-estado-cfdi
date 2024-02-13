<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Clients\Http;

use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientResponseInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

interface HttpConsumerFactoryInterface
{
    public function httpClient(): ClientInterface;

    public function requestFactory(): RequestFactoryInterface;

    public function streamFactory(): StreamFactoryInterface;

    public function newConsumerClientResponse(): ConsumerClientResponseInterface;
}
