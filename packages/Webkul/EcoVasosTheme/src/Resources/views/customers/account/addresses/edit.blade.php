<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.addresses.edit.edit')
        @lang('shop::app.customers.account.addresses.edit.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="addresses.edit" :entity="$address" />
        @endSection
    @endif

    @push('styles')
    <style>
        .eco-addr-edit-wrap {
            flex: 1;
            min-width: 0;
            font-family: 'Poppins', sans-serif;
            padding-bottom: 3rem;
        }
        .eco-addr-edit-header {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 1.75rem;
        }
        .eco-addr-edit-back {
            display: none;
            color: #374151;
            font-size: 1.4rem;
            text-decoration: none;
        }
        @media (max-width: 768px) { .eco-addr-edit-back { display: flex; } }
        .eco-addr-edit-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.6rem;
            color: #012b17;
            margin: 0;
        }
        .eco-addr-edit-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            padding: 1.75rem;
        }
        .eco-addr-edit-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        @media (max-width: 640px) { .eco-addr-edit-grid-2 { grid-template-columns: 1fr; } }

        .eco-addr-edit-wrap .control-label {
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: #374151;
            margin-bottom: .4rem;
            display: block;
        }
        .eco-addr-edit-wrap label.required::after,
        .eco-addr-edit-wrap .label.required::after {
            content: ' *';
            color: #008138;
        }

        .eco-addr-update-btn {
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
            margin-top: 1.5rem;
        }
        .eco-addr-update-btn:hover {
            background: #016630;
            box-shadow: 0 6px 20px rgba(0,129,56,.3);
            transform: translateY(-1px);
        }
        .eco-addr-update-btn:active { transform: translateY(0); }
        @media (max-width: 640px) { .eco-addr-update-btn { width: 100%; justify-content: center; } }
    </style>
    @endpush

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="eco-addr-edit-wrap mx-4 max-md:mx-6 max-sm:mx-4">

        <!-- Header -->
        <div class="eco-addr-edit-header">
            <a class="eco-addr-edit-back" href="{{ route('shop.customers.account.addresses.index') }}">
                <span class="icon-arrow-left rtl:icon-arrow-right"></span>
            </a>
            <h2 class="eco-addr-edit-title">
                @lang('shop::app.customers.account.addresses.edit.edit')
                @lang('shop::app.customers.account.addresses.edit.title')
            </h2>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.address.edit.before', ['address' => $address]) !!}

        <v-edit-customer-address>
            <x-shop::shimmer.form.control-group :count="10" />
        </v-edit-customer-address>

        {!! view_render_event('bagisto.shop.customers.account.address.edit.after', ['address' => $address]) !!}

    </div>

    @push('scripts')
        <script type="text/x-template" id="v-edit-customer-address-template">
            <div class="eco-addr-edit-card">
                <x-shop::form
                    method="PUT"
                    :action="route('shop.customers.account.addresses.update', $address->id)"
                >
                    {!! view_render_event('bagisto.shop.customers.account.address.edit_form_controls.before', ['address' => $address]) !!}

                    <!-- Company Name -->
                    <x-shop::form.control-group class="mb-4">
                        <x-shop::form.control-group.label class="control-label">
                            @lang('shop::app.customers.account.addresses.edit.company-name')
                        </x-shop::form.control-group.label>
                        <x-shop::form.control-group.control
                            type="text"
                            name="company_name"
                            :value="old('company_name') ?? $address->company_name"
                            :label="trans('shop::app.customers.account.addresses.edit.company-name')"
                            :placeholder="trans('shop::app.customers.account.addresses.edit.company-name')"
                        />
                        <x-shop::form.control-group.error control-name="company_name" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.addresses.edit_form_controls.company_name.after', ['address' => $address]) !!}

                    <!-- First + Last Name -->
                    <div class="eco-addr-edit-grid-2">
                        <x-shop::form.control-group class="!mb-0">
                            <x-shop::form.control-group.label class="required control-label">
                                @lang('shop::app.customers.account.addresses.edit.first-name')
                            </x-shop::form.control-group.label>
                            <x-shop::form.control-group.control
                                type="text"
                                name="first_name"
                                rules="required"
                                :value="old('first_name') ?? $address->first_name"
                                :label="trans('shop::app.customers.account.addresses.edit.first-name')"
                                :placeholder="trans('shop::app.customers.account.addresses.edit.first-name')"
                            />
                            <x-shop::form.control-group.error control-name="first_name" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.customers.account.addresses.edit_form_controls.first_name.after', ['address' => $address]) !!}

                        <x-shop::form.control-group class="!mb-0">
                            <x-shop::form.control-group.label class="required control-label">
                                @lang('shop::app.customers.account.addresses.edit.last-name')
                            </x-shop::form.control-group.label>
                            <x-shop::form.control-group.control
                                type="text"
                                name="last_name"
                                rules="required"
                                :value="old('last_name') ?? $address->last_name"
                                :label="trans('shop::app.customers.account.addresses.edit.last-name')"
                                :placeholder="trans('shop::app.customers.account.addresses.edit.last-name')"
                            />
                            <x-shop::form.control-group.error control-name="last_name" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.customers.account.addresses.edit_form_controls.last_name.after', ['address' => $address]) !!}
                    </div>

                    <!-- Email + VAT -->
                    <div class="eco-addr-edit-grid-2 mt-4">
                        <x-shop::form.control-group class="!mb-0">
                            <x-shop::form.control-group.label class="required control-label">
                                @lang('Email')
                            </x-shop::form.control-group.label>
                            <x-shop::form.control-group.control
                                type="email"
                                name="email"
                                rules="required|email"
                                :value="old('email') ?? $address->email"
                                :label="trans('Email')"
                                :placeholder="trans('Email')"
                            />
                            <x-shop::form.control-group.error control-name="email" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.customers.account.addresses.edit_form_controls.email.after', ['address' => $address]) !!}

                        <x-shop::form.control-group class="!mb-0">
                            <x-shop::form.control-group.label class="control-label">
                                @lang('shop::app.customers.account.addresses.edit.vat-id')
                            </x-shop::form.control-group.label>
                            <x-shop::form.control-group.control
                                type="text"
                                name="vat_id"
                                :value="old('vat_id') ?? $address->vat_id"
                                :label="trans('shop::app.customers.account.addresses.edit.vat-id')"
                                :placeholder="trans('shop::app.customers.account.addresses.edit.vat-id')"
                            />
                            <x-shop::form.control-group.error control-name="vat_id" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.customers.account.addresses.edit_form_controls.vat_id.after', ['address' => $address]) !!}
                    </div>

                    @php $addresses = explode(PHP_EOL, $address->address); @endphp

                    <!-- Street Address -->
                    <x-shop::form.control-group class="mt-4">
                        <x-shop::form.control-group.label class="required control-label">
                            @lang('shop::app.customers.account.addresses.edit.street-address')
                        </x-shop::form.control-group.label>
                        <x-shop::form.control-group.control
                            type="text"
                            name="address[]"
                            :value="collect(old('address'))->first() ?? $addresses[0]"
                            rules="required|address"
                            :label="trans('shop::app.customers.account.addresses.edit.street-address')"
                            :placeholder="trans('shop::app.customers.account.addresses.edit.street-address')"
                        />
                        <x-shop::form.control-group.error control-name="address[]" />
                    </x-shop::form.control-group>

                    @if (
                        core()->getConfigData('customer.address.information.street_lines')
                        && core()->getConfigData('customer.address.information.street_lines') > 1
                    )
                        @for ($i = 1; $i < core()->getConfigData('customer.address.information.street_lines'); $i++)
                            <x-shop::form.control-group.control
                                type="text"
                                name="address[{{ $i }}]"
                                :value="old('address[{{$i}}]', $addresses[$i] ?? '')"
                                rules="address"
                                :label="trans('shop::app.customers.account.addresses.edit.street-address')"
                                :placeholder="trans('shop::app.customers.account.addresses.edit.street-address')"
                            />
                            <x-shop::form.control-group.error class="mb-2" name="address[{{ $i }}]" />
                        @endfor
                    @endif

                    {!! view_render_event('bagisto.shop.customers.account.addresses.edit_form_controls.street-addres.after', ['address' => $address]) !!}

                    <!-- Country + State -->
                    <div class="eco-addr-edit-grid-2 mt-4">
                        <x-shop::form.control-group class="!mb-0">
                            <x-shop::form.control-group.label class="{{ core()->isCountryRequired() ? 'required' : '' }} control-label">
                                @lang('shop::app.customers.account.addresses.edit.country')
                            </x-shop::form.control-group.label>
                            <x-shop::form.control-group.control
                                type="select"
                                name="country"
                                rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                                v-model="addressData.country"
                                :aria-label="trans('shop::app.customers.account.addresses.edit.country')"
                                :label="trans('shop::app.customers.account.addresses.edit.country')"
                            >
                                @foreach (core()->countries() as $country)
                                    <option
                                        {{ $country->code === config('app.default_country') ? 'selected' : '' }}
                                        value="{{ $country->code }}"
                                    >{{ $country->name }}</option>
                                @endforeach
                            </x-shop::form.control-group.control>
                            <x-shop::form.control-group.error control-name="country" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.customers.account.addresses.edit_form_controls.country.after', ['address' => $address]) !!}

                        <x-shop::form.control-group class="!mb-0">
                            <x-shop::form.control-group.label class="{{ core()->isStateRequired() ? 'required' : '' }} control-label">
                                @lang('shop::app.customers.account.addresses.edit.state')
                            </x-shop::form.control-group.label>
                            <template v-if="haveStates()">
                                <x-shop::form.control-group.control
                                    type="select"
                                    name="state"
                                    id="state"
                                    rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                                    v-model="addressData.state"
                                    :label="trans('shop::app.customers.account.addresses.edit.state')"
                                    :placeholder="trans('shop::app.customers.account.addresses.edit.state')"
                                >
                                    <option v-for="(state, index) in countryStates[addressData.country]" :value="state.code">
                                        @{{ state.default_name }}
                                    </option>
                                </x-shop::form.control-group.control>
                            </template>
                            <template v-else>
                                <x-shop::form.control-group.control
                                    type="text"
                                    name="state"
                                    rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                                    :value="old('state') ?? $address->state"
                                    :label="trans('shop::app.customers.account.addresses.edit.state')"
                                    :placeholder="trans('shop::app.customers.account.addresses.edit.state')"
                                />
                            </template>
                            <x-shop::form.control-group.error control-name="state" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.customers.account.addresses.edit_form_controls.state.after', ['address' => $address]) !!}
                    </div>

                    <!-- City + Postcode -->
                    <div class="eco-addr-edit-grid-2 mt-4">
                        <x-shop::form.control-group class="!mb-0">
                            <x-shop::form.control-group.label class="required control-label">
                                @lang('shop::app.customers.account.addresses.edit.city')
                            </x-shop::form.control-group.label>
                            <x-shop::form.control-group.control
                                type="text"
                                name="city"
                                rules="required"
                                :value="old('city') ?? $address->city"
                                :label="trans('shop::app.customers.account.addresses.edit.city')"
                                :placeholder="trans('shop::app.customers.account.addresses.edit.city')"
                            />
                            <x-shop::form.control-group.error control-name="city" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.customers.account.addresses.edit_form_controls.city.after', ['address' => $address]) !!}

                        <x-shop::form.control-group class="!mb-0">
                            <x-shop::form.control-group.label class="{{ core()->isPostCodeRequired() ? 'required' : '' }} control-label">
                                @lang('shop::app.customers.account.addresses.edit.post-code')
                            </x-shop::form.control-group.label>
                            <x-shop::form.control-group.control
                                type="text"
                                name="postcode"
                                rules="{{ core()->isPostCodeRequired() ? 'required' : '' }}|postcode"
                                :value="old('postal-code') ?? $address->postcode"
                                :label="trans('shop::app.customers.account.addresses.edit.post-code')"
                                :placeholder="trans('shop::app.customers.account.addresses.edit.post-code')"
                            />
                            <x-shop::form.control-group.error control-name="postcode" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.customers.account.addresses.edit_form_controls.postcode.after', ['address' => $address]) !!}
                    </div>

                    <!-- Phone -->
                    <x-shop::form.control-group class="mt-4">
                        <x-shop::form.control-group.label class="required control-label">
                            @lang('shop::app.customers.account.addresses.edit.phone')
                        </x-shop::form.control-group.label>
                        <x-shop::form.control-group.control
                            type="text"
                            name="phone"
                            rules="required|phone"
                            :value="old('phone') ?? $address->phone"
                            :label="trans('shop::app.customers.account.addresses.edit.phone')"
                            :placeholder="trans('shop::app.customers.account.addresses.edit.phone')"
                        />
                        <x-shop::form.control-group.error control-name="phone" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.addresses.edit_form_controls.phone.after', ['address' => $address]) !!}

                    <button type="submit" class="eco-addr-update-btn">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                        </svg>
                        @lang('shop::app.customers.account.addresses.edit.update-btn')
                    </button>

                    {!! view_render_event('bagisto.shop.customers.account.address.edit_form_controls.after', ['address' => $address]) !!}

                </x-shop::form>
            </div>
        </script>

        <script type="module">
            app.component('v-edit-customer-address', {
                template: '#v-edit-customer-address-template',

                data() {
                    return {
                        addressData: {
                            country: "{{ old('country') ?? $address->country }}",
                            state: "{{ old('state') ?? $address->state }}",
                        },
                        countryStates: @json(core()->groupedStatesByCountries()),
                    };
                },

                methods: {
                    haveStates() {
                        return !!this.countryStates[this.addressData.country]?.length;
                    },
                },
            });
        </script>
    @endpush

</x-shop::layouts.account>
