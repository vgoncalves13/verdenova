@if(!empty($additional['mp_order_id']) || !empty($additional['mp_payment_id']))
    <span class="mt-4 block w-full border-b dark:border-gray-800"></span>

    <p class="pt-4 font-semibold text-gray-800 dark:text-white">MercadoPago</p>

    @if(!empty($additional['mp_order_id']))
        <p class="text-xs text-gray-500 dark:text-gray-400">
            Order ID: {{ $additional['mp_order_id'] }}
        </p>
    @endif

    @if(!empty($additional['mp_payment_id']))
        <p class="text-xs text-gray-500 dark:text-gray-400">
            Payment ID: {{ $additional['mp_payment_id'] }}
        </p>
    @endif

    @if(!empty($additional['payment_method_id']))
        <p class="text-xs text-gray-500 dark:text-gray-400">
            Método: {{ $additional['payment_method_id'] }}
        </p>
    @endif

    @if(!empty($additional['installments']) && (int) $additional['installments'] > 1)
        <p class="pt-2 text-xs text-gray-500 dark:text-gray-400">
            Parcelamento: {{ $additional['installments'] }}x
            &mdash; Total cobrado: R$ {{ number_format((float) $additional['total_paid'], 2, ',', '.') }}
            &mdash; Juros: R$ {{ number_format((float) $additional['interest_amount'], 2, ',', '.') }}
        </p>
    @endif
@endif
