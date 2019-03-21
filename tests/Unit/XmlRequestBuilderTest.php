<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit;

use PhpCfdi\SatEstadoCfdi\Tests\TestCase;
use PhpCfdi\SatEstadoCfdi\XmlRequestBuilder;

class XmlRequestBuilderTest extends TestCase
{
    public function testCanCreateAnXmlSoapRequestFromSomeExpression(): void
    {
        $builder = new XmlRequestBuilder();
        $xml = $builder->build('?a=foo&b=bar');
        $this->assertXmlStringEqualsXmlFile($this->filePath('soap-request-foobar.xml'), $xml);
    }
}
