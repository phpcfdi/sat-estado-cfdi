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
        private string $codigoEstatus,
        private string $estado,
        private string $esCancelable,
        private string $estatusCancelacion,
        private string $validacionEFOS,
    ) {
    }

    public function createQueryStatus(): QueryStatus
    {
        // S - Comprobante obtenido satisfactoriamente
        if (str_starts_with($this->codigoEstatus, 'S - ')) {
            return QueryStatus::found();
        }
        // N - 60? ...
        return QueryStatus::notFound();
    }

    public function createDocumentSatus(): DocumentStatus
    {
        if ('Vigente' === $this->estado) {
            return DocumentStatus::active();
        }
        if ('Cancelado' === $this->estado) {
            return DocumentStatus::cancelled();
        }
        // No encontrado
        return DocumentStatus::notFound();
    }

    public function createCancellableStatus(): CancellableStatus
    {
        if ('Cancelable sin aceptación' === $this->esCancelable) {
            return CancellableStatus::cancellableByDirectCall();
        }
        if ('Cancelable con aceptación' === $this->esCancelable) {
            return CancellableStatus::cancellableByApproval();
        }
        // No cancelable
        return CancellableStatus::notCancellable();
    }

    public function createCancellationStatus(): CancellationStatus
    {
        if ('Cancelado sin aceptación' === $this->estatusCancelacion) {
            return CancellationStatus::cancelledByDirectCall();
        }
        if ('Plazo vencido' === $this->estatusCancelacion) {
            return CancellationStatus::cancelledByExpiration();
        }
        if ('Cancelado con aceptación' === $this->estatusCancelacion) {
            return CancellationStatus::cancelledByApproval();
        }
        if ('En proceso' === $this->estatusCancelacion) {
            return CancellationStatus::pending();
        }
        if ('Solicitud rechazada' === $this->estatusCancelacion) {
            return CancellationStatus::disapproved();
        }
        // vacío
        return CancellationStatus::undefined();
    }

    public function createEfosStatus(): EfosStatus
    {
        if ('200' === $this->validacionEFOS) {
            return EfosStatus::excluded();
        }
        return EfosStatus::included();
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
