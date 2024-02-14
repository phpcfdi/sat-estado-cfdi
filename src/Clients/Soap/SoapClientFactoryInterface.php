<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Clients\Soap;

use SoapClient;

interface SoapClientFactoryInterface
{
    public function create(string $serviceLocation): SoapClient;
}
