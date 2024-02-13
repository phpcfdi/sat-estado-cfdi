<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit\Clients\Http;

use PhpCfdi\SatEstadoCfdi\Clients\Http\HttpConsumerFactory;
use PhpCfdi\SatEstadoCfdi\Clients\Http\HttpConsumerFactoryInterface;
use PhpCfdi\SatEstadoCfdi\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class HttpConsumerFactoryTest extends TestCase
{
    private function createFactoryWithMockObjects(): HttpConsumerFactoryInterface
    {
        /** @var ClientInterface&MockObject $client */
        $client = $this->createMock(ClientInterface::class);
        /** @var RequestFactoryInterface&MockObject $requestFactory */
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        /** @var StreamFactoryInterface&MockObject $streamFactory */
        $streamFactory = $this->createMock(StreamFactoryInterface::class);

        return new HttpConsumerFactory($client, $requestFactory, $streamFactory);
    }

    public function testFactoryHttpClientAlwaysReturnTheSameObject(): void
    {
        $factory = $this->createFactoryWithMockObjects();
        $first = $factory->httpClient();
        $second = $factory->httpClient();
        $this->assertSame($first, $second);
    }

    public function testFactoryRequestFactoryAlwaysReturnTheSameObject(): void
    {
        $factory = $this->createFactoryWithMockObjects();
        $first = $factory->requestFactory();
        $second = $factory->requestFactory();
        $this->assertSame($first, $second);
    }

    public function testFactoryStreamFactoryAlwaysReturnTheSameObject(): void
    {
        $factory = $this->createFactoryWithMockObjects();
        $first = $factory->streamFactory();
        $second = $factory->streamFactory();
        $this->assertSame($first, $second);
    }

    public function testFactoryNewConsumerClientResponseReturnsNewObjects(): void
    {
        $factory = $this->createFactoryWithMockObjects();
        $first = $factory->newConsumerClientResponse();
        $second = $factory->newConsumerClientResponse();
        $this->assertNotSame($first, $second);
    }
}
