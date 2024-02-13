<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi;

class CfdiStatus
{
    public function __construct(
        private readonly Status\QueryStatus $query,
        private readonly Status\DocumentStatus $document,
        private readonly Status\CancellableStatus $cancellable,
        private readonly Status\CancellationStatus $cancellation,
        private readonly Status\EfosStatus $efos
    ) {
    }

    public function query(): Status\QueryStatus
    {
        return $this->query;
    }

    public function document(): Status\DocumentStatus
    {
        return $this->document;
    }

    public function cancellable(): Status\CancellableStatus
    {
        return $this->cancellable;
    }

    public function cancellation(): Status\CancellationStatus
    {
        return $this->cancellation;
    }

    public function efos(): Status\EfosStatus
    {
        return $this->efos;
    }
}
