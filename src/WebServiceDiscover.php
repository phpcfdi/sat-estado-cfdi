<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class WebServiceDiscover
{
    public function createFactory(
        ClientInterface $httpClient = null,
        RequestFactoryInterface $requestFactory = null,
        StreamFactoryInterface $streamFactory = null
    ): WebServiceFactory {
        return new WebServiceFactory(
            $httpClient ?? $this->discoverClient(),
            $requestFactory ?? $this->discoverRequestFactory(),
            $streamFactory ?? $this->discoverStreamFactory()
        );
    }

    public function discoverClient(): ClientInterface
    {
        return Psr18ClientDiscovery::find();
    }

    public function discoverRequestFactory(): RequestFactoryInterface
    {
        return Psr17FactoryDiscovery::findRequestFactory();
    }

    public function discoverStreamFactory(): StreamFactoryInterface
    {
        return Psr17FactoryDiscovery::findStreamFactory();
    }
}
