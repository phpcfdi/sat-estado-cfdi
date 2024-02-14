<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Clients\Soap;

use PhpCfdi\SatEstadoCfdi\Contracts\Constants;
use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientInterface;
use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientResponseInterface;
use PhpCfdi\SatEstadoCfdi\Utils\ConsumerClientResponse;
use SoapFault;
use SoapVar;
use stdClass;

final readonly class SoapConsumerClient implements ConsumerClientInterface
{
    public const SOAP_OPTIONS = [
        'soapaction' => Constants::SOAP_ACTION,
    ];

    public function __construct(
        private SoapClientFactory $soapClientFactory = new SoapClientFactory(),
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
            new SoapVar($expression, $encoding, '', '', 'expresionImpresa', Constants::XMLNS_SOAP_URI),
        ];
        $options = self::SOAP_OPTIONS;

        // make call
        /** @var stdClass|array<mixed>|false $data */
        $data = $soapClient->__soapCall('Consulta', $arguments, $options);

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
}
