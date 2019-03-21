<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi;

use DOMDocument;
use DOMElement;

class CfdiExpressionBuilder
{
    /** @var string Namespace of Comprobante (CFDI) version 3.2 & 3.3 */
    public const NS_CFDI = 'http://www.sat.gob.mx/cfd/3';

    /** @var string Namespace of Timbre Fiscal Digital version 1.0 & 1.1 */
    public const NS_TFD = 'http://www.sat.gob.mx/TimbreFiscalDigital';

    /** @var DOMElement */
    private $comprobante;

    /** @var string */
    private $version;

    public function __construct(DOMDocument $document)
    {
        $this->comprobante = $this->extractComprobante($document);
        $this->version = $this->extractVersion($this->comprobante);
    }

    public static function createFromString(string $source): self
    {
        $document = new DOMDocument();
        $document->loadXML($source);
        return new self($document);
    }

    protected function extractComprobante(DOMDocument $document): DOMElement
    {
        /** @var DOMElement|null $comprobante */
        $comprobante = $document->documentElement;
        if (null === $comprobante) {
            throw new \RuntimeException('Xml document does not have root element');
        }
        if ('Comprobante' !== $comprobante->localName) {
            throw new \RuntimeException('Xml root element is not Comprobante');
        }
        if (self::NS_CFDI !== $comprobante->namespaceURI) {
            throw new \RuntimeException(sprintf('Xml root element does not belong to %s', self::NS_CFDI));
        }

        return $comprobante;
    }

    protected function extractVersion(DOMElement $comprobante): string
    {
        if ('3.2' === $comprobante->getAttribute('version')) {
            return '3.2';
        }
        if ('3.3' === $comprobante->getAttribute('Version')) {
            return '3.3';
        }

        throw new \RuntimeException(sprintf('Cannot extract cfdi version from Comprobante'));
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function isVersion32(): bool
    {
        return '3.2' === $this->getVersion();
    }

    public function isVersion33(): bool
    {
        return '3.3' === $this->getVersion();
    }

    public function getRfcEmisor(): string
    {
        $emisor = $this->getFirstElementByTagNameNS(self::NS_CFDI, 'Emisor');
        if (null === $emisor) {
            return '';
        }
        return $emisor->getAttribute(($this->isVersion33()) ? 'Rfc' : 'rfc');
    }

    public function getRfcReceptor(): string
    {
        $receptor = $this->getFirstElementByTagNameNS(self::NS_CFDI, 'Receptor');
        if (null === $receptor) {
            return '';
        }
        return $receptor->getAttribute(($this->isVersion33()) ? 'Rfc' : 'rfc');
    }

    public function getTotal(): string
    {
        return $this->comprobante->getAttribute(($this->isVersion33()) ? 'Total' : 'total');
    }

    public function getUuid(): string
    {
        $tfd = $this->getFirstElementByTagNameNS(self::NS_TFD, 'TimbreFiscalDigital');
        if (null === $tfd) {
            return '';
        }
        return $tfd->getAttribute('UUID');
    }

    public function getSello(): string
    {
        return $this->comprobante->getAttribute(($this->isVersion33()) ? 'Sello' : 'sello');
    }

    public function build(): CfdiExpression
    {
        return new CfdiExpression(
            $this->getVersion(),
            $this->getRfcEmisor(),
            $this->getRfcReceptor(),
            $this->getTotal(),
            $this->getUuid(),
            $this->getSello()
        );
    }

    protected function getFirstElementByTagNameNS(string $namespaceURI, string $localname): ?DOMElement
    {
        $elements = $this->comprobante->getElementsByTagNameNS($namespaceURI, $localname);
        $element = $elements->item(0);
        if ($element instanceof DOMElement) {
            return $element;
        }
        return null;
    }
}
