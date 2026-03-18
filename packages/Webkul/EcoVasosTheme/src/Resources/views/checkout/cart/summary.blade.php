<div class="vn-cart-summary w-[418px] max-w-full max-md:w-full">
    <!-- Accent stripe -->
    <div class="vn-cart-summary__accent"></div>

    <div class="vn-cart-summary__body">
        {!! view_render_event('bagisto.shop.checkout.cart.summary.title.before') !!}

        <p
            class="vn-cart-summary__title"
            role="heading"
            aria-level="1"
        >
            @lang('shop::app.checkout.cart.summary.cart-summary')
        </p>

        {!! view_render_event('bagisto.shop.checkout.cart.summary.title.after') !!}

        <!-- Cart Totals -->
        <div class="mt-5 flex flex-col gap-3 max-md:mt-3 max-md:gap-2">
            <!-- Estimate Tax and Shipping -->
            @if (core()->getConfigData('sales.checkout.shopping_cart.estimate_shipping'))
                <template v-if="cart.have_stockable_items">
                    @include('shop::checkout.cart.summary.estimate-shipping')
                </template>
            @endif

            <!-- Sub Total -->
            {!! view_render_event('bagisto.shop.checkout.cart.summary.sub_total.before') !!}

            <template v-if="displayTax.subtotal == 'including_tax'">
                <div class="vn-cart-summary__row">
                    <p class="vn-cart-summary__label">
                        @lang('shop::app.checkout.cart.summary.sub-total')
                    </p>

                    <p class="vn-cart-summary__value">
                        @{{ cart.formatted_sub_total_incl_tax }}
                    </p>
                </div>
            </template>

            <template v-else-if="displayTax.subtotal == 'both'">
                <div class="vn-cart-summary__row">
                    <p class="vn-cart-summary__label">
                        @lang('shop::app.checkout.cart.summary.sub-total-excl-tax')
                    </p>

                    <p class="vn-cart-summary__value">
                        @{{ cart.formatted_sub_total }}
                    </p>
                </div>

                <div class="vn-cart-summary__row">
                    <p class="vn-cart-summary__label">
                        @lang('shop::app.checkout.cart.summary.sub-total-incl-tax')
                    </p>

                    <p class="vn-cart-summary__value">
                        @{{ cart.formatted_sub_total_incl_tax }}
                    </p>
                </div>
            </template>

            <template v-else>
                <div class="vn-cart-summary__row">
                    <p class="vn-cart-summary__label">
                        @lang('shop::app.checkout.cart.summary.sub-total')
                    </p>

                    <p class="vn-cart-summary__value">
                        @{{ cart.formatted_sub_total }}
                    </p>
                </div>
            </template>

            {!! view_render_event('bagisto.shop.checkout.cart.summary.sub_total.after') !!}

            <!-- Discount -->
            {!! view_render_event('bagisto.shop.checkout.cart.summary.discount_amount.before') !!}

            <div
                class="vn-cart-summary__row"
                v-if="cart.discount_amount && parseFloat(cart.discount_amount) > 0"
            >
                <p class="vn-cart-summary__label">
                    @lang('shop::app.checkout.cart.summary.discount-amount')
                </p>

                <p class="vn-cart-summary__value vn-cart-summary__value--discount">
                    @{{ cart.formatted_discount_amount }}
                </p>
            </div>

            {!! view_render_event('bagisto.shop.checkout.cart.summary.discount_amount.after') !!}

            <!-- Apply Coupon -->
            {!! view_render_event('bagisto.shop.checkout.cart.summary.coupon.before') !!}

            @include('shop::checkout.coupon')

            {!! view_render_event('bagisto.shop.checkout.cart.summary.coupon.after') !!}

            <!-- Shipping Rates -->
            {!! view_render_event('bagisto.shop.checkout.onepage.summary.delivery_charges.before') !!}

            <template v-if="displayTax.shipping == 'including_tax'">
                <div class="vn-cart-summary__row">
                    <p class="vn-cart-summary__label">
                        @lang('shop::app.checkout.cart.summary.delivery-charges')
                    </p>

                    <p class="vn-cart-summary__value">
                        @{{ cart.formatted_shipping_amount_incl_tax }}
                    </p>
                </div>
            </template>

            <template v-else-if="displayTax.shipping == 'both'">
                <div class="vn-cart-summary__row">
                    <p class="vn-cart-summary__label">
                        @lang('shop::app.checkout.cart.summary.delivery-charges-excl-tax')
                    </p>

                    <p class="vn-cart-summary__value">
                        @{{ cart.formatted_shipping_amount }}
                    </p>
                </div>

                <div class="vn-cart-summary__row">
                    <p class="vn-cart-summary__label">
                        @lang('shop::app.checkout.cart.summary.delivery-charges-incl-tax')
                    </p>

                    <p class="vn-cart-summary__value">
                        @{{ cart.formatted_shipping_amount_incl_tax }}
                    </p>
                </div>
            </template>

            <template v-else>
                <div class="vn-cart-summary__row">
                    <p class="vn-cart-summary__label">
                        @lang('shop::app.checkout.cart.summary.delivery-charges')
                    </p>

                    <p class="vn-cart-summary__value">
                        @{{ cart.formatted_shipping_amount }}
                    </p>
                </div>
            </template>

            {!! view_render_event('bagisto.shop.checkout.onepage.summary.delivery_charges.after') !!}

            <!-- Taxes -->
            {!! view_render_event('bagisto.shop.checkout.cart.summary.tax.before') !!}

            <div
                class="vn-cart-summary__row"
                v-if="! cart.tax_total"
            >
                <p class="vn-cart-summary__label">
                    @lang('shop::app.checkout.cart.summary.tax')
                </p>

                <p class="vn-cart-summary__value">
                    @{{ cart.formatted_tax_total }}
                </p>
            </div>

            <div
                class="flex flex-col gap-2 border-y border-darkGreen/8 py-2"
                v-else
            >
                <div
                    class="vn-cart-summary__row cursor-pointer"
                    @click="cart.show_taxes = ! cart.show_taxes"
                >
                    <p class="vn-cart-summary__label">
                        @lang('shop::app.checkout.cart.summary.tax')
                    </p>

                    <p class="vn-cart-summary__value flex items-center gap-1">
                        @{{ cart.formatted_tax_total }}

                        <span
                            class="text-lg text-darkGreen/50"
                            :class="{'icon-arrow-up': cart.show_taxes, 'icon-arrow-down': ! cart.show_taxes}"
                        ></span>
                    </p>
                </div>

                <div
                    class="flex flex-col gap-1 pl-2"
                    v-show="cart.show_taxes"
                >
                    <div
                        class="flex justify-between gap-1"
                        v-for="(amount, index) in cart.applied_taxes"
                    >
                        <p class="text-xs text-zinc-400">@{{ index }}</p>

                        <p class="text-xs font-medium text-zinc-600">@{{ amount }}</p>
                    </div>
                </div>
            </div>

            {!! view_render_event('bagisto.shop.checkout.cart.summary.tax.after') !!}

            <!-- Grand Total -->
            {!! view_render_event('bagisto.shop.checkout.cart.summary.grand_total.before') !!}

            <div class="vn-cart-summary__grand-total">
                <p class="vn-cart-summary__grand-label">
                    @lang('shop::app.checkout.cart.summary.grand-total')
                </p>

                <p class="vn-cart-summary__grand-value">
                    @{{ cart.formatted_grand_total }}
                </p>
            </div>

            {!! view_render_event('bagisto.shop.checkout.cart.summary.grand_total.after') !!}

            {!! view_render_event('bagisto.shop.checkout.cart.summary.proceed_to_checkout.before') !!}

            <a
                href="{{ route('shop.checkout.onepage.index') }}"
                class="vn-cart-summary__checkout-btn"
            >
                @lang('shop::app.checkout.cart.summary.proceed-to-checkout')
                <span class="icon-arrow-right text-lg"></span>
            </a>

            {!! view_render_event('bagisto.shop.checkout.cart.summary.proceed_to_checkout.after') !!}
        </div>
    </div>
</div>
