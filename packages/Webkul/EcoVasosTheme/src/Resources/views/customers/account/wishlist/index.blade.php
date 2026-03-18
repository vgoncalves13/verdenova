<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.wishlist.page-title')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="wishlist" />
        @endSection
    @endif

    @push('styles')
    <style>
        .eco-wishlist-wrap {
            flex: 1;
            min-width: 0;
            font-family: 'Poppins', sans-serif;
            padding-bottom: 3rem;
        }

        /* Header */
        .eco-wishlist-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.75rem;
        }
        .eco-wishlist-header-left {
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .eco-wishlist-back {
            display: none;
            color: #374151;
            font-size: 1.4rem;
            text-decoration: none;
        }
        @media (max-width: 768px) { .eco-wishlist-back { display: flex; } }

        .eco-wishlist-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.6rem;
            color: #012b17;
            margin: 0;
        }

        /* Delete all button */
        .eco-wishlist-delete-all {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .5rem 1rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: .8rem;
            font-weight: 500;
            color: #6b7280;
            background: transparent;
            cursor: pointer;
            transition: border-color .15s, color .15s, background .15s;
            font-family: 'Poppins', sans-serif;
        }
        .eco-wishlist-delete-all:hover {
            border-color: #ef4444;
            color: #ef4444;
            background: #fef2f2;
        }

        /* Item cards */
        .eco-wishlist-list {
            display: flex;
            flex-direction: column;
            gap: .75rem;
        }

        .eco-wishlist-item {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 1.25rem 1.4rem;
            display: flex;
            align-items: flex-start;
            gap: 1.25rem;
            transition: box-shadow .15s, border-color .15s;
        }
        .eco-wishlist-item:hover {
            border-color: #d1d5db;
            box-shadow: 0 4px 12px rgba(0,0,0,.06);
        }

        .eco-wishlist-item-img {
            width: 100px;
            height: 100px;
            min-width: 100px;
            border-radius: 10px;
            object-fit: cover;
        }
        @media (max-width: 640px) {
            .eco-wishlist-item-img { width: 72px; height: 72px; min-width: 72px; }
        }

        .eco-wishlist-item-body {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: .5rem;
        }

        .eco-wishlist-item-name {
            font-size: .92rem;
            font-weight: 600;
            color: #111827;
            margin: 0;
            line-height: 1.4;
        }

        .eco-wishlist-item-attrs-toggle {
            font-size: .78rem;
            color: #008138;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: .3rem;
        }

        .eco-wishlist-item-attr-row {
            font-size: .78rem;
            color: #6b7280;
        }
        .eco-wishlist-item-attr-row strong {
            color: #374151;
        }

        .eco-wishlist-item-actions {
            display: flex;
            align-items: center;
            gap: .75rem;
            flex-wrap: wrap;
            margin-top: .25rem;
        }

        /* Move to cart button */
        .eco-wishlist-item-actions .primary-button {
            background: #008138 !important;
            border-color: #008138 !important;
            border-radius: 8px !important;
            font-size: .8rem !important;
            padding: .45rem 1.1rem !important;
            height: auto !important;
            max-height: none !important;
            font-family: 'Poppins', sans-serif !important;
        }
        .eco-wishlist-item-actions .primary-button:hover {
            background: #016630 !important;
            border-color: #016630 !important;
        }

        /* Quantity changer */
        .eco-wishlist-item-actions [class*="rounded-[54px]"] {
            border-color: #d1d5db !important;
            border-radius: 8px !important;
            padding: .4rem .85rem !important;
        }

        /* Price + remove */
        .eco-wishlist-item-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: .4rem;
            min-width: 100px;
        }
        @media (max-width: 640px) { .eco-wishlist-item-right { display: none; } }

        .eco-wishlist-item-price {
            font-size: 1rem;
            font-weight: 700;
            color: #012b17;
        }

        .eco-wishlist-item-remove {
            font-size: .78rem;
            color: #9ca3af;
            cursor: pointer;
            text-decoration: none;
            transition: color .15s;
            display: flex;
            align-items: center;
            gap: .3rem;
        }
        .eco-wishlist-item-remove:hover { color: #ef4444; }

        /* Mobile remove icon */
        .eco-wishlist-item-remove-mobile {
            color: #9ca3af;
            font-size: 1.2rem;
            cursor: pointer;
            display: none;
            transition: color .15s;
        }
        @media (max-width: 640px) { .eco-wishlist-item-remove-mobile { display: block; } }

        /* Mobile price */
        .eco-wishlist-item-price-mobile {
            font-size: .9rem;
            font-weight: 700;
            color: #012b17;
            display: none;
        }
        @media (max-width: 640px) { .eco-wishlist-item-price-mobile { display: block; } }

        /* Empty state */
        .eco-wishlist-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 5rem 2rem;
            text-align: center;
            gap: 1rem;
        }
        .eco-wishlist-empty img { width: 120px; opacity: .6; }
        .eco-wishlist-empty p {
            font-size: .95rem;
            color: #9ca3af;
        }
    </style>
    @endpush

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="eco-wishlist-wrap mx-4">
        <!-- Wishlist Vue Component -->
        <v-wishlist-products>
            <!-- Shimmer -->
            <x-shop::shimmer.customers.account.wishlist :count="4" />
        </v-wishlist-products>
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-wishlist-products-template"
        >
            <div>
                <template v-if="isLoading">
                    <x-shop::shimmer.customers.account.wishlist :count="4" />
                </template>

                {!! view_render_event('bagisto.shop.customers.account.wishlist.list.before') !!}

                <template v-else>
                    <!-- Header -->
                    <div class="eco-wishlist-header">
                        <div class="eco-wishlist-header-left">
                            <a class="eco-wishlist-back" href="{{ route('shop.customers.account.index') }}">
                                <span class="icon-arrow-left rtl:icon-arrow-right"></span>
                            </a>
                            <h2 class="eco-wishlist-title">
                                @lang('shop::app.customers.account.wishlist.page-title')
                            </h2>
                        </div>

                        {!! view_render_event('bagisto.shop.customers.account.wishlist.delete_all.before') !!}

                        <button
                            class="eco-wishlist-delete-all"
                            @click="removeAll"
                            v-if="wishlistItems.length"
                        >
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6l-1 14H6L5 6"></path>
                                <path d="M10 11v6M14 11v6"></path>
                                <path d="M9 6V4h6v2"></path>
                            </svg>
                            @lang('shop::app.customers.account.wishlist.delete-all')
                        </button>

                        {!! view_render_event('bagisto.shop.customers.account.wishlist.delete_all.after') !!}
                    </div>

                    <!-- Items -->
                    <template v-if="wishlistItems.length">
                        <div class="eco-wishlist-list">
                            <v-wishlist-products-item
                                v-for="(wishlist, index) in wishlistItems"
                                :wishlist="wishlist"
                                :key="wishlist.id"
                                @wishlist-items="(items) => wishlistItems = items"
                            >
                                <x-shop::shimmer.customers.account.wishlist />
                            </v-wishlist-products-item>
                        </div>
                    </template>

                    <!-- Empty -->
                    <template v-else>
                        <div class="eco-wishlist-empty">
                            <img
                                src="{{ bagisto_asset('images/wishlist.png') }}"
                                alt="@lang('shop::app.customers.account.wishlist.empty')"
                            >
                            <p>@lang('shop::app.customers.account.wishlist.empty')</p>
                        </div>
                    </template>
                </template>

                {!! view_render_event('bagisto.shop.customers.account.wishlist.list.after') !!}
            </div>
        </script>

        <script
            type="text/x-template"
            id="v-wishlist-products-item-template"
        >
            <div class="eco-wishlist-item">
                {!! view_render_event('bagisto.shop.customers.account.wishlist.image.before') !!}

                <!-- Image -->
                <a :href="`{{ route('shop.product_or_category.index', '') }}/${wishlist.product.url_key}`">
                    <img
                        class="eco-wishlist-item-img"
                        :src="wishlist.product.base_image.small_image_url"
                        alt="Product Image"
                    />
                </a>

                {!! view_render_event('bagisto.shop.customers.account.wishlist.image.after') !!}

                <!-- Body -->
                <div class="eco-wishlist-item-body">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:.5rem;">
                        <p class="eco-wishlist-item-name">@{{ wishlist.product.name }}</p>
                        <span
                            class="eco-wishlist-item-remove-mobile icon-bin"
                            @click="remove"
                        ></span>
                    </div>

                    <!-- Mobile price -->
                    <p
                        class="eco-wishlist-item-price-mobile"
                        v-html="wishlist.product.price_html"
                    ></p>

                    <!-- Attributes -->
                    <div v-if="wishlist.options?.attributes">
                        <p
                            class="eco-wishlist-item-attrs-toggle"
                            @click="wishlist.option_show = !wishlist.option_show"
                        >
                            @lang('shop::app.customers.account.wishlist.see-details')
                            <span :class="{ 'icon-arrow-up': wishlist.option_show, 'icon-arrow-down': !wishlist.option_show }"></span>
                        </p>
                        <div v-show="wishlist.option_show" style="margin-top:.35rem; display:flex; flex-direction:column; gap:.2rem;">
                            <div v-for="option in wishlist.options?.attributes" class="eco-wishlist-item-attr-row">
                                <strong>@{{ option.attribute_name }}:</strong>
                                <template v-if="option?.attribute_type === 'file'">
                                    <a :href="option.file_url" style="color:#008138;" target="_blank" :download="option.file_name">@{{ option.file_name }}</a>
                                </template>
                                <template v-else>@{{ option.option_label }}</template>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="eco-wishlist-item-actions">
                        {!! view_render_event('bagisto.shop.customers.account.wishlist.perform_actions.before') !!}

                        <x-shop::quantity-changer
                            name="quantity"
                            ::value="wishlist.options.quantity ?? 1"
                            class="flex max-h-10 items-center gap-x-2.5 rounded-[54px] border border-navyBlue px-3.5 py-1.5 max-md:gap-x-1 max-md:px-1.5 max-md:py-1"
                            @change="(qty) => wishlist.quantity = qty"
                        />

                        @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                            <x-shop::button
                                class="primary-button max-h-10 w-max rounded-2xl px-6 py-1.5 text-center text-base max-md:px-4 max-md:py-1.5 max-md:text-sm"
                                :title="trans('shop::app.customers.account.wishlist.move-to-cart')"
                                ::loading="movingToCart"
                                ::disabled="movingToCart"
                                @click="moveToCart"
                            />
                        @endif

                        {!! view_render_event('bagisto.shop.customers.account.wishlist.perform_actions.after') !!}
                    </div>
                </div>

                <!-- Right: price + remove (desktop) -->
                <div class="eco-wishlist-item-right">
                    <p class="eco-wishlist-item-price" v-html="wishlist.product.price_html"></p>

                    {!! view_render_event('bagisto.shop.customers.account.wishlist.remove_button.before') !!}

                    <a class="eco-wishlist-item-remove" @click="remove">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6l-1 14H6L5 6"></path>
                            <path d="M10 11v6M14 11v6"></path>
                            <path d="M9 6V4h6v2"></path>
                        </svg>
                        @lang('shop::app.customers.account.wishlist.remove')
                    </a>

                    {!! view_render_event('bagisto.shop.customers.account.wishlist.remove_button.after') !!}
                </div>
            </div>
        </script>

        <script type="module">
            app.component("v-wishlist-products", {
                template: '#v-wishlist-products-template',

                data() {
                    return {
                        isLoading: true,
                        wishlistItems: [],
                    };
                },

                mounted() {
                    this.get();
                },

                methods: {
                    get() {
                        this.$axios.get("{{ route('shop.api.customers.account.wishlist.index') }}")
                            .then(response => {
                                this.isLoading = false;
                                this.wishlistItems = response.data.data;
                            })
                            .catch(error => {});
                    },

                    removeAll() {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                this.$axios.post("{{ route('shop.api.customers.account.wishlist.destroy_all') }}", {
                                        '_method': 'DELETE',
                                    })
                                    .then(response => {
                                        this.wishlistItems = [];
                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                                    })
                                    .catch(error => {});
                            },
                        });
                    },
                },
            });

            app.component('v-wishlist-products-item', {
                template: '#v-wishlist-products-item-template',

                props: ['wishlist'],

                emits: ['wishlist-items'],

                data() {
                    return {
                        movingToCart: false,
                    };
                },

                methods: {
                    remove() {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                this.$axios
                                    .delete(`{{ route('shop.api.customers.account.wishlist.destroy', '') }}/${this.wishlist.id}`)
                                    .then(response => {
                                        this.$emit('wishlist-items', response.data.data);
                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                    })
                                    .catch(error => {});
                            },
                        });
                    },

                    moveToCart() {
                        this.movingToCart = true;

                        const endpoint = `{{ route('shop.api.customers.account.wishlist.move_to_cart', ':wishlistId:') }}`.replace(':wishlistId:', this.wishlist.id);

                        this.$axios.post(endpoint, {
                                quantity: (this.wishlist.quantity ?? this.wishlist.options.quantity) ?? 1,
                                product_id: this.wishlist.product.id,
                            })
                            .then(response => {
                                if (response.data?.redirect) {
                                    this.$emitter.emit('add-flash', { type: 'warning', message: response.data.message });
                                    window.location.href = response.data.data;
                                    return;
                                }

                                this.$emit('wishlist-items', response.data.data?.wishlist);
                                this.$emitter.emit('update-mini-cart', response.data.data.cart);
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                this.movingToCart = false;
                            })
                            .catch(error => {
                                this.movingToCart = false;
                                this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.message });
                            });
                    },
                },
            });
        </script>
    @endpushOnce
</x-shop::layouts.account>
