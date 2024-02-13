<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Clients\Http\Internal;

use DOMDocument;
use DOMElement;

/**
 * Class to create requests and process responses
 * This class is an extraction from HttpConsumerClient to clarify its responsabilities
 *
 * @internal
 */
class SoapXml
{
    private const XML_NAMESPACE = 'http://tempuri.org/';

    /**
     * Extract the information from expected soap response
     *
     * @return array<string, string>
     */
    public function extractDataFromXmlResponse(string $xmlResponse, string $elementName): array
    {
        if ('' === $xmlResponse) {
            return [];
        }

        $document = new DOMDocument();
        $document->loadXML($xmlResponse);

        $consultaResult = $this->obtainFirstElement($document, $elementName);
        if (null === $consultaResult) {
            return [];
        }

        $extracted = [];
        foreach ($consultaResult->childNodes as $children) {
            if (! $children instanceof DOMElement) {
                continue;
            }
            $extracted[$children->localName] = $children->textContent;
        }

        return $extracted;
    }

    private function obtainFirstElement(DOMDocument $document, string $elementName): ?DOMElement
    {
        /** @var iterable<DOMElement> $elements */
        $elements = $document->getElementsByTagNameNS(self::XML_NAMESPACE, $elementName);
        foreach ($elements as $consultaResult) {
            return $consultaResult;
        }
        return null;
    }

    public function createXmlRequest(string $expression): string
    {
        $soap = 'http://schemas.xmlsoap.org/soap/envelope/';
        $document = new DOMDocument('1.0', 'UTF-8');
        $document->appendChild($document->createElementNS($soap, 's:Envelope'))
            ->appendChild($document->createElementNS($soap, 's:Body'))
            ->appendChild($document->createElementNS(self::XML_NAMESPACE, 'c:Consulta'))
            ->appendChild($document->createElementNS(self::XML_NAMESPACE, 'c:expresionImpresa'))
            ->appendChild($document->createTextNode($expression));
        return strval($document->saveXML());
    }
}