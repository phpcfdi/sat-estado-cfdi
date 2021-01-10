<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi;

class CfdiStatus
{
    /** @var Status\QueryStatus */
    private $query;

    /** @var Status\DocumentStatus */
    private $document;

    /** @var Status\CancellableStatus */
    private $cancellable;

    /** @var Status\CancellationStatus */
    private $cancellation;

    /** @var Status\EfosStatus */
    private $efos;

    public function __construct(
        Status\QueryStatus $query,
        Status\DocumentStatus $document,
        Status\CancellableStatus $cancellable,
        Status\CancellationStatus $cancellation,
        Status\EfosStatus $efos
    ) {
        $this->query = $query;
        $this->document = $document;
        $this->cancellable = $cancellable;
        $this->cancellation = $cancellation;
        $this->efos = $efos;
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
