<?php

declare(strict_types=1);

use PhpCfdi\CfdiExpresiones\DiscoverExtractor;
use PhpCfdi\SatEstadoCfdi\Clients\Soap\SoapConsumerClient;
use PhpCfdi\SatEstadoCfdi\Consumer;

require __DIR__ . '/bootstrap.php';

exit(call_user_func(function (string $command, string ...$arguments): int {
    if ([] !== array_intersect($arguments, ['-h', '--help'])) {
        echo implode(PHP_EOL, [
            basename($command) . ' [-h|--help] cfdi.xml',
            '  -h, --help   Show this help',
            '  cfdi.xml     Source file to obtain expression',
            '  WARNING: This program can change at any time! Do not depend on this file or its results!',
            '',
        ]);
        return 0;
    }
    try {
        $filename = $arguments[0];
        if (! file_exists($filename)) {
            throw new Exception("File $filename does not exists");
        }

        $document = new DOMDocument();
        $document->load($filename);

        $extractor = new DiscoverExtractor();
        $expression = $extractor->extract($document);

        $consumer = new Consumer(new SoapConsumerClient());
        $response = $consumer->execute($expression);

        $result = [
            'expression' => $expression,
            'query' => $response->query()->name,
            'document' => $response->document()->name,
            'cancellable' => $response->cancellable()->name,
            'cancellation' => $response->cancellation()->name,
            'efos' => $response->efos()->name,
        ];

        echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), PHP_EOL;

        return 0;
    } catch (Throwable $exception) {
        file_put_contents('php://stderr', $exception->getMessage() . PHP_EOL, FILE_APPEND);
        return 1;
    }
}, ...$argv));
