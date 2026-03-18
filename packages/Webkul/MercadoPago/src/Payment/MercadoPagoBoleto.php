<?php

namespace Webkul\MercadoPago\Payment;

class MercadoPagoBoleto extends MercadoPago
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'mercadopago_boleto';

    public function getTitle(): string
    {
        return 'Boleto Bancário';
    }

    public function getImage(): string
    {
        return asset('vendor/mercadopago/images/boleto.svg');
    }
}
