<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit\Clients\Http;

use PhpCfdi\SatEstadoCfdi\Clients\Http\Internal\SoapXml;
use PhpCfdi\SatEstadoCfdi\Tests\TestCase;

final class SoapXmlTest extends TestCase
{
    public function testExtractDataFromXmlResponse(): void
    {
        $xml = <<< XML
            <s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
                <s:Body>
                    <ResponseResponse xmlns="http://tempuri.org/">
                        <Result xmlns:a="http://tempuri.org/a" xmlns:b="http://tempuri.org/b">
                            <first>x-first</first>
                            <a:second>x-second</a:second>
                            <b:third>x-third</b:third>
                        </Result>
                        <Result xmlns:a="http://tempuri.org/a" xmlns:b="http://tempuri.org/b">
                            <first>must be ignored</first>
                        </Result>
                    </ResponseResponse>
                </s:Body>
            </s:Envelope>
            XML;

        $soapXml = new SoapXml();
        $extracted = $soapXml->extractDataFromXmlResponse($xml, 'Result');

        $expected = [
            'first' => 'x-first',
            'second' => 'x-second',
            'third' => 'x-third',
        ];
        $this->assertSame($expected, $extracted);
    }

    public function testExtractDataFromXmlResponseWithIncorrectXml(): void
    {
        $xml = <<< XML
            <s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
                <s:Body>
                    <s:Fault>
                        <s:faultcode>SOAP-ENV:Server</s:faultcode>
                        <s:faultstring>Internal Server Error</s:faultstring>
                    </s:Fault>
                </s:Body>
            </s:Envelope>
            XML;

        $soapXml = new SoapXml();
        $extracted = $soapXml->extractDataFromXmlResponse($xml, 'Result');

        $expected = [];
        $this->assertSame($expected, $extracted);
    }

    public function testCreateConsumerClientResponse(): void
    {
        $expression = 'Expresión con caracteres & y Ñ';

        $expressionXml = htmlspecialchars($expression, ENT_XML1);
        $expected = <<< XML
            <s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
                <s:Body>
                    <c:Consulta xmlns:c="http://tempuri.org/">
                        <c:expresionImpresa>$expressionXml</c:expresionImpresa>
                    </c:Consulta>
                </s:Body>
            </s:Envelope>
            XML;

        $soapXml = new SoapXml();
        $createdXml = $soapXml->createXmlRequest($expression);

        $this->assertXmlStringEqualsXmlString($expected, $createdXml);
    }

    public function testExtractDataFromXmlResponseWhenIsEmpty(): void
    {
        $soapXml = new SoapXml();
        $extracted = $soapXml->extractDataFromXmlResponse('', 'root');
        $this->assertSame([], $extracted);
    }
}
