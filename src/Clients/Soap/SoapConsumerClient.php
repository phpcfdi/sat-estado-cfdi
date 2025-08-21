<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Clients\Soap;

use PhpCfdi\SatEstadoCfdi\Contracts\Constants;
use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientInterface;
use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientResponseInterface;
use PhpCfdi\SatEstadoCfdi\Utils\ConsumerClientResponse;
use SoapFault;
use SoapVar;

final readonly class SoapConsumerClient implements ConsumerClientInterface
{
    public function __construct(
        public SoapClientFactoryInterface $soapClientFactory = new SoapClientFactory(),
    ) {
    }

    /** @throws SoapFault */
    public function consume(string $uri, string $expression): ConsumerClientResponseInterface
    {
        // make soap call
        $soapClient = $this->soapClientFactory->create($uri);

        // prepare arguments
        $encoding = null;
        $arguments = [new SoapVar($expression, $encoding, '', '', 'expresionImpresa', Constants::XMLNS_SOAP_URI)];

        // prepare options
        $options = ['soapaction' => Constants::SOAP_ACTION];

        // make call
        $data = $soapClient->__soapCall(Constants::SOAP_METHOD, $arguments, $options);

        return ConsumerClientResponse::createFromValues($data);
    }
}
