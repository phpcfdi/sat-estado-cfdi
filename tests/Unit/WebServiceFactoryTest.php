<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit;

use PhpCfdi\SatEstadoCfdi\Tests\TestCase;
use PhpCfdi\SatEstadoCfdi\WebServiceDiscover;
use PhpCfdi\SatEstadoCfdi\WebServiceFactory;

class WebServiceFactoryTest extends TestCase
{
    public function testCreatedConsumerHasCorrectUri(): void
    {
        $factory = (new WebServiceDiscover())->createFactory();
        $production = $factory->getConsumer();
        $this->assertSame(WebServiceFactory::URI_PRODUCTION, $production->getUri());

        $development = $factory->getConsumerDevelopment();
        $this->assertSame(WebServiceFactory::URI_DEVELOPMENT, $development->getUri());
    }
}
