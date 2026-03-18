<?php

namespace Webkul\MercadoPago\Payment;

class MercadoPagoPix extends MercadoPago
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'mercadopago_pix';

    public function getTitle(): string
    {
        return 'PIX';
    }

    public function getImage(): string
    {
        return asset('vendor/mercadopago/images/pix.svg');
    }
}
