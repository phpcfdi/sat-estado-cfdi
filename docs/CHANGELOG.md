# CHANGELOG

## Acerca de SemVer

Usamos [Versionado Semántico 2.0.0](SEMVER.md) por lo que puedes usar esta librería sin temor a romper tu aplicación.

## Cambios no liberados en una versión

Pueden aparecer cambios no liberados que se integran a la rama principal, pero no ameritan una nueva liberación de
versión, aunque sí su incorporación en la rama principal de trabajo. Generalmente, se tratan de cambios en el desarrollo.

### Mantenimiento 2025-09-26

No ha sido necesario liberar una nueva versión debido a que estos cambios no afectan el código ejecutable.

- Se corrigen las insignias relacionadas a SonarQube Cloud.
- Se actualizan las reglas de *PHPCSFixer* a la versión 3.88.
- Se agregan las reglas `array_indentation` y `global_namespace_import` a *PHPCSFixer*.
- Se actualizan las herramientas de desarrollo.
- En el flujo de trabajo `build` se usa la variable `matrix.php-version` en singular.

### Mantenimiento 2025-08-21

No ha sido necesario liberar una nueva versión debido a que estos cambios no afectan el código ejecutable.

- Se actualiza la licencia del proyecto a 2025.
- Se agrega PHP 8.4 a la matriz de pruebas.
- Se actualiza la integración con *SonarQube Cloud* separando el flujo de trabajo
  a su propio archivo `sonarqube-cloud.yml`.
- Se mueve el trabajo `infection` al flujo de trabajo `build.yml`.
- Se agrega `composer-normalize` al proyecto:
  - Se incluye en las herramientas de desarrollo.
  - Se agrega a la construcción con `composer` en `dev:check-style` y `dev:fix-style`.
  - Se agrega a flujo de trabajo principal de construcción.
- Se elimina el flujo de trabajo `coverage.yml`.
- Se remueve *PSalm* de las herramientas de desarrollo, gracias por todo.
- Se actualiza *PHPStan* a la última versión mayor, con esto se corrige la contrucción semanal del proyecto.
- Se actualizan las reglas de *PHPCSFixer* a la versión mínima de PHP 8.2.
- Se actualizan las herramientas de desarrollo.

## Listado de cambios

### Versión 2.0.0 2024-03-06

Si ya habías implementado la versión 1.x, consula la [Guía de actualización de la versión 1.x a 2.x](UPGRADE_v1_v2.md).
Si es una implementación nueva, solamente sigue la documentación del proyecto.

Cambios más relevantes:

- La versión mínima es ahora PHP 8.2, se agrega PHP 8.3 a la matriz de pruebas.
- Se fusiona `phpcfdi/sat-estado-cfdi-soap` en `\PhpCfdi\SatEstadoCfdi\Clients\Soap`.
- Se fusiona `phpcfdi/sat-estado-cfdi-http-psr` en `\PhpCfdi\SatEstadoCfdi\Clients\Http`.
- Se dejan de utilizar *getters* a favor de propiedades públicas de solo lectura, excepto en *Excepciones*.
- Los enumeradores cambian de `eclipxe/enum` a tipos de PHP.
- Se usa una nueva interface `\PhpCfdi\SatEstadoCfdi\Contracts\Constants` para la especificación de constantes.
- Se actualiza el año en el archivo de licencia. Feliz 2024.
- Se actualiza el flujo de trabajo para ejecutar los trabajos en PHP 8.3.
- Se actualizan las herramientas de desarrollo.

### Mantenimiento 2023-02-27

Esta es una actualización de mantenimiento que no genera una nueva liberación de código.

- Se actualiza el año en la licencia. ¡Feliz 2023!
- Se actualiza la configuración de estilo de código a la utilizada por otros proyectos de phpCfdi.
- Se corrige la insignia `badge-build`.
- Se corrige la liga al proyecto en la guía de contribución.
- En los flujos de trabajo de integración continua:
  - Se agrega PHP 8.2 a la matriz de pruebas
  - Los trabajos se ejecutan en PHP 8.2
  - Se actualizan las acciones de GitHub a la versión 3.
  - Se sustituye la directiva `::set-output` por `$GITHUB_OUTPUT`.
  - Se elimina la instalación de `composer` donde no es necesaria.
  - Se agrega el evento `workflow_dispatch`.
- Se actualizan las herramientas de desarrollo.

### Version 1.0.3 2022-02-22

- Se actualiza el año en el archivo de licencia. Feliz 2022.
- Se corrige el grupo de mantenedores de phpCfdi.
- Se actualizan las dependencias de desarrollo.
- Se corrige el archivo de configuración de Psalm porque el atributo `totallyTyped` está deprecado.
- Se actualiza la dependencia de desarrollo `phpcfdi/cfdi-expresiones:^3.0`.
- Se deja de utilizar Scrutinizer CI. Gracias Scrutinizer CI.
- El flujo de integración continua se cambia de pasos a trabajos.

### Version 1.0.2 2021-11-04

- Las reglas del SAT cambiaron y la prueba de aceptación antes devolvía el estado "Cancelable sin aceptación"
  y ahora devuelve el estado "Cancelable con aceptación".
- Se corrige el nombre del archivo de configuración de PHPStan para ser excluido del paquete de distribución.

### Version 1.0.1 2021-09-03

- La versión menor de PHP es 7.3.
- Se actualiza PHPUnit a 9.5.
- Se migra de Travis-CI a GitHub Workflows. Gracias Travis-CI.
- Se instalan las herramientas de desarrollo usando `phive` en lugar de `composer`.
- Se agregan revisiones de `psalm` e `infection`.
- Se cambia la rama principal a `main`.

### Version 1.0.0 2021-01-10

- Se ha agregado soporte para la nueva propiedad `VerificacionEFOS`.
- A partir de esta versión se ha puesto la documentación del proyecto en español.
- Se garatiza la compatibilidad con PHP 8.0.

### Version 0.7.1 2021-01-08

- Add support for PHP 8.0.
- Change ownership from Carlos C Soto to PhpCfdi.
- Documentation: Update README badges, contributing instructions and license.
- Upgrade to PHPStan 0.12.
- Update Travis-CI and Scrutinizer pipelines.
- Remove PHPLint.

### Version 0.7.0 2019-05-16

- Remove CfdiStatus::request() & CfdiStatus::active() (fixes #7).

### Version 0.6.1 2019-05-16

- On version 0.6.0 class names where renamed but property names where not.
  This release is the last of 0.6.x and is created to throw warnings on deprecated property names.
- Rename CfdiStatus::request() to CfdiStatus::query(),
  if CfdiStatus::request() is consumed will trigger a `E_USER_DEPRECATED` error.
- Rename CfdiStatus::active() to CfdiStatus::document(),
  if CfdiStatus::active() is consumed will trigger a `E_USER_DEPRECATED` error.
  
### Version 0.6.0 2019-03-25

- Rename CfdiStatus properties, status classes and status enums using descriptions.
- Remove references to sunrise package that is not going to exists on phpcfdi umbrella.
- Update `README.md` according to last changes.

### Version 0.5.0 2019-03-28

- Remove `CfdiExpression` and `CfdiExpressionBuilder` (now on its own project `phpcfdi/cfdi-expresiones`)
- Rename `ResponseStatus` to `CfdiStatus`
- Rename `ResponseStatusBuilder` to `Utils\CfdiStatusBuilder`
- Rename `ConsumerClientResponse` to `Utils\ConsumerClientResponse`
- Rename `WebServiceConsumer` to `Consumer`
- Document usage example on `README.md`.

### Version 0.4.0 2019-03-25

- Split this package to separate concerns.
- More information about this separation of concerns inside `docs/DEVNOTES.md`.
- Rewrite `README.md`

### Version 0.3.0 2019-03-25

- Move from `spatie/enum` to `eclipxe/enum`.
- Move testing dependences to sunrise packages.
- Improve documentation about PSR and examples.
- Add more ideas to `docs/DEVNOTES.md` and `doc/TODO.md`.

### Version 0.2.0 2019-03-22

- Fix typo, used to say `cancellabe` instead of `cancellable`
- composer now require some package that satisfy PSR's virtual packages

### Version 0.1.0 2019-03-20

- Initial working release for testing with friends
