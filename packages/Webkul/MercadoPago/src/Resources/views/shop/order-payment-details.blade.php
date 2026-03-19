@if(!empty($additional['mp_payment_id']) || !empty($additional['installments']))
    @if(!empty($additional['installments']) && (int) $additional['installments'] > 1)
        <div class="mt-1 text-xs text-gray-600">
            {{ $additional['installments'] }}x
            — Total: R$ {{ number_format((float) $additional['total_paid'], 2, ',', '.') }}
            (juros: R$ {{ number_format((float) $additional['interest_amount'], 2, ',', '.') }})
        </div>
    @endif
@endif
