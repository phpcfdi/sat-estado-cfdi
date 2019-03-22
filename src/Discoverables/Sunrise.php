<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Discoverables;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Strategy\DiscoveryStrategy;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Sunrise\Http\Client\Curl\Client as SunriseCurlClient;
use Sunrise\Http\Factory\RequestFactory;
use Sunrise\Http\Factory\ResponseFactory;
use Sunrise\Http\Factory\ServerRequestFactory;
use Sunrise\Http\Factory\StreamFactory;
use Sunrise\Http\Factory\UploadedFileFactory;
use Sunrise\Http\Factory\UriFactory;

final class Sunrise implements DiscoveryStrategy
{
    /**
     * {@inheritdoc}
     */
    public static function getCandidates($type): array
    {
        $classes = [
            RequestFactoryInterface::class => [RequestFactory::class],
            ResponseFactoryInterface::class => [ResponseFactory::class],
            ServerRequestFactoryInterface::class => [ServerRequestFactory::class],
            StreamFactoryInterface::class => [StreamFactory::class],
            UploadedFileFactoryInterface::class => [UploadedFileFactory::class],
            UriFactoryInterface::class => [UriFactory::class],
            ClientInterface::class => [[self::class, 'createSunriseCurlClientWithOtherDiscoverables']],
        ];
        $candidates = [];
        if (isset($classes[$type])) {
            foreach ($classes[$type] as $class) {
                $candidates[] = ['class' => $class, 'condition' => [$class]];
            }
        }

        return $candidates;
    }

    public static function createSunriseCurlClientWithOtherDiscoverables(): SunriseCurlClient
    {
        $responseFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        $client = new SunriseCurlClient($responseFactory, $streamFactory);
        return $client;
    }
}
