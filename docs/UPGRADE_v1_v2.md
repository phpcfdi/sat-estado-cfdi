# Guía de actualización de la versión 1.x a 2.x

Para esta nueva versión hay cambios muy relevantes:

- La versión mínima es ahora PHP 8.2, se agrega PHP 8.3 a la matriz de pruebas.
- Se fusiona `phpcfdi/sat-estado-cfdi-soap` y `phpcfdi/sat-estado-cfdi-http-psr` en `phpcfdi/sat-estado-cfdi`.
- Se usan propiedades públicas de solo lectura en lugar de *getters*.
- Los enumeradores cambian de `eclipxe/enum` a tipos de PHP.
- Se mueven las constantes a `\PhpCfdi\SatEstadoCfdi\Contracts\Constants`.
- Las clases ahora son finales y de solo lectura.

En un uso normal de la librería, solo deberás remover el paquete `phpcfdi/sat-estado-cfdi-http-psr` o `phpcfdi/sat-estado-cfdi`,
y renombrar los espacios de nombres. La mayoría de los cambios, a pesar de ser mayores, no deberían afectar
el uso que normalmente hacías en la versión 1.x de la librería.

## PHP

La versión mínima es ahora PHP 8.2. Se comprueba la compatibilidad de PHP 8.3 con la matriz de pruebas.

## Fusión de `phpcfdi/sat-estado-cfdi-soap`

El proyecto [`phpcfdi/sat-estado-cfdi-soap`](https://github.com/phpcfdi/sat-estado-cfdi-soap) se ha fusionado
y el repositorio ha sido archivado.

Si estabas usando esta librería, elimínala de tus dependencias antes de migrar a esta versión.

El espacio de nombre cambia de `PhpCfdi\SatEstadoCfdi\Soap` a `PhpCfdi\SatEstadoCfdi\Clients\Soap`,
en tu proyecto este sería el cambio más importante:

```diff
- use PhpCfdi\SatEstadoCfdi\Soap\SoapConsumerClient;
+ use PhpCfdi\SatEstadoCfdi\Clients\Soap\SoapConsumerClient;
```

No olvides contar con la extension de PHP `ext-soap` activa al momento de la ejecución,
de lo contrario se producirá un error.

## Fusión de `phpcfdi/sat-estado-cfdi-http-psr`

El proyecto [`phpcfdi/sat-estado-cfdi-http-psr`](https://github.com/phpcfdi/sat-estado-cfdi-http-psr) se ha fusionado
y el repositorio ha sido archivado.

Si estabas usando esta librería, elimínala de tus dependencias antes de migrar a esta versión.

El espacio de nombre cambia de `PhpCfdi\SatEstadoCfdi\HttpPsr` a `PhpCfdi\SatEstadoCfdi\Clients\Http`,
en tu proyecto este sería el cambio más importante:

```diff
- use PhpCfdi\SatEstadoCfdi\HttpPsr\HttpConsumerFactory;
- use PhpCfdi\SatEstadoCfdi\HttpPsr\HttpConsumerFactoryInterface;
+ use PhpCfdi\SatEstadoCfdi\Clients\Http\HttpConsumerFactory;
+ use PhpCfdi\SatEstadoCfdi\Clients\Http\HttpConsumerFactoryInterface;
```

No olvides contar con la extension de PHP `ext-dom` activa al momento de la ejecución,
de lo contrario se producirá un error.

## Propiedades públicas de solo lectura en lugar de *getters*

Aprovechando las mejoras de PHP y que las clases ahora son finales y de solo lectura, se han dejado de
utilizar los métodos para consultar propiedades (*getters*) y en su lugar se usan las propiedades públicas
en modo solo lectura. El código que seguramente deberás modificar es para la clase `CfdiStatus`.

```diff
- return $cfdiStatus->document()->isCancelled();
+ return $cfdiStatus->document->isCancelled();
```

## Enumeradores de estado

Los enumeradores ya no usan `eclipxe/enum`, ahora son enumeradores de PHP, por lo que las comparaciones idénticas son válidas.

Si estabas instanciando algún estado, tu código debería cambiar de un método a un valor de enumerador, por ejemplo:

```diff
- return QueryStatus::notFound();
+ return QueryStatus::NotFound;
```

Los métodos de comprobación `is*` siguen funcionando, por ejemplo: `$result->query->isFound()`.

Toma en cuenta que los enumeradores no son `Stringable`, por lo que debes usar la propiedad `name`.
También el texto cambió de ser la primera letra minúscula a la primera letra mayúscula.
Si necesitas el texto exacto puedes usar `lcfirst($enum->name)`.

```diff
- echo QueryStatus::notFound();     // notFound
+ echo QueryStatus::NotFound->name  // NotFound
```

## Constantes en `\PhpCfdi\SatEstadoCfdi\Contracts\Constants`

Las constantes se mueven a la interface `\PhpCfdi\SatEstadoCfdi\Contracts\Constants`.
El cambio más importante está en:

```diff
- PhpCfdi\SatEstadoCfdi\Consumer::WEBSERVICE_URI_PRODUCTION
- PhpCfdi\SatEstadoCfdi\Consumer::WEBSERVICE_URI_DEVELOPMENT
+ PhpCfdi\SatEstadoCfdi\Contracts\Constants::WEBSERVICE_URI_PRODUCTION
+ PhpCfdi\SatEstadoCfdi\Contracts\Constants::WEBSERVICE_URI_DEVELOPMENT
```

## Clases finales y de solo lectura

Es bastante poco probable que hayas extendido las clases de esta librería, pero si ese es el caso,
encontrarás que la mayoría de las clases ahora son finales y sus propiedades de solo lectura.
