<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi;

use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientInterface;
use PhpCfdi\SatEstadoCfdi\Utils\CfdiStatusBuilder;

final readonly class Consumer
{
    public const WEBSERVICE_URI_PRODUCTION = 'https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc';

    public const WEBSERVICE_URI_DEVELOPMENT = 'https://pruebacfdiconsultaqr.cloudapp.net/ConsultaCFDIService.svc';

    public function __construct(
        private ConsumerClientInterface $client,
        private string $uri = self::WEBSERVICE_URI_PRODUCTION,
    ) {
    }

    public function getClient(): ConsumerClientInterface
    {
        return $this->client;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function execute(string $expression): CfdiStatus
    {
        $responseConsumer = $this->getClient()->consume($this->getUri(), $expression);

        $builder = new CfdiStatusBuilder(
            $responseConsumer->get('CodigoEstatus'),
            $responseConsumer->get('Estado'),
            $responseConsumer->get('EsCancelable'),
            $responseConsumer->get('EstatusCancelacion'),
            $responseConsumer->get('ValidacionEFOS')
        );

        return $builder->create();
    }
}
