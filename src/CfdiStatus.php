<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi;

class CfdiStatus
{
    public function __construct(
        private Status\QueryStatus $query,
        private Status\DocumentStatus $document,
        private Status\CancellableStatus $cancellable,
        private Status\CancellationStatus $cancellation,
        private Status\EfosStatus $efos
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
