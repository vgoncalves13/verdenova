<?php

return [
    'mercadopago_card' => [
        'code'        => 'mercadopago_card',
        'title'       => 'Cartão de Crédito / Débito',
        'description' => 'Pague com cartão de crédito ou débito. Powered by Mercado Pago.',
        'class'       => 'Webkul\MercadoPago\Payment\MercadoPagoCard',
        'active'      => true,
        'sort'        => 1,
    ],

    'mercadopago_pix' => [
        'code'        => 'mercadopago_pix',
        'title'       => 'PIX',
        'description' => 'Pagamento instantâneo via PIX. O QR Code é gerado na próxima tela.',
        'class'       => 'Webkul\MercadoPago\Payment\MercadoPagoPix',
        'active'      => true,
        'sort'        => 2,
    ],

    'mercadopago_boleto' => [
        'code'        => 'mercadopago_boleto',
        'title'       => 'Boleto Bancário',
        'description' => 'Pague com boleto. Prazo de pagamento: até 3 dias úteis.',
        'class'       => 'Webkul\MercadoPago\Payment\MercadoPagoBoleto',
        'active'      => true,
        'sort'        => 3,
    ],
];
