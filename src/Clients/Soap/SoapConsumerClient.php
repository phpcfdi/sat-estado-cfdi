<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Clients\Soap;

use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientInterface;
use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientResponseInterface;
use PhpCfdi\SatEstadoCfdi\Utils\ConsumerClientResponse;
use SoapClient;
use SoapFault;
use SoapVar;
use stdClass;

class SoapConsumerClient implements ConsumerClientInterface
{
    public const SOAP_OPTIONS = [
        'soapaction' => 'http://tempuri.org/IConsultaCFDIService/Consulta',
    ];

    public function __construct(
        private readonly SoapClientFactory $soapClientFactory = new SoapClientFactory(),
    ) {
    }

    public function getSoapClientFactory(): SoapClientFactory
    {
        return $this->soapClientFactory;
    }

    /** @throws SoapFault */
    public function consume(string $uri, string $expression): ConsumerClientResponseInterface
    {
        // prepare call
        /** @var int $encoding Override because inspectors does not know that second argument can be NULL */
        $encoding = null;
        $soapClient = $this->getSoapClientFactory()->create($uri);
        $arguments = [
            new SoapVar($expression, $encoding, '', '', 'expresionImpresa', 'http://tempuri.org/'),
        ];
        $options = self::SOAP_OPTIONS;

        // make call
        $data = $this->callConsulta($soapClient, $arguments, $options);

        // process call
        if (is_array($data)) {
            $data = (object) $data;
        }
        if (! ($data instanceof stdClass)) {
            $data = new stdClass();
        }
        $clientResponse = new ConsumerClientResponse();
        /** @psalm-var mixed $value */
        foreach (get_object_vars($data) as $key => $value) {
            if (is_scalar($value)) {
                $clientResponse->set(strval($key), strval($value));
            }
        }

        return $clientResponse;
    }

    /**
     * This method is abstracted here to be able to mock responses in tests.
     *
     * @param mixed[] $arguments
     * @param array<string, mixed> $options
     * @return stdClass|mixed[]|false
     * @throws SoapFault
     */
    protected function callConsulta(SoapClient $soapClient, array $arguments, array $options)
    {
        /** @var stdClass|mixed[]|false $return */
        $return = $soapClient->__soapCall('Consulta', $arguments, $options);
        return $return;
    }
}
