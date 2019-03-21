<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class WebServiceFactory
{
    public const URI_PRODUCTION = 'https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc';

    public const URI_DEVELOPMENT = ' https://pruebacfdiconsultaqr.cloudapp.net/ConsultaCFDIService.svc';

    /** @var ClientInterface */
    private $httpClient;

    /** @var RequestFactoryInterface */
    private $requestFactory;

    /** @var StreamFactoryInterface */
    private $streamFactory;

    public function __construct(
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory
    ) {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
    }

    public function getHttpClient(): ClientInterface
    {
        return $this->httpClient;
    }

    public function getRequestFactory(): RequestFactoryInterface
    {
        return $this->requestFactory;
    }

    public function getStreamFactory(): StreamFactoryInterface
    {
        return $this->streamFactory;
    }

    public function getXmlRequestBuilder(): XmlRequestBuilder
    {
        return new XmlRequestBuilder();
    }

    public function getXmlResponseBuilder(): XmlResponseBuilder
    {
        return new XmlResponseBuilder();
    }

    public function getConsumer(): WebServiceConsumer
    {
        return new WebServiceConsumer($this, self::URI_PRODUCTION);
    }

    public function getConsumerDevelopment(): WebServiceConsumer
    {
        return new WebServiceConsumer($this, self::URI_DEVELOPMENT);
    }
}
