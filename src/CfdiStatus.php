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

    public function __construct(
        Status\QueryStatus $query,
        Status\DocumentStatus $document,
        Status\CancellableStatus $cancellable,
        Status\CancellationStatus $cancellation
    ) {
        $this->query = $query;
        $this->document = $document;
        $this->cancellable = $cancellable;
        $this->cancellation = $cancellation;
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
}
