{!! view_render_event('bagisto.shop.checkout.onepage.payment_methods.before') !!}

<v-payment-methods
    :methods="paymentMethods"
    :cart-total="cart.grand_total"
    @processing="stepForward"
    @processed="stepProcessed"
>
    <x-shop::shimmer.checkout.onepage.payment-method />
</v-payment-methods>

{!! view_render_event('bagisto.shop.checkout.onepage.payment_methods.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-payment-methods-template"
    >
        <div class="mb-7 max-md:last:!mb-0">
            <template v-if="! methods">
                <!-- Payment Method shimmer Effect -->
                <x-shop::shimmer.checkout.onepage.payment-method />
            </template>

            <template v-else>
                {!! view_render_event('bagisto.shop.checkout.onepage.payment_method.accordion.before') !!}

                <!-- Accordion: only the payment method selector cards -->
                <x-shop::accordion class="overflow-hidden !border-b-0 max-md:rounded-lg max-md:!border-none max-md:!bg-green-50">
                    <x-slot:header class="px-0 py-4 max-md:p-3 max-md:text-sm max-md:font-medium max-sm:p-2">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-medium max-md:text-base">
                                @lang('shop::app.checkout.onepage.payment.payment-method')
                            </h2>
                        </div>
                    </x-slot>

                    <x-slot:content class="mt-8 !p-0 max-md:mt-0 max-md:rounded-t-none max-md:border max-md:border-t-0 max-md:!p-4">
                        <div class="flex flex-wrap gap-7 max-md:gap-4 max-sm:gap-2.5">
                            <div
                                class="relative cursor-pointer max-md:max-w-full max-md:flex-auto"
                                v-for="(payment, index) in methods"
                            >
                                {!! view_render_event('bagisto.shop.checkout.payment-method.before') !!}

                                <input
                                    type="radio"
                                    name="payment[method]"
                                    :value="payment.payment"
                                    :id="payment.method"
                                    class="peer hidden"
                                    @change="onMethodChange(payment)"
                                >

                                <label
                                    :for="payment.method"
                                    class="icon-radio-unselect peer-checked:icon-radio-select absolute top-5 cursor-pointer text-2xl text-navyBlue ltr:right-5 rtl:left-5"
                                >
                                </label>

                                <label
                                    :for="payment.method"
                                    class="block w-[190px] cursor-pointer rounded-xl border border-zinc-200 p-5 max-md:flex max-md:w-full max-md:gap-5 max-md:rounded-lg max-sm:gap-4 max-sm:px-4 max-sm:py-2.5"
                                >
                                    {!! view_render_event('bagisto.shop.checkout.onepage.payment-method.image.before') !!}

                                    <img
                                        class="max-h-11 max-w-14"
                                        :src="payment.image"
                                        width="55"
                                        height="55"
                                        :alt="payment.method_title"
                                        :title="payment.method_title"
                                    />

                                    {!! view_render_event('bagisto.shop.checkout.onepage.payment-method.image.after') !!}

                                    <div>
                                        {!! view_render_event('bagisto.shop.checkout.onepage.payment-method.title.before') !!}

                                        <p class="mt-1.5 text-sm font-semibold max-md:mt-1 max-sm:mt-0">
                                            @{{ payment.method_title }}
                                        </p>

                                        {!! view_render_event('bagisto.shop.checkout.onepage.payment-method.title.after') !!}

                                        {!! view_render_event('bagisto.shop.checkout.onepage.payment-method.description.before') !!}

                                        <p class="mt-2.5 text-xs font-medium text-zinc-500 max-md:mt-1 max-sm:mt-0">
                                            @{{ payment.description }}
                                        </p>

                                        {!! view_render_event('bagisto.shop.checkout.onepage.payment-method.description.after') !!}
                                    </div>
                                </label>

                                {!! view_render_event('bagisto.shop.checkout.payment-method.after') !!}
                            </div>
                        </div>
                    </x-slot>
                </x-shop::accordion>

                {!! view_render_event('bagisto.shop.checkout.onepage.payment_method.accordion.after') !!}

                <!--
                    MercadoPago Custom UI — rendered OUTSIDE the accordion.
                    Each payment method (card, pix, boleto) is a separate Bagisto method.
                -->

                <!-- Cartão -->
                <template v-if="selectedMethod === 'mercadopago_card'">
                    <div class="mt-4 overflow-hidden rounded-2xl border border-darkGreen/20">
                        <div id="mp-card-brick" class="p-2"></div>
                        <div v-if="mpCardError" class="px-6 pb-4">
                            <p class="mb-3 text-sm text-red-500">@{{ mpCardError }}</p>
                            <button
                                v-if="mpCardRetry"
                                type="button"
                                class="w-full rounded-xl border border-darkGreen py-2.5 text-sm font-semibold text-darkGreen transition hover:bg-darkGreen hover:text-white"
                                @click="mpCardError = null; mpCardRetry = false; destroyMPFields(); $nextTick(() => requestAnimationFrame(() => initMPFields()))"
                            >
                                Tentar novamente
                            </button>
                        </div>
                    </div>
                </template>

                <!-- PIX -->
                <template v-if="selectedMethod === 'mercadopago_pix'">
                    <div class="mt-4 overflow-hidden rounded-2xl border border-darkGreen/20 p-6 text-center">
                        <div class="mb-4 flex justify-center">
                            <svg class="h-14 w-14 text-darkGreen" viewBox="0 0 512 512" fill="currentColor">
                                <path d="M242.4 292.5C247.8 287.1 257.1 287.1 262.5 292.5L339.5 369.5C353.7 383.7 372.6 391.5 392.6 391.5H407.7L310.2 488.1C280.3 517.7 231.1 517.7 201.2 488.1L103.3 391.5H118.4C138.4 391.5 157.3 383.7 171.5 369.5L242.4 292.5zM262.5 218.9C257.1 224.4 247.8 224.4 242.4 218.9L171.5 141.9C157.3 127.7 138.4 119.9 118.4 119.9H103.3L201.2 23.3C231.1-6.3 280.3-6.3 310.2 23.3L407.7 119.9H392.6C372.6 119.9 353.7 127.7 339.5 141.9L262.5 218.9zM112 144H118.4C132 144 144.1 149.3 153 158.1L240.1 244.4C253.1 257.4 258.9 257.4 271.9 244.4L358.1 158.1C367 149.3 379.1 144 392.6 144H399C419 144 438.6 151.5 453.4 165.2L484.3 195.2C512.5 222.9 512.5 268.7 484.3 296.5L453.4 326.5C438.6 340.2 419 347.7 399 347.7H392.6C379.1 347.7 367 342.4 358.1 333.6L271.9 247.3C258.9 234.3 253.1 234.3 240.1 247.3L153 333.6C144.1 342.4 132 347.7 118.4 347.7H112C92 347.7 72.4 340.2 57.6 326.5L26.7 296.5C-1.5 268.7-1.5 222.9 26.7 195.2L57.6 165.2C72.4 151.5 92 144 112 144z"/>
                            </svg>
                        </div>
                        <h3 class="mb-2 font-semibold text-navyBlue">Pague com PIX</h3>
                        <p class="mb-6 text-sm text-zinc-500">Pagamento instantâneo. O QR Code será gerado na próxima tela.</p>
                        <p v-if="mpPixError" class="mb-3 text-sm text-red-500">@{{ mpPixError }}</p>
                        <button
                            type="button"
                            class="w-full rounded-xl bg-darkGreen py-3 text-sm font-semibold text-white transition hover:opacity-90 disabled:opacity-60"
                            :disabled="mpSubmitting"
                            @click="submitPix()"
                        >
                            <span v-if="mpSubmitting">Gerando...</span>
                            <span v-else>Gerar QR Code PIX</span>
                        </button>
                    </div>
                </template>

                <!-- Boleto -->
                <template v-if="selectedMethod === 'mercadopago_boleto'">
                    <div class="mt-4 overflow-hidden rounded-2xl border border-darkGreen/20 p-6 text-center">
                        <div class="mb-4 flex justify-center">
                            <svg class="h-14 w-14 text-darkGreen" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="2" y="4" width="20" height="16" rx="2"/>
                                <path d="M7 8v8M10 8v8M14 8v8M17 8v8M5 8v8"/>
                            </svg>
                        </div>
                        <h3 class="mb-2 font-semibold text-navyBlue">Pague com Boleto</h3>
                        <p class="mb-6 text-sm text-zinc-500">Prazo de pagamento: até 3 dias úteis. O boleto será gerado na próxima tela.</p>

                        <input
                            v-model="mpBoletoCpf"
                            type="text"
                            maxlength="14"
                            placeholder="CPF do pagador (somente números)"
                            class="mb-4 w-full rounded-xl border border-zinc-200 px-4 py-3 text-sm outline-none focus:border-darkGreen"
                        >

                        <p v-if="mpBoletoError" class="mb-3 text-sm text-red-500">@{{ mpBoletoError }}</p>
                        <button
                            type="button"
                            class="w-full rounded-xl bg-darkGreen py-3 text-sm font-semibold text-white transition hover:opacity-90 disabled:opacity-60"
                            :disabled="mpSubmitting || mpBoletoCpf.replace(/\D/g, '').length < 11"
                            @click="submitBoleto()"
                        >
                            <span v-if="mpSubmitting">Gerando...</span>
                            <span v-else>Gerar Boleto</span>
                        </button>
                    </div>
                </template>

            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-payment-methods', {
            template: '#v-payment-methods-template',

            props: {
                methods: {
                    type: Object,
                    required: true,
                    default: () => null,
                },

                cartTotal: {
                    type: Number,
                    default: null,
                },
            },

            emits: ['processing', 'processed'],

            data() {
                return {
                    selectedMethod: null,
                    mpCurrentPayment: null,
                    mpPublicKey: @json(core()->getConfigData('sales.payment_methods.mercadopago.public_key')),
                    mpAmount: {{ (float) (optional(\Webkul\Checkout\Facades\Cart::getCart())->grand_total ?? 0) }},

                    mpBrickController: null,
                    mpSubmitting: false,
                    mpCardError: null,
                    mpCardRetry: false,
                    mpPixError: null,
                    mpBoletoError: null,
                    mpBoletoCpf: '',
                };
            },

            methods: {
                onMethodChange(payment) {
                    const code = payment.method ?? payment.payment?.method;
                    const mpCodes = ['mercadopago_card', 'mercadopago_pix', 'mercadopago_boleto'];

                    if (mpCodes.includes(code)) {
                        this.selectedMethod = code;
                        this.mpCurrentPayment = payment;

                        if (code === 'mercadopago_card') {
                            // v-if on the card container: wait for Vue DOM update AND browser layout pass
                            this.$nextTick(() => requestAnimationFrame(() => this.initMPFields()));
                        } else {
                            this.destroyMPFields();
                        }
                    } else {
                        this.selectedMethod = code;
                        this.destroyMPFields();
                        this.store(payment);
                    }
                },

                store(selectedMethod) {
                    this.$emit('processing', 'review');

                    this.$axios.post("{{ route('shop.checkout.onepage.payment_methods.store') }}", {
                            payment: selectedMethod
                        })
                        .then(response => {
                            this.$emit('processed', response.data.cart);

                            if (window.innerWidth <= 768) {
                                window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
                            }
                        })
                        .catch(error => {
                            this.$emit('processing', 'payment');

                            if (error.response.data.redirect_url) {
                                window.location.href = error.response.data.redirect_url;
                            }
                        });
                },

                loadMPSdk() {
                    return new Promise((resolve, reject) => {
                        if (window.MercadoPago) { resolve(); return; }

                        const script = document.createElement('script');
                        script.src = 'https://sdk.mercadopago.com/js/v2';
                        script.onload = resolve;
                        script.onerror = reject;
                        document.head.appendChild(script);
                    });
                },

                async initMPFields() {
                    if (! this.mpPublicKey) {
                        console.warn('[MercadoPago] Public key not configured.');
                        return;
                    }

                    this.destroyMPFields();

                    try {
                        await this.loadMPSdk();
                    } catch (e) {
                        console.error('[MercadoPago] Failed to load SDK:', e);
                        return;
                    }

                    const mp = new window.MercadoPago(this.mpPublicKey, { locale: 'pt-BR' });
                    const bricks = mp.bricks();

                    this.mpBrickController = await bricks.create('cardPayment', 'mp-card-brick', {
                        initialization: {
                            amount: this.cartTotal ?? this.mpAmount,
                        },
                        customization: {
                            visual: {
                                style: { theme: 'default' },
                                hideFormTitle: true,
                            },
                            paymentMethods: {
                                minInstallments: 1,
                                maxInstallments: 12,
                            },
                        },
                        callbacks: {
                            onReady: () => {},
                            onError: (error) => {
                                console.error('[MercadoPago Brick]', error);
                                this.mpCardError = 'Erro ao carregar o formulário de cartão.';
                            },
                            onSubmit: async (formData) => {
                                this.mpCardError = null;
                                this.mpSubmitting = true;

                                try {
                                    await this.$axios.post("{{ route('mercadopago.token') }}", formData);
                                    await this.storePaymentAndPlaceOrder();
                                } catch (e) {
                                    console.error('[MercadoPago] Card submit error:', e);
                                    this.mpCardError = e.response?.data?.message ?? 'Erro ao processar o cartão. Verifique os dados e tente novamente.';
                                    this.mpSubmitting = false;
                                    this.mpCardRetry = true;
                                }
                            },
                        },
                    });
                },

                destroyMPFields() {
                    if (this.mpBrickController) {
                        try { this.mpBrickController.unmount(); } catch (_) {}
                        this.mpBrickController = null;
                    }
                },

                async submitPix() {
                    this.mpPixError = null;
                    this.mpSubmitting = true;

                    try {
                        await this.$axios.post("{{ route('mercadopago.token') }}", {
                            payment_type_id: 'bank_transfer',
                            payment_method_id: 'pix',
                            payer: {},
                        });

                        await this.storePaymentAndPlaceOrder();
                    } catch (e) {
                        console.error('[MercadoPago] PIX submit error:', e);
                        this.mpPixError = 'Erro ao gerar o PIX. Tente novamente.';
                        this.mpSubmitting = false;
                    }
                },

                async submitBoleto() {
                    this.mpBoletoError = null;
                    this.mpSubmitting = true;

                    try {
                        await this.$axios.post("{{ route('mercadopago.token') }}", {
                            payment_type_id: 'ticket',
                            payment_method_id: 'bolbradesco',
                            cpf: this.mpBoletoCpf,
                            payer: {},
                        });

                        await this.storePaymentAndPlaceOrder();
                    } catch (e) {
                        console.error('[MercadoPago] Boleto submit error:', e);
                        this.mpBoletoError = 'Erro ao gerar o boleto. Tente novamente.';
                        this.mpSubmitting = false;
                    }
                },

                async storePaymentAndPlaceOrder() {
                    await this.$axios.post("{{ route('shop.checkout.onepage.payment_methods.store') }}", {
                        payment: this.mpCurrentPayment,
                    });

                    // Place order directly — no "Finalizar Pedido" button needed
                    const orderResp = await this.$axios.post("{{ route('shop.checkout.onepage.orders.store') }}");

                    if (orderResp.data.data.redirect) {
                        window.location.href = orderResp.data.data.redirect_url;
                    } else {
                        window.location.href = "{{ route('shop.checkout.onepage.success') }}";
                    }
                },
            },
        });
    </script>
@endPushOnce
