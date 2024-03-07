<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Status;

use PhpCfdi\SatEstadoCfdi\Internal\EnumIsTypeTrait;

/**
 * @method bool isUndefined()
 * @method bool isPending()
 * @method bool isDisapproved()
 * @method bool isCancelledByApproval()
 * @method bool isCancelledByExpiration()
 * @method bool isCancelledByDirectCall()
 */
enum CancellationStatus
{
    use EnumIsTypeTrait;

    case Undefined;
    case Pending;
    case Disapproved;
    case CancelledByApproval;
    case CancelledByExpiration;
    case CancelledByDirectCall;
}
