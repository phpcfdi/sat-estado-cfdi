# Notas de desarrollo

He tomado la decisión de dividir esta librería en diferentes paquetes:

## phpcfdi/sat-estado-cfdi

Esta librería, contiene common helpers y la interfaz `ConsumerClientInterface` que se debe implementar.

Puedes crear tu propia implementación o alguna ya hecha por PhpCfdi.

## phpcfdi/sat-estado-cfdi-soap

Implementación de `ConsumerClientInterface` (de `phpcfdi/sat-estado-cfdi`) utilizando SOAP (sin WSDL).

Esta ya es una implementación concreta usable al 100%.

## phpcfdi/sat-estado-cfdi-http-psr

Implementación de `ConsumerClientInterface` utilizando PSR-18, PSR-7 y PSR-17.

Sigue siendo una implementación genérica a la que debes abastecer con
las implementaciones de PSR-18, PSR-7 y PSR-17.

## phpcfdi/sat-estado-cfdi-http-sunrise

Implementación de `ConsumerClientInterface` utilizando PSR-18, PSR-7 y PSR-17.

Implementación de `ConsumerHttpFactoryInterface` (de `phpcfdi/sat-estado-cfdi-http-psr`).

Esta ya es una implementación concreta usable al 100%.

