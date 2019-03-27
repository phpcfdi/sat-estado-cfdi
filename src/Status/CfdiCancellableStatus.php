<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Status;

use Eclipxe\Enum\Enum;

/**
 * @method static self directMethod()
 * @method static self requestMethod()
 * @method static self notCancellable()
 *
 * @method bool isDirectMethod()
 * @method bool isRequestMethod()
 * @method bool isNotCancellable()
 */
class CfdiCancellableStatus extends Enum
{
}
