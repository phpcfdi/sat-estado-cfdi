<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests\Functional\Compliance;

use PhpCfdi\SatEstadoCfdi\Clients\Soap\SoapConsumerClient;
use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientInterface;
use PhpCfdi\SatEstadoCfdi\Tests\Functional\ComplaintTestsTrait;
use PhpCfdi\SatEstadoCfdi\Tests\TestCase;
use PHPUnit\Framework\Attributes\Large;

#[Large]
final class ComplianceSoapTest extends TestCase
{
    use ComplaintTestsTrait;

    public function getConsumerClient(): ConsumerClientInterface
    {
        return new SoapConsumerClient();
    }
}
