<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Integration;

use PhpCfdi\SatEstadoCfdi\CfdiExpressionBuilder;
use PhpCfdi\SatEstadoCfdi\Tests\TestCase;
use PhpCfdi\SatEstadoCfdi\WebServiceDiscover;

class ConsumeWebServiceTest extends TestCase
{
    public function testConsumeServiceFromKnownCfdi33(): void
    {
        $cfdi = $this->fileContentPath('cfdi33-real.xml');
        $parameters = CfdiExpressionBuilder::createFromString($cfdi)->build();
        $expression = $parameters->expression();

        $discoverer = new WebServiceDiscover();
        $factory = $discoverer->createFactory();
        $consumer = $factory->getConsumer();

        $status = $consumer->execute($expression);

        $this->assertTrue($status->request()->isFound());
        $this->assertTrue($status->active()->isActive());
        $this->assertTrue($status->cancellabe()->isDirectMethod());
        $this->assertTrue($status->cancellation()->isUndefined());
    }

    public function testConsumeServiceFromKnownCfdi32(): void
    {
        $cfdi = $this->fileContentPath('cfdi32-real.xml');
        $parameters = CfdiExpressionBuilder::createFromString($cfdi)->build();
        $expression = $parameters->expression();

        $discoverer = new WebServiceDiscover();
        $factory = $discoverer->createFactory();
        $consumer = $factory->getConsumer();

        $status = $consumer->execute($expression);

        $this->assertTrue($status->request()->isFound());
        $this->assertTrue($status->active()->isActive());
        $this->assertTrue($status->cancellabe()->isDirectMethod());
        $this->assertTrue($status->cancellation()->isUndefined());
    }
}
