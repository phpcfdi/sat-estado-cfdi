<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit;

use PhpCfdi\SatEstadoCfdi\Tests\TestCase;
use PhpCfdi\SatEstadoCfdi\WebServiceDiscover;

class WebServiceDiscoverTest extends TestCase
{
    public function testCanCreateFactoryWithoutArguments(): void
    {
        $discovery = new WebServiceDiscover();
        $discovery->createFactory();
        $this->assertTrue(true, 'This test must not produce any error or exception');
    }
}
