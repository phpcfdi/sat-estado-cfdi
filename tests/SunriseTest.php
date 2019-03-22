<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Sunrise\Http\Client\Curl\Client as SunriseCurlClient;

class SunriseTest extends TestCase
{
    public function testSunriseClientCanConnectToExampleDotCom(): void
    {
        $responseFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        $client = new SunriseCurlClient($responseFactory, $streamFactory, [
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        $request = $requestFactory->createRequest('GET', 'http://example.com/');

        $response = $client->sendRequest($request);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function testDiscoveryCreatesSunriseClient(): void
    {
        $client = Psr18ClientDiscovery::find();
        $this->assertInstanceOf(SunriseCurlClient::class, $client);
    }
}
