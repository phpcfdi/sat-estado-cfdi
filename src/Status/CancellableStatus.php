<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Status;

use PhpCfdi\SatEstadoCfdi\Internal\EnumIsTypeTrait;

/**
 * @method bool isCancellableByDirectCall()
 * @method bool isCancellableByApproval()
 * @method bool isNotCancellable()
 */
enum CancellableStatus
{
    use EnumIsTypeTrait;

    case CancellableByDirectCall;
    case CancellableByApproval;
    case NotCancellable;
}
