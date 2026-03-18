<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.downloadable-products.name')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="downloadable-products" />
        @endSection
    @endif

    @push('styles')
    <style>
        .eco-downloads-wrap {
            flex: 1;
            min-width: 0;
            font-family: 'Poppins', sans-serif;
            padding-bottom: 3rem;
        }

        .eco-downloads-header {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 1.75rem;
        }
        .eco-downloads-back {
            display: none;
            color: #374151;
            font-size: 1.4rem;
            text-decoration: none;
        }
        @media (max-width: 768px) { .eco-downloads-back { display: flex; } }

        .eco-downloads-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.6rem;
            color: #012b17;
            margin: 0;
        }

        /* Mobile cards */
        .eco-download-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 1.1rem 1.25rem;
            margin-bottom: .75rem;
            transition: box-shadow .15s, border-color .15s;
        }
        .eco-download-card:last-child { margin-bottom: 0; }
        .eco-download-card:hover {
            border-color: #d1d5db;
            box-shadow: 0 4px 12px rgba(0,0,0,.06);
        }

        .eco-download-card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: .6rem;
        }

        .eco-download-card-order {
            font-size: .85rem;
            font-weight: 600;
            color: #111827;
        }
        .eco-download-card-date {
            font-size: .72rem;
            color: #9ca3af;
            margin-top: .15rem;
        }

        .eco-download-card-product {
            font-size: .85rem;
            font-weight: 600;
            color: #008138;
            margin-bottom: .3rem;
        }

        .eco-download-card-meta {
            font-size: .78rem;
            color: #6b7280;
        }
        .eco-download-card-meta span {
            color: #374151;
            font-weight: 500;
        }
    </style>
    @endpush

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="eco-downloads-wrap mx-4 max-md:mx-6 max-sm:mx-4">

        <!-- Header -->
        <div class="eco-downloads-header">
            <a class="eco-downloads-back" href="{{ route('shop.customers.account.index') }}">
                <span class="icon-arrow-left rtl:icon-arrow-right"></span>
            </a>
            <h2 class="eco-downloads-title">
                @lang('shop::app.customers.account.downloadable-products.name')
            </h2>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.downloadable_products.list.before') !!}

        <!-- Desktop -->
        <div class="max-md:hidden">
            <x-shop::datagrid :src="route('shop.customers.account.downloadable_products.index')" />
        </div>

        <!-- Mobile -->
        <div class="hidden max-md:block">
            <x-shop::datagrid :src="route('shop.customers.account.downloadable_products.index')">
                <template #header="{ isLoading, available, applied, selectAll, sort, performAction }">
                    <div class="hidden"></div>
                </template>

                <template #body="{ isLoading, available, applied, selectAll, sort, performAction }">
                    <template v-if="isLoading">
                        <x-shop::shimmer.datagrid.table.body />
                    </template>

                    <template v-else>
                        <template
                            v-for="record in available.records"
                            v-if="available.records.length"
                        >
                            <div class="eco-download-card">
                                <div class="eco-download-card-top">
                                    <div>
                                        <p class="eco-download-card-order">
                                            @lang('shop::app.customers.account.downloadable-products.orderId'): #@{{ record.increment_id }}
                                        </p>
                                        <p class="eco-download-card-date">@{{ record.created_at }}</p>
                                    </div>
                                    <div v-html="record.status"></div>
                                </div>

                                <p class="eco-download-card-product" v-html="record.product_name"></p>

                                <p class="eco-download-card-meta">
                                    @lang('Remaining Downloads'):
                                    <span>@{{ record.remaining_downloads }}</span>
                                </p>
                            </div>
                        </template>

                        <template v-else>
                            @{{ available.records.length }} @lang('shop::app.customers.account.downloadable-products.records-found')
                        </template>
                    </template>
                </template>
            </x-shop::datagrid>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.downloadable_products.list.after') !!}

    </div>
</x-shop::layouts.account>
