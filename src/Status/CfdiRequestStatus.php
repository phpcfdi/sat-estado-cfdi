<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Status;

use Spatie\Enum\Enum;

/**
 * @method static self found()
 * @method static self notFound()
 * @method bool isFound()
 * @method bool isNotFound()
 */
class CfdiRequestStatus extends Enum
{
}
