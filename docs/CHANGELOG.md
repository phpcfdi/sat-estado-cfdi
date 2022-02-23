# CHANGELOG

## Acerca de SemVer

Usamos [Versionado Semántico 2.0.0](SEMVER.md) por lo que puedes usar esta librería sin temor a romper tu aplicación.

## Cambios no liberados en una versión

Pueden aparecer cambios no liberados que se integran a la rama principal, pero no ameritan una nueva liberación de
versión aunque sí su incorporación en la rama principal de trabajo, generalmente se tratan de cambios en el desarrollo.

## Listado de cambios

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
