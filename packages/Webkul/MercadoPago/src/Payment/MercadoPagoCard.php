<?php

namespace Webkul\MercadoPago\Payment;

class MercadoPagoCard extends MercadoPago
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'mercadopago_card';

    public function getTitle(): string
    {
        return 'Cartão de Crédito / Débito';
    }

    public function getImage(): string
    {
        return asset('vendor/mercadopago/images/card.svg');
    }
}
