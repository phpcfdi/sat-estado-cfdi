<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit;

use PhpCfdi\SatEstadoCfdi\Tests\TestCase;
use PhpCfdi\SatEstadoCfdi\XmlResponseBuilder;

class XmlResponseBuilderTest extends TestCase
{
    public function testCanReadAnXmlSoapResponse(): void
    {
        $builder = new XmlResponseBuilder();
        $xml = $this->fileContentPath('soap-response.xml');
        $data = $builder->build($xml);
        $expected = [
            'CodigoEstatus' => 'S - Comprobante obtenido satisfactoriamente.',
            'EsCancelable' => 'Cancelable con aceptación',
            'Estado' => 'Vigente',
            'EstatusCancelacion' => 'Solicitud rechazada',
        ];
        $this->assertEquals($expected, $data);
    }
}
