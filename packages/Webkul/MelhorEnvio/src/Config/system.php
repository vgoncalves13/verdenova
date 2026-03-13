<?php

return [
    [
        'key'    => 'sales.carriers.melhorenvio',
        'name'   => 'Melhor Envio',
        'info'   => 'Frete calculado via API do Melhor Envio',
        'sort'   => 3,
        'fields' => [
            [
                'name'    => 'title',
                'title'   => 'Título',
                'type'    => 'text',
                'default' => 'Melhor Envio',
            ],
            [
                'name'    => 'active',
                'title'   => 'Ativo',
                'type'    => 'boolean',
                'default' => true,
            ],
            [
                'name'    => 'environment',
                'title'   => 'Ambiente',
                'type'    => 'select',
                'options' => [
                    ['value' => 'sandbox',    'title' => 'Sandbox'],
                    ['value' => 'production', 'title' => 'Produção'],
                ],
                'default' => 'sandbox',
            ],
            [
                'name'  => 'token',
                'title' => 'Token Pessoal (dev/teste)',
                'type'  => 'text',
                'info'  => 'Gere em: Painel → Integrações → Gerenciar Tokens. Quando preenchido, ignora o OAuth abaixo.',
            ],
            [
                'name'       => 'client_id',
                'title'      => 'Client ID (OAuth — produção)',
                'type'       => 'text',
            ],
            [
                'name'  => 'client_secret',
                'title' => 'Client Secret (OAuth — produção)',
                'type'  => 'text',
            ],
            [
                // Renders the "Connect to Melhor Envio" button + status
                'name'  => 'oauth_connect',
                'title' => 'Autorização OAuth',
                'type'  => 'blade',
                'path'  => 'melhorenvio::admin.oauth-connect',
            ],
            [
                'name'       => 'origin_postcode',
                'title'      => 'CEP de Origem',
                'type'       => 'text',
                'validation' => 'required',
            ],
            // -----------------------------------------------------------------
            // Fallback dimensions — used only when a product has no dimensions
            // configured. Fill product attributes (height/width/length/weight)
            // in the catalog for accurate quotes.
            // -----------------------------------------------------------------
            [
                'name'    => 'default_weight',
                'title'   => 'Peso padrão por item (kg) — fallback',
                'type'    => 'text',
                'default' => 0.3,
            ],
            [
                'name'    => 'default_height',
                'title'   => 'Altura padrão (cm) — fallback',
                'type'    => 'text',
                'default' => 10,
            ],
            [
                'name'    => 'default_width',
                'title'   => 'Largura padrão (cm) — fallback',
                'type'    => 'text',
                'default' => 15,
            ],
            [
                'name'    => 'default_length',
                'title'   => 'Comprimento padrão (cm) — fallback',
                'type'    => 'text',
                'default' => 20,
            ],
        ],
    ],
];
