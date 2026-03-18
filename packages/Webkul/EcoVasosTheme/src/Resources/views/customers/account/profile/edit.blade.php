<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.profile.edit.edit-profile')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="profile.edit" />
        @endSection
    @endif

    @push('styles')
    <style>
        .eco-edit-wrap {
            flex: 1;
            min-width: 0;
            font-family: 'Poppins', sans-serif;
        }

        /* Header */
        .eco-edit-header {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 1.75rem;
        }
        .eco-edit-back {
            display: none;
            color: #374151;
            font-size: 1.4rem;
            text-decoration: none;
        }
        @media (max-width: 768px) { .eco-edit-back { display: flex; } }
        .eco-edit-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.6rem;
            color: #012b17;
            margin: 0;
        }

        /* Section cards */
        .eco-edit-section {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            padding: 1.5rem;
            margin-bottom: 1.25rem;
        }
        .eco-edit-section-title {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #9ca3af;
            margin: 0 0 1.25rem;
        }
        .eco-edit-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        @media (max-width: 640px) { .eco-edit-grid-2 { grid-template-columns: 1fr; } }

        /* Labels */
        .eco-edit-wrap .control-label {
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: #374151;
            margin-bottom: .4rem;
            display: block;
        }
        .eco-edit-wrap label.required::after,
        .eco-edit-wrap .label.required::after {
            content: ' *';
            color: #008138;
        }

        /* Newsletter checkbox */
        .eco-newsletter-row {
            display: flex;
            align-items: center;
            gap: .55rem;
            padding: 1rem 1.5rem;
            background: #f9fafb;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        .eco-newsletter-row input[type="checkbox"] {
            accent-color: #008138;
            width: 15px; height: 15px;
            cursor: pointer;
            flex-shrink: 0;
        }
        .eco-newsletter-row label {
            font-size: .83rem;
            color: #6b7280;
            cursor: pointer;
            user-select: none;
        }

        /* Save button */
        .eco-save-btn {
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
        .eco-save-btn:hover {
            background: #016630;
            box-shadow: 0 6px 20px rgba(0,129,56,.3);
            transform: translateY(-1px);
        }
        .eco-save-btn:active { transform: translateY(0); }
        @media (max-width: 640px) { .eco-save-btn { width: 100%; justify-content: center; } }

        /* Bottom spacing */
        .eco-edit-wrap { padding-bottom: 3rem; }
    </style>
    @endpush

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="eco-edit-wrap mx-4 max-md:mx-6 max-sm:mx-4">

        <!-- Header -->
        <div class="eco-edit-header">
            <a class="eco-edit-back" href="{{ route('shop.customers.account.profile.index') }}">
                <span class="icon-arrow-left rtl:icon-arrow-right"></span>
            </a>
            <h2 class="eco-edit-title">
                @lang('shop::app.customers.account.profile.edit.edit-profile')
            </h2>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit.before', ['customer' => $customer]) !!}

        <x-shop::form
            :action="route('shop.customers.account.profile.update')"
            enctype="multipart/form-data"
        >
            {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.before', ['customer' => $customer]) !!}

            <!-- Photo -->
            <div class="eco-edit-section">
                <p class="eco-edit-section-title">Foto de perfil</p>
                <x-shop::form.control-group class="!mb-0">
                    <x-shop::form.control-group.control
                        type="image"
                        class="max-md:[&>*]:[&>*]:rounded-full mb-0 rounded-xl !p-0 text-gray-700 max-md:grid max-md:justify-center"
                        name="image[]"
                        :label="trans('Image')"
                        :is-multiple="false"
                        accepted-types="image/*"
                        :src="$customer->image_url"
                    />
                    <x-shop::form.control-group.error control-name="image[]" />
                </x-shop::form.control-group>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.image.after') !!}

            <!-- Personal info -->
            <div class="eco-edit-section">
                <p class="eco-edit-section-title">Informações pessoais</p>

                <div class="eco-edit-grid-2">
                    <x-shop::form.control-group class="!mb-0">
                        <x-shop::form.control-group.label class="required control-label">
                            @lang('shop::app.customers.account.profile.edit.first-name')
                        </x-shop::form.control-group.label>
                        <x-shop::form.control-group.control
                            type="text"
                            name="first_name"
                            rules="required"
                            :value="old('first_name') ?? $customer->first_name"
                            :label="trans('shop::app.customers.account.profile.edit.first-name')"
                            :placeholder="trans('shop::app.customers.account.profile.edit.first-name')"
                        />
                        <x-shop::form.control-group.error control-name="first_name" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.first_name.after') !!}

                    <x-shop::form.control-group class="!mb-0">
                        <x-shop::form.control-group.label class="required control-label">
                            @lang('shop::app.customers.account.profile.edit.last-name')
                        </x-shop::form.control-group.label>
                        <x-shop::form.control-group.control
                            type="text"
                            name="last_name"
                            rules="required"
                            :value="old('last_name') ?? $customer->last_name"
                            :label="trans('shop::app.customers.account.profile.edit.last-name')"
                            :placeholder="trans('shop::app.customers.account.profile.edit.last-name')"
                        />
                        <x-shop::form.control-group.error control-name="last_name" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.last_name.after') !!}
                </div>

                <div class="eco-edit-grid-2 mt-4">
                    <x-shop::form.control-group class="!mb-0">
                        <x-shop::form.control-group.label class="required control-label">
                            @lang('shop::app.customers.account.profile.edit.email')
                        </x-shop::form.control-group.label>
                        <x-shop::form.control-group.control
                            type="email"
                            name="email"
                            rules="required|email"
                            :value="old('email') ?? $customer->email"
                            :label="trans('shop::app.customers.account.profile.edit.email')"
                            :placeholder="trans('shop::app.customers.account.profile.edit.email')"
                        />
                        <x-shop::form.control-group.error control-name="email" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.email.after') !!}

                    <x-shop::form.control-group class="!mb-0">
                        <x-shop::form.control-group.label class="required control-label">
                            @lang('shop::app.customers.account.profile.edit.phone')
                        </x-shop::form.control-group.label>
                        <x-shop::form.control-group.control
                            type="text"
                            name="phone"
                            rules="required|phone"
                            :value="old('phone') ?? $customer->phone"
                            :label="trans('shop::app.customers.account.profile.edit.phone')"
                            :placeholder="trans('shop::app.customers.account.profile.edit.phone')"
                        />
                        <x-shop::form.control-group.error control-name="phone" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.phone.after') !!}
                </div>

                <div class="eco-edit-grid-2 mt-4">
                    <x-shop::form.control-group class="!mb-0">
                        <x-shop::form.control-group.label class="required control-label">
                            @lang('shop::app.customers.account.profile.edit.gender')
                        </x-shop::form.control-group.label>
                        <x-shop::form.control-group.control
                            type="select"
                            name="gender"
                            rules="required"
                            :value="old('gender') ?? $customer->gender"
                            :aria-label="trans('shop::app.customers.account.profile.edit.select-gender')"
                            :label="trans('shop::app.customers.account.profile.edit.gender')"
                        >
                            <option value="Other">@lang('shop::app.customers.account.profile.edit.other')</option>
                            <option value="Male">@lang('shop::app.customers.account.profile.edit.male')</option>
                            <option value="Female">@lang('shop::app.customers.account.profile.edit.female')</option>
                        </x-shop::form.control-group.control>
                        <x-shop::form.control-group.error control-name="gender" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.gender.after') !!}

                    <x-shop::form.control-group class="!mb-0">
                        <x-shop::form.control-group.label class="control-label">
                            @lang('shop::app.customers.account.profile.edit.dob')
                        </x-shop::form.control-group.label>
                        <x-shop::form.control-group.control
                            type="date"
                            name="date_of_birth"
                            :value="old('date_of_birth') ?? $customer->date_of_birth"
                            :label="trans('shop::app.customers.account.profile.edit.dob')"
                            :placeholder="trans('shop::app.customers.account.profile.edit.dob')"
                        />
                        <x-shop::form.control-group.error control-name="date_of_birth" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.date_of_birth.after') !!}
                </div>
            </div>

            <!-- Change password -->
            <div class="eco-edit-section">
                <p class="eco-edit-section-title">Alterar senha</p>

                <x-shop::form.control-group class="mb-4">
                    <x-shop::form.control-group.label class="control-label">
                        @lang('shop::app.customers.account.profile.edit.current-password')
                    </x-shop::form.control-group.label>
                    <x-shop::form.control-group.control
                        type="password"
                        name="current_password"
                        value=""
                        :label="trans('shop::app.customers.account.profile.edit.current-password')"
                        :placeholder="trans('shop::app.customers.account.profile.edit.current-password')"
                    />
                    <x-shop::form.control-group.error control-name="current_password" />
                </x-shop::form.control-group>

                {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.old_password.after') !!}

                <div class="eco-edit-grid-2">
                    <x-shop::form.control-group class="!mb-0">
                        <x-shop::form.control-group.label class="control-label">
                            @lang('shop::app.customers.account.profile.edit.new-password')
                        </x-shop::form.control-group.label>
                        <x-shop::form.control-group.control
                            type="password"
                            name="new_password"
                            value=""
                            :label="trans('shop::app.customers.account.profile.edit.new-password')"
                            :placeholder="trans('shop::app.customers.account.profile.edit.new-password')"
                        />
                        <x-shop::form.control-group.error control-name="new_password" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.new_password.after') !!}

                    <x-shop::form.control-group class="!mb-0">
                        <x-shop::form.control-group.label class="control-label">
                            @lang('shop::app.customers.account.profile.edit.confirm-password')
                        </x-shop::form.control-group.label>
                        <x-shop::form.control-group.control
                            type="password"
                            name="new_password_confirmation"
                            rules="confirmed:@new_password"
                            value=""
                            :label="trans('shop::app.customers.account.profile.edit.confirm-password')"
                            :placeholder="trans('shop::app.customers.account.profile.edit.confirm-password')"
                        />
                        <x-shop::form.control-group.error control-name="new_password_confirmation" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.new_password_confirmation.after') !!}
                </div>
            </div>

            <!-- Newsletter -->
            <div class="eco-newsletter-row">
                <input
                    type="checkbox"
                    name="subscribed_to_news_letter"
                    id="is-subscribed"
                    @checked($customer->subscribed_to_news_letter)
                />
                <label for="is-subscribed">
                    @lang('shop::app.customers.account.profile.edit.subscribe-to-newsletter')
                </label>
            </div>

            <!-- Save -->
            <button type="submit" class="eco-save-btn">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                </svg>
                @lang('shop::app.customers.account.profile.edit.save')
            </button>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.after', ['customer' => $customer]) !!}

        </x-shop::form>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit.after', ['customer' => $customer]) !!}

    </div>
</x-shop::layouts.account>
