<!-- Mini Cart Vue Component -->
<v-mini-cart>
    <span
        class="icon-cart cursor-pointer text-2xl text-white"
        role="button"
        aria-label="@lang('shop::app.checkout.cart.mini-cart.shopping-cart')"
    ></span>
</v-mini-cart>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-mini-cart-template"
    >
        {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.before') !!}

        @if (core()->getConfigData('sales.checkout.mini_cart.display_mini_cart'))
            <x-shop::drawer>
                <!-- Drawer Toggler -->
                <x-slot:toggle>
                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.toggle.before') !!}

                    <span class="relative">
                        <span
                            class="icon-cart cursor-pointer text-2xl text-white transition-opacity hover:opacity-80"
                            role="button"
                            aria-label="@lang('shop::app.checkout.cart.mini-cart.shopping-cart')"
                            tabindex="0"
                            @click="getCart"
                        ></span>

                        @if (core()->getConfigData('sales.checkout.my_cart.summary') == 'display_item_quantity')
                            <span
                                class="vn-cart-badge"
                                v-if="cart?.items_qty"
                            >
                                @{{ cart.items_qty }}
                            </span>
                        @else
                            <span
                                class="vn-cart-badge"
                                v-if="cart?.items_count"
                            >
                                @{{ cart.items_count }}
                            </span>
                        @endif
                    </span>

                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.toggle.after') !!}
                </x-slot>

                <!-- Drawer Header -->
                <x-slot:header class="vn-mc-header-area">
                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.header.before') !!}

                    <div class="vn-mc-header">
                        <div class="vn-mc-header__accent"></div>

                        <div class="vn-mc-header__inner">
                            <div class="flex items-center gap-3">
                                <span class="icon-cart text-2xl text-white/70"></span>
                                <p class="vn-mc-header__title">
                                    @lang('shop::app.checkout.cart.mini-cart.shopping-cart')
                                </p>
                            </div>

                            @if (core()->getConfigData('sales.checkout.mini_cart.offer_info'))
                                <p class="vn-mc-header__offer">
                                    {{ core()->getConfigData('sales.checkout.mini_cart.offer_info') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.header.after') !!}
                </x-slot>

                <!-- Drawer Content -->
                <x-slot:content>
                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.before') !!}

                    <!-- Cart Item Listing -->
                    <div
                        class="flex flex-col gap-4 py-5 max-md:py-3"
                        v-if="cart?.items?.length"
                    >
                        <div
                            class="vn-mc-item"
                            v-for="item in cart?.items"
                        >
                            <!-- Cart Item Image -->
                            {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.image.before') !!}

                            <a
                                :href="`{{ route('shop.product_or_category.index', '') }}/${item.product_url_key}`"
                                class="vn-mc-item__img-wrap flex-shrink-0"
                            >
                                <img
                                    :src="item.base_image.small_image_url"
                                    class="vn-mc-item__img"
                                />
                            </a>

                            {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.image.after') !!}

                            <!-- Cart Item Information -->
                            <div class="flex flex-col flex-1 gap-2 min-w-0">
                                <div class="flex justify-between gap-2 items-start">
                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.name.before') !!}

                                    <a
                                        :href="`{{ route('shop.product_or_category.index', '') }}/${item.product_url_key}`"
                                        class="vn-mc-item__name"
                                    >
                                        @{{ item.name }}
                                    </a>

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.name.after') !!}

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.price.before') !!}

                                    <template v-if="displayTax.prices == 'including_tax'">
                                        <p class="vn-mc-item__price flex-shrink-0">
                                            @{{ item.formatted_price_incl_tax }}
                                        </p>
                                    </template>

                                    <template v-else-if="displayTax.prices == 'both'">
                                        <p class="vn-mc-item__price flex-shrink-0 flex flex-col items-end">
                                            @{{ item.formatted_price_incl_tax }}

                                            <span class="vn-mc-item__price-sub">
                                                @lang('shop::app.checkout.cart.mini-cart.excl-tax')
                                                <span class="font-semibold">@{{ item.formatted_price }}</span>
                                            </span>
                                        </p>
                                    </template>

                                    <template v-else>
                                        <p class="vn-mc-item__price flex-shrink-0">
                                            @{{ item.formatted_price }}
                                        </p>
                                    </template>

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.price.after') !!}
                                </div>

                                <!-- Cart Item Options Container -->
                                <div
                                    class="grid select-none gap-x-2 gap-y-1"
                                    v-if="item.options.length"
                                >
                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.product_details.before') !!}

                                    <!-- Details Toggler -->
                                    <div>
                                        <p
                                            class="flex cursor-pointer items-center gap-x-2 text-xs text-zinc-400 hover:text-darkGreen transition-colors"
                                            @click="item.option_show = ! item.option_show"
                                        >
                                            @lang('shop::app.checkout.cart.mini-cart.see-details')

                                            <span
                                                class="text-base"
                                                :class="{'icon-arrow-up': item.option_show, 'icon-arrow-down': ! item.option_show}"
                                            ></span>
                                        </p>
                                    </div>

                                    <!-- Option Details -->
                                    <div
                                        class="grid gap-1.5 pl-2 border-l-2 border-ecoYellow/60"
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

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.product_details.after') !!}
                                </div>

                                <div class="flex flex-wrap items-center gap-3 mt-auto pt-1">
                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.quantity_changer.before') !!}

                                    <!-- Cart Item Quantity Changer -->
                                    <x-shop::quantity-changer
                                        v-if="item.can_change_qty"
                                        class="vn-mc-qty max-h-8 max-w-[120px] gap-x-2 rounded-full px-3 py-1"
                                        name="quantity"
                                        ::value="item?.quantity"
                                        @change="updateItem($event, item)"
                                    />

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.quantity_changer.after') !!}

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.remove_button.before') !!}

                                    <!-- Cart Item Remove Button -->
                                    <button
                                        type="button"
                                        class="vn-mc-remove"
                                        @click="removeItem(item.id)"
                                    >
                                        <span class="icon-bin text-sm"></span>
                                        @lang('shop::app.checkout.cart.mini-cart.remove')
                                    </button>

                                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.remove_button.after') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty Cart Section -->
                    <div
                        class="flex flex-col items-center justify-center py-16 px-6 gap-6"
                        v-else
                    >
                        <div class="vn-mc-empty-icon">
                            <span class="icon-cart text-4xl" style="color: #016630; opacity: 0.25;"></span>
                        </div>

                        <div class="text-center">
                            <p
                                class="vn-mc-empty-title"
                                role="heading"
                            >
                                @lang('shop::app.checkout.cart.mini-cart.empty-cart')
                            </p>

                            <p class="text-sm text-zinc-400 mt-1">
                                Adicione produtos para começar suas compras.
                            </p>
                        </div>
                    </div>

                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.after') !!}
                </x-slot>

                <!-- Drawer Footer -->
                <x-slot:footer>
                    <div
                        v-if="cart?.items?.length"
                        class="vn-mc-footer"
                    >
                        <!-- Subtotal Row -->
                        <div
                            class="vn-mc-subtotal"
                            :class="{'!justify-end': isLoading}"
                        >
                            {!! view_render_event('bagisto.shop.checkout.mini-cart.subtotal.before') !!}

                            <template v-if="! isLoading">
                                <p class="vn-mc-subtotal__label">
                                    @lang('shop::app.checkout.cart.mini-cart.subtotal')
                                </p>

                                <template v-if="displayTax.subtotal == 'including_tax'">
                                    <p class="vn-mc-total">
                                        @{{ cart.formatted_sub_total_incl_tax }}
                                    </p>
                                </template>

                                <template v-else-if="displayTax.subtotal == 'both'">
                                    <p class="vn-mc-total flex flex-col items-end">
                                        @{{ cart.formatted_sub_total_incl_tax }}

                                        <span class="text-sm font-normal text-zinc-500">
                                            @lang('shop::app.checkout.cart.mini-cart.excl-tax')
                                            <span class="font-semibold text-darkGreen">@{{ cart.formatted_sub_total }}</span>
                                        </span>
                                    </p>
                                </template>

                                <template v-else>
                                    <p class="vn-mc-total">
                                        @{{ cart.formatted_sub_total }}
                                    </p>
                                </template>
                            </template>

                            <template v-else>
                                <!-- Spinner -->
                                <svg
                                    class="h-7 w-7 animate-spin"
                                    style="color: #016630;"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    aria-hidden="true"
                                    viewBox="0 0 24 24"
                                >
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    ></circle>

                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    ></path>
                                </svg>
                            </template>

                            {!! view_render_event('bagisto.shop.checkout.mini-cart.subtotal.after') !!}
                        </div>

                        {!! view_render_event('bagisto.shop.checkout.mini-cart.action.before') !!}

                        <!-- Cart Action Buttons -->
                        <div class="flex flex-col gap-3 px-5 pt-4 pb-5 max-md:px-4 max-md:pb-4">
                            {!! view_render_event('bagisto.shop.checkout.mini-cart.continue_to_checkout.before') !!}

                            <a
                                href="{{ route('shop.checkout.onepage.index') }}"
                                class="vn-mc-btn-checkout"
                            >
                                @lang('shop::app.checkout.cart.mini-cart.continue-to-checkout')
                                <span class="icon-arrow-right text-lg"></span>
                            </a>

                            {!! view_render_event('bagisto.shop.checkout.mini-cart.continue_to_checkout.after') !!}

                            <a
                                href="{{ route('shop.checkout.cart.index') }}"
                                class="vn-mc-btn-view-cart"
                            >
                                @lang('shop::app.checkout.cart.mini-cart.view-cart')
                            </a>
                        </div>

                        {!! view_render_event('bagisto.shop.checkout.mini-cart.action.after') !!}
                    </div>
                </x-slot>
            </x-shop::drawer>

        @else
            <a href="{{ route('shop.checkout.onepage.index') }}">
                {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.toggle.before') !!}

                <span class="relative">
                    <span
                        class="icon-cart cursor-pointer text-2xl text-white"
                        role="button"
                        aria-label="@lang('shop::app.checkout.cart.mini-cart.shopping-cart')"
                        tabindex="0"
                    ></span>

                    <span
                        class="vn-cart-badge"
                        v-if="cart?.items_qty"
                    >
                        @{{ cart.items_qty }}
                    </span>
                </span>

                {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.toggle.after') !!}
            </a>
        @endif

        {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.after') !!}
    </script>

    <script type="module">
        app.component("v-mini-cart", {
            template: '#v-mini-cart-template',

            data() {
                return  {
                    cart: null,

                    isLoading: false,

                    displayTax: {
                        prices: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_prices') }}",
                        subtotal: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_subtotal') }}",
                    },
                }
            },

            mounted() {
                if (!this.cart) {
                    this.getCart();
                }

                /**
                 * Action.
                 */
                this.$emitter.on('update-mini-cart', (cart) => {
                    this.cart = cart;
                });
            },

            methods: {
                getCart() {
                    this.$axios.get('{{ route('shop.api.checkout.cart.index') }}')
                        .then(response => {
                            this.cart = response.data.data;
                        })
                        .catch(error => {});
                },

                updateItem(quantity, item) {
                    this.isLoading = true;

                    let qty = {};

                    qty[item.id] = quantity;

                    this.$axios.put('{{ route('shop.api.checkout.cart.update') }}', { qty })
                        .then(response => {
                            if (response.data.message) {
                                this.cart = response.data.data;
                            } else {
                                this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                            }

                            this.isLoading = false;
                        }).catch(error => this.isLoading = false);
                },

                removeItem(itemId) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            this.isLoading = true;

                            this.$axios.post('{{ route('shop.api.checkout.cart.destroy') }}', {
                                '_method': 'DELETE',
                                'cart_item_id': itemId,
                            })
                            .then(response => {
                                this.cart = response.data.data;

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                this.isLoading = false;
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', { type: 'error', message: response.data.message });

                                this.isLoading = false;
                            });
                        }
                    });
                },
            },
        });
    </script>
@endpushOnce
