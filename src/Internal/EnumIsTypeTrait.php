<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Internal;

use BadMethodCallException;

/**
 * Este rasgo permite a un enumerador consultar usando en método mágico `isCaso()`
 * @internal
 */
trait EnumIsTypeTrait
{
    /** @param mixed[] $arguments */
    public function __call(string $method, array $arguments): mixed
    {
        if (str_starts_with(strtolower($method), 'is') && strlen($method) > 2) {
            $name = substr($method, 2);
            return 0 === strcasecmp($name, $this->name);
        }
        throw new BadMethodCallException(sprintf('Call to undefined method %s::%s', self::class, $method));
    }
}
