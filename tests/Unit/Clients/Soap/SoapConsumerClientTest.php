<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit\Clients\Soap;

use ArrayObject;
use PhpCfdi\SatEstadoCfdi\Clients\Soap\SoapClientFactory;
use PhpCfdi\SatEstadoCfdi\Clients\Soap\SoapConsumerClient;
use PhpCfdi\SatEstadoCfdi\Contracts\Constants;
use PhpCfdi\SatEstadoCfdi\Tests\TestCase;
use PhpCfdi\SatEstadoCfdi\Utils\ConsumerClientResponse;
use PHPUnit\Framework\MockObject\MockObject;
use SoapClient;
use SoapFault;
use SoapVar;

final class SoapConsumerClientTest extends TestCase
{
    private function createClientWithMockSoapClient(mixed $expectedResultValues): SoapConsumerClient
    {
        /** @var SoapClient&MockObject $soapClient */
        $soapClient = $this->createMock(SoapClient::class);
        $soapClient->expects($this->once())
            ->method('__soapCall')
            ->willReturn($expectedResultValues);

        $factory = new FakeSoapClientFactory($soapClient);

        return new SoapConsumerClient($factory);
    }

    public function testConsumerClientCanBeCreatedWithoutArguments(): void
    {
        $client = new SoapConsumerClient();
        $this->assertInstanceOf(SoapClientFactory::class, $client->soapClientFactory);
    }

    public function testConsumerClientCanBeCreatedWithSoapClientFactory(): void
    {
        $factory = new SoapClientFactory();
        $client = new SoapConsumerClient($factory);
        $this->assertSame($factory, $client->soapClientFactory);
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

    /**
     * This test check the call to SoapClient::__soapCall arguments: name, args & options.
     */
    public function testConsumeSoapCall(): void
    {
        $expression = '?a=foo&amp;b=bar';
        $soapLocation = 'https://non-existent-domain.tld/non-existent-service';
        $soapClient = new SpySoapClient(null, [
            'uri' => Constants::XMLNS_SOAP_URI,
            'location' => $soapLocation,
            'trace' => true,
        ]);
        $factory = new FakeSoapClientFactory($soapClient);
        $client = new SoapConsumerClient($factory);

        try {
            @$client->consume(Constants::SOAP_ACTION, $expression);
        } catch (SoapFault) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement
        }

        $soapCall = $soapClient->getLastSoapCall();
        $soapVar = $soapCall['args'][0] ?? null;

        $this->assertSame(Constants::SOAP_METHOD, $soapCall['name']);

        $this->assertInstanceOf(SoapVar::class, $soapVar);
        $this->assertSame('expresionImpresa', $soapVar->enc_name);
        $this->assertSame($expression, $soapVar->enc_value);

        $this->assertSame(['soapaction' => Constants::SOAP_ACTION], $soapCall['options']);
    }
}
