# CHANGELOG

## Version 0.6.0 2019-03-25

- Rename CfdiStatus properties, status classes and status enums using descriptions.
- Remove references to sunrise package that is not going to exists on phpcfdi umbrella.
- Update `README.md` according to last changes.


## Version 0.5.0 2019-03-28

- Remove `CfdiExpression` and `CfdiExpressionBuilder` (now on its own project `phpcfdi/cfdi-expresiones`)
- Rename `ResponseStatus` to `CfdiStatus`
- Rename `ResponseStatusBuilder` to `Utils\CfdiStatusBuilder`
- Rename `ConsumerClientResponse` to `Utils\ConsumerClientResponse`
- Rename `WebServiceConsumer` to `Consumer`
- Document usage example on `README.md`.


## Version 0.4.0 2019-03-25

- Split this package to separate concerns.
- More information about this separation of concerns inside `docs/DEVNOTES.md`.
- Rewrite `README.md`


## Version 0.3.0 2019-03-25

- Move from `spatie/enum` to `eclipxe/enum`.
- Move testing dependences to sunrise packages.
- Improve documentation about PSR and examples.
- Add more ideas to `docs/DEVNOTES.md` and `doc/TODO.md`.


## Version 0.2.0 2019-03-22

- Fix typo, use to say `cancellabe` instead of `cancellable`
- composer now require some package that satisfy PSR's virtual packages


## Version 0.1.0 2019-03-20

- Initial working release for testing with friends
