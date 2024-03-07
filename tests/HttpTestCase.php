<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use PhpCfdi\SatEstadoCfdi\Clients\Http\HttpConsumerFactory;
use PhpCfdi\SatEstadoCfdi\Clients\Http\HttpConsumerFactoryInterface;

abstract class HttpTestCase extends TestCase
{
    public function createHttpConsumerFactory(): HttpConsumerFactoryInterface
    {
        // PSR-18 \Psr\Http\Client\ClientInterface
        $httpClient = new Client();
        // PSR-17 Psr\Http\Message\RequestFactoryInterface and Psr\Http\Message\StreamFactoryInterface
        $guzzleFactory = new HttpFactory();
        return new HttpConsumerFactory($httpClient, $guzzleFactory, $guzzleFactory);
    }
}
