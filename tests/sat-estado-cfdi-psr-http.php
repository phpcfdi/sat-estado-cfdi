<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use PhpCfdi\CfdiExpresiones\DiscoverExtractor;
use PhpCfdi\SatEstadoCfdi\Clients\Http\HttpConsumerClient;
use PhpCfdi\SatEstadoCfdi\Clients\Http\HttpConsumerFactory;
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

        $factory = new HttpConsumerFactory(
            new Client(),
            new HttpFactory(),
            new HttpFactory(),
        );
        $consumer = new Consumer(new HttpConsumerClient($factory));

        $response = $consumer->execute($expression);

        $result = [
            'expression' => $expression,
            'query' => $response->query()->name,
            'document' => $response->document()->name,
            'cancellable' => $response->cancellable()->name,
            'cancellation' => $response->cancellation()->name,
            'efos' => $response->efos()->name,
        ];

        echo json_encode(
            $result,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS,
        ), PHP_EOL;

        return 0;
    } catch (Throwable $exception) {
        file_put_contents('php://stderr', $exception->getMessage() . PHP_EOL, FILE_APPEND);
        return 1;
    }
}, ...$argv));
