<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\ComplianceTester;

use PhpCfdi\SatEstadoCfdi\CfdiExpression;
use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientInterface;
use PhpCfdi\SatEstadoCfdi\WebServiceConsumer;

/**
 * Create this object from your tests to see if it really get data from werbservice.
 * If it throws an exceptions means that it fail
 */
class ComplianceTester
{
    /** @var ConsumerClientInterface */
    private $client;

    public function __construct(ConsumerClientInterface $client)
    {
        $this->client = $client;
    }

    public function runComplianceTests(): bool
    {
        $tests = [
            'contactWebServiceWithActiveCfdi',
            'contactWebServiceWithCancelledCfdi',
        ];
        foreach ($tests as $test) {
            try {
                $this->{$test}();
            } catch (\Throwable $exception) {
                throw new \RuntimeException(
                    sprintf('ConsumerClientInterface %s dod not pass %s', get_class($this->client), $test),
                    0,
                    $exception
                );
            }
        }
        return true;
    }

    protected function contactWebServiceWithActiveCfdi(): void
    {
        $cfdiExpression = new CfdiExpression(
            '3.3',
            'POT9207213D6',
            'DIM8701081LA',
            '2010.01',
            'CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC',
            '/OAgdg=='
        );

        $consumer = new WebServiceConsumer($this->client);
        $response = $consumer->execute($cfdiExpression->expression());

        if (! $response->request()->isFound()) {
            throw new \RuntimeException('It was expected CFDI status request: found');
        }
        if (! $response->active()->isActive()) {
            throw new \RuntimeException('It was expected CFDI status active: active');
        }
        if (! $response->cancellable()->isDirectMethod()) {
            throw new \RuntimeException('It was expected CFDI status cancellable: directMethod');
        }
        if (! $response->cancellation()->isUndefined()) {
            throw new \RuntimeException('It was expected CFDI status cancellation: undefined');
        }
    }

    protected function contactWebServiceWithCancelledCfdi(): void
    {
        $cfdiExpression = new CfdiExpression(
            '3.3',
            'DIM8701081LA',
            'XEXX010101000',
            '8413.00',
            '3be40815-916c-4c91-84e2-6070d4bc3949',
            '3f86Og=='
        );

        $consumer = new WebServiceConsumer($this->client);
        $response = $consumer->execute($cfdiExpression->expression());

        if (! $response->request()->isFound()) {
            throw new \RuntimeException('It was expected CFDI status request: found');
        }
        if (! $response->active()->isCancelled()) {
            throw new \RuntimeException('It was expected CFDI status active: cancelled');
        }
        if (! $response->cancellable()->isNotCancellable()) {
            throw new \RuntimeException('It was expected CFDI status cancellable: notCancellable');
        }
        if (! $response->cancellation()->isUndefined()) {
            throw new \RuntimeException('It was expected CFDI status cancellation: undefined');
        }
    }
}
