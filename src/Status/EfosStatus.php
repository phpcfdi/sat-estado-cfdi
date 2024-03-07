<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Status;

use PhpCfdi\SatEstadoCfdi\Internal\EnumIsTypeTrait;

/**
 * @method bool isIncluded()
 * @method bool isExcluded()
 */
enum EfosStatus
{
    use EnumIsTypeTrait;

    case Included;
    case Excluded;
}
