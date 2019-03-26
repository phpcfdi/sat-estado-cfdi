<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Status;

use Eclipxe\Enum\Enum;

/**
 * @method static self undefined()
 * @method static self pending()
 * @method static self cancelDirect()
 * @method static self cancelByRequest()
 * @method static self cancelByTimeout()
 * @method static self rejected()
 * @method bool isUndefined()
 * @method bool isPending()
 * @method bool isCancelDirect()
 * @method bool isCancelByRequest()
 * @method bool isCancelByTimeout()
 * @method bool isRejected()
 */
class CfdiCancellationStatus extends Enum
{
}
