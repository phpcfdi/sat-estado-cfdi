# phpcfdi/sat-estado-cfdi

[![Source Code][badge-source]][source]
[![Packagist PHP Version Support][badge-php-version]][php-version]
[![Discord][badge-discord]][discord]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][build]
[![Reliability][badge-reliability]][reliability]
[![Maintainability][badge-maintainability]][maintainability]
[![Code Coverage][badge-coverage]][coverage]
[![Violations][badge-violations]][violations]
[![Total Downloads][badge-downloads]][downloads]

> Consulta el estado de un CFDI en el webservice del SAT

:us: This library contains helpers to consume the **Servicio de Consulta de CFDI** from **SAT**.
The documentation of this project is in spanish as this is the natural language for intended audience.

:mexico: Esta librería se utiliza para consumir el **Servicio de Consulta de CFDI del SAT**.
La documentación del proyecto está en español porque ese es el lenguaje de los usuarios que la utilizarán.

Esta librería solo permite verificar el estado de un *CFDI Regular* y no de *CFDI de Retenciones e información de pagos*.
Para estos últimos, use la librería [phpcfdi/sat-estado-retenciones](https://github.com/phpcfdi/sat-estado-retenciones).

**Servicio de Consulta de CFDI del SAT**:

- Servicio productivo: <https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc>
- Servicio de pruebas: <https://pruebacfdiconsultaqr.cloudapp.net/ConsultaCFDIService.svc>
- SAT: <https://www.sat.gob.mx/consultas/20585/conoce-los-servicios-especializados-de-validacion>
- Documentación del Servicio de Consulta de CFDIVersión 1.4 (noviembre 2022):
  <https://www.sat.gob.mx/cs/Satellite?blobcol=urldata&blobkey=id&blobtable=MungoBlobs&blobwhere=1461175223997&ssbinary=true>

**Cambios recientes en el servicio**:

- Por motivo del cambio en el proceso de cancelación, en 2018 agregaron nuevos estados.
- Por una razón desconocida e inexplicable, el WSDL no estuvo disponible de 2018 a 2020.
  Esta librería usa una estrategia en donde no depende del WSDL para consumir el servicio.
- A finales de 2020 agregaron el campo de respuesta `VerificacionEFOS`.

## Instalación

Usa [composer](https://getcomposer.org/)

```shell
composer require phpcfdi/sat-estado-cfdi
```

## Ejemplo básico de uso

Los pasos básicos son:

- Tener un cliente que implemente `ConsumerClientInterface`.
- Crear un consumidor del servicio `Consumer`
- Realizar la solicitud con una *expresión* definida.
- Usar el resultado

```php
<?php
use PhpCfdi\SatEstadoCfdi\Consumer;
use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientInterface;

/** @var ConsumerClientInterface $client */
$consumer = new Consumer($client);

$cfdiStatus = $consumer->execute('...expression');

if ($cfdiStatus->cancellable()->isNotCancellable()) {
    echo 'CFDI no es cancelable';
}
```

### Clientes de consumo `ConsumerClientInterface`

Esta librería incluye dos diferentes clientes de consumo: `SoapConsumerClient` y `HttpConsumerClient`.

Además, puedes usar tu propio cliente de consumo implementando la interface `ConsumerClientInterface`.

#### Cliente SOAP `SoapConsumerClient`

El cliente `SoapConsumerClient` permite hacer el consumo usando la estrategia SOAP.

Requerimientos:

- `ext-soap`: Extensión SOAP de PHP.

Ejemplo:

```php
<?php
use PhpCfdi\SatEstadoCfdi\Clients\Soap\SoapConsumerClient;
use PhpCfdi\SatEstadoCfdi\Consumer;

function createConsumerUsingSoap(): Consumer
{
    $client = new SoapConsumerClient();
    return new Consumer($client);
}
```

#### Cliente HTTP PSR `HttpConsumerClient`

El cliente `HttpConsumerClient` permite hacer el consumo usando la estrategia HTTP con base en los estándares PSR.

Estándares utilizados:

- PSR-18: HTTP Client: Interfaces para clientes HTTP (el que hace la llamada POST).
  <https://www.php-fig.org/psr/psr-18/>
- PSR-17: HTTP Factories: Interfaces de fábricas de HTTP Request y Response (para PSR-7).
  <https://www.php-fig.org/psr/psr-17/>

Las librerías de Guzzle
[`guzzlehttp/guzzle`](https://github.com/guzzle/guzzle), y
[`guzzlehttp/psr7`](https://github.com/guzzle/psr7)
proveen los estándares necesarios.

O puedes ver en [Packagist](https://packagist.org/) los que te agraden:

- PSR-18: <https://packagist.org/providers/psr/http-client-implementation>
- PSR-17: <https://packagist.org/providers/psr/http-factory-implementation>

Requerimientos:

- `ext-dom`: Extensión DOM de PHP.
- `psr/http-client: ^1.0`: Estándar PSR-18 (Cliente HTTP).
- `psr/http-factory: ^1.0`: Estándar PSR-17 (Fábricas de mensajes HTTP).
- Algunas librerías que implementen PSR-18 y PSR-17, por ejemplo:
  - Guzzle: `guzzlehttp/guzzle` y `guzzlehttp/psr7`.
  - Symfony: `symfony/http-client` y `nyholm/psr7` o `laminas/laminas-diactoros`.

Ejemplo:

```php
<?php
use PhpCfdi\SatEstadoCfdi\Clients\Http\HttpConsumerClient;
use PhpCfdi\SatEstadoCfdi\Clients\Http\HttpConsumerFactory;
use PhpCfdi\SatEstadoCfdi\Consumer;

function createConsumerUsingGuzzle(): Consumer
{
    // Implements PSR-18 \Psr\Http\Client\ClientInterface
    $guzzleClient = new \GuzzleHttp\Client();
    // Implements PSR-17 \Psr\Http\Message\RequestFactoryInterface and PSR-17 \Psr\Http\Message\StreamFactoryInterface
    $guzzleFactory = new \GuzzleHttp\Psr7\HttpFactory();

    $factory = new HttpConsumerFactory($guzzleClient, $guzzleFactory, $guzzleFactory);
    $client = new HttpConsumerClient($factory);
    return new Consumer($client);
}
```

El siguiente es un ejemplo usando `symfony/http-client` y `nyholm/psr7`:

```php
use PhpCfdi\SatEstadoCfdi\Consumer;
use PhpCfdi\SatEstadoCfdi\Clients\Http\HttpConsumerClient;
use PhpCfdi\SatEstadoCfdi\Clients\Http\HttpConsumerFactory;

function createConsumerUsingSymfonyNyholm(): Consumer
{
    $httpClient = new \Symfony\Component\HttpClient\Psr18Client();
    $messageFactory = new \Nyholm\Psr7\Factory\Psr17Factory();
    
    $factory = new HttpConsumerFactory($httpClient, $messageFactory, $messageFactory);
    $client = new HttpConsumerClient($factory);
    return new Consumer($client);
}
```

Para Laravel puedes usar algún paquete adicional como [`wimski/laravel-psr-http`](https://packagist.org/packages/wimski/laravel-psr-http),
que gracias al uso del propio framework y `php-http/discovery`, facilita la creación de los objetos,
ya sea que los fabrique directamente usando el contenedor, o bien los inyecte como dependencias.

```php
<?php
use PhpCfdi\SatEstadoCfdi\Consumer;
use PhpCfdi\SatEstadoCfdi\Clients\Http\HttpConsumerClient;
use PhpCfdi\SatEstadoCfdi\Clients\Http\HttpConsumerFactory;

function createConsumerUsingLaravel(): Consumer
{
    $httpClient = app(\Psr\Http\Client\ClientInterface::class);
    $requestFactory = app(Psr\Http\Message\RequestFactoryInterface::class);
    $streamFactory = app(Psr\Http\Message\StreamFactoryInterface::class);
    
    $factory = new HttpConsumerFactory($httpClient, $requestFactory, $streamFactory);
    $client = new HttpConsumerClient($factory);
    return new Consumer($client);
}
```

También te recomiendo hacer tu propio *Service Provider* o configurar el *Service Container*
y solo requerir la clase `Consumer` como cualquier otra dependencia y permitir que sea inyectada.

### Expresiones (input)

El consumidor requiere una expresión para poder consultar.
La expresión es el texto que viene en el código QR de la representación impresa de un CFDI.

Las expresiones son diferentes para CFDI 3.2, CFDI 3.3, CFDI 4.0, RET 1.0 y RET 2.0.
Tienen reglas específicas de formato y de la información que debe contener.

Si no cuentas con la expresión, te recomiendo usar la librería
[`phpcfdi/cfdi-expresiones`](https://github.com/phpcfdi/cfdi-expresiones) que puedes instalar
usando `composer require phpcfdi/cfdi-expresiones`.

```php
<?php
use PhpCfdi\CfdiExpresiones\DiscoverExtractor;
use PhpCfdi\SatEstadoCfdi\Consumer;

// lectura del contenido del CFDI
$document = new DOMDocument();
$document->load('archivo-cfdi.xml');

// creación de la expresión
$expressionExtractor = new DiscoverExtractor();
$expression = $expressionExtractor->extract($document);

// realizar la consulta con la expresión obtenida
/** @var Consumer $consumer */
$cfdiStatus = $consumer->execute($expression);

// usar el estado
if ($cfdiStatus->document()->isActive()) {
    echo 'El CFDI se encuentra vigente';
}
```

### Estados (salida)

Después de consumir el servicio, se responderá con un objeto `CfdiStatus` que agrupa de los cuatro estados.

Los estados son enumeradores, puedes compararlos rápidamente usando métodos de ayuda `is*`,
por ejemplo: `$response->document()->isCancelled()`.

Posibles estados:

- `CodigoEstatus`: `query(): QueryStatus`.
    - `Found`: Si el estado inicia con `S - `.
    - `NotFound`: en cualquier otro caso.

- `Estado`: `document(): DocumentStatus`.
    - `Active`: Si el estado reportó `Vigente`.
    - `Cancelled`: Si el estado reportó `Cancelado`.
    - `NotFound`: en cualquier otro caso.

- `EsCancelable`: `cancellable(): CancellableStatus`.
    - `CancellableByDirectCall`: Si el estado reportó `Cancelable sin aceptación`.
    - `CancellableByApproval`: Si el estado reportó `Cancelable con aceptación`.
    - `NotCancellable`: en cualquier otro caso.

- `EstatusCancelacion`: `cancellation(): CancellationStatus`.
    - `CancelledByDirectCall`: Si el estado reportó `Cancelado sin aceptación`.
    - `CancelledByApproval`: Si el estado reportó `Cancelado con aceptación`.
    - `CancelledByExpiration`: Si el estado reportó `Plazo vencido`.
    - `Pending`: Si el estado reportó `En proceso`.
    - `Disapproved`: Si el estado reportó `Solicitud rechazada`.
    - `Undefined`: en cualquier otro caso.

- `ValidacionEFOS`: `efos(): EfosStatus`.
    - `Included`: Si el estado no reportó `200` o `201`.
    - `Excluded`: Si el estado reportó `200` o `201`.

#### Estados mutuamente excluyentes

| CodigoEstatus | Estado    | EsCancelable              | EstatusCancelacion       | Explicación                                               |
|---------------|-----------|---------------------------|--------------------------|-----------------------------------------------------------|
| N - ...       | *         | *                         | *                        | El SAT no sabe del CFDI con la expresión dada             |
| S - ...       | Cancelado | *                         | Plazo vencido            | Cancelado por plazo vencido                               |
| S - ...       | Cancelado | *                         | Cancelado con aceptación | Cancelado con aceptación del receptor                     |
| S - ...       | Cancelado | *                         | Cancelado sin aceptación | No fue requerido preguntarle al receptor y se canceló     |
| S - ...       | Vigente   | No cancelable             | *                        | No se puede cancelar                                      |
| S - ...       | Vigente   | Cancelable sin aceptación | *                        | Se puede cancelar, pero no se ha realizado la cancelación |
| S - ...       | Vigente   | Cancelable con aceptación | (ninguno)                | Se puede cancelar, pero no se ha realizado la solicitud   |
| S - ...       | Vigente   | Cancelable con aceptación | En proceso               | Se hizo la solicitud y está en espera de respuesta        |
| S - ...       | Vigente   | Cancelable con aceptación | Solicitud rechazada      | Se hizo la solicitud y fue rechazada                      |

Cuando tienes un CFDI en estado *Cancelable con aceptación*
y mandas a hacer la cancelación entonces su estado de cancelación cambiaría a *En proceso*.

El receptor puede aceptar la cancelación (*Cancelado con aceptación*) o rechazarla (*Solicitud rechazada*).

Si es la *primera vez* que se hace la solicitud, el receptor tiene 72 horas para aceptarla o rechazarla,
si no lo hace entonces automáticamente será cancelada (*Plazo vencido*).

Podrías volver a enviar la solicitud de cancelación *por segunda vez* aun cuando la solicitud fue previamente rechazada.

En ese caso, el receptor puede aceptar o rechazar la cancelación, pero ya no aplicará un lapso de 72 horas.
Por lo anterior entonces podrías tener el CFDI en estado de cancelación *en proceso* indefinidamente.
Incluso, que la cancelación suceda meses después de lo esperado.

## Compatibilidad

Esta librería se mantendrá compatible con al menos la versión con
[soporte activo de PHP](https://www.php.net/supported-versions.php) más reciente.

También utilizamos [Versionado Semántico 2.0.0](docs/SEMVER.md) por lo que puedes usar esta librería
sin temor a romper tu aplicación.

| `sat-estado-cfdi` | Versiones soportadas de PHP  |
|-------------------|------------------------------|
| 1.0.3             | 7.3, 7.4, 8.0, 8.1, 8.2, 8.3 |
| 2.0.0             | 8.2, 8.3                     |

## Contribuciones

Las contribuciones con bienvenidas. Por favor lee [CONTRIBUTING][] para más detalles
y recuerda revisar el archivo de tareas pendientes [TODO][] y el archivo [CHANGELOG][].

## Copyright and License

The `phpcfdi/sat-estado-cfdi` library is copyright © [PhpCfdi](https://www.phpcfdi.com/)
and licensed for use under the MIT License (MIT). Please see [LICENSE][] for more information.

[contributing]: https://github.com/phpcfdi/sat-estado-cfdi/blob/main/CONTRIBUTING.md
[changelog]: https://github.com/phpcfdi/sat-estado-cfdi/blob/main/docs/CHANGELOG.md
[todo]: https://github.com/phpcfdi/sat-estado-cfdi/blob/main/docs/TODO.md

[source]: https://github.com/phpcfdi/sat-estado-cfdi
[php-version]: https://packagist.org/packages/phpcfdi/sat-estado-cfdi
[discord]: https://discord.gg/aFGYXvX
[release]: https://github.com/phpcfdi/sat-estado-cfdi/releases
[license]: https://github.com/phpcfdi/sat-estado-cfdi/blob/main/LICENSE
[build]: https://github.com/phpcfdi/sat-estado-cfdi/actions/workflows/build.yml?query=branch:main
[reliability]:https://sonarcloud.io/component_measures?id=phpcfdi_sat-estado-cfdi&metric=Reliability
[maintainability]: https://sonarcloud.io/component_measures?id=phpcfdi_sat-estado-cfdi&metric=Maintainability
[coverage]: https://sonarcloud.io/component_measures?id=phpcfdi_sat-estado-cfdi&metric=Coverage
[violations]: https://sonarcloud.io/project/issues?id=phpcfdi_sat-estado-cfdi&resolved=false
[downloads]: https://packagist.org/packages/phpcfdi/sat-estado-cfdi

[badge-source]: https://img.shields.io/badge/source-phpcfdi/sat--estado--cfdi-blue?logo=github
[badge-discord]: https://img.shields.io/discord/459860554090283019?logo=discord
[badge-php-version]: https://img.shields.io/packagist/php-v/phpcfdi/sat-estado-cfdi?logo=php
[badge-release]: https://img.shields.io/github/release/phpcfdi/sat-estado-cfdi?logo=git
[badge-license]: https://img.shields.io/github/license/phpcfdi/sat-estado-cfdi?logo=open-source-initiative
[badge-build]: https://img.shields.io/github/actions/workflow/status/phpcfdi/sat-estado-cfdi/build.yml?branch=main&logo=github-actions
[badge-reliability]: https://sonarcloud.io/api/project_badges/measure?project=phpcfdi_sat-estado-cfdi&metric=reliability_rating
[badge-maintainability]: https://sonarcloud.io/api/project_badges/measure?project=phpcfdi_sat-estado-cfdi&metric=sqale_rating
[badge-coverage]: https://img.shields.io/sonar/coverage/phpcfdi_sat-estado-cfdi/main?logo=sonarcloud&server=https%3A%2F%2Fsonarcloud.io
[badge-violations]: https://img.shields.io/sonar/violations/phpcfdi_sat-estado-cfdi/main?format=long&logo=sonarcloud&server=https%3A%2F%2Fsonarcloud.io
[badge-downloads]: https://img.shields.io/packagist/dt/phpcfdi/sat-estado-cfdi?logo=packagist
