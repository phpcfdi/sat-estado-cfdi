<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Functional\Compliance;

use PhpCfdi\SatEstadoCfdi\Clients\Http\HttpConsumerClient;
use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientInterface;
use PhpCfdi\SatEstadoCfdi\Tests\Functional\ComplaintTestsTrait;
use PhpCfdi\SatEstadoCfdi\Tests\HttpTestCase as TestCase;
use PHPUnit\Framework\Attributes\Large;

#[Large]
final class ComplianceHttpTest extends TestCase
{
    use ComplaintTestsTrait;

    public function getConsumerClient(): ConsumerClientInterface
    {
        return new HttpConsumerClient($this->createHttpConsumerFactory());
    }
}
