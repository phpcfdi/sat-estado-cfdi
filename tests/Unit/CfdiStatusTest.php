<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit;

use PhpCfdi\SatEstadoCfdi\CfdiStatus;
use PhpCfdi\SatEstadoCfdi\Status\CancellableStatus;
use PhpCfdi\SatEstadoCfdi\Status\CancellationStatus;
use PhpCfdi\SatEstadoCfdi\Status\DocumentStatus;
use PhpCfdi\SatEstadoCfdi\Status\QueryStatus;
use PhpCfdi\SatEstadoCfdi\Tests\TestCase;

class CfdiStatusTest extends TestCase
{
    public function testObjectReturnCorrectProperties(): void
    {
        $query = QueryStatus::found();
        $document = DocumentStatus::active();
        $cancellable = CancellableStatus::notCancellable();
        $cancellation = CancellationStatus::undefined();
        $cfdiStatus = new CfdiStatus($query, $document, $cancellable, $cancellation);

        $this->assertSame($query, $cfdiStatus->query());
        $this->assertSame($document, $cfdiStatus->document());
        $this->assertSame($cancellable, $cfdiStatus->cancellable());
        $this->assertSame($cancellation, $cfdiStatus->cancellation());
    }
}
