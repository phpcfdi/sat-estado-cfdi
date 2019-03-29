<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi;

class CfdiStatus
{
    /** @var Status\QueryStatus */
    private $request;

    /** @var Status\DocumentStatus */
    private $active;

    /** @var Status\CancellableStatus */
    private $cancellable;

    /** @var Status\CancellationStatus */
    private $cancellation;

    public function __construct(
        Status\QueryStatus $request,
        Status\DocumentStatus $active,
        Status\CancellableStatus $cancellable,
        Status\CancellationStatus $cancellation
    ) {
        $this->request = $request;
        $this->active = $active;
        $this->cancellable = $cancellable;
        $this->cancellation = $cancellation;
    }

    public function request(): Status\QueryStatus
    {
        return $this->request;
    }

    public function active(): Status\DocumentStatus
    {
        return $this->active;
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
