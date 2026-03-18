<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.addresses.create.add-address')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="addresses.create" />
        @endSection
    @endif

    @push('styles')
    <style>
        .eco-addr-create-wrap {
            flex: 1;
            min-width: 0;
            font-family: 'Poppins', sans-serif;
            padding-bottom: 3rem;
        }
        .eco-addr-create-header {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 1.75rem;
        }
        .eco-addr-create-back {
            display: none;
            color: #374151;
            font-size: 1.4rem;
            text-decoration: none;
        }
        @media (max-width: 768px) { .eco-addr-create-back { display: flex; } }
        .eco-addr-create-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.6rem;
            color: #012b17;
            margin: 0;
        }

        /* Form card */
        .eco-addr-form-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            padding: 1.75rem;
        }

        /* Grid layouts */
        .eco-addr-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        @media (max-width: 640px) { .eco-addr-grid-2 { grid-template-columns: 1fr; } }

        /* Labels */
        .eco-addr-create-wrap .control-label {
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: #374151;
            margin-bottom: .4rem;
            display: block;
        }
        .eco-addr-create-wrap label.required::after,
        .eco-addr-create-wrap .label.required::after {
            content: ' *';
            color: #008138;
        }

        /* Default checkbox */
        .eco-addr-default-row {
            display: flex;
            align-items: center;
            gap: .55rem;
            padding: .9rem 1rem;
            background: #f9fafb;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        .eco-addr-default-row input[type="checkbox"] {
            accent-color: #008138;
            width: 15px; height: 15px;
            cursor: pointer;
        }
        .eco-addr-default-row label {
            font-size: .83rem;
            color: #6b7280;
            cursor: pointer;
            user-select: none;
        }

        /* Save button */
        .eco-addr-save-btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .75rem 2rem;
            background: #008138;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-size: .88rem;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background .2s, transform .15s, box-shadow .2s;
        }
        .eco-addr-save-btn:hover {
            background: #016630;
            box-shadow: 0 6px 20px rgba(0,129,56,.3);
            transform: translateY(-1px);
        }
        .eco-addr-save-btn:active { transform: translateY(0); }
        @media (max-width: 640px) { .eco-addr-save-btn { width: 100%; justify-content: center; } }
    </style>
    @endpush

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="eco-addr-create-wrap mx-4 max-md:mx-6 max-sm:mx-4">

        <!-- Header -->
        <div class="eco-addr-create-header">
            <a class="eco-addr-create-back" href="{{ route('shop.customers.account.addresses.index') }}">
                <span class="icon-arrow-left rtl:icon-arrow-right"></span>
            </a>
            <h2 class="eco-addr-create-title">
                @lang('shop::app.customers.account.addresses.create.add-address')
            </h2>
        </div>

        <v-create-customer-address>
            <x-shop::shimmer.form.control-group :count="10" />
        </v-create-customer-address>

    </div>

    @push('scripts')
        <script type="text/x-template" id="v-create-customer-address-template">
            <div class="eco-addr-form-card">
                <x-shop::form :action="route('shop.customers.account.addresses.store')">
                    {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.before') !!}

                    <!-- Company Name -->
                    <x-shop::form.control-group class="mb-4">
                        <x-shop::form.control-group.label class="control-label">
                            @lang('shop::app.customers.account.addresses.create.company-name')
                        </x-shop::form.control-group.label>
                        <x-shop::form.control-group.control
                            type="text"
                            name="company_name"
                            :value="old('company_name')"
                            :label="trans('shop::app.customers.account.addresses.create.company-name')"
                            :placeholder="trans('shop::app.customers.account.addresses.create.company-name')"
                        />
                        <x-shop::form.control-group.error control-name="company_name" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.company_name.after') !!}

                    <!-- First + Last Name -->
                    <div class="eco-addr-grid-2">
                        <x-shop::form.control-group class="!mb-0">
                            <x-shop::form.control-group.label class="required control-label">
                                @lang('shop::app.customers.account.addresses.create.first-name')
                            </x-shop::form.control-group.label>
                            <x-shop::form.control-group.control
                                type="text"
                                name="first_name"
                                rules="required"
                                :value="old('first_name')"
                                :label="trans('shop::app.customers.account.addresses.create.first-name')"
                                :placeholder="trans('shop::app.customers.account.addresses.create.first-name')"
                            />
                            <x-shop::form.control-group.error control-name="first_name" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.first_name.after') !!}

                        <x-shop::form.control-group class="!mb-0">
                            <x-shop::form.control-group.label class="required control-label">
                                @lang('shop::app.customers.account.addresses.create.last-name')
                            </x-shop::form.control-group.label>
                            <x-shop::form.control-group.control
                                type="text"
                                name="last_name"
                                rules="required"
                                :value="old('last_name')"
                                :label="trans('shop::app.customers.account.addresses.create.last-name')"
                                :placeholder="trans('shop::app.customers.account.addresses.create.last-name')"
                            />
                            <x-shop::form.control-group.error control-name="last_name" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.last_name.after') !!}
                    </div>

                    <!-- Email + VAT -->
                    <div class="eco-addr-grid-2 mt-4">
                        <x-shop::form.control-group class="!mb-0">
                            <x-shop::form.control-group.label class="required control-label">
                                @lang('shop::app.customers.account.addresses.create.email')
                            </x-shop::form.control-group.label>
                            <x-shop::form.control-group.control
                                type="email"
                                name="email"
                                rules="required|email"
                                :value="old('email')"
                                :label="trans('shop::app.customers.account.addresses.create.email')"
                                :placeholder="trans('shop::app.customers.account.addresses.create.email')"
                            />
                            <x-shop::form.control-group.error control-name="email" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.email.after') !!}

                        <x-shop::form.control-group class="!mb-0">
                            <x-shop::form.control-group.label class="control-label">
                                @lang('shop::app.customers.account.addresses.create.vat-id')
                            </x-shop::form.control-group.label>
                            <x-shop::form.control-group.control
                                type="text"
                                name="vat_id"
                                :value="old('vat_id')"
                                :label="trans('shop::app.customers.account.addresses.create.vat-id')"
                                :placeholder="trans('shop::app.customers.account.addresses.create.vat-id')"
                            />
                            <x-shop::form.control-group.error control-name="vat_id" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.vat_id.after') !!}
                    </div>

                    <!-- Street Address -->
                    <x-shop::form.control-group class="mt-4">
                        <x-shop::form.control-group.label class="required control-label">
                            @lang('shop::app.customers.account.addresses.create.street-address')
                        </x-shop::form.control-group.label>
                        <x-shop::form.control-group.control
                            type="text"
                            name="address[]"
                            rules="required|address"
                            :value="collect(old('address'))->first()"
                            :label="trans('shop::app.customers.account.addresses.create.street-address')"
                            :placeholder="trans('shop::app.customers.account.addresses.create.street-address')"
                        />
                        <x-shop::form.control-group.error control-name="address[]" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.street_address.after') !!}

                    @if (
                        core()->getConfigData('customer.address.information.street_lines')
                        && core()->getConfigData('customer.address.information.street_lines') > 1
                    )
                        @for ($i = 1; $i < core()->getConfigData('customer.address.information.street_lines'); $i++)
                            <x-shop::form.control-group.control
                                type="text"
                                name="address[{{ $i }}]"
                                :value="old('address[{{ $i }}]')"
                                rules="address"
                                :label="trans('shop::app.customers.account.addresses.create.street-address')"
                                :placeholder="trans('shop::app.customers.account.addresses.create.street-address')"
                            />
                            <x-shop::form.control-group.error class="mb-2" name="address[{{ $i }}]" />
                        @endfor
                    @endif

                    {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.street_address.after') !!}

                    <!-- Country + State -->
                    <div class="eco-addr-grid-2 mt-4">
                        <x-shop::form.control-group class="!mb-0">
                            <x-shop::form.control-group.label class="{{ core()->isCountryRequired() ? 'required' : '' }} control-label">
                                @lang('shop::app.customers.account.addresses.create.country')
                            </x-shop::form.control-group.label>
                            <x-shop::form.control-group.control
                                type="select"
                                name="country"
                                rules="{{ core()->isCountryRequired() ? 'required' : '' }}"
                                v-model="country"
                                :aria-label="trans('shop::app.customers.account.addresses.create.country')"
                                :label="trans('shop::app.customers.account.addresses.create.country')"
                            >
                                <option value="">@lang('shop::app.customers.account.addresses.create.select-country')</option>
                                @foreach (core()->countries() as $country)
                                    <option value="{{ $country->code }}">{{ $country->name }}</option>
                                @endforeach
                            </x-shop::form.control-group.control>
                            <x-shop::form.control-group.error control-name="country" />
                        </x-shop::form.control-group>

                        <x-shop::form.control-group class="!mb-0">
                            <x-shop::form.control-group.label class="{{ core()->isStateRequired() ? 'required' : '' }} control-label">
                                @lang('shop::app.customers.account.addresses.create.state')
                            </x-shop::form.control-group.label>
                            <template v-if="haveStates()">
                                <x-shop::form.control-group.control
                                    type="select"
                                    id="state"
                                    name="state"
                                    rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                                    v-model="state"
                                    :label="trans('shop::app.customers.account.addresses.create.state')"
                                    :placeholder="trans('shop::app.customers.account.addresses.create.state')"
                                >
                                    <option v-for="(state, index) in countryStates[country]" :value="state.code">
                                        @{{ state.default_name }}
                                    </option>
                                </x-shop::form.control-group.control>
                            </template>
                            <template v-else>
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="state"
                                    :value="old('state')"
                                    rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                                    :label="trans('shop::app.customers.account.addresses.create.state')"
                                    :placeholder="trans('shop::app.customers.account.addresses.create.state')"
                                />
                            </template>
                            <x-shop::form.control-group.error control-name="state" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.state.after') !!}
                    </div>

                    <!-- City + Postcode + Phone -->
                    <div class="eco-addr-grid-2 mt-4">
                        <x-shop::form.control-group class="!mb-0">
                            <x-shop::form.control-group.label class="required control-label">
                                @lang('shop::app.customers.account.addresses.create.city')
                            </x-shop::form.control-group.label>
                            <x-shop::form.control-group.control
                                type="text"
                                name="city"
                                rules="required"
                                :value="old('city')"
                                :label="trans('shop::app.customers.account.addresses.create.city')"
                                :placeholder="trans('shop::app.customers.account.addresses.create.city')"
                            />
                            <x-shop::form.control-group.error control-name="city" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.city.after') !!}

                        <x-shop::form.control-group class="!mb-0">
                            <x-shop::form.control-group.label class="{{ core()->isPostCodeRequired() ? 'required' : '' }} control-label">
                                @lang('shop::app.customers.account.addresses.create.post-code')
                            </x-shop::form.control-group.label>
                            <x-shop::form.control-group.control
                                type="text"
                                name="postcode"
                                rules="{{ core()->isPostCodeRequired() ? 'required' : '' }}|postcode"
                                :value="old('postcode')"
                                :label="trans('shop::app.customers.account.addresses.create.post-code')"
                                :placeholder="trans('shop::app.customers.account.addresses.create.post-code')"
                            />
                            <x-shop::form.control-group.error control-name="postcode" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.postcode.after') !!}
                    </div>

                    <x-shop::form.control-group class="mt-4">
                        <x-shop::form.control-group.label class="required control-label">
                            @lang('shop::app.customers.account.addresses.create.phone')
                        </x-shop::form.control-group.label>
                        <x-shop::form.control-group.control
                            type="text"
                            name="phone"
                            rules="required|phone"
                            :value="old('phone')"
                            :label="trans('shop::app.customers.account.addresses.create.phone')"
                            :placeholder="trans('shop::app.customers.account.addresses.create.phone')"
                        />
                        <x-shop::form.control-group.error control-name="phone" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.phone.after') !!}

                    <!-- Default address checkbox -->
                    <div class="eco-addr-default-row">
                        <input
                            type="checkbox"
                            name="default_address"
                            value="1"
                            id="default_address"
                        >
                        <label for="default_address">
                            @lang('shop::app.customers.account.addresses.create.set-as-default')
                        </label>
                    </div>

                    <button type="submit" class="eco-addr-save-btn">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                        </svg>
                        @lang('shop::app.customers.account.addresses.create.save')
                    </button>

                    {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.after') !!}
                </x-shop::form>
                {!! view_render_event('bagisto.shop.customers.account.address.create.after') !!}
            </div>
        </script>

        <script type="module">
            app.component('v-create-customer-address', {
                template: '#v-create-customer-address-template',

                data() {
                    return {
                        country: "{{ old('country') }}",
                        state: "{{ old('state') }}",
                        countryStates: @json(core()->groupedStatesByCountries()),
                    }
                },

                methods: {
                    haveStates() {
                        return !!this.countryStates[this.country]?.length;
                    },
                }
            });
        </script>
    @endpush

</x-shop::layouts.account>
