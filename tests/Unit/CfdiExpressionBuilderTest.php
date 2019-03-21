<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit;

use PhpCfdi\SatEstadoCfdi\CfdiExpressionBuilder;
use PhpCfdi\SatEstadoCfdi\Tests\TestCase;
use PHPUnit\Framework\Error\Error;

class CfdiExpressionBuilderTest extends TestCase
{
    public function testCanObtainDataFromCfdi33(): void
    {
        $contents = $this->fileContentPath('cfdi33-real.xml');
        $builder = CfdiExpressionBuilder::createFromString($contents);
        $this->assertSame('3.3', $builder->getVersion());
        $this->assertSame('POT9207213D6', $builder->getRfcEmisor());
        $this->assertSame('DIM8701081LA', $builder->getRfcReceptor());
        $this->assertSame('2010.01', $builder->getTotal());
        $this->assertSame('CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC', $builder->getUuid());
        $this->assertStringEndsWith('/OAgdg==', $builder->getSello()); // only last 8 chars
    }

    public function testCanObtainDataFromCfdi32(): void
    {
        $contents = $this->fileContentPath('cfdi32-real.xml');
        $builder = CfdiExpressionBuilder::createFromString($contents);
        $this->assertSame('3.2', $builder->getVersion());
        $this->assertSame('CTO021007DZ8', $builder->getRfcEmisor());
        $this->assertSame('XAXX010101000', $builder->getRfcReceptor());
        $this->assertSame('4685.00', $builder->getTotal());
        $this->assertSame('80824F3B-323E-407B-8F8E-40D83FE2E69F', $builder->getUuid());
        $this->assertStringEndsWith('mmVYiA==', $builder->getSello()); // only last 8 chars
    }

    public function testCanBuildDataFromCfdi33(): void
    {
        $contents = $this->fileContentPath('cfdi33-real.xml');
        $builder = CfdiExpressionBuilder::createFromString($contents);
        $expression = $builder->build();

        $this->assertSame('3.3', $expression->getVersion());
        $this->assertSame('POT9207213D6', $expression->getRfcEmisor());
        $this->assertSame('DIM8701081LA', $expression->getRfcReceptor());
        $this->assertSame('2010.01', $expression->getTotal());
        $this->assertSame('CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC', $expression->getUuid());
        $this->assertSame('/OAgdg==', $expression->getSello());
    }

    public function testCanBuildDataFromCfdi32(): void
    {
        $contents = $this->fileContentPath('cfdi32-real.xml');
        $builder = CfdiExpressionBuilder::createFromString($contents);
        $expression = $builder->build();

        $this->assertSame('3.2', $expression->getVersion());
        $this->assertSame('CTO021007DZ8', $expression->getRfcEmisor());
        $this->assertSame('XAXX010101000', $expression->getRfcReceptor());
        $this->assertSame('4685.00', $expression->getTotal());
        $this->assertSame('80824F3B-323E-407B-8F8E-40D83FE2E69F', $expression->getUuid());
        $this->assertSame('mmVYiA==', $expression->getSello());
    }

    public function textConstructWithIncompleteXmlThrowsException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Xml document does not have root element');

        $document = new \DOMDocument();
        new CfdiExpressionBuilder($document);
    }

    public function testWithEmptyXml(): void
    {
        $this->expectException(Error::class);

        $contents = '';
        CfdiExpressionBuilder::createFromString($contents);
    }

    public function testWithInvalidXml(): void
    {
        $this->expectException(Error::class);

        $contents = 'foo';
        CfdiExpressionBuilder::createFromString($contents);
    }

    public function testWithInvalidCfdiRootElement(): void
    {
        $this->expectException(\RuntimeException::class);

        $contents = '<cfdi:Element xmlns:cfdi="http://www.sat.gob.mx/cfd/3"/>';
        CfdiExpressionBuilder::createFromString($contents);
    }

    public function testWithInvalidCfdiInvalidNamespace(): void
    {
        $this->expectException(\RuntimeException::class);

        $contents = '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3/x"/>';
        CfdiExpressionBuilder::createFromString($contents);
    }

    public function testWithInvalidCfdiInvalidVersion32(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('version');

        $contents = '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.2"/>';
        CfdiExpressionBuilder::createFromString($contents);
    }

    public function testWithInvalidCfdiInvalidVersion33(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('version');

        $contents = '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" version="3.3"/>';
        CfdiExpressionBuilder::createFromString($contents);
    }

    public function testWithComprobanteButNoData33(): void
    {
        $contents = '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3"/>';
        $builder = CfdiExpressionBuilder::createFromString($contents);

        $this->assertSame('3.3', $builder->getVersion());
        $this->assertSame('', $builder->getRfcEmisor());
        $this->assertSame('', $builder->getRfcReceptor());
        $this->assertSame('', $builder->getTotal());
        $this->assertSame('', $builder->getUuid());
        $this->assertSame('', $builder->getSello());

        $this->assertTrue($builder->isVersion33());
        $this->assertFalse($builder->isVersion32());
    }

    public function testWithComprobanteButNoData32(): void
    {
        $contents = '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" version="3.2"/>';
        $builder = CfdiExpressionBuilder::createFromString($contents);

        $this->assertSame('3.2', $builder->getVersion());
        $this->assertSame('', $builder->getRfcEmisor());
        $this->assertSame('', $builder->getRfcReceptor());
        $this->assertSame('', $builder->getTotal());
        $this->assertSame('', $builder->getUuid());
        $this->assertSame('', $builder->getSello());

        $this->assertFalse($builder->isVersion33());
        $this->assertTrue($builder->isVersion32());
    }
}
