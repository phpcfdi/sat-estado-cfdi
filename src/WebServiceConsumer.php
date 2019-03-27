<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi;

use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientInterface;

class WebServiceConsumer
{
    public const WEBSERVICE_URI_PRODUCTION = 'https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc';

    public const WEBSERVICE_URI_DEVELOPMENT = 'https://pruebacfdiconsultaqr.cloudapp.net/ConsultaCFDIService.svc';

    /** @var ConsumerClientInterface */
    private $client;

    /** @var string */
    private $uri;

    public function __construct(ConsumerClientInterface $factory, string $uri = self::WEBSERVICE_URI_PRODUCTION)
    {
        $this->client = $factory;
        $this->uri = $uri;
    }

    public function getClient(): ConsumerClientInterface
    {
        return $this->client;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function execute(string $expression): ResponseStatus
    {
        $responseConsumer = $this->getClient()->consume($this->getUri(), $expression);

        $builder = new ResponseStatusBuilder(
            $responseConsumer->get('CodigoEstatus'),
            $responseConsumer->get('Estado'),
            $responseConsumer->get('EsCancelable'),
            $responseConsumer->get('EstatusCancelacion')
        );

        $responseStatus = $builder->create();

        return $responseStatus;
    }
}
