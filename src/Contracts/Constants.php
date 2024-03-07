<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Contracts;

interface Constants
{
    public const WEBSERVICE_URI_PRODUCTION = 'https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc';

    public const WEBSERVICE_URI_DEVELOPMENT = 'https://pruebacfdiconsultaqr.cloudapp.net/ConsultaCFDIService.svc';

    public const SOAP_ACTION = 'http://tempuri.org/IConsultaCFDIService/Consulta';

    public const SOAP_METHOD = 'Consulta';

    public const XMLNS_SOAP_URI = 'http://tempuri.org/';

    public const XMLNS_ENVELOPE = 'http://schemas.xmlsoap.org/soap/envelope/';
}
