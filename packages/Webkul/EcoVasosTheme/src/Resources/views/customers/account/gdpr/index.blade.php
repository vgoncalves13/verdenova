<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.gdpr.index.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="addresses" />
        @endSection
    @endif

    @push('styles')
    <style>
        .eco-gdpr-wrap {
            flex: 1;
            min-width: 0;
            font-family: 'Poppins', sans-serif;
            padding-bottom: 3rem;
        }

        .eco-gdpr-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.75rem;
            flex-wrap: wrap;
        }
        .eco-gdpr-header-left {
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .eco-gdpr-back {
            display: none;
            color: #374151;
            font-size: 1.4rem;
            text-decoration: none;
        }
        @media (max-width: 768px) { .eco-gdpr-back { display: flex; } }

        .eco-gdpr-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.6rem;
            color: #012b17;
            margin: 0;
        }

        .eco-gdpr-actions {
            display: flex;
            align-items: center;
            gap: .6rem;
            flex-wrap: wrap;
        }

        /* Secondary action buttons (PDF / HTML) */
        .eco-gdpr-btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .5rem 1rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: .8rem;
            font-weight: 500;
            color: #6b7280;
            text-decoration: none;
            background: transparent;
            transition: border-color .15s, color .15s;
            font-family: 'Poppins', sans-serif;
            white-space: nowrap;
        }
        .eco-gdpr-btn-secondary:hover {
            border-color: #9ca3af;
            color: #374151;
        }

        /* Primary create button */
        .eco-gdpr-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .5rem 1.1rem;
            border: 1.5px solid #008138;
            border-radius: 8px;
            font-size: .82rem;
            font-weight: 600;
            color: #008138;
            background: transparent;
            cursor: pointer;
            transition: background .15s, color .15s;
            font-family: 'Poppins', sans-serif;
            white-space: nowrap;
        }
        .eco-gdpr-btn-primary:hover {
            background: #008138;
            color: #fff;
        }

        /* Mobile cards */
        .eco-gdpr-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 1.1rem 1.25rem;
            margin-bottom: .75rem;
            transition: box-shadow .15s, border-color .15s;
        }
        .eco-gdpr-card:last-child { margin-bottom: 0; }

        .eco-gdpr-card-row {
            display: flex;
            gap: .5rem;
            align-items: baseline;
            margin-bottom: .3rem;
            font-size: .82rem;
        }
        .eco-gdpr-card-row:last-child { margin-bottom: 0; }

        .eco-gdpr-card-label {
            color: #9ca3af;
            white-space: nowrap;
            min-width: 70px;
        }

        .eco-gdpr-card-value {
            color: #374151;
            font-weight: 500;
        }

        .eco-gdpr-card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }

        .eco-gdpr-card-fields {
            flex: 1;
            min-width: 0;
        }
    </style>
    @endpush

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="eco-gdpr-wrap mx-4">

        <!-- Header -->
        <div class="eco-gdpr-header">
            <div class="eco-gdpr-header-left">
                <a class="eco-gdpr-back" href="{{ route('shop.customers.account.index') }}">
                    <span class="icon-arrow-left rtl:icon-arrow-right"></span>
                </a>
                <h2 class="eco-gdpr-title">
                    @lang('shop::app.customers.account.gdpr.index.title')
                </h2>
            </div>

            <div class="eco-gdpr-actions">
                <a
                    href="{{ route('shop.customers.account.gdpr.pdf-view') }}"
                    class="eco-gdpr-btn-secondary"
                >
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                    @lang('shop::app.customers.account.gdpr.index.pdf')
                </a>

                <a
                    href="{{ route('shop.customers.account.gdpr.html-view') }}"
                    target="_blank"
                    class="eco-gdpr-btn-secondary"
                >
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <line x1="10" y1="14" x2="21" y2="3"></line>
                    </svg>
                    @lang('shop::app.customers.account.gdpr.index.html')
                </a>

                <button
                    class="eco-gdpr-btn-primary"
                    @click="$refs.loginModel.open()"
                >
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    @lang('shop::app.customers.account.gdpr.index.create-btn')
                </button>
            </div>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.gdpr.list.before') !!}

        <!-- Desktop -->
        <div class="max-md:hidden">
            <x-shop::datagrid :src="route('shop.customers.account.gdpr.index')" />
        </div>

        <!-- Mobile -->
        <div class="md:hidden">
            <x-shop::datagrid :src="route('shop.customers.account.gdpr.index')">
                <template #header="{ isLoading, available, applied, selectAll, sort, performAction }">
                    <div class="hidden"></div>
                </template>

                <template #body="{ isLoading, available, applied, selectAll, sort, performAction }">
                    <template v-if="isLoading">
                        <x-shop::shimmer.datagrid.table.body />
                    </template>

                    <template v-else>
                        <template v-for="record in available.records">
                            <div class="eco-gdpr-card">
                                <div class="eco-gdpr-card-top">
                                    <div class="eco-gdpr-card-fields">
                                        <div class="eco-gdpr-card-row">
                                            <span class="eco-gdpr-card-label">@lang('shop::app.customers.account.gdpr.index.datagrid.id'):</span>
                                            <span class="eco-gdpr-card-value">@{{ record.id }}</span>
                                        </div>
                                        <div class="eco-gdpr-card-row">
                                            <span class="eco-gdpr-card-label">@lang('shop::app.customers.account.gdpr.index.datagrid.type'):</span>
                                            <span class="eco-gdpr-card-value">@{{ record.type }}</span>
                                        </div>
                                        <div class="eco-gdpr-card-row">
                                            <span class="eco-gdpr-card-label">@lang('shop::app.customers.account.gdpr.index.datagrid.date'):</span>
                                            <span class="eco-gdpr-card-value">@{{ record.created_at }}</span>
                                        </div>
                                        <div class="eco-gdpr-card-row">
                                            <span class="eco-gdpr-card-label">@lang('shop::app.customers.account.gdpr.index.datagrid.message'):</span>
                                            <span class="eco-gdpr-card-value">@{{ record.message }}</span>
                                        </div>
                                        <div class="eco-gdpr-card-row">
                                            <span class="eco-gdpr-card-label">@lang('shop::app.customers.account.gdpr.index.datagrid.status'):</span>
                                            <span v-html="record.status"></span>
                                        </div>
                                    </div>
                                    <div v-html="record.revoke"></div>
                                </div>
                            </div>
                        </template>
                    </template>
                </template>
            </x-shop::datagrid>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.gdpr.list.after') !!}
    </div>

    <!-- GDPR Request Modal -->
    <x-shop::form action="{{ route('shop.customers.account.gdpr.store') }}">
        {!! view_render_event('bagisto.shop.customers.account.gdpr.request.form_controls.before') !!}

        <x-shop::modal ref="loginModel">
            <x-slot:header>
                <h2 class="text-2xl">
                    @lang('shop::app.customers.account.gdpr.index.modal.title')
                </h2>
            </x-slot>

            <x-slot:content>
                <!-- Type -->
                <x-shop::form.control-group>
                    <x-shop::form.control-group.label class="required">
                        @lang('shop::app.customers.account.gdpr.index.modal.type.title')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="select"
                        name="type"
                        rules="required"
                    >
                        <option value="" disabled selected>
                            @lang('shop::app.customers.account.gdpr.index.modal.type.choose')
                        </option>
                        <option value="update">
                            @lang('shop::app.customers.account.gdpr.index.modal.type.update')
                        </option>
                        <option value="delete">
                            @lang('shop::app.customers.account.gdpr.index.modal.type.delete')
                        </option>
                    </x-shop::form.control-group.control>

                    <x-shop::form.control-group.error control-name="type" />
                </x-shop::form.control-group>

                <!-- Message -->
                <x-shop::form.control-group class="!mb-0">
                    <x-shop::form.control-group.label class="required">
                        @lang('shop::app.customers.account.gdpr.index.modal.message')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="textarea"
                        name="message"
                        rules="required"
                    />

                    <x-shop::form.control-group.error control-name="message" />
                </x-shop::form.control-group>
            </x-slot>

            <x-slot:footer>
                <div class="flex flex-wrap items-center gap-4">
                    <x-shop::button
                        class="primary-button max-w-none flex-auto rounded-2xl px-11 py-3 max-md:rounded-lg max-md:py-1.5"
                        :title="trans('shop::app.customers.account.gdpr.index.modal.save')"
                        ::loading="isStoring"
                        ::disabled="isStoring"
                    />
                </div>
            </x-slot>
        </x-shop::modal>

        {!! view_render_event('bagisto.shop.customers.account.gdpr.request.form_controls.after') !!}
    </x-shop::form>
</x-shop::layouts.account>
