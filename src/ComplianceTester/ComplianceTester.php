<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\ComplianceTester;

use PhpCfdi\CfdiExpresiones\DiscoverExtractor;
use PhpCfdi\SatEstadoCfdi\Consumer;
use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientInterface;
use RuntimeException;
use Throwable;

/**
 * Create this object from your tests to see if it really gets data from webservice.
 * If it throws an exception means that it fail
 * @codeCoverageIgnore
 */
readonly class ComplianceTester
{
    public function __construct(
        private ConsumerClientInterface $client,
    ) {
    }

    /**
     * Call this method to check for compliance on consumer client implementation
     *
     * @noinspection PhpUnused
     */
    public function runComplianceTests(): bool
    {
        $tests = [
            'contact webservice with active cfdi' => function (): void {
                $this->contactWebServiceWithActiveCfdi();
            },
            'contact webservice with cancelled cfdi' => function (): void {
                $this->contactWebServiceWithCancelledCfdi();
            },
        ];
        foreach ($tests as $name => $closure) {
            try {
                call_user_func($closure);
            } catch (Throwable $exception) {
                $message = sprintf('ConsumerClientInterface %s did not pass: %s', $this->client::class, $name);
                throw new RuntimeException($message, 0, $exception);
            }
        }
        return true;
    }

    protected function contactWebServiceWithActiveCfdi(): void
    {
        $expressionExtractor = new DiscoverExtractor();
        $expression = $expressionExtractor->format([
            're' => 'POT9207213D6',
            'rr' => 'DIM8701081LA',
            'tt' => '2010.01',
            'id' => 'CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC',
            'fe' => '/OAgdg==',
        ], 'CFDI33');

        $consumer = new Consumer($this->client);
        $response = $consumer->execute($expression);

        if (! $response->query()->isFound()) {
            throw new RuntimeException('It was expected CFDI status request: found');
        }
        if (! $response->document()->isActive()) {
            throw new RuntimeException('It was expected CFDI status active: active');
        }
        if (! $response->cancellable()->isCancellableByApproval()) {
            throw new RuntimeException('It was expected CFDI status cancellable: by approval');
        }
        if (! $response->cancellation()->isUndefined()) {
            throw new RuntimeException('It was expected CFDI status cancellation: undefined');
        }
        if (! $response->efos()->isExcluded()) {
            throw new RuntimeException('It was expected the efos status: excluded');
        }
    }

    protected function contactWebServiceWithCancelledCfdi(): void
    {
        $expressionExtractor = new DiscoverExtractor();
        $expression = $expressionExtractor->format([
            're' => 'DIM8701081LA',
            'rr' => 'XEXX010101000',
            'tt' => '8413.00',
            'id' => '3be40815-916c-4c91-84e2-6070d4bc3949',
            'fe' => '3f86Og==',
        ], 'CFDI33');

        $consumer = new Consumer($this->client);
        $response = $consumer->execute($expression);

        if (! $response->query()->isFound()) {
            throw new RuntimeException('It was expected CFDI status request: found');
        }
        if (! $response->document()->isCancelled()) {
            throw new RuntimeException('It was expected CFDI status active: cancelled');
        }
        if (! $response->cancellable()->isNotCancellable()) {
            throw new RuntimeException('It was expected CFDI status cancellable: notCancellable');
        }
        if (! $response->cancellation()->isUndefined()) {
            throw new RuntimeException('It was expected CFDI status cancellation: undefined');
        }
        if (! $response->efos()->isExcluded()) {
            throw new RuntimeException('It was expected the efos status: excluded');
        }
    }
}
