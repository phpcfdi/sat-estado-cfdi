<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit\Clients\Soap;

use Exception;
use SoapClient;
use SoapFault;
use SoapHeader;

final class SpySoapClient extends SoapClient
{
    /** @var array<mixed> */
    public array $createdWithOptions;

    /** @var array<int, array{name: string, args: array<mixed>, options: array<mixed>|null}> */
    public array $soapCalls = [];

    /** @param array<mixed> $options */
    public function __construct(?string $wsdl, array $options = [])
    {
        $this->createdWithOptions = $options;
        parent::__construct($wsdl, $options);
    }

    /**
     * @phpstan-param array<mixed> $args
     * @phpstan-param array<mixed>|null $options
     * @phpstan-param array<mixed>|SoapHeader|null $inputHeaders
     * @phpstan-param mixed $outputHeaders
     * @throws SoapFault
     */
    public function __soapCall(
        string $name,
        array $args,
        array|null $options = null,
        mixed $inputHeaders = null,
        mixed &$outputHeaders = null,
    ): mixed {
        $this->soapCalls[] = [
            'name' => $name,
            'args' => $args,
            'options' => $options,
        ];

        return parent::__soapCall($name, $args, $options, $inputHeaders, $outputHeaders);
    }

    /**
     * @return array{name: string, args: array<mixed>, options: array<mixed>|null}
     * @throws Exception If there is no registered SOAP calls
     */
    public function getLastSoapCall(): array
    {
        if ([] === $this->soapCalls) {
            throw new Exception('There is no registered SOAP calls');
        }
        return $this->soapCalls[array_key_last($this->soapCalls)];
    }
}
