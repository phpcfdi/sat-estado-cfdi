<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit\Internal;

use BadMethodCallException;
use PhpCfdi\SatEstadoCfdi\Status\QueryStatus;
use PhpCfdi\SatEstadoCfdi\Tests\TestCase;

final class EnumIsTypeTraitTest extends TestCase
{
    public function testCallIsMethodWithValidCases(): void
    {
        $enum = QueryStatus::Found;
        $this->assertTrue($enum->isFound());
        $this->assertFalse($enum->isNotFound());
    }

    public function testCallIsUsingDifferentLetterCase(): void
    {
        $enum = QueryStatus::Found;
        $this->assertTrue($enum->{'ISFOUND'}()); /** @phpstan-ignore method.notFound */
        $this->assertFalse($enum->{'isNOTfound'}()); /** @phpstan-ignore method.notFound */
    }

    public function testCallIsMethodWithoutCaseName(): void
    {
        $enum = QueryStatus::Found;
        $this->expectException(BadMethodCallException::class);
        $enum->{'is'}(); /** @phpstan-ignore method.notFound */
    }

    public function testCallInvalidMethod(): void
    {
        $enum = QueryStatus::Found;
        $this->expectException(BadMethodCallException::class);
        $enum->{'invalidMethod'}(); /** @phpstan-ignore method.notFound */
    }
}
