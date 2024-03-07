<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi;

final readonly class CfdiStatus
{
    public function __construct(
        public Status\QueryStatus $query,
        public Status\DocumentStatus $document,
        public Status\CancellableStatus $cancellable,
        public Status\CancellationStatus $cancellation,
        public Status\EfosStatus $efos,
    ) {
    }
}
