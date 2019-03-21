<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit;

use PhpCfdi\SatEstadoCfdi\Tests\TestCase;
use PhpCfdi\SatEstadoCfdi\WebServiceConsumer;
use PhpCfdi\SatEstadoCfdi\WebServiceFactory;

class WebServiceConsumerClientTest extends TestCase
{
    public function testConsumerHasSameFactoryAndUriAsConstructed(): void
    {
        /** @var WebServiceFactory&\PHPUnit\Framework\MockObject\MockObject $factory */
        $factory = $this->createMock(WebServiceFactory::class);
        $uri = 'https://example.com';

        $consumer = new WebServiceConsumer($factory, $uri);
        $this->assertSame($factory, $consumer->getFactory());
        $this->assertSame($uri, $consumer->getUri());
    }
}
