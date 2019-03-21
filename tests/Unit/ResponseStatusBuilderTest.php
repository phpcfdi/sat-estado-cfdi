<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Unit;

use PhpCfdi\SatEstadoCfdi\ResponseStatusBuilder;
use PhpCfdi\SatEstadoCfdi\Tests\TestCase;

class ResponseStatusBuilderTest extends TestCase
{
    public function testCreateUsingEmptyResponse(): void
    {
        $builder = new ResponseStatusBuilder('', '', '', '');

        $response = $builder->create();
        // default states
        $this->assertTrue($response->request()->isNotFound());
        $this->assertTrue($response->active()->isNotFound());
        $this->assertTrue($response->cancellabe()->isNotCancellable());
        $this->assertTrue($response->cancellation()->isUndefined());
    }

    public function testCreateUsingRequestDifferentThanFound(): void
    {
        $builder = new ResponseStatusBuilder('foo', '', '', '');
        $this->assertTrue($builder->getRequestStatus()->isNotFound());
    }

    public function testCreateUsingRequestFound(): void
    {
        $builder = new ResponseStatusBuilder('S - ...', '', '', '');
        $this->assertTrue($builder->getRequestStatus()->isFound());
    }

    public function testCreateUsingActiveIsActive(): void
    {
        $builder = new ResponseStatusBuilder('', 'Vigente', '', '');
        $this->assertTrue($builder->getActiveStatus()->isActive());
    }

    public function testCreateUsingActiveIsCancelled(): void
    {
        $builder = new ResponseStatusBuilder('', 'Cancelado', '', '');
        $this->assertTrue($builder->getActiveStatus()->isCancelled());
    }

    public function testCreateUsingActiveAnyOtherValue(): void
    {
        $builder = new ResponseStatusBuilder('', '', '', '');
        $this->assertTrue($builder->getActiveStatus()->isNotFound());
    }

    public function testCreateUsingCancellableIsDirectMethod(): void
    {
        $builder = new ResponseStatusBuilder('', '', 'Cancelable sin aceptación', '');
        $this->assertTrue($builder->getCancellableStatus()->isDirectMethod());
    }

    public function testCreateUsingCancellableIsRequestMethod(): void
    {
        $builder = new ResponseStatusBuilder('', '', 'Cancelable con aceptación', '');
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
        $builder = new ResponseStatusBuilder('', '', $input, '');
        $this->assertTrue($builder->getCancellableStatus()->isNotCancellable());
    }

    public function testCreateUsingCancellationIsPending(): void
    {
        $builder = new ResponseStatusBuilder('', '', '', 'En proceso');
        $this->assertTrue($builder->getCancellationStatus()->isPending());
    }

    public function testCreateUsingCancellationIsCancelByRequest(): void
    {
        $builder = new ResponseStatusBuilder('', '', '', 'Cancelado con aceptación');
        $this->assertTrue($builder->getCancellationStatus()->isCancelByRequest());
    }

    public function testCreateUsingCancellationIsCancelByTimeout(): void
    {
        $builder = new ResponseStatusBuilder('', '', '', 'Plazo vencido');
        $this->assertTrue($builder->getCancellationStatus()->isCancelByTimeout());
    }

    public function testCreateUsingCancellationIsCancelDirect(): void
    {
        $builder = new ResponseStatusBuilder('', '', '', 'Cancelado sin aceptación');
        $this->assertTrue($builder->getCancellationStatus()->isCancelDirect());
    }

    public function testCreateUsingCancellationIsRejected(): void
    {
        $builder = new ResponseStatusBuilder('', '', '', 'Solicitud rechazada');
        $this->assertTrue($builder->getCancellationStatus()->isRejected());
    }

    /**
     * @param string $input
     * @testWith [""]
     *           ["foo"]
     */
    public function testCreateUsingCancellationAnyOtherValue(string $input): void
    {
        $builder = new ResponseStatusBuilder('', '', '', $input);
        $this->assertTrue($builder->getCancellationStatus()->isUndefined());
    }
}
