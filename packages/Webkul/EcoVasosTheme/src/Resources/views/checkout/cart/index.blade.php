@push('styles')
<style>
    body, #app, main#main { background-color: #f4f1ea; }
</style>
@endPush

<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('shop::app.checkout.cart.index.cart')"/>
    <meta name="keywords" content="@lang('shop::app.checkout.cart.index.cart')"/>
@endPush

<x-shop::layouts
    :has-header="true"
    :has-feature="false"
    :has-footer="true"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.checkout.cart.index.cart')
    </x-slot>

    <div class="min-h-screen" style="background-color: #f4f1ea;">
        {!! view_render_event('bagisto.shop.checkout.cart.header.before') !!}
        {!! view_render_event('bagisto.shop.checkout.cart.header.after') !!}

        <!-- Page Header -->
        <div class="container px-[60px] max-lg:px-8 max-md:px-4">
            <div class="py-8 max-md:py-5">
                {!! view_render_event('bagisto.shop.checkout.cart.breadcrumbs.before') !!}

                @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
                    <x-shop::breadcrumbs name="cart" />
                @endif

                {!! view_render_event('bagisto.shop.checkout.cart.breadcrumbs.after') !!}

                <h1 class="vn-cart-page-header__title mt-2">
                    @lang('shop::app.checkout.cart.index.cart')
                </h1>
            </div>
        </div>

        <div class="flex-auto pb-12 max-md:pb-6">
            <div class="container px-[60px] max-lg:px-8 max-md:px-4">
                @php
                    $errors = \Webkul\Checkout\Facades\Cart::getErrors();
                @endphp

                @if (! empty($errors) && $errors['error_code'] === 'MINIMUM_ORDER_AMOUNT')
                    <div class="mb-5 w-full rounded-xl border border-amber-200 bg-amber-50 px-5 py-3 text-sm text-amber-800 max-sm:px-3 max-sm:py-2">
                        {{ $errors['message'] }}: {{ $errors['amount'] }}
                    </div>
                @endif

                <v-cart ref="vCart">
                    <!-- Cart Shimmer Effect -->
                    <x-shop::shimmer.checkout.cart :count="3" />
                </v-cart>
            </div>
        </div>

        @if (core()->getConfigData('sales.checkout.shopping_cart.cross_sell'))
            {!! view_render_event('bagisto.shop.checkout.cart.cross_sell_carousel.before') !!}

            <!-- Cross-sell Product Carousel -->
            <x-shop::products.carousel
                :title="trans('shop::app.checkout.cart.index.cross-sell.title')"
                :src="route('shop.api.checkout.cart.cross-sell.index')"
            >
            </x-shop::products.carousel>

            {!! view_render_event('bagisto.shop.checkout.cart.cross_sell_carousel.after') !!}
        @endif
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-cart-template"
        >
            <div>
                <!-- Cart Shimmer Effect -->
                <template v-if="isLoading">
                    <x-shop::shimmer.checkout.cart :count="3" />
                </template>

                <!-- Cart Information -->
                <template v-else>
                    <div
                        class="mt-6 flex flex-wrap gap-8 pb-8 max-1060:flex-col max-md:mt-4 max-md:gap-5 max-md:pb-0"
                        v-if="cart?.items?.length"
                    >
                        <!-- Items Column -->
                        <div class="flex flex-1 flex-col gap-4 max-md:gap-3">

                            {!! view_render_event('bagisto.shop.checkout.cart.cart_mass_actions.before') !!}

                            <!-- Mass Action Bar -->
                            <div class="vn-cart-mass-action">
                                <div class="flex select-none items-center gap-3">
                                    <input
                                        type="checkbox"
                                        id="select-all"
                                        class="peer hidden"
                                        v-model="allSelected"
                                        @change="selectAll"
                                    >

                                    <label
                                        class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl text-zinc-300 peer-checked:text-darkGreen transition-colors"
                                        for="select-all"
                                        tabindex="0"
                                        aria-label="@lang('shop::app.checkout.cart.index.select-all')"
                                        aria-labelledby="select-all-label"
                                    ></label>

                                    <span
                                        class="text-sm font-medium text-zinc-500"
                                        role="heading"
                                        aria-level="2"
                                    >
                                        @{{ "@lang('shop::app.checkout.cart.index.items-selected')".replace(':count', selectedItemsCount) }}
                                    </span>
                                </div>

                                <div
                                    class="flex items-center gap-3"
                                    v-if="selectedItemsCount"
                                >
                                    <button
                                        type="button"
                                        class="vn-cart-mass-btn vn-cart-mass-btn--danger"
                                        tabindex="0"
                                        @click="removeSelectedItems"
                                    >
                                        <span class="icon-bin text-sm"></span>
                                        @lang('shop::app.checkout.cart.index.remove')
                                    </button>

                                    @if (auth()->guard()->check())
                                        <span class="h-4 w-px bg-zinc-200"></span>

                                        <button
                                            type="button"
                                            class="vn-cart-mass-btn vn-cart-mass-btn--wishlist"
                                            tabindex="0"
                                            @click="moveToWishlistSelectedItems"
                                        >
                                            <span class="icon-heart text-sm"></span>
                                            @lang('shop::app.checkout.cart.index.move-to-wishlist')
                                        </button>
                                    @endif
                                </div>
                            </div>

                            {!! view_render_event('bagisto.shop.checkout.cart.cart_mass_actions.after') !!}

                            {!! view_render_event('bagisto.shop.checkout.cart.item.listing.before') !!}

                            <!-- Cart Item Cards -->
                            <div
                                class="vn-cart-item"
                                v-for="item in cart?.items"
                            >
                                <!-- Checkbox -->
                                <div class="mt-1 select-none flex-shrink-0">
                                    <input
                                        type="checkbox"
                                        :id="'item_' + item.id"
                                        class="peer hidden"
                                        v-model="item.selected"
                                        @change="updateAllSelected"
                                    >

                                    <label
                                        class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl text-zinc-300 peer-checked:text-darkGreen transition-colors"
                                        :for="'item_' + item.id"
                                        tabindex="0"
                                        aria-label="@lang('shop::app.checkout.cart.index.select-cart-item')"
                                        aria-labelledby="select-item-label"
                                    ></label>
                                </div>

                                {!! view_render_event('bagisto.shop.checkout.cart.item_image.before') !!}

                                <!-- Product Image -->
                                <a :href="`{{ route('shop.product_or_category.index', '') }}/${item.product_url_key}`" class="flex-shrink-0">
                                    <x-shop::media.images.lazy
                                        class="vn-cart-item__img"
                                        ::src="item.base_image.small_image_url"
                                        ::alt="item.name"
                                        width="110"
                                        height="110"
                                        ::key="item.id"
                                        ::index="item.id"
                                    />
                                </a>

                                {!! view_render_event('bagisto.shop.checkout.cart.item_image.after') !!}

                                <!-- Item Info -->
                                <div class="flex flex-1 flex-col gap-2 min-w-0">
                                    {!! view_render_event('bagisto.shop.checkout.cart.item_name.before') !!}

                                    <a :href="`{{ route('shop.product_or_category.index', '') }}/${item.product_url_key}`">
                                        <p class="text-base font-medium text-zinc-800 hover:text-darkGreen transition-colors max-sm:text-sm">
                                            @{{ item.name }}
                                        </p>
                                    </a>

                                    {!! view_render_event('bagisto.shop.checkout.cart.item_name.after') !!}

                                    {!! view_render_event('bagisto.shop.checkout.cart.item_details.before') !!}

                                    <!-- Product Options -->
                                    <div
                                        class="grid select-none gap-1"
                                        v-if="item.options.length"
                                    >
                                        <div>
                                            <p
                                                class="flex cursor-pointer items-center gap-x-2 text-xs text-zinc-400 hover:text-darkGreen transition-colors"
                                                @click="item.option_show = ! item.option_show"
                                            >
                                                @lang('shop::app.checkout.cart.index.see-details')

                                                <span
                                                    class="text-base"
                                                    :class="{'icon-arrow-up': item.option_show, 'icon-arrow-down': ! item.option_show}"
                                                ></span>
                                            </p>
                                        </div>

                                        <div
                                            class="grid gap-1 pl-2 border-l-2 border-ecoYellow/60"
                                            v-show="item.option_show"
                                        >
                                            <template v-for="attribute in item.options">
                                                <div class="grid gap-0.5">
                                                    <p class="text-xs font-medium text-zinc-400">
                                                        @{{ attribute.attribute_name + ':' }}
                                                    </p>

                                                    <p class="text-xs text-zinc-600">
                                                        <template v-if="attribute?.attribute_type === 'file'">
                                                            <a
                                                                :href="attribute.file_url"
                                                                class="text-darkGreen underline"
                                                                target="_blank"
                                                                :download="attribute.file_name"
                                                            >
                                                                @{{ attribute.file_name }}
                                                            </a>
                                                        </template>

                                                        <template v-else>
                                                            @{{ attribute.option_label }}
                                                        </template>
                                                    </p>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    {!! view_render_event('bagisto.shop.checkout.cart.item_details.after') !!}

                                    <!-- Mobile Price -->
                                    {!! view_render_event('bagisto.shop.checkout.cart.formatted_total.before') !!}

                                    <div class="md:hidden">
                                        <p class="vn-cart-item__price">
                                            <template v-if="displayTax.prices == 'including_tax'">
                                                @{{ item.formatted_total_incl_tax }}
                                            </template>

                                            <template v-else-if="displayTax.prices == 'both'">
                                                @{{ item.formatted_total_incl_tax }}
                                                <span class="text-xs font-normal text-zinc-400">
                                                    @lang('shopTheme::app.checkout.cart.index.excl-tax')
                                                    <span class="font-medium">@{{ item.formatted_total }}</span>
                                                </span>
                                            </template>

                                            <template v-else>
                                                @{{ item.formatted_total }}
                                            </template>
                                        </p>
                                    </div>

                                    {!! view_render_event('bagisto.shop.checkout.cart.formatted_total.after') !!}

                                    {!! view_render_event('bagisto.shop.checkout.cart.quantity_changer.before') !!}

                                    <div class="flex items-center gap-3 mt-auto pt-1">
                                        <x-shop::quantity-changer
                                            v-if="item.can_change_qty"
                                            class="vn-cart-qty flex max-w-max items-center gap-x-2.5 rounded-full border border-darkGreen/20 px-3.5 py-1.5 text-sm max-md:gap-x-1.5 max-md:px-2 max-md:py-1"
                                            name="quantity"
                                            ::value="item?.quantity"
                                            @change="setItemQuantity(item.id, $event)"
                                        />

                                        <button
                                            type="button"
                                            class="vn-cart-remove-btn"
                                            tabindex="0"
                                            @click="removeItem(item.id)"
                                        >
                                            <span class="icon-bin text-sm"></span>
                                            @lang('shop::app.checkout.cart.index.remove')
                                        </button>
                                    </div>

                                    {!! view_render_event('bagisto.shop.checkout.cart.quantity_changer.after') !!}
                                </div>

                                <!-- Desktop Price + Remove -->
                                <div class="text-right flex-shrink-0 flex flex-col items-end justify-between max-md:hidden">
                                    {!! view_render_event('bagisto.shop.checkout.cart.total.before') !!}

                                    <div>
                                        <template v-if="displayTax.prices == 'including_tax'">
                                            <p class="vn-cart-item__price">
                                                @{{ item.formatted_total_incl_tax }}
                                            </p>
                                        </template>

                                        <template v-else-if="displayTax.prices == 'both'">
                                            <p class="vn-cart-item__price flex flex-col items-end">
                                                @{{ item.formatted_total_incl_tax }}

                                                <span class="text-xs font-normal text-zinc-400">
                                                    @lang('shop::app.checkout.cart.index.excl-tax')
                                                    <span class="font-medium">@{{ item.formatted_total }}</span>
                                                </span>
                                            </p>
                                        </template>

                                        <template v-else>
                                            <p class="vn-cart-item__price">
                                                @{{ item.formatted_total }}
                                            </p>
                                        </template>
                                    </div>

                                    {!! view_render_event('bagisto.shop.checkout.cart.total.after') !!}

                                    {!! view_render_event('bagisto.shop.checkout.cart.remove_button.before') !!}

                                    <button
                                        type="button"
                                        class="vn-cart-remove-btn mt-3"
                                        tabindex="0"
                                        @click="removeItem(item.id)"
                                    >
                                        <span class="icon-bin text-sm"></span>
                                        @lang('shop::app.checkout.cart.index.remove')
                                    </button>

                                    {!! view_render_event('bagisto.shop.checkout.cart.remove_button.after') !!}
                                </div>
                            </div>

                            {!! view_render_event('bagisto.shop.checkout.cart.item.listing.after') !!}

                            {!! view_render_event('bagisto.shop.checkout.cart.controls.before') !!}

                            <!-- Cart Actions -->
                            <div class="flex flex-wrap justify-end gap-4 mt-2 max-md:justify-between">
                                {!! view_render_event('bagisto.shop.checkout.cart.continue_shopping.before') !!}

                                <a
                                    class="secondary-button max-h-14 rounded-2xl max-md:rounded-lg max-md:px-6 max-md:py-3 max-md:text-sm max-sm:py-2"
                                    href="{{ route('shop.home.index') }}"
                                >
                                    @lang('shop::app.checkout.cart.index.continue-shopping')
                                </a>

                                {!! view_render_event('bagisto.shop.checkout.cart.continue_shopping.after') !!}

                                {!! view_render_event('bagisto.shop.checkout.cart.update_cart.before') !!}

                                <x-shop::button
                                    class="secondary-button max-h-14 rounded-2xl max-md:rounded-lg max-md:px-6 max-md:py-3 max-md:text-sm max-sm:py-2"
                                    :title="trans('shop::app.checkout.cart.index.update-cart')"
                                    ::loading="isStoring"
                                    ::disabled="isStoring"
                                    @click="update()"
                                />

                                {!! view_render_event('bagisto.shop.checkout.cart.update_cart.after') !!}
                            </div>

                            {!! view_render_event('bagisto.shop.checkout.cart.controls.after') !!}
                        </div>

                        {!! view_render_event('bagisto.shop.checkout.cart.summary.before') !!}

                        <!-- Cart Summary -->
                        @include('shop::checkout.cart.summary')

                        {!! view_render_event('bagisto.shop.checkout.cart.summary.after') !!}
                    </div>

                    <!-- Empty Cart -->
                    <div
                        class="flex flex-col items-center justify-center py-24 text-center gap-6"
                        v-else
                    >
                        <div class="vn-cart-empty-icon">
                            <span class="icon-cart text-5xl" style="color: #016630; opacity: 0.22;"></span>
                        </div>

                        <div>
                            <p
                                class="vn-cart-empty-title"
                                role="heading"
                            >
                                @lang('shop::app.checkout.cart.index.empty-product')
                            </p>

                            <p class="text-sm text-zinc-400 mt-2">
                                Explore nosso catálogo e encontre produtos incríveis.
                            </p>
                        </div>

                        <a
                            href="{{ route('shop.home.index') }}"
                            class="primary-button rounded-xl mt-2"
                        >
                            @lang('shop::app.checkout.cart.index.continue-shopping')
                        </a>
                    </div>
                </template>
            </div>
        </script>

        <script type="module">
            app.component("v-cart", {
                template: '#v-cart-template',

                data() {
                    return  {
                        cart: [],

                        allSelected: false,

                        applied: {
                            quantity: {},
                        },

                        displayTax: {
                            prices: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_prices') }}",

                            subtotal: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_subtotal') }}",

                            shipping: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_shipping_amount') }}",
                        },

                        isLoading: true,

                        isStoring: false,
                    }
                },

                mounted() {
                    this.getCart();
                },

                computed: {
                    selectedItemsCount() {
                        return this.cart.items.filter(item => item.selected).length;
                    },
                },

                methods: {
                    getCart() {
                        this.$axios.get('{{ route('shop.api.checkout.cart.index') }}')
                            .then(response => {
                                this.cart = response.data.data;

                                this.isLoading = false;

                                if (response.data.message) {
                                    this.$emitter.emit('add-flash', { type: 'info', message: response.data.message });
                                }
                            })
                            .catch(error => {});
                    },

                    setCart(cart) {
                        this.cart = cart;
                    },

                    selectAll() {
                        for (let item of this.cart.items) {
                            item.selected = this.allSelected;
                        }
                    },

                    updateAllSelected() {
                        this.allSelected = this.cart.items.every(item => item.selected);
                    },

                    update() {
                        this.isStoring = true;

                        this.$axios.put('{{ route('shop.api.checkout.cart.update') }}', { qty: this.applied.quantity })
                            .then(response => {
                                if (response.data.message) {
                                    this.cart = response.data.data;

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                } else {
                                    this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                                }

                                this.isStoring = false;

                            })
                            .catch(error => {
                                this.isStoring = false;
                            });
                    },

                    setItemQuantity(itemId, quantity) {
                        this.applied.quantity[itemId] = quantity;
                    },

                    removeItem(itemId) {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                this.$axios.post('{{ route('shop.api.checkout.cart.destroy') }}', {
                                        '_method': 'DELETE',
                                        'cart_item_id': itemId,
                                    })
                                    .then(response => {
                                        this.cart = response.data.data;

                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                    })
                                    .catch(error => {});
                            }
                        });
                    },

                    removeSelectedItems() {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                const selectedItemsIds = this.cart.items.flatMap(item => item.selected ? item.id : []);

                                this.$axios.post('{{ route('shop.api.checkout.cart.destroy_selected') }}', {
                                        '_method': 'DELETE',
                                        'ids': selectedItemsIds,
                                    })
                                    .then(response => {
                                        this.cart = response.data.data;

                                        this.$emitter.emit('update-mini-cart', response.data.data );

                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                    })
                                    .catch(error => {});
                            }
                        });
                    },

                    moveToWishlistSelectedItems() {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                const selectedItemsIds = this.cart.items.flatMap(item => item.selected ? item.id : []);

                                const selectedItemsQty = this.cart.items.filter(item => item.selected).map(item => this.applied.quantity[item.id] ?? item.quantity);

                                this.$axios.post('{{ route('shop.api.checkout.cart.move_to_wishlist') }}', {
                                        'ids': selectedItemsIds,
                                        'qty': selectedItemsQty
                                    })
                                    .then(response => {
                                        this.cart = response.data.data;

                                        this.$emitter.emit('update-mini-cart', response.data.data );

                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                    })
                                    .catch(error => {});
                            }
                        });
                    },
                }
            });
        </script>
    @endpushOnce
</x-shop::layouts>
