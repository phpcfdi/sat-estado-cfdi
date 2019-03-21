<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi;

class ResponseStatus
{
    /** @var Status\CfdiRequestStatus */
    private $request;

    /** @var Status\CfdiActiveStatus */
    private $active;

    /** @var Status\CfdiCancellableStatus */
    private $cancellabe;

    /** @var Status\CfdiCancellationStatus */
    private $cancellation;

    public function __construct(
        Status\CfdiRequestStatus $request,
        Status\CfdiActiveStatus $active,
        Status\CfdiCancellableStatus $cancellabe,
        Status\CfdiCancellationStatus $cancellation
    ) {
        $this->request = $request;
        $this->active = $active;
        $this->cancellabe = $cancellabe;
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

    public function cancellabe(): Status\CfdiCancellableStatus
    {
        return $this->cancellabe;
    }

    public function cancellation(): Status\CfdiCancellationStatus
    {
        return $this->cancellation;
    }
}
