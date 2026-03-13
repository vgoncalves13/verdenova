<?php

return [
    [
        'key'    => 'sales.payment_methods.mercadopago',
        'name'   => 'MercadoPago',
        'info'   => 'Checkout transparente com cartão, Pix e boleto.',
        'sort'   => 10,
        'fields' => [
            [
                'name'    => 'title',
                'title'   => 'Título',
                'type'    => 'text',
                'default' => 'Cartão, Pix ou Boleto (Mercado Pago)',
                'channel_based' => true,
                'locale_based'  => true,
            ],
            [
                'name'          => 'description',
                'title'         => 'Descrição',
                'type'          => 'textarea',
                'default'       => 'Pague com cartão, Pix ou boleto. Powered by Mercado Pago.',
                'channel_based' => true,
                'locale_based'  => true,
            ],
            [
                'name'    => 'active',
                'title'   => 'Ativo',
                'type'    => 'boolean',
                'default' => true,
            ],
            [
                'name'    => 'sandbox',
                'title'   => 'Modo Sandbox (teste)',
                'type'    => 'boolean',
                'default' => true,
            ],
            [
                'name'  => 'public_key',
                'title' => 'Public Key',
                'type'  => 'text',
            ],
            [
                'name'  => 'access_token',
                'title' => 'Access Token',
                'type'  => 'password',
            ],
            [
                'name'    => 'pix_expiration',
                'title'   => 'Expiração do Pix (ISO 8601)',
                'type'    => 'text',
                'default' => 'PT24H',
                'info'    => 'Exemplos: PT1H (1 hora), PT24H (24 horas), P3D (3 dias).',
            ],
            [
                'name'    => 'sort',
                'title'   => 'Ordenação',
                'type'    => 'text',
                'default' => 1,
            ],
        ],
    ],
];
