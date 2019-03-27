<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoCfdi;

class CfdiExpression
{
    /** @var string */
    private $version;

    /** @var string */
    private $rfcEmisor;

    /** @var string */
    private $rfcReceptor;

    /** @var string */
    private $total;

    /** @var float */
    private $totalFloat;

    /** @var string */
    private $uuid;

    /** @var string */
    private $sello;

    public function __construct(
        string $version,
        string $rfcEmisor,
        string $rfcReceptor,
        string $total,
        string $uuid,
        string $sello = ''
    ) {
        $this->version = $version;
        $this->rfcEmisor = $rfcEmisor;
        $this->rfcReceptor = $rfcReceptor;
        $this->total = $total;
        $this->totalFloat = floatval(trim(str_replace(',', '', $this->total)));
        $this->uuid = $uuid;
        $this->sello = $sello;
    }

    public function version(): string
    {
        return $this->version;
    }

    public function rfcEmisor(): string
    {
        return $this->rfcEmisor;
    }

    public function rfcReceptor(): string
    {
        return $this->rfcReceptor;
    }

    public function total(): string
    {
        return $this->total;
    }

    public function totalAsFloat(): float
    {
        return $this->totalFloat;
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function sello(): string
    {
        return $this->sello;
    }

    public function expression(): string
    {
        if ('3.2' === $this->version) {
            return $this->expressionVersion32();
        }
        if ('3.3' === $this->version) {
            return $this->expressionVersion33();
        }
        return '';
    }

    protected function expressionVersion32(): string
    {
        return '?' . implode('&', [
            're=' . strval($this->rfcEmisor),
            'rr=' . strval($this->rfcReceptor),
            'tt=' . str_pad(number_format($this->totalFloat, 6, '.', ''), 17, '0', STR_PAD_LEFT),
            'id=' . strval($this->uuid),
        ]);
    }

    protected function expressionVersion33(): string
    {
        $total = rtrim(number_format($this->totalFloat, 6, '.', ''), '0');
        if ('.' === substr($total, -1, 1)) {
            $total = $total . '0'; // add trailing zero
        }
        return 'https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?' . implode('&', [
            'id=' . strval($this->uuid),
            're=' . strval($this->rfcEmisor),
            'rr=' . strval($this->rfcReceptor),
            'tt=' . $total,
            'fe=' . substr($this->sello, -8),
        ]);
    }
}
