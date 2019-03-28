<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit\Utils;

use PhpCfdi\SatEstadoCfdi\Tests\TestCase;
use PhpCfdi\SatEstadoCfdi\Utils\CfdiStatusBuilder;

class CfdiStatusBuilderTest extends TestCase
{
    public function testCreateUsingEmptyResponse(): void
    {
        $builder = new CfdiStatusBuilder('', '', '', '');

        $response = $builder->create();
        // default states
        $this->assertTrue($response->request()->isNotFound());
        $this->assertTrue($response->active()->isNotFound());
        $this->assertTrue($response->cancellable()->isNotCancellable());
        $this->assertTrue($response->cancellation()->isUndefined());
    }

    public function testCreateUsingRequestDifferentThanFound(): void
    {
        $builder = new CfdiStatusBuilder('foo', '', '', '');
        $this->assertTrue($builder->getRequestStatus()->isNotFound());
    }

    public function testCreateUsingRequestFound(): void
    {
        $builder = new CfdiStatusBuilder('S - ...', '', '', '');
        $this->assertTrue($builder->getRequestStatus()->isFound());
    }

    public function testCreateUsingActiveIsActive(): void
    {
        $builder = new CfdiStatusBuilder('', 'Vigente', '', '');
        $this->assertTrue($builder->getActiveStatus()->isActive());
    }

    public function testCreateUsingActiveIsCancelled(): void
    {
        $builder = new CfdiStatusBuilder('', 'Cancelado', '', '');
        $this->assertTrue($builder->getActiveStatus()->isCancelled());
    }

    public function testCreateUsingActiveAnyOtherValue(): void
    {
        $builder = new CfdiStatusBuilder('', '', '', '');
        $this->assertTrue($builder->getActiveStatus()->isNotFound());
    }

    public function testCreateUsingCancellableIsDirectMethod(): void
    {
        $builder = new CfdiStatusBuilder('', '', 'Cancelable sin aceptación', '');
        $this->assertTrue($builder->getCancellableStatus()->isDirectMethod());
    }

    public function testCreateUsingCancellableIsRequestMethod(): void
    {
        $builder = new CfdiStatusBuilder('', '', 'Cancelable con aceptación', '');
        $this->assertTrue($builder->getCancellableStatus()->isRequestMethod());
    }

    /**
     * @param string $input
     * @testWith ["No cancelable"]
     *           ["foo"]
     *           [""]
     */
    public function testCreateUsingCancellableNotCancellable(string $input): void
    {
        $builder = new CfdiStatusBuilder('', '', $input, '');
        $this->assertTrue($builder->getCancellableStatus()->isNotCancellable());
    }

    public function testCreateUsingCancellationIsPending(): void
    {
        $builder = new CfdiStatusBuilder('', '', '', 'En proceso');
        $this->assertTrue($builder->getCancellationStatus()->isPending());
    }

    public function testCreateUsingCancellationIsCancelByRequest(): void
    {
        $builder = new CfdiStatusBuilder('', '', '', 'Cancelado con aceptación');
        $this->assertTrue($builder->getCancellationStatus()->isCancelByRequest());
    }

    public function testCreateUsingCancellationIsCancelByTimeout(): void
    {
        $builder = new CfdiStatusBuilder('', '', '', 'Plazo vencido');
        $this->assertTrue($builder->getCancellationStatus()->isCancelByTimeout());
    }

    public function testCreateUsingCancellationIsCancelDirect(): void
    {
        $builder = new CfdiStatusBuilder('', '', '', 'Cancelado sin aceptación');
        $this->assertTrue($builder->getCancellationStatus()->isCancelDirect());
    }

    public function testCreateUsingCancellationIsRejected(): void
    {
        $builder = new CfdiStatusBuilder('', '', '', 'Solicitud rechazada');
        $this->assertTrue($builder->getCancellationStatus()->isRejected());
    }

    /**
     * @param string $input
     * @testWith [""]
     *           ["foo"]
     */
    public function testCreateUsingCancellationAnyOtherValue(string $input): void
    {
        $builder = new CfdiStatusBuilder('', '', '', $input);
        $this->assertTrue($builder->getCancellationStatus()->isUndefined());
    }
}
