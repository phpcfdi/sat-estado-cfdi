# PhpCfdi/SatEstadoCfdi

[![Source Code][badge-source]][source]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][build]
[![Scrutinizer][badge-quality]][quality]
[![Coverage Status][badge-coverage]][coverage]
[![Total Downloads][badge-downloads]][downloads]

> Consulta el estado de un cfdi en el webservice del SAT

:us: This library contains helpers to consume the **Servicio de Consulta de CFDI** from **SAT**.
The documentation of this project is in spanish as this is the natural language for intented audience.

:mexico: Esta librería contiene objetos de ayuda para consumir el **Servicio de Consulta de CFDI del SAT**.
La documentación del proyecto está en español porque ese es el lenguaje de los usuarios que la utilizarán.

**Servicio de Consulta de CFDI del SAT**:

- Servicio productivo: <https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc>
- Servicio de pruebas: <https://pruebacfdiconsultaqr.cloudapp.net/ConsultaCFDIService.svc>
- Documentación: <https://www.sat.gob.mx/cs/Satellite?blobcol=urldata&blobkey=id&blobtable=MungoBlobs&blobwhere=1461173518263&ssbinary=true>

**Cambios recientes en el servicio**:

- Por motivo del cambio en el proceso de facturación en 2018 agregaron nuevos estados.
- Por una razón desconocida y hasta cierto punto inexplicable, el WSDL ya no se encuentra disponible desde 2018.
Aunque sí se puede consumir el servicio.

Esta librería **no utiliza SOAP** para hacer la llamada, hace una llamada HTTP que construye e interpreta.

Para contactar al servicio utiliza [PSR-18: HTTP Client](https://www.php-fig.org/psr/psr-18/)
y [PSR-17: HTTP Factories](https://www.php-fig.org/psr/psr-17/).
De esta forma, tu puedes usar el cliente HTTP que mejor te convenga.


## Instalación

Usa [composer](https://getcomposer.org/)

```shell
composer require phpcfdi/sat-estado-cfdi
```


## Ejemplo básico de uso

Los pasos básicos son:

- Crear la fábrica de objetos `WebServiceFactory`.
- Pedirle a la fábrica de objetos que nos entregue un consumidor `Consumer`.
- Solicitarle al consumidor que ejecute la petición sobre una expresión.

```php
<?php
$consumer = (new \PhpCfdi\SatEstadoCfdi\WebServiceDiscover())->createFactory()->getConsumer();

$response = $consumer->execute('...expression');

if ($response->cancellable()->isNotCancellable()) {
    echo 'CFDI no es cancelable';
}
```

### Expresiones (input)

El consumidor requiere una expresión para poder consultar. Las expresiones son diferentes para CFDI 3.2 y 3.3.

Ejemplo de expresión para CFDI 3.3:

```text
https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?id=CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC&re=POT9207213D6&rr=DIM8701081LA&tt=2010.01&fe=/OAgdg==
```

Si no cuentas con ella, puedes usar el objeto `CfdiExpressionBuilder` para fabricarla:

```php
<?php
// lectura del contenido del CFDI
$cfdiContents = file_get_contents('cfdi.xml');
$builder = \PhpCfdi\SatEstadoCfdi\CfdiExpressionBuilder::createFromString($cfdiContents);
$parameters = $builder->build();
$expression = $parameters->expression();
```

### Estados (salida)

Después de consumir el servicio, se responderá con un objeto con estados.

No compares directamente los valores de los estados, en su lugar utiliza los métodos `is*`,
por ejemplo `$response->active()->isCancelled()`.

Posibles estados:

- `CfdiRequestStatus request`: `CodigoEstatus`.
    - `found`: Si el estado inicia con `S - `.
    - `notFound`: en cualquier otro caso.

- `CfdiActiveStatus active`: `Estado`.
    - `active`: Si el estado reportó `Vigente`.
    - `cancelled`: Si el estado reportó `Cancelado`.
    - `notFound`: en cualquier otro caso.

- `CfdiCancellableStatus cancellable`: `EsCancelable`.
    - `directMethod`: Si el estado reportó `Cancelable sin aceptación`.
    - `requestMethod`: Si el estado reportó `Cancelable con aceptación`.
    - `notCancellable`: en cualquier otro caso.

- `CfdiCancellationStatus cancelation`: `EstatusCancelacion`.
    - `cancelDirect`: Si el estado reportó `Cancelado sin aceptación`.
    - `pending`: Si el estado reportó `En proceso`.
    - `cancelByTimeout`: Si el estado reportó `Plazo vencido`.
    - `cancelByRequest`: Si el estado reportó `Cancelado con aceptación`.
    - `rejected`: Si el estado reportó `Solicitud rechazada`.
    - `undefined`: en cualquier otro caso.


#### Estados mutuamente excluyentes:

CodigoEstatus | Estado        | EsCancelable              | EstatusCancelacion       | Explicación
------------- | ------------- | ------------------------- | ------------------------ | -----------------------------------------------------
N - ...       | *             | *                         | *                        | El SAT no sabe del CFDI con los datos ofrecidos
S - ...       | Cancelado     | *                         | Plazo vencido            | Cancelado por plazo vencido
S - ...       | Cancelado     | *                         | Cancelado con aceptación | Cancelado con aceptación del receptor
S - ...       | Cancelado     | *                         | Cancelado sin aceptación | No fue requerido preguntarle al receptor y se canceló
S - ...       | Vigente       | No cancelable             | *                        | No se puede cancelar
S - ...       | Vigente       | Cancelable sin aceptación | *                        | Se puede cancelar pero no se ha realizado solicitud, termina en SuccessStatus
S - ...       | Vigente       | Cancelable con aceptación | (ninguno)                | Se puede cancelar pero no se ha realizado solicitud, Termina en Pending
S - ...       | Vigente       | Cancelable con aceptación | En proceso               | Se hizo la solicitud y se está en espera
S - ...       | Vigente       | Cancelable con aceptación | Solicitud rechazada      | Se hizo la solicitud y se está en espera


### Compatibilidad con PSR-7 PSR-17 y PSR-18

Esta librería busca alta compatibilidad con los estándares propuestos por el [PHP-FIG](https://www.php-fig.org/).
Por lo que utiliza los siguientes estándares. 

- PSR-7: HTTP message interfaces: Interfaces de HTTP Request y Response.
- PSR-17: HTTP Factories: Interfaces de fábricas de HTTP Request y Response (para PSR-7).
- PSR-18: HTTP Client: Interfaces para clientes HTTP (el que hace la llamada POST).

Esta librería no contiene las implementaciones de los estándares,
las librerías que implementan las interfaces ya existen fuera del ámbito de la aplicación.

Te recomiendo usar las librerías de Sunrise
[`sunrise/http-client-curl`](https://github.com/sunrise-php/http-client-curl),
[`sunrise/http-factory`](https://github.com/sunrise-php/http-factory) y
[`sunrise/http-message`](https://github.com/sunrise-php/http-message).

```shell
# librerías para implementar PSR-18, PSR-17 y PSR-7
composer require sunrise/http-client-curl, sunrise/http-factory y sunrise/http-message
```

Y puedes crear tu cliente de esta forma:

```php
<?php
use PhpCfdi\SatEstadoCfdi\WebServiceFactory;

function createSunriseSatEstadoCfdiFactory(): WebServiceFactory {
    $responseFactory = new Sunrise\Http\Factory\ResponseFactory();
    $requestFactory = new Sunrise\Http\Factory\RequestFactory();
    $streamFactory = new Sunrise\Http\Factory\StreamFactory();
    $httpClient = new \Sunrise\Http\Client\Curl\Client($responseFactory, $streamFactory);
    return new WebServiceFactory($httpClient, $requestFactory, $streamFactory);
}

$consumer = createSunriseSatEstadoCfdiFactory()->getConsumer();
```


### Compatibilidad con HTTP Plug

Si tu aplicación usa HTTP Plug o es compatible con `php-http/discovery` entonces podrías usar
la clase `WebServiceDiscover`.

```php
<?php
$discover = new \PhpCfdi\SatEstadoCfdi\WebServiceDiscover();
$factory = $discover->createFactory();
$consumer = $factory->getConsumer();
```

En el archivo [`tests/Discoverables/Sunrise.php`](blob/master/tests/Discoverables/Sunrise.php)
puedes ver una librería para que las implementaciones de Sunrise sean automáticamente descubiertas.

Para decirle al descubridor de componentes de HTTP Plug que las reconozca incluye las siguientes
líneas: 

```php
<?php
\Http\Discovery\Psr17FactoryDiscovery::prependStrategy(\PhpCfdi\SatEstadoCfdi\Tests\Discoverables\Sunrise::class);
\Http\Discovery\Psr18ClientDiscovery::prependStrategy(\PhpCfdi\SatEstadoCfdi\Tests\Discoverables\Sunrise::class);
``` 


## Compatilibilidad

Esta librería se mantendrá compatible con al menos la versión con
[soporte activo de PHP](http://php.net/supported-versions.php) más reciente.

También utilizamos [Versionado Semántico 2.0.0](https://semver.org/lang/es/) por lo que puedes usar esta librería
sin temor a romper tu aplicación.


## Contribuciones

Las contribuciones con bienvenidas. Por favor leee [CONTRIBUTING][] para más detalles
y recuerda revisar el archivo de tareas pendientes [TODO][] y el [CHANGELOG][].


## Copyright and License

The PhpCfdi/SatEstadoCfdi library is copyright © [Carlos C Soto](http://eclipxe.com.mx/)
and licensed for use under the MIT License (MIT). Please see [LICENSE][] for more information.


[contributing]: https://github.com/PhpCfdi/SatEstadoCfdi/blob/master/CONTRIBUTING.md
[changelog]: https://github.com/PhpCfdi/SatEstadoCfdi/blob/master/docs/CHANGELOG.md
[todo]: https://github.com/PhpCfdi/SatEstadoCfdi/blob/master/docs/TODO.md

[source]: https://github.com/PhpCfdi/SatEstadoCfdi
[release]: https://github.com/PhpCfdi/SatEstadoCfdi/releases
[license]: https://github.com/PhpCfdi/SatEstadoCfdi/blob/master/LICENSE
[build]: https://travis-ci.org/phpcfdi/SatEstadoCfdi?branch=master
[quality]: https://scrutinizer-ci.com/g/PhpCfdi/SatEstadoCfdi/
[coverage]: https://scrutinizer-ci.com/g/PhpCfdi/SatEstadoCfdi/code-structure/master/code-coverage
[downloads]: https://packagist.org/packages/phpcfdi/sat-estado-cfdi

[badge-source]: http://img.shields.io/badge/source-PhpCfdi/SatEstadoCfdi-blue.svg?style=flat-square
[badge-release]: https://img.shields.io/github/release/PhpCfdi/SatEstadoCfdi.svg?style=flat-square
[badge-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[badge-build]: https://img.shields.io/travis/phpcfdi/SatEstadoCfdi/master.svg?style=flat-square
[badge-quality]: https://img.shields.io/scrutinizer/g/PhpCfdi/SatEstadoCfdi/master.svg?style=flat-square
[badge-coverage]: https://img.shields.io/scrutinizer/coverage/g/PhpCfdi/SatEstadoCfdi/master.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/phpcfdi/sat-estado-cfdi.svg?style=flat-square
