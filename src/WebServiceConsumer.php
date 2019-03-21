<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class WebServiceConsumer
{
    /** @var WebServiceFactory */
    private $factory;

    /** @var string */
    private $uri;

    public function __construct(WebServiceFactory $factory, string $uri)
    {
        $this->factory = $factory;
        $this->uri = $uri;
    }

    public function getFactory(): WebServiceFactory
    {
        return $this->factory;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function buildRequest(string $expression): RequestInterface
    {
        $builder = $this->factory->getXmlRequestBuilder();

        // request
        $request = $this->factory->getRequestFactory()->createRequest('POST', $this->uri);

        // headers
        $request = $request->withHeader('Content-Type', 'text/xml; charset=utf-8');
        $request = $request->withHeader('SOAPAction', $builder->soapAction());
        $request = $request->withHeader('cache-control', 'no-cache');

        // body
        $xml = $builder->build($expression);
        $body = $this->factory->getStreamFactory()->createStream($xml);
        $request = $request->withBody($body);

        return $request;
    }

    public function dataFromResponse(ResponseInterface $response): array
    {
        $body = $response->getBody()->getContents();
        $interpreter = $this->factory->getXmlResponseBuilder();
        $data = $interpreter->build($body);
        return $data;
    }

    public function responseStatusFromData(array $data): ResponseStatus
    {
        // TODO: this should be in factory?
        $builder = ResponseStatusBuilder::fromArray($data);
        $responseStatus = $builder->create();
        return $responseStatus;
    }

    public function execute(string $expression): ResponseStatus
    {
        $request = $this->buildRequest($expression);
        $response = $this->factory->getHttpClient()->sendRequest($request);
        $data = $this->dataFromResponse($response);
        $responseStatus = $this->responseStatusFromData($data);
        return $responseStatus;
    }
}
