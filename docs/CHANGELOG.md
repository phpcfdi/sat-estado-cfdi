# CHANGELOG

## About SemVer

This library uses SemVer 2.0 `major . minor . fix`. It means that it will not introduce breaking changes
on `major` versions, introduce new features on `minor` versions and bugfixes on `fix` version. However, this rules
does not apply to version `0.*.*`.

## About unreleased changes

Unreleased changes are listed at the top and include any change that does not include any bugfix or any change
on source code. On the first opportunity this changes will be included on the next release.

## Releases

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
