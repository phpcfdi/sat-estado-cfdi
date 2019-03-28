<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi;

class CfdiStatus
{
    /** @var Status\CfdiRequestStatus */
    private $request;

    /** @var Status\CfdiActiveStatus */
    private $active;

    /** @var Status\CfdiCancellableStatus */
    private $cancellable;

    /** @var Status\CfdiCancellationStatus */
    private $cancellation;

    public function __construct(
        Status\CfdiRequestStatus $request,
        Status\CfdiActiveStatus $active,
        Status\CfdiCancellableStatus $cancellable,
        Status\CfdiCancellationStatus $cancellation
    ) {
        $this->request = $request;
        $this->active = $active;
        $this->cancellable = $cancellable;
        $this->cancellation = $cancellation;
    }

    public function request(): Status\CfdiRequestStatus
    {
        return $this->request;
    }

    public function active(): Status\CfdiActiveStatus
    {
        return $this->active;
    }

    public function cancellable(): Status\CfdiCancellableStatus
    {
        return $this->cancellable;
    }

    public function cancellation(): Status\CfdiCancellationStatus
    {
        return $this->cancellation;
    }
}
