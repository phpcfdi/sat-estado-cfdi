<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi;

use DOMDocument;
use DOMElement;

class XmlRequestBuilder
{
    private const NS_TEMPURI = 'http://tempuri.org/';

    public function build(string $expression): string
    {
        $tempuri = self::NS_TEMPURI;
        $template = <<<"EOT"
<?xml version="1.0" encoding="UTF-8"?>
<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
    <s:Body>
        <c:Consulta xmlns:c="{$tempuri}">
            <c:expresionImpresa></c:expresionImpresa>
        </c:Consulta>
    </s:Body>
</s:Envelope>
EOT;

        /** @var DOMDocument $document */
        $document = new DOMDocument();
        $document->preserveWhiteSpace = false;
        $document->formatOutput = false;
        $document->loadXML($template);
        $expresionImpresa = $document->getElementsByTagNameNS($tempuri, 'expresionImpresa')->item(0);
        if ($expresionImpresa instanceof DOMElement) {
            $expresionImpresa->textContent = $expression;
        }
        return $document->saveXML();
    }

    public function soapAction(): string
    {
        return self::NS_TEMPURI . 'IConsultaCFDIService/Consulta';
    }
}
