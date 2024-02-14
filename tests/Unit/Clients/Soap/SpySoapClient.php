<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit\Clients\Soap;

use SoapClient;

final class SpySoapClient extends SoapClient
{
    /** @var array<mixed> */
    public array $createdWithOptions;

    /** @param array<mixed> $options */
    public function __construct(?string $wsdl, array $options = [])
    {
        $this->createdWithOptions = $options;
        parent::__construct($wsdl, $options);
    }
}
