<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Utils;

use PhpCfdi\SatEstadoCfdi\CfdiStatus;
use PhpCfdi\SatEstadoCfdi\Status\CancellableStatus;
use PhpCfdi\SatEstadoCfdi\Status\CancellationStatus;
use PhpCfdi\SatEstadoCfdi\Status\DocumentStatus;
use PhpCfdi\SatEstadoCfdi\Status\EfosStatus;
use PhpCfdi\SatEstadoCfdi\Status\QueryStatus;

/**
 * Use this object to create a CfdiStatus from the raw string states from SAT webservice
 */
class CfdiStatusBuilder
{
    public function __construct(
        private readonly string $codigoEstatus,
        private readonly string $estado,
        private readonly string $esCancelable,
        private readonly string $estatusCancelacion,
        private readonly string $validacionEFOS,
    ) {
    }

    public function createQueryStatus(): QueryStatus
    {
        // S - Comprobante obtenido satisfactoriamente
        if (str_starts_with($this->codigoEstatus, 'S - ')) {
            return QueryStatus::Found;
        }
        // N - 60? ...
        return QueryStatus::NotFound;
    }

    public function createDocumentSatus(): DocumentStatus
    {
        if ('Vigente' === $this->estado) {
            return DocumentStatus::Active;
        }
        if ('Cancelado' === $this->estado) {
            return DocumentStatus::Cancelled;
        }
        // No encontrado
        return DocumentStatus::NotFound;
    }

    public function createCancellableStatus(): CancellableStatus
    {
        if ('Cancelable sin aceptación' === $this->esCancelable) {
            return CancellableStatus::CancellableByDirectCall;
        }
        if ('Cancelable con aceptación' === $this->esCancelable) {
            return CancellableStatus::CancellableByApproval;
        }
        // No cancelable
        return CancellableStatus::NotCancellable;
    }

    public function createCancellationStatus(): CancellationStatus
    {
        if ('Cancelado sin aceptación' === $this->estatusCancelacion) {
            return CancellationStatus::CancelledByDirectCall;
        }
        if ('Plazo vencido' === $this->estatusCancelacion) {
            return CancellationStatus::CancelledByExpiration;
        }
        if ('Cancelado con aceptación' === $this->estatusCancelacion) {
            return CancellationStatus::CancelledByApproval;
        }
        if ('En proceso' === $this->estatusCancelacion) {
            return CancellationStatus::Pending;
        }
        if ('Solicitud rechazada' === $this->estatusCancelacion) {
            return CancellationStatus::Disapproved;
        }
        // vacío
        return CancellationStatus::Undefined;
    }

    public function createEfosStatus(): EfosStatus
    {
        if ('200' === $this->validacionEFOS) {
            return EfosStatus::Excluded;
        }
        return EfosStatus::Included;
    }

    public function create(): CfdiStatus
    {
        return new CfdiStatus(
            $this->createQueryStatus(),
            $this->createDocumentSatus(),
            $this->createCancellableStatus(),
            $this->createCancellationStatus(),
            $this->createEfosStatus()
        );
    }
}
