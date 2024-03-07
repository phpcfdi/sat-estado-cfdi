<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Status;

use PhpCfdi\SatEstadoCfdi\Internal\EnumIsTypeTrait;

/**
 * @method bool isFound()
 * @method bool isNotFound()
 */
enum QueryStatus
{
    use EnumIsTypeTrait;

    case Found;
    case NotFound;
}
