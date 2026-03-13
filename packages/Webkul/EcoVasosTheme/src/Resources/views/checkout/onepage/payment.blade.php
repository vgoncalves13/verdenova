{!! view_render_event('bagisto.shop.checkout.onepage.payment_methods.before') !!}

<v-payment-methods
    :methods="paymentMethods"
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

                <!-- Accordion Blade Component -->
                <x-shop::accordion class="overflow-hidden !border-b-0 max-md:rounded-lg max-md:!border-none max-md:!bg-gray-100">
                    <!-- Accordion Blade Component Header -->
                    <x-slot:header class="px-0 py-4 max-md:p-3 max-md:text-sm max-md:font-medium max-sm:p-2">

                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-medium max-md:text-base">
                                @lang('shop::app.checkout.onepage.payment.payment-method')
                            </h2>
                        </div>
                    </x-slot>

                    <!-- Accordion Blade Component Content -->
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

                                <!-- Todo implement the additionalDetails -->
                                {{-- \Webkul\Payment\Payment::getAdditionalDetails($payment['method'] --}}
                            </div>
                        </div>

                        <!-- MercadoPago Payment Brick container (hidden until MP is selected) -->
                        <div
                            id="mp-brick-container"
                            class="mt-6"
                            v-show="selectedMethod === 'mercadopago'"
                        ></div>
                    </x-slot>
                </x-shop::accordion>

                {!! view_render_event('bagisto.shop.checkout.onepage.payment_method.accordion.after') !!}
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
            },

            emits: ['processing', 'processed'],

            data() {
                return {
                    selectedMethod: null,
                    mpBrickController: null,
                    mpPublicKey: @json(core()->getConfigData('sales.payment_methods.mercadopago.public_key')),
                    mpAmount: {{ (float) (optional(\Webkul\Checkout\Facades\Cart::getCart())->grand_total ?? 0) }},
                };
            },

            methods: {
                onMethodChange(payment) {
                    const code = payment.method ?? payment.payment?.method;

                    if (code === 'mercadopago') {
                        this.selectedMethod = 'mercadopago';
                        this.$nextTick(() => this.initMPBrick(payment));
                    } else {
                        this.selectedMethod = code;
                        this.destroyMPBrick();
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

                            // Used in mobile view.
                            if (window.innerWidth <= 768) {
                                window.scrollTo({
                                    top: document.body.scrollHeight,
                                    behavior: 'smooth'
                                });
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
                        if (window.MercadoPago) {
                            resolve();
                            return;
                        }

                        const script = document.createElement('script');
                        script.src = 'https://sdk.mercadopago.com/js/v2';
                        script.onload = resolve;
                        script.onerror = reject;
                        document.head.appendChild(script);
                    });
                },

                async initMPBrick(payment) {
                    if (! this.mpPublicKey) {
                        console.warn('[MercadoPago] Public key not configured.');
                        return;
                    }

                    this.destroyMPBrick();

                    try {
                        await this.loadMPSdk();
                    } catch (e) {
                        console.error('[MercadoPago] Failed to load SDK:', e);
                        return;
                    }

                    const mp = new window.MercadoPago(this.mpPublicKey, { locale: 'pt-BR' });
                    const bricks = mp.bricks();

                    const settings = {
                        initialization: {
                            amount: this.mpAmount,
                        },
                        customization: {
                            paymentMethods: {
                                creditCard: 'all',
                                debitCard: 'all',
                                ticket: 'all',
                                bankTransfer: ['pix'],
                            },
                        },
                        callbacks: {
                            onReady: () => {},
                            onError: (error) => {
                                console.error('[MercadoPago Brick] error:', error);
                            },
                            onSubmit: async ({ selectedPaymentMethod, formData }) => {
                                try {
                                    await this.$axios.post(
                                        "{{ route('mercadopago.token') }}",
                                        formData
                                    );

                                    this.store(payment);
                                } catch (err) {
                                    console.error('[MercadoPago] token save error:', err);
                                }
                            },
                        },
                    };

                    this.mpBrickController = await bricks.create(
                        'payment',
                        'mp-brick-container',
                        settings
                    );
                },

                destroyMPBrick() {
                    if (this.mpBrickController) {
                        try { this.mpBrickController.unmount(); } catch (_) {}
                        this.mpBrickController = null;
                    }
                },
            },
        });
    </script>
@endPushOnce
