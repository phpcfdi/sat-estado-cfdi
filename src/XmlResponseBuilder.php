<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi;

use DOMDocument;

class XmlResponseBuilder
{
    private const NS_TEMPURI = 'http://tempuri.org/';

    public function build(string $source): array
    {
        $extracted = [];
        $document = new DOMDocument();
        $document->loadXML($source);
        /** @var \DOMElement $consultaResult */
        foreach ($document->getElementsByTagNameNS(self::NS_TEMPURI, 'ConsultaResult') as $consultaResult) {
            /** @var \DOMNode $children */
            foreach ($consultaResult->childNodes as $children) {
                if (XML_ELEMENT_NODE !== $children->nodeType) {
                    continue;
                }
                $extracted[$children->localName] = $children->textContent;
            }
            break; // exit loop if for any reason got more than 1 element ConsultaResult
        }
        return $extracted;
    }
}
