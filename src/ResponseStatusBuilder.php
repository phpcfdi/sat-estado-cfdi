<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi;

use PhpCfdi\SatEstadoCfdi\Status\CfdiActiveStatus;
use PhpCfdi\SatEstadoCfdi\Status\CfdiCancellableStatus;
use PhpCfdi\SatEstadoCfdi\Status\CfdiCancellationStatus;
use PhpCfdi\SatEstadoCfdi\Status\CfdiRequestStatus;

class ResponseStatusBuilder
{
    /** @var string */
    private $codigoEstatus;

    /** @var string */
    private $estado;

    /** @var string */
    private $esCancelable;

    /** @var string */
    private $estatusCancelacion;

    public function __construct(string $codigoEstatus, string $estado, string $esCancelable, string $estatusCancelacion)
    {
        $this->codigoEstatus = $codigoEstatus;
        $this->estado = $estado;
        $this->esCancelable = $esCancelable;
        $this->estatusCancelacion = $estatusCancelacion;
    }

    public function getRequestStatus(): CfdiRequestStatus
    {
        // S - Comprobante obtenido satisfactoriamente
        if (0 === strpos($this->codigoEstatus, 'S - ')) {
            return CfdiRequestStatus::found();
        }
        // N - 60? ...
        return CfdiRequestStatus::notFound();
    }

    public function getActiveStatus(): CfdiActiveStatus
    {
        if ('Vigente' === $this->estado) {
            return CfdiActiveStatus::active();
        }
        if ('Cancelado' === $this->estado) {
            return CfdiActiveStatus::cancelled();
        }
        // No encontrado
        return CfdiActiveStatus::notFound();
    }

    public function getCancellableStatus(): CfdiCancellableStatus
    {
        if ('Cancelable sin aceptación' === $this->esCancelable) {
            return CfdiCancellableStatus::directMethod();
        }
        if ('Cancelable con aceptación' === $this->esCancelable) {
            return CfdiCancellableStatus::requestMethod();
        }
        // No cancelable
        return CfdiCancellableStatus::notCancellable();
    }

    public function getCancellationStatus(): CfdiCancellationStatus
    {
        if ('Cancelado sin aceptación' === $this->estatusCancelacion) {
            return CfdiCancellationStatus::cancelDirect();
        }
        if ('En proceso' === $this->estatusCancelacion) {
            return CfdiCancellationStatus::pending();
        }
        if ('Plazo vencido' === $this->estatusCancelacion) {
            return CfdiCancellationStatus::cancelByTimeout();
        }
        if ('Cancelado con aceptación' === $this->estatusCancelacion) {
            return CfdiCancellationStatus::cancelByRequest();
        }
        if ('Solicitud rechazada' === $this->estatusCancelacion) {
            return CfdiCancellationStatus::rejected();
        }
        // vacío
        return CfdiCancellationStatus::undefined();
    }

    public function create(): ResponseStatus
    {
        return new ResponseStatus(
            $this->getRequestStatus(),
            $this->getActiveStatus(),
            $this->getCancellableStatus(),
            $this->getCancellationStatus()
        );
    }
}
