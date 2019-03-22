# Notas de desarrollo

## Paquetes virtuales de PSR

Este paquete depende de los paquetes virtuales de PSR para asegurar que se han instalado las dependencias.

Originalmente estas dependencias están cumplidas con kriswallsmith/buzz, nyholm/psr7 y php-http/message-factory

He encontrado unos paquetes que lucen muy prometedores para el entorno de desarrollo:

```json
{
    "require-dev": {
        "ext-curl": "*",
        "sunrise/http-factory": "^1.0",
        "sunrise/http-message": "^1.0",
        "sunrise/http-client-curl": "^1.0"
    }
}
```

Sin embargo, el paquete `sunrise/http-client-curl` no dice que provee `psr/http-client-implementation`.
He creado un issue en <https://github.com/sunrise-php/http-client-curl/issues/12> al respecto.

Mientras ese issue no se resuelva, no se deben usar las librerías.

Por otro lado, tampoco tiene una forma directa de registrar su descubridor. Aunque es muy fácil de crear.

* Discoverables/Sunrise.php

```text
<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Discoverables;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Strategy\DiscoveryStrategy;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Client\ClientInterface;
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
```

* tests/bootstrap

```text
...
\Http\Discovery\Psr17FactoryDiscovery::appendStrategy(\PhpCfdi\SatEstadoCfdi\Discoverables\Sunrise::class);
\Http\Discovery\Psr18ClientDiscovery::appendStrategy(\PhpCfdi\SatEstadoCfdi\Discoverables\Sunrise::class);
```

* tests/SunriseTest.php

```text
<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Tests;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Sunrise\Http\Client\Curl\Client as SunriseCurlClient;

class SunriseTest extends TestCase
{
    public function testSunriseClientCanConnectToExampleDotCom()
    {
        $responseFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        $client = new SunriseCurlClient($responseFactory, $streamFactory, [
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        $request = $requestFactory->createRequest('GET', 'http://example.com/');

        $response = $client->sendRequest($request);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function testDiscoveryCreatesSunriseClient()
    {
        $client = Psr18ClientDiscovery::find();
        $this->assertInstanceOf(SunriseCurlClient::class, $client);
    }
}
```
