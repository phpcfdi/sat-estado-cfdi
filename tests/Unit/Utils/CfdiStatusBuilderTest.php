<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit\Utils;

use PhpCfdi\SatEstadoCfdi\Tests\TestCase;
use PhpCfdi\SatEstadoCfdi\Utils\CfdiStatusBuilder;
use PHPUnit\Framework\Attributes\TestWith;

final class CfdiStatusBuilderTest extends TestCase
{
    public function testCreateUsingEmptyResponse(): void
    {
        $builder = new CfdiStatusBuilder('', '', '', '', '');

        $response = $builder->create();
        // default states
        $this->assertTrue($response->query()->isNotFound());
        $this->assertTrue($response->document()->isNotFound());
        $this->assertTrue($response->cancellable()->isNotCancellable());
        $this->assertTrue($response->cancellation()->isUndefined());
        $this->assertTrue($response->efos()->isIncluded());
    }

    public function testCreateUsingRequestDifferentThanFound(): void
    {
        $builder = new CfdiStatusBuilder('foo', '', '', '', '');
        $this->assertTrue($builder->createQueryStatus()->isNotFound());
    }

    public function testCreateUsingRequestFound(): void
    {
        $builder = new CfdiStatusBuilder('S - ...', '', '', '', '');
        $this->assertTrue($builder->createQueryStatus()->isFound());
    }

    public function testCreateUsingActiveIsActive(): void
    {
        $builder = new CfdiStatusBuilder('', 'Vigente', '', '', '');
        $this->assertTrue($builder->createDocumentSatus()->isActive());
    }

    public function testCreateUsingActiveIsCancelled(): void
    {
        $builder = new CfdiStatusBuilder('', 'Cancelado', '', '', '');
        $this->assertTrue($builder->createDocumentSatus()->isCancelled());
    }

    public function testCreateUsingActiveAnyOtherValue(): void
    {
        $builder = new CfdiStatusBuilder('', '', '', '', '');
        $this->assertTrue($builder->createDocumentSatus()->isNotFound());
    }

    public function testCreateUsingCancellableIsDirectMethod(): void
    {
        $builder = new CfdiStatusBuilder('', '', 'Cancelable sin aceptación', '', '');
        $this->assertTrue($builder->createCancellableStatus()->isCancellableByDirectCall());
    }

    public function testCreateUsingCancellableIsRequestMethod(): void
    {
        $builder = new CfdiStatusBuilder('', '', 'Cancelable con aceptación', '', '');
        $this->assertTrue($builder->createCancellableStatus()->isCancellableByApproval());
    }

    #[TestWith(['No cancelable'])]
    #[TestWith(['foo'])]
    #[TestWith([''])]
    public function testCreateUsingCancellableNotCancellable(string $input): void
    {
        $builder = new CfdiStatusBuilder('', '', $input, '', '');
        $this->assertTrue($builder->createCancellableStatus()->isNotCancellable());
    }

    public function testCreateUsingCancellationIsPending(): void
    {
        $builder = new CfdiStatusBuilder('', '', '', 'En proceso', '');
        $this->assertTrue($builder->createCancellationStatus()->isPending());
    }

    public function testCreateUsingCancellationIsCancelByRequest(): void
    {
        $builder = new CfdiStatusBuilder('', '', '', 'Cancelado con aceptación', '');
        $this->assertTrue($builder->createCancellationStatus()->isCancelledByApproval());
    }

    public function testCreateUsingCancellationIsCancelByTimeout(): void
    {
        $builder = new CfdiStatusBuilder('', '', '', 'Plazo vencido', '');
        $this->assertTrue($builder->createCancellationStatus()->isCancelledByExpiration());
    }

    public function testCreateUsingCancellationIsCancelDirect(): void
    {
        $builder = new CfdiStatusBuilder('', '', '', 'Cancelado sin aceptación', '');
        $this->assertTrue($builder->createCancellationStatus()->isCancelledByDirectCall());
    }

    public function testCreateUsingCancellationIsRejected(): void
    {
        $builder = new CfdiStatusBuilder('', '', '', 'Solicitud rechazada', '');
        $this->assertTrue($builder->createCancellationStatus()->isDisapproved());
    }

    #[TestWith([''])]
    #[TestWith(['foo'])]
    public function testCreateUsingCancellationAnyOtherValue(string $input): void
    {
        $builder = new CfdiStatusBuilder('', '', '', $input, '');
        $this->assertTrue($builder->createCancellationStatus()->isUndefined());
    }

    #[TestWith([''])]
    #[TestWith(['100'])]
    #[TestWith(['199'])]
    #[TestWith(['201'])]
    #[TestWith(['other'])]
    public function testCreateUsingValidacionEfosIncluded(string $input): void
    {
        $builder = new CfdiStatusBuilder('', '', '', '', $input);
        $this->assertTrue($builder->createEfosStatus()->isIncluded());
    }

    public function testCreateUsingValidacionEfosExcluded(): void
    {
        $builder = new CfdiStatusBuilder('', '', '', '', '200');
        $this->assertTrue($builder->createEfosStatus()->isExcluded());
    }
}
