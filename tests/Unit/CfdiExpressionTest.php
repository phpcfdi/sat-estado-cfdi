<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit;

use PhpCfdi\SatEstadoCfdi\CfdiExpression;
use PhpCfdi\SatEstadoCfdi\Tests\TestCase;

class CfdiExpressionTest extends TestCase
{
    public function testConstructorAndGetters(): void
    {
        $parameters = new CfdiExpression(
            '3.3',
            'AAA010101AAA',
            'COSC8001137NA',
            '1,234.5678',
            'CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC',
            '0123456789'
        );
        $this->assertSame('3.3', $parameters->version());
        $this->assertSame('AAA010101AAA', $parameters->rfcEmisor());
        $this->assertSame('COSC8001137NA', $parameters->rfcReceptor());
        $this->assertSame('1,234.5678', $parameters->total());
        $this->assertEqualsWithDelta(1234.5678, $parameters->totalAsFloat(), 0.0000001);
        $this->assertSame('CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC', $parameters->uuid());
        // sello only stores last 8 characters
        $this->assertSame('0123456789', $parameters->sello());
    }

    public function testExpressionWithVersion33(): void
    {
        $parameters = new CfdiExpression(
            '3.3',
            'AAA010101AAA',
            'COSC8001137NA',
            '1,234.5678',
            'CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC',
            '0123456789'
        );

        $expected33 = 'https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx'
            . '?id=CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC'
            . '&re=AAA010101AAA'
            . '&rr=COSC8001137NA'
            . '&tt=1234.5678'
            . '&fe=23456789';

        $this->assertSame($expected33, $parameters->expression());
    }

    public function testExpressionWithVersion32(): void
    {
        $parameters = new CfdiExpression(
            '3.2',
            'AAA010101AAA',
            'COSC8001137NA',
            '1,234.5678',
            'CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC',
            '0123456789'
        );

        $expected32 = ''
            . '?re=AAA010101AAA'
            . '&rr=COSC8001137NA'
            . '&tt=0000001234.567800'
            . '&id=CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC';

        $this->assertSame($expected32, $parameters->expression());
    }

    public function testExpressionWithVersionNot32Or33(): void
    {
        $expression = new CfdiExpression('1.1', '', '', '', '');
        $this->assertSame('', $expression->expression());
    }

    public function testConstructorWithEmptyValuesMustNotThrowAnyException(): void
    {
        new CfdiExpression('', '', '', '', '', '');
        $this->assertTrue(true, 'Must not create any exception');
    }

    /**
     * @param string $total
     * @param string $expected
     *
     * @testWith ["9.123456", "9.123456"]
     *           ["0.123456", "0.123456"]
     *           ["1", "1.0"]
     *           ["0.1", "0.1"]
     *           ["1.1", "1.1"]
     *           ["0", "0.0"]
     *           ["0.1234567", "0.123457"]
     *
     */
    public function testExpressionTotalExamples($total, $expected): void
    {
        $parameters = new CfdiExpression(
            '3.3',
            'AAA010101AAA',
            'COSC8001137NA',
            $total,
            'CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC',
            '0123456789'
        );

        $this->assertStringContainsString('&tt=' . $expected . '&', $parameters->expression());
    }
}
