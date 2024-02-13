<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Status;

use PhpCfdi\SatEstadoCfdi\Internal\EnumIsTypeTrait;

/**
 * @method bool isActive()
 * @method bool isCancelled()
 * @method bool isNotFound()
 */
enum DocumentStatus
{
    use EnumIsTypeTrait;

    case Active;
    case Cancelled;
    case NotFound;
}
