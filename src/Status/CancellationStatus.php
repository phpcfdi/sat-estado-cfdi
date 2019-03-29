<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Status;

use Eclipxe\Enum\Enum;

/**
 * @method static self undefined()
 * @method static self pending()
 * @method static self disapproved()
 * @method static self cancelledByApproval()
 * @method static self cancelledByExpiration()
 * @method static self cancelledByDirectCall()
 *
 * @method bool isUndefined()
 * @method bool isPending()
 * @method bool isDisapproved()
 * @method bool isCancelledByApproval()
 * @method bool isCancelledByExpiration()
 * @method bool isCancelledByDirectCall()
 */
class CancellationStatus extends Enum
{
}
