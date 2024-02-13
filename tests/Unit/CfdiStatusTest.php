<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit;

use PhpCfdi\SatEstadoCfdi\CfdiStatus;
use PhpCfdi\SatEstadoCfdi\Status\CancellableStatus;
use PhpCfdi\SatEstadoCfdi\Status\CancellationStatus;
use PhpCfdi\SatEstadoCfdi\Status\DocumentStatus;
use PhpCfdi\SatEstadoCfdi\Status\EfosStatus;
use PhpCfdi\SatEstadoCfdi\Status\QueryStatus;
use PhpCfdi\SatEstadoCfdi\Tests\TestCase;

final class CfdiStatusTest extends TestCase
{
    public function testObjectReturnCorrectProperties(): void
    {
        $query = QueryStatus::Found;
        $document = DocumentStatus::Active;
        $cancellable = CancellableStatus::NotCancellable;
        $cancellation = CancellationStatus::Undefined;
        $efos = EfosStatus::Excluded;
        $cfdiStatus = new CfdiStatus($query, $document, $cancellable, $cancellation, $efos);

        $this->assertSame($query, $cfdiStatus->query());
        $this->assertSame($document, $cfdiStatus->document());
        $this->assertSame($cancellable, $cfdiStatus->cancellable());
        $this->assertSame($cancellation, $cfdiStatus->cancellation());
        $this->assertSame($efos, $cfdiStatus->efos());
    }
}
