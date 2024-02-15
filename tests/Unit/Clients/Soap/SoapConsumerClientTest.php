<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit\Clients\Soap;

use ArrayObject;
use DOMDocument;
use DOMXPath;
use PhpCfdi\SatEstadoCfdi\Clients\Soap\SoapClientFactory;
use PhpCfdi\SatEstadoCfdi\Clients\Soap\SoapClientFactoryInterface;
use PhpCfdi\SatEstadoCfdi\Clients\Soap\SoapConsumerClient;
use PhpCfdi\SatEstadoCfdi\Contracts\Constants;
use PhpCfdi\SatEstadoCfdi\Tests\TestCase;
use PhpCfdi\SatEstadoCfdi\Utils\ConsumerClientResponse;
use PHPUnit\Framework\MockObject\MockObject;
use SoapClient;
use SoapFault;

final class SoapConsumerClientTest extends TestCase
{
    private function createClientWithMockSoapClient(mixed $expectedResultValues): SoapConsumerClient
    {
        /** @var SoapClient&MockObject $soapClient */
        $soapClient = $this->createMock(SoapClient::class);
        $soapClient->expects($this->once())
            ->method('__soapCall')
            ->willReturn($expectedResultValues);

        /** @var SoapClientFactoryInterface&MockObject $factory */
        $factory = $this->createMock(SoapClientFactoryInterface::class);
        $factory->expects($this->once())
            ->method('create')
            ->willReturn($soapClient);

        return new SoapConsumerClient($factory);
    }

    public function testConsumerClientCanBeCreatedWithoutArguments(): void
    {
        $client = new SoapConsumerClient();
        $this->assertInstanceOf(SoapClientFactory::class, $client->getSoapClientFactory());
    }

    public function testConsumerClientCanBeCreatedWithSoapClientFactory(): void
    {
        $factory = new SoapClientFactory();
        $client = new SoapConsumerClient($factory);
        $this->assertSame($factory, $client->getSoapClientFactory());
    }

    public function testConsumeReceivingFalseAsResponse(): void
    {
        $client = $this->createClientWithMockSoapClient(false);

        $response = $client->consume('serviceUri', 'expression');

        $this->assertSame('', $response->get('EstadoConsulta'));
    }

    public function testConsumeReceivingArrayAsResponse(): void
    {
        $callReturn = ['EstadoConsulta' => 'X - dummy!'];
        $client = $this->createClientWithMockSoapClient($callReturn);

        $response = $client->consume('serviceUri', 'expression');

        $this->assertSame('X - dummy!', $response->get('EstadoConsulta'));
    }

    public function testConsumeReceivingObjectAsResponse(): void
    {
        $callReturn = (object) ['EstadoConsulta' => 'X - dummy!'];
        $client = $this->createClientWithMockSoapClient($callReturn);

        $response = $client->consume('serviceUri', 'expression');

        $this->assertSame('X - dummy!', $response->get('EstadoConsulta'));
    }

    public function testConsumeReceivingTraversableAsResponse(): void
    {
        $callReturn = new ArrayObject(['EstadoConsulta' => 'X - dummy!']);
        $client = $this->createClientWithMockSoapClient($callReturn);

        $response = $client->consume('serviceUri', 'expression');

        $this->assertSame('X - dummy!', $response->get('EstadoConsulta'));
    }

    public function testMethodCallConsulta(): void
    {
        $expectedResultValues = ['x-key' => 'x-value'];
        $expectedResult = new ConsumerClientResponse($expectedResultValues);
        $client = $this->createClientWithMockSoapClient((object) $expectedResultValues);

        $value = $client->consume('serviceUri', '...expression');

        $this->assertEquals($expectedResult, $value);
    }

    public function testSoapRequestContent(): void
    {
        $expectedExpression = 'expression';
        $soapUriHost = 'localhost';
        $soapUriPath = '/non-existent-service';
        $soapUri = sprintf('%s://%s%s', 'http', $soapUriHost, $soapUriPath);
        $expectedHeaderHost = sprintf('Host: %s', $soapUriHost);
        $expectedHeaderCommand = sprintf('POST %s', $soapUriPath);
        $expectedHeaderHostAction = sprintf('SOAPAction: "%s"', Constants::SOAP_ACTION);
        $soapClient = new SoapClient(null, [
            'uri' => Constants::XMLNS_SOAP_URI,
            'location' => $soapUri,
            'trace' => true,
        ]);
        /** @var SoapClientFactoryInterface&MockObject $factory */
        $factory = $this->createMock(SoapClientFactoryInterface::class);
        $factory->expects($this->once())
            ->method('create')
            ->willReturn($soapClient);
        $client = new SoapConsumerClient($factory);

        try {
            $client->consume($soapUri, $expectedExpression);
        } catch (SoapFault) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement
        }

        $requestHeaders = (string) $soapClient->__getLastRequestHeaders();
        $this->assertStringContainsString($expectedHeaderHost, $requestHeaders);
        $this->assertStringContainsString($expectedHeaderCommand, $requestHeaders);
        $this->assertStringContainsString($expectedHeaderHostAction, $requestHeaders);

        $requestBody = (string) $soapClient->__getLastRequest();
        $document = new DOMDocument();
        $document->preserveWhiteSpace = false;
        $document->formatOutput = true;
        $document->loadXML($requestBody);
        $xpath = new DOMXPath($document, false);
        $xpath->registerNamespace('e', Constants::XMLNS_ENVELOPE);
        $xpath->registerNamespace('x', Constants::XMLNS_SOAP_URI);

        $this->assertSame(
            $expectedExpression,
            ($xpath->query('//x:Consulta/x:expresionImpresa/text()') ?: null)?->item(0)?->textContent,
        );
    }
}
