<v-product-card
    {{ $attributes }}
    :product="product"
>
</v-product-card>

@pushOnce('styles')
<style>
    /* ── EcoVasos Product Card ── */
    .eco-card {
        font-family: 'Poppins', sans-serif;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
        transition: box-shadow .2s, border-color .2s;
        display: flex;
        flex-direction: column;
    }
    .eco-card:hover {
        border-color: #c6e8d5;
        box-shadow: 0 8px 24px rgba(0, 129, 56, .10);
    }

    /* Image wrapper */
    .eco-card-img-wrap {
        position: relative;
        overflow: hidden;
        background: #f9fafb;
        aspect-ratio: 1 / 1;
    }
    .eco-card-img-wrap img,
    .eco-card-img-wrap .lazy-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .35s ease;
        display: block;
    }
    .eco-card:hover .eco-card-img-wrap img,
    .eco-card:hover .eco-card-img-wrap .lazy-image {
        transform: scale(1.04);
    }

    /* Badges */
    .eco-card-badge {
        position: absolute;
        top: .6rem;
        left: .6rem;
        padding: .2rem .65rem;
        border-radius: 20px;
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        z-index: 2;
    }
    .eco-card-badge-sale { background: #ef4444; color: #fff; }
    .eco-card-badge-new  { background: #008138; color: #fff; }

    /* Rating pill on image */
    .eco-card-rating {
        position: absolute;
        bottom: .6rem;
        left: .6rem;
        z-index: 2;
    }

    /* Wishlist / compare mobile */
    .eco-card-mobile-actions {
        position: absolute;
        top: .6rem;
        right: .6rem;
        display: flex;
        flex-direction: column;
        gap: .35rem;
        z-index: 2;
    }
    .eco-card-icon-btn {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border-radius: 50%;
        border: 1px solid #e5e7eb;
        font-size: 1rem;
        cursor: pointer;
        transition: border-color .15s, color .15s;
    }
    .eco-card-icon-btn:hover { border-color: #008138; color: #008138; }

    /* Info section */
    .eco-card-info {
        padding: .85rem 1rem 1rem;
        display: flex;
        flex-direction: column;
        gap: .35rem;
        flex: 1;
    }

    .eco-card-name {
        font-size: .88rem;
        font-weight: 500;
        color: #111827;
        line-height: 1.45;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin: 0;
    }

    .eco-card-price {
        font-size: .95rem;
        font-weight: 700;
        color: #008138;
        display: flex;
        align-items: center;
        gap: .4rem;
        flex-wrap: wrap;
    }
    /* Strike-through old price */
    .eco-card-price del, .eco-card-price .price-label-old-price { color: #9ca3af; font-weight: 400; font-size: .8rem; }

    /* Desktop hover actions */
    .eco-card-actions {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin-top: .15rem;
        opacity: 0;
        transform: translateY(4px);
        transition: opacity .2s, transform .2s;
    }
    .eco-card:hover .eco-card-actions {
        opacity: 1;
        transform: translateY(0);
    }

    .eco-card-add-btn {
        flex: 1;
        padding: .5rem .75rem;
        background: #008138;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: .78rem;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: background .15s;
        white-space: nowrap;
    }
    .eco-card-add-btn:hover { background: #016630; }
    .eco-card-add-btn:disabled { background: #9ca3af; cursor: default; }

    .eco-card-action-icon {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1.1rem;
        color: #6b7280;
        cursor: pointer;
        transition: border-color .15s, color .15s;
        flex-shrink: 0;
    }
    .eco-card-action-icon:hover { border-color: #008138; color: #008138; }
    .eco-card-action-icon.active { color: #ef4444; border-color: #fca5a5; }

    /* ── List mode card ── */
    .eco-card-list {
        display: flex;
        gap: 1.25rem;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
        padding: 1rem;
        transition: box-shadow .2s, border-color .2s;
        font-family: 'Poppins', sans-serif;
    }
    .eco-card-list:hover {
        border-color: #c6e8d5;
        box-shadow: 0 6px 18px rgba(0,129,56,.08);
    }
    .eco-card-list-img {
        width: 160px;
        min-width: 160px;
        height: 160px;
        border-radius: 10px;
        overflow: hidden;
        background: #f9fafb;
        position: relative;
    }
    .eco-card-list-img img { width: 100%; height: 100%; object-fit: cover; }

    .eco-card-list-body {
        display: flex;
        flex-direction: column;
        gap: .5rem;
        flex: 1;
        min-width: 0;
    }
    .eco-card-list-name {
        font-size: 1rem;
        font-weight: 500;
        color: #111827;
        margin: 0;
    }
    .eco-card-list-price {
        font-size: 1.1rem;
        font-weight: 700;
        color: #008138;
        display: flex;
        align-items: center;
        gap: .5rem;
    }
    .eco-card-list-add-btn {
        display: inline-flex;
        align-items: center;
        padding: .55rem 1.25rem;
        background: #008138;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: .85rem;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: background .15s;
        width: fit-content;
    }
    .eco-card-list-add-btn:hover { background: #016630; }
    .eco-card-list-add-btn:disabled { background: #9ca3af; cursor: default; }

    @media (max-width: 640px) {
        .eco-card-list { flex-direction: column; }
        .eco-card-list-img { width: 100%; height: 200px; }
        .eco-card-actions { opacity: 1; transform: none; }
    }
</style>
@endpushOnce

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-product-card-template"
    >
        <!-- Grid Card -->
        <div
            class="eco-card w-full"
            v-if="mode != 'list'"
        >
            <!-- Image -->
            <div class="eco-card-img-wrap">
                {!! view_render_event('bagisto.shop.components.products.card.image.before') !!}

                <a
                    :href="`{{ route('shop.product_or_category.index', '') }}/${product.url_key}`"
                    :aria-label="product.name"
                >
                    <x-shop::media.images.lazy
                        ::src="product.base_image.medium_image_url"
                        ::srcset="`${product.base_image.small_image_url} 150w, ${product.base_image.medium_image_url} 300w`"
                        sizes="(max-width: 768px) 150px, 300px"
                        ::key="product.id"
                        ::index="product.id"
                        width="291"
                        height="291"
                        ::alt="product.name"
                    />
                </a>

                {!! view_render_event('bagisto.shop.components.products.card.image.after') !!}

                <!-- Badges -->
                {!! view_render_event('bagisto.shop.components.products.card.average_ratings.before') !!}

                @if (core()->getConfigData('catalog.products.review.summary') == 'star_counts')
                    <div class="eco-card-rating">
                        <x-shop::products.ratings
                            class="!border-white bg-white/80 !px-2 !py-1 text-xs"
                            ::average="product.ratings.average"
                            ::total="product.ratings.total"
                            ::rating="false"
                            v-if="product.ratings.total"
                        />
                    </div>
                @else
                    <div class="eco-card-rating">
                        <x-shop::products.ratings
                            class="!border-white bg-white/80 !px-2 !py-1 text-xs"
                            ::average="product.ratings.average"
                            ::total="product.reviews.total"
                            ::rating="false"
                            v-if="product.reviews.total"
                        />
                    </div>
                @endif

                {!! view_render_event('bagisto.shop.components.products.card.average_ratings.after') !!}

                <!-- Sale / New badge -->
                <p class="eco-card-badge eco-card-badge-sale" v-if="product.on_sale">
                    @lang('shop::app.components.products.card.sale')
                </p>
                <p class="eco-card-badge eco-card-badge-new" v-else-if="product.is_new">
                    @lang('shop::app.components.products.card.new')
                </p>

                <!-- Mobile quick actions -->
                <div class="eco-card-mobile-actions md:hidden">
                    {!! view_render_event('bagisto.shop.components.products.card.wishlist_option.before') !!}

                    @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                        <span
                            class="eco-card-icon-btn"
                            role="button"
                            aria-label="@lang('shop::app.components.products.card.add-to-wishlist')"
                            :class="product.is_wishlist ? 'icon-heart-fill active' : 'icon-heart'"
                            @click="addToWishlist()"
                        ></span>
                    @endif

                    {!! view_render_event('bagisto.shop.components.products.card.wishlist_option.after') !!}

                    {!! view_render_event('bagisto.shop.components.products.card.compare_option.before') !!}

                    @if (core()->getConfigData('catalog.products.settings.compare_option'))
                        <span
                            class="eco-card-icon-btn icon-compare"
                            role="button"
                            aria-label="@lang('shop::app.components.products.card.add-to-compare')"
                            @click="addToCompare(product.id)"
                        ></span>
                    @endif

                    {!! view_render_event('bagisto.shop.components.products.card.compare_option.after') !!}
                </div>
            </div>

            <!-- Info -->
            <div class="eco-card-info">
                {!! view_render_event('bagisto.shop.components.products.card.name.before') !!}

                <a :href="`{{ route('shop.product_or_category.index', '') }}/${product.url_key}`">
                    <p class="eco-card-name">@{{ product.name }}</p>
                </a>

                {!! view_render_event('bagisto.shop.components.products.card.name.after') !!}

                {!! view_render_event('bagisto.shop.components.products.card.price.before') !!}

                <div class="eco-card-price" v-html="product.price_html"></div>

                {!! view_render_event('bagisto.shop.components.products.card.price.after') !!}

                <!-- Desktop hover actions -->
                <div class="eco-card-actions max-md:hidden">
                    @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                        {!! view_render_event('bagisto.shop.components.products.card.add_to_cart.before') !!}

                        <button
                            class="eco-card-add-btn"
                            :disabled="! product.is_saleable || isAddingToCart"
                            @click="addToCart()"
                        >
                            @lang('shop::app.components.products.card.add-to-cart')
                        </button>

                        {!! view_render_event('bagisto.shop.components.products.card.add_to_cart.after') !!}
                    @endif

                    {!! view_render_event('bagisto.shop.components.products.card.wishlist_option.before') !!}

                    @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                        <span
                            class="eco-card-action-icon"
                            role="button"
                            aria-label="@lang('shop::app.components.products.card.add-to-wishlist')"
                            :class="product.is_wishlist ? 'icon-heart-fill active' : 'icon-heart'"
                            @click="addToWishlist()"
                        ></span>
                    @endif

                    {!! view_render_event('bagisto.shop.components.products.card.wishlist_option.after') !!}

                    {!! view_render_event('bagisto.shop.components.products.card.compare_option.before') !!}

                    @if (core()->getConfigData('catalog.products.settings.compare_option'))
                        <span
                            class="eco-card-action-icon icon-compare"
                            role="button"
                            aria-label="@lang('shop::app.components.products.card.add-to-compare')"
                            @click="addToCompare(product.id)"
                        ></span>
                    @endif

                    {!! view_render_event('bagisto.shop.components.products.card.compare_option.after') !!}
                </div>
            </div>
        </div>

        <!-- List Card -->
        <div
            class="eco-card-list"
            v-else
        >
            <!-- Image -->
            <div class="eco-card-list-img">
                {!! view_render_event('bagisto.shop.components.products.card.image.before') !!}

                <a :href="`{{ route('shop.product_or_category.index', '') }}/${product.url_key}`">
                    <x-shop::media.images.lazy
                        ::src="product.base_image.medium_image_url"
                        ::key="product.id"
                        ::index="product.id"
                        width="160"
                        height="160"
                        ::alt="product.name"
                    />
                </a>

                {!! view_render_event('bagisto.shop.components.products.card.image.after') !!}

                <p class="eco-card-badge eco-card-badge-sale" v-if="product.on_sale">
                    @lang('shop::app.components.products.card.sale')
                </p>
                <p class="eco-card-badge eco-card-badge-new" v-else-if="product.is_new">
                    @lang('shop::app.components.products.card.new')
                </p>

                {!! view_render_event('bagisto.shop.components.products.card.wishlist_option.before') !!}

                @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                    <span
                        style="position:absolute;top:.5rem;right:.5rem;"
                        class="eco-card-icon-btn"
                        role="button"
                        :class="product.is_wishlist ? 'icon-heart-fill active' : 'icon-heart'"
                        @click="addToWishlist()"
                    ></span>
                @endif

                {!! view_render_event('bagisto.shop.components.products.card.wishlist_option.after') !!}
            </div>

            <!-- Body -->
            <div class="eco-card-list-body">
                {!! view_render_event('bagisto.shop.components.products.card.name.before') !!}

                <a :href="`{{ route('shop.product_or_category.index', '') }}/${product.url_key}`">
                    <p class="eco-card-list-name">@{{ product.name }}</p>
                </a>

                {!! view_render_event('bagisto.shop.components.products.card.name.after') !!}

                {!! view_render_event('bagisto.shop.components.products.card.price.before') !!}

                <div class="eco-card-list-price" v-html="product.price_html"></div>

                {!! view_render_event('bagisto.shop.components.products.card.price.after') !!}

                {!! view_render_event('bagisto.shop.components.products.card.average_ratings.before') !!}

                <template v-if="! product.ratings.total">
                    <p class="text-sm text-zinc-400">
                        @lang('shop::app.components.products.card.review-description')
                    </p>
                </template>
                <template v-else>
                    @if (core()->getConfigData('catalog.products.review.summary') == 'star_counts')
                        <x-shop::products.ratings
                            ::average="product.ratings.average"
                            ::total="product.ratings.total"
                            ::rating="false"
                        />
                    @else
                        <x-shop::products.ratings
                            ::average="product.ratings.average"
                            ::total="product.reviews.total"
                            ::rating="false"
                        />
                    @endif
                </template>

                {!! view_render_event('bagisto.shop.components.products.card.average_ratings.after') !!}

                @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                    {!! view_render_event('bagisto.shop.components.products.card.add_to_cart.before') !!}

                    <x-shop::button
                        class="eco-card-list-add-btn"
                        :title="trans('shop::app.components.products.card.add-to-cart')"
                        ::loading="isAddingToCart"
                        ::disabled="! product.is_saleable || isAddingToCart"
                        @click="addToCart()"
                    />

                    {!! view_render_event('bagisto.shop.components.products.card.add_to_cart.after') !!}
                @endif
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-product-card', {
            template: '#v-product-card-template',

            props: ['mode', 'product'],

            data() {
                return {
                    isCustomer: '{{ auth()->guard('customer')->check() }}',
                    isAddingToCart: false,
                }
            },

            methods: {
                addToWishlist() {
                    if (this.isCustomer) {
                        this.$axios.post(`{{ route('shop.api.customers.account.wishlist.store') }}`, {
                                product_id: this.product.id
                            })
                            .then(response => {
                                this.product.is_wishlist = ! this.product.is_wishlist;
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                            })
                            .catch(error => {});
                    } else {
                        window.location.href = "{{ route('shop.customer.session.index')}}";
                    }
                },

                addToCompare(productId) {
                    if (this.isCustomer) {
                        this.$axios.post('{{ route("shop.api.compare.store") }}', {
                                'product_id': productId
                            })
                            .then(response => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                            })
                            .catch(error => {
                                if ([400, 422].includes(error.response.status)) {
                                    this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.data.message });
                                    return;
                                }
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message});
                            });
                        return;
                    }

                    let items = this.getStorageValue() ?? [];

                    if (items.length) {
                        if (! items.includes(productId)) {
                            items.push(productId);
                            localStorage.setItem('compare_items', JSON.stringify(items));
                            this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.components.products.card.add-to-compare-success')" });
                        } else {
                            this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('shop::app.components.products.card.already-in-compare')" });
                        }
                    } else {
                        localStorage.setItem('compare_items', JSON.stringify([productId]));
                        this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.components.products.card.add-to-compare-success')" });
                    }
                },

                getStorageValue(key) {
                    let value = localStorage.getItem('compare_items');
                    if (! value) { return []; }
                    return JSON.parse(value);
                },

                addToCart() {
                    this.isAddingToCart = true;

                    this.$axios.post('{{ route("shop.api.checkout.cart.store") }}', {
                            'quantity': 1,
                            'product_id': this.product.id,
                        })
                        .then(response => {
                            if (response.data.message) {
                                this.$emitter.emit('update-mini-cart', response.data.data);
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            } else {
                                this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                            }
                            this.isAddingToCart = false;
                        })
                        .catch(error => {
                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                            if (error.response.data.redirect_uri) {
                                window.location.href = error.response.data.redirect_uri;
                            }
                            this.isAddingToCart = false;
                        });
                },
            },
        });
    </script>
@endpushOnce
