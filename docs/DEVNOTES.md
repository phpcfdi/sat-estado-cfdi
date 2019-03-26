# Notas de desarrollo

La librería parece muy cargada cuando podría ser más sencilla.
Principalmente se debe a las dependencias de los PSR.

Se podría crear un segundo paquete con una implementación ya hecha que permita
simplemente consumir el paquete en una línea, del tipo:

```php
$curlOptions = []; // optional
$consumer = \PhpCfdi\SatEstadoCfdi\SunriseFactory($curlOptions)->createClient();
$consumer->execute($expression);
```

