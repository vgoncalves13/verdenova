<!--
    This code needs to be refactored to reduce the amount of PHP in the Blade
    template as much as possible.
-->
@php
    $showCompare = (bool) core()->getConfigData('catalog.products.settings.compare_option');

    $showWishlist = (bool) core()->getConfigData('customer.settings.wishlist.wishlist_option');
@endphp

<div class="flex flex-col lg:hidden">
    <div class="flex items-center justify-between px-4 pt-4 pb-3">
        <!-- Left: Hamburger + Logo -->
        <div class="flex items-center gap-x-2">
            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.before') !!}

            <!-- Drawer -->
            <v-mobile-drawer></v-mobile-drawer>

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.after') !!}

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.logo.before') !!}

            <a
                href="{{ route('shop.home.index') }}"
                class="vn-mob-logo"
                aria-label="@lang('shop::app.components.layouts.header.mobile.bagisto')"
            >
                <img
                    src="{{ core()->getCurrentChannel()->logo_url ?? asset('images/logo.png') }}"
                    alt="{{ config('app.name') }}"
                    width="131"
                    height="29"
                >
            </a>

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.logo.after') !!}
        </div>

        <!-- Right: Icons -->
        <div class="flex items-center gap-x-5">
            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.compare.before') !!}

            @if($showCompare)
                <a
                    href="{{ route('shop.compare.index') }}"
                    aria-label="@lang('shop::app.components.layouts.header.mobile.compare')"
                >
                    <span class="vn-mob-icon icon-compare"></span>
                </a>
            @endif

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.compare.after') !!}

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.mini_cart.before') !!}

            @if(core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                @include('shop::checkout.cart.mini-cart')
            @endif

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.mini_cart.after') !!}

            <!-- For Large screens -->
            <div class="max-md:hidden">
                <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                    <x-slot:toggle>
                        <span class="vn-mob-icon icon-users"></span>
                    </x-slot>

                    <!-- Guest Dropdown -->
                    @guest('customer')
                        <x-slot:content>
                            <div class="grid gap-2.5">
                                <p class="text-xl font-dmserif">
                                    @lang('shop::app.components.layouts.header.mobile.welcome-guest')
                                </p>

                                <p class="text-sm">
                                    @lang('shop::app.components.layouts.header.mobile.dropdown-text')
                                </p>
                            </div>

                            <p class="w-full mt-3 border border-zinc-200"></p>

                            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.customers_action.before') !!}

                            <div class="flex gap-4 mt-6">
                                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.sign_in_button.before') !!}

                                <a
                                    href="{{ route('shop.customer.session.create') }}"
                                    class="block py-4 m-0 mx-auto text-base font-medium text-center text-white cursor-pointer w-max rounded-2xl bg-darkGreen px-7 ltr:ml-0 rtl:mr-0"
                                >
                                    @lang('shop::app.components.layouts.header.mobile.sign-in')
                                </a>

                                <a
                                    href="{{ route('shop.customers.register.index') }}"
                                    class="m-0 mx-auto block w-max cursor-pointer rounded-2xl border-2 border-darkGreen bg-white px-7 py-3.5 text-center text-base font-medium text-darkGreen ltr:ml-0 rtl:mr-0"
                                >
                                    @lang('shop::app.components.layouts.header.mobile.sign-up')
                                </a>

                                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.sign_in_button.after') !!}
                            </div>

                            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.customers_action.after') !!}
                        </x-slot>
                    @endguest

                    <!-- Customers Dropdown -->
                    @auth('customer')
                        <x-slot:content class="!p-0">
                            <div class="grid gap-2.5 p-5 pb-0">
                                <p class="text-xl font-dmserif" v-pre>
                                    @lang('shop::app.components.layouts.header.mobile.welcome')'
                                    {{ auth()->guard('customer')->user()->first_name }}
                                </p>

                                <p class="text-sm">
                                    @lang('shop::app.components.layouts.header.mobile.dropdown-text')
                                </p>
                            </div>

                            <p class="w-full mt-3 border border-zinc-200"></p>

                            <div class="mt-2.5 grid gap-1 pb-2.5">
                                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.profile_dropdown.links.before') !!}

                                <a
                                    class="px-5 py-2 text-base cursor-pointer"
                                    href="{{ route('shop.customers.account.profile.index') }}"
                                >
                                    @lang('shop::app.components.layouts.header.mobile.profile')
                                </a>

                                <a
                                    class="px-5 py-2 text-base cursor-pointer"
                                    href="{{ route('shop.customers.account.orders.index') }}"
                                >
                                    @lang('shop::app.components.layouts.header.mobile.orders')
                                </a>

                                @if ($showWishlist)
                                    <a
                                        class="px-5 py-2 text-base cursor-pointer"
                                        href="{{ route('shop.customers.account.wishlist.index') }}"
                                    >
                                        @lang('shop::app.components.layouts.header.mobile.wishlist')
                                    </a>
                                @endif

                                <!--Customers logout-->
                                @auth('customer')
                                    <x-shop::form
                                        method="DELETE"
                                        action="{{ route('shop.customer.session.destroy') }}"
                                        id="customerLogout"
                                    />

                                    <a
                                        class="px-5 py-2 text-base cursor-pointer"
                                        href="{{ route('shop.customer.session.destroy') }}"
                                        onclick="event.preventDefault(); document.getElementById('customerLogout').submit();"
                                    >
                                        @lang('shop::app.components.layouts.header.mobile.logout')
                                    </a>
                                @endauth

                                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.index.profile_dropdown.links.after') !!}
                            </div>
                        </x-slot>
                    @endauth
                </x-shop::dropdown>
            </div>

            <!-- For Medium and small screen -->
            <div class="md:hidden">
                @guest('customer')
                    <a
                        href="{{ route('shop.customer.session.create') }}"
                        aria-label="@lang('shop::app.components.layouts.header.mobile.account')"
                    >
                        <span class="vn-mob-icon icon-users"></span>
                    </a>
                @endguest

                @auth('customer')
                    <a
                        href="{{ route('shop.customers.account.index') }}"
                        aria-label="@lang('shop::app.components.layouts.header.mobile.account')"
                    >
                        <span class="vn-mob-icon icon-users"></span>
                    </a>
                @endauth
            </div>
        </div>
    </div>

    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.search.before') !!}

    <!-- Search Catalog Form -->
    <form action="{{ route('shop.search.index') }}" class="px-4 pb-3">
        <label
            for="vn-mobile-search"
            class="sr-only"
        >
            @lang('shop::app.components.layouts.header.mobile.search')
        </label>

        <div class="relative">
            <span class="icon-search pointer-events-none absolute top-1/2 -translate-y-1/2 text-xl text-white/50 ltr:left-4 rtl:right-4"></span>

            <input
                type="text"
                id="vn-mobile-search"
                class="vn-mob-search-input"
                name="query"
                value="{{ request('query') }}"
                placeholder="@lang('shop::app.components.layouts.header.mobile.search-text')"
                required
            >

            @if (core()->getConfigData('catalog.products.settings.image_search'))
                @include('shop::search.images.index')
            @endif
        </div>
    </form>

    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.search.after') !!}
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-mobile-drawer-template">
        <x-shop::drawer
            position="left"
            width="100%"
            @close="onDrawerClose"
        >
            <x-slot:toggle>
                <span class="vn-mob-icon icon-hamburger"></span>
            </x-slot>

            <x-slot:header>
                <div class="flex items-center justify-between">
                    <a href="{{ route('shop.home.index') }}">
                        <img
                            src="{{ core()->getCurrentChannel()->logo_url ?? asset('images/logo.png') }}"
                            alt="{{ config('app.name') }}"
                            width="131"
                            height="29"
                        >
                    </a>
                </div>
            </x-slot>

            <x-slot:content class="!p-0">
                <!-- Account Profile Hero Section -->
                <div class="vn-dw-profile">
                    <img
                        src="{{ auth()->user()?->image_url ?? bagisto_asset('images/user-placeholder.png') }}"
                        class="vn-dw-profile__avatar"
                        alt="Avatar"
                    >

                    @guest('customer')
                        <a
                            href="{{ route('shop.customer.session.create') }}"
                            class="vn-dw-profile__login"
                        >
                            @lang('shop::app.components.layouts.header.mobile.login')
                            <i class="icon-double-arrow vn-dw-arrow"></i>
                        </a>
                    @endguest

                    @auth('customer')
                        <div v-pre>
                            <p class="vn-dw-profile__name">{{ auth()->user()?->first_name }}</p>
                            <p class="vn-dw-profile__email">{{ auth()->user()?->email }}</p>
                        </div>
                    @endauth
                </div>

                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.categories.before') !!}

                <!-- Mobile category view -->
                <v-mobile-category ref="mobileCategory"></v-mobile-category>

                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.categories.after') !!}
            </x-slot>

            <x-slot:footer>
                <!-- Localization & Currency Section -->
                @if(core()->getCurrentChannel()->locales()->count() > 1 || core()->getCurrentChannel()->currencies()->count() > 1 )
                    <div class="fixed bottom-0 z-10 grid w-full max-w-full grid-cols-[1fr_auto_1fr] items-center justify-items-center border-t border-zinc-200 bg-white px-5 ltr:left-0 rtl:right-0">
                        <!-- Filter Drawer -->
                        <x-shop::drawer
                            position="bottom"
                            width="100%"
                        >
                            <!-- Drawer Toggler -->
                            <x-slot:toggle>
                                <div
                                    class="flex cursor-pointer items-center gap-x-2.5 px-2.5 py-3.5 text-lg font-medium uppercase max-md:py-3 max-sm:text-base"
                                    role="button"
                                    v-pre
                                >
                                    {{ core()->getCurrentCurrency()->symbol . ' ' . core()->getCurrentCurrencyCode() }}
                                </div>
                            </x-slot>

                            <!-- Drawer Header -->
                            <x-slot:header>
                                <div class="flex items-center justify-between">
                                    <p class="text-lg font-semibold">
                                        @lang('shop::app.components.layouts.header.mobile.currencies')
                                    </p>
                                </div>
                            </x-slot>

                            <!-- Drawer Content -->
                            <x-slot:content class="!px-0">
                                <div
                                    class="overflow-auto"
                                    :style="{ height: getCurrentScreenHeight }"
                                >
                                    <v-currency-switcher></v-currency-switcher>
                                </div>
                            </x-slot>
                        </x-shop::drawer>

                        <!-- Seperator -->
                        <span class="h-5 w-0.5 bg-zinc-200"></span>

                        <!-- Sort Drawer -->
                        <x-shop::drawer
                            position="bottom"
                            width="100%"
                        >
                            <!-- Drawer Toggler -->
                            <x-slot:toggle>
                                <div
                                    class="flex cursor-pointer items-center gap-x-2.5 px-2.5 py-3.5 text-lg font-medium uppercase max-md:py-3 max-sm:text-base"
                                    role="button"
                                    v-pre
                                >
                                    <img
                                        src="{{ ! empty(core()->getCurrentLocale()->logo_url)
                                                ? core()->getCurrentLocale()->logo_url
                                                : bagisto_asset('images/default-language.svg')
                                            }}"
                                        class="h-full"
                                        alt="Default locale"
                                        width="24"
                                        height="16"
                                    />

                                    {{ core()->getCurrentChannel()->locales()->orderBy('name')->where('code', app()->getLocale())->value('name') }}
                                </div>
                            </x-slot>

                            <!-- Drawer Header -->
                            <x-slot:header>
                                <div class="flex items-center justify-between">
                                    <p class="text-lg font-semibold">
                                        @lang('shop::app.components.layouts.header.mobile.locales')
                                    </p>
                                </div>
                            </x-slot>

                            <!-- Drawer Content -->
                            <x-slot:content class="!px-0">
                                <div
                                    class="overflow-auto"
                                    :style="{ height: getCurrentScreenHeight }"
                                >
                                    <v-locale-switcher></v-locale-switcher>
                                </div>
                            </x-slot>
                        </x-shop::drawer>
                    </div>
                @endif
            </x-slot>
        </x-shop::drawer>
    </script>

    <script
        type="text/x-template"
        id="v-mobile-category-template"
    >
        <!-- Wrapper with transition effects -->
        <div class="relative h-full overflow-hidden">
            <!-- Sliding container -->
            <div
                class="flex h-full transition-transform duration-300"
                :class="{
                    'ltr:translate-x-0 rtl:translate-x-0': currentViewLevel !== 'third',
                    'ltr:-translate-x-full rtl:translate-x-full': currentViewLevel === 'third'
                }"
            >
                <!-- First level view -->
                <div class="flex-shrink-0 w-full h-full overflow-auto">
                    <div
                        v-for="category in categories"
                        :key="category.id"
                        class="vn-dw-cat-l1"
                    >
                        <a :href="category.url" class="vn-dw-cat-l1__link">
                            @{{ category.name }}
                        </a>

                        <!-- Second Level Categories -->
                        <div v-if="category.children && category.children.length" class="vn-dw-cat-l2">
                            <div
                                v-for="secondLevelCategory in category.children"
                                :key="secondLevelCategory.id"
                                class="vn-dw-cat-l2__row"
                                @click="showThirdLevel(secondLevelCategory, category, $event)"
                            >
                                <a :href="secondLevelCategory.url">
                                    @{{ secondLevelCategory.name }}
                                </a>

                                <span
                                    v-if="secondLevelCategory.children && secondLevelCategory.children.length"
                                    class="icon-arrow-right rtl:icon-arrow-left vn-dw-cat-l2__chevron"
                                ></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Third level view -->
                <div
                    class="flex-shrink-0 w-full h-full"
                    v-if="currentViewLevel === 'third'"
                >
                    <button
                        @click="goBackToMainView"
                        class="vn-dw-back"
                        aria-label="Go back"
                    >
                        <span class="icon-arrow-left rtl:icon-arrow-right vn-dw-back__arrow"></span>
                        <span class="vn-dw-back__label">
                            @lang('shop::app.components.layouts.header.mobile.back-button')
                        </span>
                    </button>

                    <!-- Third Level Content -->
                    <div class="vn-dw-cat-l3">
                        <a
                            v-for="thirdLevelCategory in currentSecondLevelCategory?.children"
                            :key="thirdLevelCategory.id"
                            :href="thirdLevelCategory.url"
                            class="vn-dw-cat-l3__link"
                        >
                            @{{ thirdLevelCategory.name }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-mobile-category', {
            template: '#v-mobile-category-template',

            data() {
                return  {
                    categories: [],
                    currentViewLevel: 'main',
                    currentSecondLevelCategory: null,
                    currentParentCategory: null
                }
            },

            mounted() {
                this.initCategories();
            },

            computed: {
                getCurrentScreenHeight() {
                    return window.innerHeight - (window.innerWidth < 920 ? 61 : 0) + 'px';
                },
            },

            methods: {
                initCategories() {
                    try {
                        const stored = localStorage.getItem('categories');

                        if (stored) {
                            this.categories = JSON.parse(stored);
                            this.isLoading = false;
                            return;
                        }

                    } catch (e) {}

                    this.getCategories();
                },
                getCategories() {
                    this.$axios.get("{{ route('shop.api.categories.tree') }}")
                        .then(response => {
                            this.categories = response.data.data;
                            localStorage.setItem('categories', JSON.stringify(this.categories));
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },

                showThirdLevel(secondLevelCategory, parentCategory, event) {
                    if (secondLevelCategory.children && secondLevelCategory.children.length) {
                        this.currentSecondLevelCategory = secondLevelCategory;
                        this.currentParentCategory = parentCategory;
                        this.currentViewLevel = 'third';

                        if (event) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                    }
                },

                goBackToMainView() {
                    this.currentViewLevel = 'main';
                }
            },
        });

        app.component('v-mobile-drawer', {
            template: '#v-mobile-drawer-template',

            methods: {
                onDrawerClose() {
                    this.$refs.mobileCategory.currentViewLevel = 'main';
                }
            },
        });
    </script>
@endPushOnce
