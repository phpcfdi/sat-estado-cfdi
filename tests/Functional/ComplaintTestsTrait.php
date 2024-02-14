<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Functional;

use PhpCfdi\CfdiExpresiones\DiscoverExtractor;
use PhpCfdi\SatEstadoCfdi\CfdiStatus;
use PhpCfdi\SatEstadoCfdi\Consumer;
use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientInterface;
use PhpCfdi\SatEstadoCfdi\Status\CancellableStatus;
use PhpCfdi\SatEstadoCfdi\Status\CancellationStatus;
use PhpCfdi\SatEstadoCfdi\Status\DocumentStatus;
use PhpCfdi\SatEstadoCfdi\Status\EfosStatus;
use PhpCfdi\SatEstadoCfdi\Status\QueryStatus;
use PHPUnit\Framework\TestCase;

/** @var TestCase $this */
trait ComplaintTestsTrait
{
    abstract public function getConsumerClient(): ConsumerClientInterface;

    public function testContactWebserviceWithActiveCfdi(): void
    {
        $expressionExtractor = new DiscoverExtractor();
        $expression = $expressionExtractor->format([
            're' => 'POT9207213D6',
            'rr' => 'DIM8701081LA',
            'tt' => '2010.01',
            'id' => 'CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC',
            'fe' => '/OAgdg==',
        ], 'CFDI33');

        $consumer = new Consumer($this->getConsumerClient());
        $response = $consumer->execute($expression);

        $this->compareCfdiStatus(
            new CfdiStatus(
                QueryStatus::Found,
                DocumentStatus::Active,
                CancellableStatus::CancellableByApproval,
                CancellationStatus::Undefined,
                EfosStatus::Excluded,
            ),
            $response,
        );
    }

    public function testContactWebserviceWithCancelledCfdi(): void
    {
        $expressionExtractor = new DiscoverExtractor();
        $expression = $expressionExtractor->format([
            're' => 'DIM8701081LA',
            'rr' => 'XEXX010101000',
            'tt' => '8413.00',
            'id' => '3be40815-916c-4c91-84e2-6070d4bc3949',
            'fe' => '3f86Og==',
        ], 'CFDI33');

        $consumer = new Consumer($this->getConsumerClient());
        $response = $consumer->execute($expression);

        $this->compareCfdiStatus(
            new CfdiStatus(
                QueryStatus::Found,
                DocumentStatus::Cancelled,
                CancellableStatus::NotCancellable,
                CancellationStatus::Undefined,
                EfosStatus::Excluded,
            ),
            $response,
        );
    }

    public function testContactWebserviceWithNotFoundCfdi(): void
    {
        $expressionExtractor = new DiscoverExtractor();
        $expression = $expressionExtractor->format([
            're' => 'AAA010101AAA',
            'rr' => 'XEXX010101000',
            'tt' => '1.00',
            'id' => '01234567-89ab-cf01-2345-67890abcd012',
            'fe' => 'aaaaaa==',
        ], 'CFDI33');

        $consumer = new Consumer($this->getConsumerClient());
        $response = $consumer->execute($expression);

        $this->compareCfdiStatus(
            new CfdiStatus(
                QueryStatus::NotFound,
                DocumentStatus::NotFound,
                CancellableStatus::NotCancellable,
                CancellationStatus::Undefined,
                EfosStatus::Included,
            ),
            $response,
        );
    }

    private function compareCfdiStatus(CfdiStatus $expected, CfdiStatus $actual): void
    {
        $this->assertSame(
            $expected->query(),
            $actual->query(),
            sprintf('It was expected CFDI status request: %s', $expected->query()->name),
        );
        $this->assertSame(
            $expected->document(),
            $actual->document(),
            sprintf('It was expected CFDI status active: %s', $expected->document()->name),
        );
        $this->assertSame(
            $expected->cancellable(),
            $actual->cancellable(),
            sprintf('It was expected CFDI status cancellable: %s', $expected->cancellable()->name),
        );
        $this->assertSame(
            $expected->cancellation(),
            $actual->cancellation(),
            sprintf('It was expected CFDI status cancellation: %s', $expected->cancellation()->name),
        );
        $this->assertSame(
            $expected->efos(),
            $actual->efos(),
            sprintf('It was expected the efos status: %s', $expected->efos()->name),
        );
    }
}
