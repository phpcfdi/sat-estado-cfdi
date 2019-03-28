<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi\Contracts;

interface ConsumerClientInterface
{
    /**
     * Implementors must return an associative array of key values
     * where each key is a status and value is the value of the status as returned by webservice.
     *
     * Example:
     * [
     *     'CodigoEstatus' => 'S - Comprobante obtenido satisfactoriamente.',
     *     'Estado' => 'Vigente',
     *     'EsCancelable' => 'Cancelable con aceptación',
     *     'EstatusCancelacion' => 'En proceso',
     * ]
     *
     * @param string $uri
     * @param string $expression
     * @return ConsumerClientResponseInterface
     */
    public function consume(string $uri, string $expression): ConsumerClientResponseInterface;
}
