<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Contracts;

interface Constants
{
    public const SOAP_ACTION = 'http://tempuri.org/IConsultaCFDIService/Consulta';

    public const XMLNS_SOAP_URI = 'http://tempuri.org/';

    public const XMLNS_ENVELOPE = 'http://schemas.xmlsoap.org/soap/envelope/';
}
