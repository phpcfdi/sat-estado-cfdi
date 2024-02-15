<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Clients\Http;

use PhpCfdi\SatEstadoCfdi\Clients\Http\Internal\SoapXml;
use PhpCfdi\SatEstadoCfdi\Contracts\Constants;
use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientInterface;
use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientResponseInterface;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final readonly class HttpConsumerClient implements ConsumerClientInterface
{
    private SoapXml $soapXml;

    public function __construct(
        private HttpConsumerFactoryInterface $factory,
    ) {
        $this->soapXml = new SoapXml();
    }

    public function getFactory(): HttpConsumerFactoryInterface
    {
        return $this->factory;
    }

    public function createHttpRequest(string $uri, string $expression): RequestInterface
    {
        // request
        $request = $this->factory->requestFactory()->createRequest('POST', $uri);

        // headers
        $request = $request->withHeader('Content-Type', 'text/xml; charset=utf-8');
        $request = $request->withHeader('SOAPAction', Constants::SOAP_ACTION);

        // body
        $xml = $this->soapXml->createXmlRequest($expression);
        $body = $this->factory->streamFactory()->createStream($xml);

        return $request->withBody($body);
    }

    public function createConsumerClientResponse(string $xmlResponse): ConsumerClientResponseInterface
    {
        $values = $this->soapXml->extractDataFromXmlResponse($xmlResponse, 'ConsultaResult');

        return $this->factory->newConsumerClientResponse($values);
    }

    /** @throws ClientExceptionInterface */
    public function consume(string $uri, string $expression): ConsumerClientResponseInterface
    {
        // parameters --convert--> request --httpCall--> response --convert--> ConsumerClientResponse
        $request = $this->createHttpRequest($uri, $expression);
        $response = $this->sendRequest($request);
        return $this->createConsumerClientResponse($response->getBody()->__toString());
    }

    /**
     * This method is abstracted here to be able to mock responses in tests.
     *
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->factory->httpClient()->sendRequest($request);
    }
}
