<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit\Clients\Soap;

use PhpCfdi\SatEstadoCfdi\Clients\Soap\SoapClientFactory;
use PhpCfdi\SatEstadoCfdi\Clients\Soap\SoapConsumerClient;
use PhpCfdi\SatEstadoCfdi\Tests\TestCase;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use SoapClient;
use SoapVar;

final class SoapConsumerClientTest extends TestCase
{
    public function testConsumerClientCanBeCreatedWithoutArguments(): void
    {
        $client = new SoapConsumerClient();
        $this->assertInstanceOf(SoapClientFactory::class, $client->getSoapClientFactory());
    }

    public function testConsumerClientCanBeCreatedWithSoapclientFactory(): void
    {
        $factory = new SoapClientFactory();
        $client = new SoapConsumerClient($factory);
        $this->assertSame($factory, $client->getSoapClientFactory());
    }

    public function testConsumeReceivingFalseAsResponse(): void
    {
        $client = new SpySoapConsumerClient(false);

        $response = $client->consume('serviceUri', 'expression');
        $this->assertSame('', $response->get('EstadoConsulta'));
    }

    public function testConsumeSpyCallConsulta(): void
    {
        $soapConsumerClient = new SpySoapConsumerClient(false);
        $soapConsumerClient->doParentCallConsulta = true;
        $expression = 'expression';
        $soapUri = 'http://localhost/non-existent-service';
        $soapConsumerClient->consume($soapUri, $expression);

        $requestHeaders = (string) $soapConsumerClient->lastSoapClient->__getLastRequestHeaders();
        $this->assertStringContainsString('Host: localhost', $requestHeaders);
        $this->assertStringContainsString('POST /non-existent-service', $requestHeaders);

        $argument = $soapConsumerClient->lastArguments[0] ?? null;
        $this->assertNotNull($argument);
        $this->assertInstanceOf(SoapVar::class, $argument);
        $this->assertSame($expression, $argument->enc_value);

        $options = $soapConsumerClient->lastOptions;
        $this->assertSame($soapConsumerClient::SOAP_OPTIONS, $options);
    }

    public function testConsumeReceivingArrayAsResponse(): void
    {
        $callReturn = ['EstadoConsulta' => 'X - dummy!'];
        $client = new SpySoapConsumerClient($callReturn);

        $response = $client->consume('serviceUri', 'expression');
        $this->assertSame('X - dummy!', $response->get('EstadoConsulta'));
    }

    public function testConsumeReceivingStdclassAsResponse(): void
    {
        $callReturn = (object) ['EstadoConsulta' => 'X - dummy!'];
        $client = new SpySoapConsumerClient($callReturn);

        $response = $client->consume('serviceUri', 'expression');
        $this->assertSame('X - dummy!', $response->get('EstadoConsulta'));
    }

    /**
     * @throws Exception
     */
    public function testMethodCallConsulta(): void
    {
        $client = new class () extends SoapConsumerClient {
            public function callConsulta(SoapClient $soapClient, array $arguments, array $options) // phpcs:ignore
            {
                return parent::callConsulta($soapClient, $arguments, $options);
            }
        };
        $fakeResult = (object) ['result' => 'ok'];
        $arguments = [];
        $options = [];
        /** @var SoapClient&MockObject $soapClient */
        $soapClient = $this->createMock(SoapClient::class);
        $soapClient->expects($this->once())
            ->method('__soapCall')
            ->with('Consulta', $arguments, $options, null, null)
            ->willReturn($fakeResult);
        $value = $client->callConsulta($soapClient, $arguments, $options);
        $this->assertSame($fakeResult, $value);
    }
}
