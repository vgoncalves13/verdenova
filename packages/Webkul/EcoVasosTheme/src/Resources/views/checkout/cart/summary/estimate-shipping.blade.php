<!-- Estimate Tax and Shipping -->
{!! view_render_event('bagisto.shop.checkout.cart.summary.estimate_shipping.before') !!}

<x-shop::accordion
    class="overflow-hidden rounded-xl border max-md:rounded-lg max-md:!border-none max-md:!bg-gray-100"
    :is-active="false"
>
    <x-slot:header class="font-semibold max-md:py-3 max-md:font-medium max-sm:p-2 max-sm:text-sm">
        @lang('shop::app.checkout.cart.summary.estimate-shipping.title')
    </x-slot>

    <x-slot:content class="p-4 pt-0 max-md:rounded-t-none max-md:border max-md:border-t-0 max-md:pt-4">
        <v-estimate-tax-shipping
            :cart="cart"
            @processed="setCart"
        ></v-estimate-tax-shipping>
    </x-slot>
</x-shop::accordion>

{!! view_render_event('bagisto.shop.checkout.cart.summary.estimate_shipping.after') !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-estimate-tax-shipping-template">
        <x-shop::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
        >
            <form @change="handleSubmit($event, estimateShipping)">
                <p class="mb-4 max-sm:text-sm">
                    @lang('shop::app.checkout.cart.summary.estimate-shipping.info')
                </p>

                <!-- Country -->
                <x-shop::form.control-group class="!mb-2.5">
                    <x-shop::form.control-group.label class="{{ core()->isCountryRequired() ? 'required' : '' }}">
                        @lang('shop::app.checkout.cart.summary.estimate-shipping.country')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="select"
                        name="country"
                        v-model="selectedCountry"
                        rules="{{ core()->isCountryRequired() ? 'required' : '' }}"
                        :label="trans('shop::app.checkout.cart.summary.estimate-shipping.country')"
                        :placeholder="trans('shop::app.checkout.cart.summary.estimate-shipping.country')"
                    >
                        <option value="">
                            @lang('shop::app.checkout.cart.summary.estimate-shipping.select-country')
                        </option>

                        <option
                            v-for="country in countries"
                            :value="country.code"
                            v-text="country.name"
                        >
                        </option>
                    </x-shop::form.control-group.control>

                    <x-shop::form.control-group.error name="country" />
                </x-shop::form.control-group>

                {!! view_render_event('bagisto.shop.checkout.onepage.address.form.country.after') !!}

                <!-- State -->
                <x-shop::form.control-group>
                    <x-shop::form.control-group.label class="{{ core()->isStateRequired() ? 'required' : '' }}">
                        @lang('shop::app.checkout.cart.summary.estimate-shipping.state')
                    </x-shop::form.control-group.label>

                    <template v-if="states">
                        <template v-if="haveStates">
                            <x-shop::form.control-group.control
                                type="select"
                                name="state"
                                v-model="selectedState"
                                rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                                :label="trans('shop::app.checkout.cart.summary.estimate-shipping.state')"
                                :placeholder="trans('shop::app.checkout.cart.summary.estimate-shipping.state')"
                            >
                                <option value="">
                                    @lang('shop::app.checkout.cart.summary.estimate-shipping.select-state')
                                </option>

                                <option
                                    v-for="(state, index) in states[selectedCountry]"
                                    :value="state.code"
                                >
                                    @{{ state.default_name }}
                                </option>
                            </x-shop::form.control-group.control>
                        </template>

                        <template v-else>
                            <x-shop::form.control-group.control
                                type="text"
                                name="state"
                                v-model="selectedState"
                                rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                                :label="trans('shop::app.checkout.cart.summary.estimate-shipping.state')"
                                :placeholder="trans('shop::app.checkout.cart.summary.estimate-shipping.state')"
                            />
                        </template>
                    </template>

                    <x-shop::form.control-group.error name="state" />
                </x-shop::form.control-group>

                <!-- Postcode -->
                <x-shop::form.control-group class="!mb-0">
                    <x-shop::form.control-group.label class="{{ core()->isPostCodeRequired() ? 'required' : '' }}">
                        @lang('shop::app.checkout.cart.summary.estimate-shipping.postcode')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="text"
                        name="postcode"
                        v-model="postcode"
                        rules="{{ core()->isPostCodeRequired() ? 'required' : '' }}|postcode"
                        :label="trans('shop::app.checkout.cart.summary.estimate-shipping.postcode')"
                        placeholder="00000-000"
                    />

                    <x-shop::form.control-group.error control-name="postcode" />
                </x-shop::form.control-group>

                <!-- Estimated Shipping Methods -->
                <div
                    class="mt-4 grid rounded-xl border border-zinc-200"
                    v-if="methods.length"
                >
                    <template v-for="method in methods">
                        {!! view_render_event('bagisto.shop.checkout.cart.summary.estimate_shipping.shipping_method.before') !!}

                        <div
                            class="relative select-none border-b border-zinc-200 last:border-b-0 max-md:max-w-full max-md:flex-auto"
                            v-for="rate in method.rates"
                        >
                            <div class="absolute top-5 ltr:left-4 rtl:right-4">
                                {{-- Native radio with .stop to prevent triggering form @change --}}
                                <input
                                    type="radio"
                                    :id="rate.method"
                                    :value="rate.method"
                                    v-model="selectedMethod"
                                    class="cursor-pointer"
                                    @change.stop
                                />
                            </div>

                            <label
                                class="flex cursor-pointer items-center gap-3 p-4 pl-12"
                                :for="rate.method"
                            >
                                <img
                                    v-if="parseDescription(rate.method_description).logo"
                                    :src="parseDescription(rate.method_description).logo"
                                    :alt="rate.method_title"
                                    class="h-8 w-8 shrink-0 rounded object-contain"
                                />

                                <div class="flex-1">
                                    <p class="text-xl font-semibold max-md:text-lg">
                                        @{{ rate.base_formatted_price }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-gray-500">
                                        <span class="font-medium text-gray-700">@{{ rate.method_title }}</span>
                                        <template v-if="parseDescription(rate.method_description).days">
                                            — @{{ parseDescription(rate.method_description).days }}
                                        </template>
                                    </p>
                                </div>
                            </label>
                        </div>

                        {!! view_render_event('bagisto.shop.checkout.cart.summary.estimate_shipping.shipping_method.after') !!}
                    </template>
                </div>

                <!-- Loading indicator -->
                <div v-if="isStoring" class="mt-3 flex items-center gap-2 text-xs text-gray-500">
                    <svg class="h-3 w-3 animate-spin" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="#016630" stroke-width="4"/>
                        <path class="opacity-75" fill="#016630" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    Calculando frete...
                </div>
            </form>
        </x-shop::form>
    </script>

    <script type="module">
        app.component('v-estimate-tax-shipping', {
            template: '#v-estimate-tax-shipping-template',

            props: ['cart'],

            data() {
                return {
                    selectedCountry: 'BR',
                    selectedState: '',
                    postcode: '',
                    countries: [],
                    states: null,
                    methods: [],
                    selectedMethod: null,
                    isStoring: false,
                    debounceTimer: null,
                };
            },

            computed: {
                haveStates() {
                    return !! this.states?.[this.selectedCountry]?.length;
                },
            },

            watch: {
                /**
                 * Auto-fetch when postcode reaches 8 digits (Brazilian CEP).
                 */
                postcode(val) {
                    const digits = val.replace(/\D/g, '');

                    if (digits.length >= 8 && this.selectedCountry && this.selectedState) {
                        clearTimeout(this.debounceTimer);
                        this.debounceTimer = setTimeout(() => this.fetchMethods(), 600);
                    }
                },

                /**
                 * Apply the selected shipping method to the cart whenever it changes.
                 */
                selectedMethod(method) {
                    if (method && this.selectedCountry && this.selectedState && this.postcode) {
                        this.applyMethod(method);
                    }
                },
            },

            mounted() {
                this.getCountries();
                this.getStates();

                // Pre-fill from existing cart shipping address if available
                const addr = this.cart?.shipping_address;

                if (addr?.country)  this.selectedCountry = addr.country;
                if (addr?.state)    this.selectedState   = addr.state;
                if (addr?.postcode) this.postcode        = addr.postcode;
            },

            methods: {
                getCountries() {
                    this.$axios.get("{{ route('shop.api.core.countries') }}")
                        .then(response => {
                            this.countries = response.data.data;
                        })
                        .catch(() => {});
                },

                getStates() {
                    this.$axios.get("{{ route('shop.api.core.states') }}")
                        .then(response => {
                            this.states = response.data.data;
                        })
                        .catch(() => {});
                },

                /**
                 * Called by the VeeValidate form @change handler (country / state / postcode changes).
                 */
                estimateShipping(params, { setErrors }) {
                    if (!params.country || !params.state || !params.postcode) return;

                    // Keep v-model values in sync
                    this.selectedCountry = params.country;
                    this.selectedState   = params.state;
                    this.postcode        = params.postcode;

                    this.isStoring = true;

                    Object.keys(params).forEach(key => params[key] == null && delete params[key]);

                    this.$axios.post('{{ route('shop.api.checkout.cart.estimate_shipping') }}', params)
                        .then(response => {
                            this.isStoring = false;
                            this.methods   = response.data.data.shipping_methods;
                            this.$emit('processed', response.data.data.cart);
                            this.autoSelectCheapest();
                        })
                        .catch(error => {
                            this.isStoring = false;

                            if (error.response?.status === 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                },

                /**
                 * Direct fetch (triggered by postcode watcher debounce).
                 */
                fetchMethods() {
                    if (!this.selectedCountry || !this.selectedState || !this.postcode) return;

                    this.isStoring = true;

                    this.$axios.post('{{ route('shop.api.checkout.cart.estimate_shipping') }}', {
                        country:  this.selectedCountry,
                        state:    this.selectedState,
                        postcode: this.postcode,
                    })
                        .then(response => {
                            this.isStoring = false;
                            this.methods   = response.data.data.shipping_methods;
                            this.$emit('processed', response.data.data.cart);
                            this.autoSelectCheapest();
                        })
                        .catch(() => {
                            this.isStoring = false;
                        });
                },

                /**
                 * Save the chosen shipping method to the cart.
                 */
                applyMethod(method) {
                    this.$axios.post('{{ route('shop.api.checkout.cart.estimate_shipping') }}', {
                        country:         this.selectedCountry,
                        state:           this.selectedState,
                        postcode:        this.postcode,
                        shipping_method: method,
                    })
                        .then(response => {
                            this.$emit('processed', response.data.data.cart);
                        })
                        .catch(() => {});
                },

                /**
                 * Pick the cheapest available rate and select it.
                 * The selectedMethod watcher will call applyMethod automatically.
                 */
                autoSelectCheapest() {
                    const cheapest = this.findCheapestRate();

                    if (cheapest && cheapest.method !== this.selectedMethod) {
                        this.selectedMethod = cheapest.method;
                    }
                },

                parseDescription(description) {
                    if (! description) return {};

                    try {
                        return JSON.parse(description);
                    } catch {
                        return { days: description };
                    }
                },

                findCheapestRate() {
                    let cheapest = null;

                    for (const method of this.methods) {
                        for (const rate of method.rates) {
                            if (!cheapest || rate.base_price < cheapest.base_price) {
                                cheapest = rate;
                            }
                        }
                    }

                    return cheapest;
                },
            },
        });
    </script>
@endPushOnce
