# phpcfdi/sat-estado-cfdi

[![Source Code][badge-source]][source]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][build]
[![Scrutinizer][badge-quality]][quality]
[![Coverage Status][badge-coverage]][coverage]
[![Total Downloads][badge-downloads]][downloads]

> Consulta el estado de un CFDI en el webservice del SAT

:us: This library contains helpers to consume the **Servicio de Consulta de CFDI** from **SAT**.
The documentation of this project is in spanish as this is the natural language for intended audience.

:mexico: Esta librería contiene objetos de ayuda para consumir el **Servicio de Consulta de CFDI del SAT**.
La documentación del proyecto está en español porque ese es el lenguaje de los usuarios que la utilizarán.

**Servicio de Consulta de CFDI del SAT**:

- Servicio productivo: <https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc>
- Servicio de pruebas: <https://pruebacfdiconsultaqr.cloudapp.net/ConsultaCFDIService.svc>
- SAT: <https://www.sat.gob.mx/consultas/20585/conoce-los-servicios-especializados-de-validacion>
- Documentación del Servicio de Consulta de CFDIVersión 1.3 (2020-11-18):
  <https://www.sat.gob.mx/cs/Satellite?blobcol=urldata&blobkey=id&blobtable=MungoBlobs&blobwhere=1579314559300&ssbinary=true>

**Cambios recientes en el servicio**:

- Por motivo del cambio en el proceso de cancelación, en 2018 agregaron nuevos estados.
- Por una razón desconocida —e inexplicable—, el WSDL ya no se encuentra disponible desde 2018. Aunque sí se puede consumir el servicio.
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
declare(strict_types=1);

use PhpCfdi\SatEstadoCfdi\Consumer;
use PhpCfdi\SatEstadoCfdi\Contracts\ConsumerClientInterface;

/** @var ConsumerClientInterface $client */
$consumer = new Consumer($client);

$cfdiStatus = $consumer->execute('...expression');

if ($cfdiStatus->cancellable()->isNotCancellable()) {
    echo 'CFDI no es cancelable';
}
```

### Expresiones (input)

El consumidor requiere una expresión para poder consultar.
La expresión es el texto que viene en el código QR de la representación impresa de un CFDI.

Las expresiones son diferentes para CFDI 3.2, CFDI 3.3 y RET 1.0.
Tienen reglas específicas de formato y de la información que debe contener.

Si no cuentas con la expresión, te recomiendo usar la librería
[`phpcfdi/cfdi-expresiones`](https://github.com/phpcfdi/cfdi-expresiones) que puedes instalar
usando `composer require phpcfdi/cfdi-expresiones`.

```php
<?php
declare(strict_types=1);

use PhpCfdi\CfdiExpresiones\DiscoverExtractor;
use PhpCfdi\SatEstadoCfdi\Consumer;

// lectura del contenido del CFDI
$document = new DOMDocument();
$document->load('archivo-cfdi33.xml');

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

No compares directamente los valores de los estados, en su lugar utiliza los métodos `is*`,
por ejemplo `$response->document()->isCancelled()`.

Posibles estados:

- `CodigoEstatus`: `query(): QueryStatus`.
    - `found`: Si el estado inicia con `S - `.
    - `notFound`: en cualquier otro caso.

- `Estado`: `document(): DocumentStatus`.
    - `active`: Si el estado reportó `Vigente`.
    - `cancelled`: Si el estado reportó `Cancelado`.
    - `notFound`: en cualquier otro caso.

- `EsCancelable`: `cancellable(): CancellableStatus`.
    - `cancellableByDirectCall`: Si el estado reportó `Cancelable sin aceptación`.
    - `cancellableByApproval`: Si el estado reportó `Cancelable con aceptación`.
    - `notCancellable`: en cualquier otro caso.

- `EstatusCancelacion`: `cancellation(): CancellationStatus`.
    - `cancelledByDirectCall`: Si el estado reportó `Cancelado sin aceptación`.
    - `cancelledByApproval`: Si el estado reportó `Cancelado con aceptación`.
    - `cancelledByExpiration`: Si el estado reportó `Plazo vencido`.
    - `pending`: Si el estado reportó `En proceso`.
    - `disapproved`: Si el estado reportó `Solicitud rechazada`.
    - `undefined`: en cualquier otro caso.

- `ValidacionEFOS`: `efos(): EfosStatus`.
    - `included`: Si el estado no reportó `200`.
    - `excluded`: Si el estado reportó `200`.

#### Estados mutuamente excluyentes:

CodigoEstatus | Estado        | EsCancelable              | EstatusCancelacion       | Explicación
------------- | ------------- | ------------------------- | ------------------------ | -----------------------------------------------------
N - ...       | *             | *                         | *                        | El SAT no sabe del CFDI con la expresión dada
S - ...       | Cancelado     | *                         | Plazo vencido            | Cancelado por plazo vencido
S - ...       | Cancelado     | *                         | Cancelado con aceptación | Cancelado con aceptación del receptor
S - ...       | Cancelado     | *                         | Cancelado sin aceptación | No fue requerido preguntarle al receptor y se canceló
S - ...       | Vigente       | No cancelable             | *                        | No se puede cancelar
S - ...       | Vigente       | Cancelable sin aceptación | *                        | Se puede cancelar, pero no se ha realizado la cancelación
S - ...       | Vigente       | Cancelable con aceptación | (ninguno)                | Se puede cancelar, pero no se ha realizado la solicitud
S - ...       | Vigente       | Cancelable con aceptación | En proceso               | Se hizo la solicitud y está en espera de respuesta
S - ...       | Vigente       | Cancelable con aceptación | Solicitud rechazada      | Se hizo la solicitud y fue rechazada

Cuando tienes un CFDI en estado *Cancelable con aceptación*
y mandas a hacer la cancelación entonces su estado de cancelación cambiaría a *En proceso*.

El receptor puede aceptar la cancelación (*Cancelado con aceptación*) o rechazarla (*Solicitud rechazada*).

Si es la *primera vez* que se hace la solicitud, el receptor tiene 72 horas para aceptarla o rechazarla,
si no lo hace entonces automáticamente será cancelada (*Plazo vencido*).

Podrías volver a enviar la solicitud de cancelación *por segunda vez* aun cuando la solicitud fue previamente rechazada.

En ese caso, el receptor puede aceptar o rechazar la cancelación, pero ya no aplicará un lapzo de 72 horas.
Por lo anterior entonces podrías tener el CFDI en estado de cancelación *en proceso* indefinidamente.
Incluso, que la cancelación suceda meses después de lo esperado.

## Clientes de conexión

Esta librería no es la que hace directamente las conexiones al webservice del SAT.

Esta función se la delega a un objeto `ConsumerClientInterface`.

Tú puedes implementar tu cliente de conexión personalizado para tu entorno siempre que
implementes la interfaz `ConsumerClientInterface`.

O si lo prefieres, existen los siguientes consumidores oficiales:

- [phpcfdi/sat-estado-cfdi-soap](https://github.com/phpcfdi/sat-estado-cfdi-soap):
  Consume el webservice haciendo una llamada SOAP (sin WSDL) para obtener el resultado.
- [phpcfdi/sat-estado-cfdi-http-psr](https://github.com/phpcfdi/sat-estado-cfdi-http-psr)
  Consume el webservice haciendo una solicitud HTTP utilizando objetos de PSR-7, PSR17 y PSR18 *que tú provees*.

### Prueba de cumplimiento de implementación

Se incluye la clase `PhpCfdi\SatEstadoCfdi\ComplianceTester\ComplianceTester` que contacta al
webservice del SAT con datos conocidos y evalua la respuesta.

Los paquetes `phpcfdi/sat-estado-cfdi-soap` y `phpcfdi/sat-estado-cfdi-http-psr` implementan
un test para asegurarse que cumplen correctamente.

Si haces tu propia implementación, asegúrate de crear un test que lo cubra, puedes ver como ejemplos
<https://github.com/phpcfdi/sat-estado-cfdi-soap/blob/main/tests/Compliance/ComplianceTest.php> o
<https://github.com/phpcfdi/sat-estado-cfdi-http-psr/blob/main/tests/Compliance/ComplianceTest.php>.

## Compatibilidad

Esta librería se mantendrá compatible con al menos la versión con
[soporte activo de PHP](https://www.php.net/supported-versions.php) más reciente.

También utilizamos [Versionado Semántico 2.0.0](docs/SEMVER.md) por lo que puedes usar esta librería
sin temor a romper tu aplicación.

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
[release]: https://github.com/phpcfdi/sat-estado-cfdi/releases
[license]: https://github.com/phpcfdi/sat-estado-cfdi/blob/main/LICENSE
[build]: https://github.com/phpcfdi/sat-estado-cfdi/actions/workflows/build.yml?query=branch:main
[quality]: https://scrutinizer-ci.com/g/phpcfdi/sat-estado-cfdi/
[coverage]: https://scrutinizer-ci.com/g/phpcfdi/sat-estado-cfdi/code-structure/main/code-coverage
[downloads]: https://packagist.org/packages/phpcfdi/sat-estado-cfdi

[badge-source]: https://img.shields.io/badge/source-phpcfdi/sat--estado--cfdi-blue?style=flat-square
[badge-release]: https://img.shields.io/github/release/phpcfdi/sat-estado-cfdi?style=flat-square
[badge-license]: https://img.shields.io/github/license/phpcfdi/sat-estado-cfdi?style=flat-square
[badge-build]: https://img.shields.io/github/workflow/status/phpcfdi/sat-estado-cfdi/build/main?style=flat-square
[badge-quality]: https://img.shields.io/scrutinizer/g/phpcfdi/sat-estado-cfdi/main?style=flat-square
[badge-coverage]: https://img.shields.io/scrutinizer/coverage/g/phpcfdi/sat-estado-cfdi/main?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/phpcfdi/sat-estado-cfdi?style=flat-square
