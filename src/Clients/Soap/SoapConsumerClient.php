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
        private SoapClientFactoryInterface $soapClientFactory = new SoapClientFactory(),
    ) {
    }

    public function getSoapClientFactory(): SoapClientFactoryInterface
    {
        return $this->soapClientFactory;
    }

    /** @throws SoapFault */
    public function consume(string $uri, string $expression): ConsumerClientResponseInterface
    {
        // make soap call
        $soapClient = $this->getSoapClientFactory()->create($uri);

        // prepare arguments
        /** @psalm-var int $encoding Psalm does not undestand that encoding can be NULL */
        $encoding = null;
        $arguments = [new SoapVar($expression, $encoding, '', '', 'expresionImpresa', Constants::XMLNS_SOAP_URI)];

        // prepare options
        $options = ['soapaction' => Constants::SOAP_ACTION];

        // make call
        /** @psalm-var mixed $data */
        $data = $soapClient->__soapCall(Constants::SOAP_METHOD, $arguments, $options);

        return ConsumerClientResponse::createFromValues($data);
    }
}
