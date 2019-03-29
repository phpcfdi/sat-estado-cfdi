<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Status;

use Eclipxe\Enum\Enum;

/**
 * @method static self cancellableByDirectCall()
 * @method static self cancellableByApproval()
 * @method static self notCancellable()
 *
 * @method bool isCancellableByDirectCall()
 * @method bool isCancellableByApproval()
 * @method bool isNotCancellable()
 */
class CancellableStatus extends Enum
{
}
