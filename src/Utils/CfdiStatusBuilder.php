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
final readonly class CfdiStatusBuilder
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
            return QueryStatus::Found;
        }
        // N - 60? ...
        return QueryStatus::NotFound;
    }

    public function createDocumentSatus(): DocumentStatus
    {
        return match ($this->estado) {
            'Vigente' => DocumentStatus::Active,
            'Cancelado' => DocumentStatus::Cancelled,
            default => DocumentStatus::NotFound,
        };
    }

    public function createCancellableStatus(): CancellableStatus
    {
        return match ($this->esCancelable) {
            'Cancelable sin aceptación' => CancellableStatus::CancellableByDirectCall,
            'Cancelable con aceptación' => CancellableStatus::CancellableByApproval,
            default => CancellableStatus::NotCancellable,
        };
    }

    public function createCancellationStatus(): CancellationStatus
    {
        return match ($this->estatusCancelacion) {
            'Cancelado sin aceptación' => CancellationStatus::CancelledByDirectCall,
            'Plazo vencido' => CancellationStatus::CancelledByExpiration,
            'Cancelado con aceptación' => CancellationStatus::CancelledByApproval,
            'En proceso' => CancellationStatus::Pending,
            'Solicitud rechazada' => CancellationStatus::Disapproved,
            default => CancellationStatus::Undefined,
        };
    }

    public function createEfosStatus(): EfosStatus
    {
        return match ($this->validacionEFOS) {
            '200', '201' => EfosStatus::Excluded,
            default => EfosStatus::Included,
        };
    }

    public function create(): CfdiStatus
    {
        return new CfdiStatus(
            $this->createQueryStatus(),
            $this->createDocumentSatus(),
            $this->createCancellableStatus(),
            $this->createCancellationStatus(),
            $this->createEfosStatus(),
        );
    }
}
