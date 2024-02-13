<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit\Clients\Soap;

use PhpCfdi\SatEstadoCfdi\Clients\Soap\SoapClientFactory;
use PhpCfdi\SatEstadoCfdi\Clients\Soap\SoapConsumerClient;
use SoapClient;
use SoapFault;
use stdClass;

final class SpySoapConsumerClient extends SoapConsumerClient
{
    public SoapClient $lastSoapClient;

    /** @var mixed[] */
    public array $lastArguments;

    /** @var array<string, mixed> */
    public array $lastOptions;

    public bool $doParentCallConsulta = false;

    /**
     * @param stdClass|mixed[]|false $callConsultaReturn
     */
    public function __construct(public $callConsultaReturn)
    {
        parent::__construct(new SoapClientFactory(['trace' => true]));
    }

    /** @return stdClass|mixed[]|false|SoapFault */
    protected function callConsulta(SoapClient $soapClient, array $arguments, array $options)
    {
        $this->lastSoapClient = $soapClient;
        $this->lastArguments = $arguments;
        $this->lastOptions = $options;

        if ($this->doParentCallConsulta) {
            try {
                return parent::callConsulta($soapClient, $arguments, $options);
            } catch (SoapFault $fault) {
                return $fault;
            }
        }

        return $this->callConsultaReturn;
    }
}
