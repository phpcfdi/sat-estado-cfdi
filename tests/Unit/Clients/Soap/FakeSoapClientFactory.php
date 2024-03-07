<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit\Clients\Soap;

use PhpCfdi\SatEstadoCfdi\Clients\Soap\SoapClientFactoryInterface;
use SoapClient;

final readonly class FakeSoapClientFactory implements SoapClientFactoryInterface
{
    public function __construct(private SoapClient $predefinedSoapClient)
    {
    }

    public function create(string $serviceLocation): SoapClient
    {
        return $this->predefinedSoapClient;
    }
}
