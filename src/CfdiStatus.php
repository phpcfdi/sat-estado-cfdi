<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi;

class CfdiStatus
{
    /** @var Status\QueryStatus */
    private $request;

    /** @var Status\DocumentStatus */
    private $document;

    /** @var Status\CancellableStatus */
    private $cancellable;

    /** @var Status\CancellationStatus */
    private $cancellation;

    public function __construct(
        Status\QueryStatus $request,
        Status\DocumentStatus $document,
        Status\CancellableStatus $cancellable,
        Status\CancellationStatus $cancellation
    ) {
        $this->request = $request;
        $this->document = $document;
        $this->cancellable = $cancellable;
        $this->cancellation = $cancellation;
    }

    public function request(): Status\QueryStatus
    {
        return $this->request;
    }

    /**
     * Use method document(), this method will be removed on version 0.7.0
     *
     * @deprecated 0.6.1:0.7.0 Due naming consistency
     * @see document()
     * @return Status\DocumentStatus
     */
    public function active(): Status\DocumentStatus
    {
        trigger_error(
            sprintf('Method %s::active() has been deprecated, use document() instead', __CLASS__),
            E_USER_DEPRECATED
        );
        return $this->document();
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
