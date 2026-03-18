<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.orders.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="orders" />
        @endSection
    @endif

    @push('styles')
    <style>
        .eco-orders-wrap {
            flex: 1;
            min-width: 0;
            font-family: 'Poppins', sans-serif;
            padding-bottom: 3rem;
        }

        .eco-orders-header {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 1.75rem;
        }
        .eco-orders-back {
            display: none;
            color: #374151;
            font-size: 1.4rem;
            text-decoration: none;
        }
        @media (max-width: 768px) { .eco-orders-back { display: flex; } }

        .eco-orders-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.6rem;
            color: #012b17;
            margin: 0;
        }

        /* Mobile order cards */
        .eco-order-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 1.1rem 1.25rem;
            margin-bottom: .75rem;
            text-decoration: none;
            display: block;
            transition: box-shadow .15s, border-color .15s;
        }
        .eco-order-card:last-child { margin-bottom: 0; }
        .eco-order-card:hover {
            border-color: #008138;
            box-shadow: 0 4px 12px rgba(0,129,56,.1);
        }

        .eco-order-card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: .6rem;
        }
        .eco-order-card-id {
            font-size: .88rem;
            font-weight: 600;
            color: #111827;
        }
        .eco-order-card-date {
            font-size: .72rem;
            color: #9ca3af;
            margin-top: .2rem;
        }
        .eco-order-card-total-label {
            font-size: .72rem;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: .2rem;
        }
        .eco-order-card-total {
            font-size: 1.15rem;
            font-weight: 700;
            color: #012b17;
        }
    </style>
    @endpush

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="eco-orders-wrap mx-4 max-md:mx-6 max-sm:mx-4">

        <div class="eco-orders-header">
            <a class="eco-orders-back" href="{{ route('shop.customers.account.index') }}">
                <span class="icon-arrow-left rtl:icon-arrow-right"></span>
            </a>
            <h2 class="eco-orders-title">
                @lang('shop::app.customers.account.orders.title')
            </h2>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.orders.list.before') !!}

        <!-- Desktop -->
        <div class="max-md:hidden">
            <x-shop::datagrid :src="route('shop.customers.account.orders.index')" />
        </div>

        <!-- Mobile -->
        <div class="md:hidden">
            <x-shop::datagrid :src="route('shop.customers.account.orders.index')">
                <template #header="{ isLoading, available, applied, selectAll, sort, performAction }">
                    <div class="hidden"></div>
                </template>

                <template #body="{ isLoading, available, applied, selectAll, sort, performAction }">
                    <template v-if="isLoading">
                        <x-shop::shimmer.datagrid.table.body />
                    </template>

                    <template v-else>
                        <template v-for="record in available.records">
                            <a :href="record.actions[0].url" class="eco-order-card">
                                <div class="eco-order-card-top">
                                    <div>
                                        <p class="eco-order-card-id">
                                            #@{{ record.id }}
                                        </p>
                                        <p class="eco-order-card-date">@{{ record.created_at }}</p>
                                    </div>
                                    <div v-html="record.status"></div>
                                </div>
                                <div>
                                    <p class="eco-order-card-total-label">
                                        @lang('shop::app.customers.account.orders.subtotal')
                                    </p>
                                    <p class="eco-order-card-total">@{{ record.grand_total }}</p>
                                </div>
                            </a>
                        </template>
                    </template>
                </template>
            </x-shop::datagrid>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.orders.list.after') !!}

    </div>
</x-shop::layouts.account>
