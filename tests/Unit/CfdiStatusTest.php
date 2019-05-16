<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit;

use PhpCfdi\SatEstadoCfdi\CfdiStatus;
use PhpCfdi\SatEstadoCfdi\Status\CancellableStatus;
use PhpCfdi\SatEstadoCfdi\Status\CancellationStatus;
use PhpCfdi\SatEstadoCfdi\Status\DocumentStatus;
use PhpCfdi\SatEstadoCfdi\Status\QueryStatus;
use PhpCfdi\SatEstadoCfdi\Tests\TestCase;
use PHPUnit\Framework\Error\Deprecated;

class CfdiStatusTest extends TestCase
{
    public function testActivePropertyThrowsDeprecationNotice(): void
    {
        $cfdiStatus = new CfdiStatus(
            QueryStatus::found(),
            DocumentStatus::active(),
            CancellableStatus::notCancellable(),
            CancellationStatus::undefined()
        );

        // silence errors on this call to check that active returns the same as document
        $this->assertSame($cfdiStatus->document(), @$cfdiStatus->active());

        // now call active without silence operator, this error will be catched as exception by phpunit
        $this->expectException(Deprecated::class);
        $cfdiStatus->active();
    }

    public function testRequestPropertyThrowsDeprecationNotice(): void
    {
        $cfdiStatus = new CfdiStatus(
            QueryStatus::found(),
            DocumentStatus::active(),
            CancellableStatus::notCancellable(),
            CancellationStatus::undefined()
        );

        // silence errors on this call to check that active returns the same as document
        $this->assertSame($cfdiStatus->query(), @$cfdiStatus->request());

        // now call active without silence operator, this error will be catched as exception by phpunit
        $this->expectException(Deprecated::class);
        $cfdiStatus->request();
    }
}
