<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.reviews.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="reviews" />
        @endSection
    @endif

    @push('styles')
    <style>
        .eco-reviews-wrap {
            flex: 1;
            min-width: 0;
            font-family: 'Poppins', sans-serif;
            padding-bottom: 3rem;
        }

        .eco-reviews-header {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 1.75rem;
        }
        .eco-reviews-back {
            display: none;
            color: #374151;
            font-size: 1.4rem;
            text-decoration: none;
        }
        @media (max-width: 768px) { .eco-reviews-back { display: flex; } }

        .eco-reviews-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.6rem;
            color: #012b17;
            margin: 0;
        }

        /* Review card */
        .eco-review-card {
            display: flex;
            gap: 1.25rem;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 1.25rem 1.4rem;
            text-decoration: none;
            transition: box-shadow .15s, border-color .15s;
            margin-bottom: .75rem;
        }
        .eco-review-card:last-child { margin-bottom: 0; }
        .eco-review-card:hover {
            border-color: #d1d5db;
            box-shadow: 0 4px 12px rgba(0,0,0,.06);
        }

        .eco-review-card-img {
            width: 90px;
            height: 90px;
            min-width: 90px;
            border-radius: 10px;
            object-fit: cover;
        }
        @media (max-width: 640px) {
            .eco-review-card-img { width: 64px; height: 64px; min-width: 64px; }
        }

        .eco-review-card-body {
            flex: 1;
            min-width: 0;
        }

        .eco-review-card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: .75rem;
            margin-bottom: .35rem;
        }

        .eco-review-card-title {
            font-size: .92rem;
            font-weight: 600;
            color: #111827;
            margin: 0;
            line-height: 1.4;
        }

        /* Stars */
        .eco-review-stars {
            display: flex;
            align-items: center;
            gap: 1px;
            flex-shrink: 0;
        }
        .eco-review-star-fill { color: #f59e0b; font-size: 1.1rem; }
        .eco-review-star-empty { color: #d1d5db; font-size: 1.1rem; }

        .eco-review-card-date {
            font-size: .72rem;
            color: #9ca3af;
            margin-bottom: .5rem;
        }

        .eco-review-card-comment {
            font-size: .82rem;
            color: #6b7280;
            line-height: 1.65;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Empty state */
        .eco-reviews-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 5rem 2rem;
            text-align: center;
            gap: 1rem;
        }
        .eco-reviews-empty img { width: 120px; opacity: .6; }
        .eco-reviews-empty p {
            font-size: .95rem;
            color: #9ca3af;
        }
    </style>
    @endpush

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="eco-reviews-wrap mx-4 max-md:mx-6 max-sm:mx-4">

        <!-- Wishlist Vue Component -->
        <v-product-reviews>
            <!-- Shimmer -->
            <x-shop::shimmer.customers.account.reviews :count="4" />
        </v-product-reviews>

    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-product-reviews-template"
        >
            <div>
                <template v-if="isLoading">
                    <x-shop::shimmer.customers.account.reviews :count="4" />
                </template>

                {!! view_render_event('bagisto.shop.customers.account.reviews.list.before', ['reviews' => $reviews]) !!}

                <template v-else>

                    <!-- Header -->
                    <div class="eco-reviews-header">
                        <a class="eco-reviews-back" href="{{ route('shop.customers.account.index') }}">
                            <span class="icon-arrow-left rtl:icon-arrow-right"></span>
                        </a>
                        <h2 class="eco-reviews-title">
                            @lang('shop::app.customers.account.reviews.title')
                        </h2>
                    </div>

                    @if (! $reviews->isEmpty())
                        <div>
                            @foreach($reviews as $review)
                                <a
                                    class="eco-review-card"
                                    href="{{ route('shop.product_or_category.index', $review->product->url_key) }}"
                                    id="{{ $review->product_id }}"
                                    aria-label="{{ $review->title }}"
                                >
                                    {!! view_render_event('bagisto.shop.customers.account.reviews.image.before', ['reviews' => $reviews]) !!}

                                    <x-shop::media.images.lazy
                                        class="eco-review-card-img"
                                        src="{{ $review->product->base_image_url ?? bagisto_asset('images/small-product-placeholder.webp') }}"
                                        alt="Review Image"
                                    />

                                    {!! view_render_event('bagisto.shop.customers.account.reviews.image.after', ['reviews' => $reviews]) !!}

                                    <div class="eco-review-card-body">
                                        <div class="eco-review-card-top">
                                            {!! view_render_event('bagisto.shop.customers.account.reviews.title.before', ['reviews' => $reviews]) !!}

                                            <p class="eco-review-card-title" v-pre>{{ $review->title }}</p>

                                            {!! view_render_event('bagisto.shop.customers.account.reviews.title.after', ['reviews' => $reviews]) !!}

                                            {!! view_render_event('bagisto.shop.customers.account.reviews.rating.before', ['reviews' => $reviews]) !!}

                                            <div class="eco-review-stars">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span class="icon-star-fill {{ $review->rating >= $i ? 'eco-review-star-fill' : 'eco-review-star-empty' }}"></span>
                                                @endfor
                                            </div>

                                            {!! view_render_event('bagisto.shop.customers.account.reviews.rating.after', ['reviews' => $reviews]) !!}
                                        </div>

                                        {!! view_render_event('bagisto.shop.customers.account.reviews.created_at.before', ['reviews' => $reviews]) !!}

                                        <p class="eco-review-card-date" v-pre>{{ $review->created_at }}</p>

                                        {!! view_render_event('bagisto.shop.customers.account.reviews.created_at.after', ['reviews' => $reviews]) !!}

                                        {!! view_render_event('bagisto.shop.customers.account.reviews.comment.before', ['reviews' => $reviews]) !!}

                                        <p class="eco-review-card-comment" v-pre>{{ $review->comment }}</p>

                                        {!! view_render_event('bagisto.shop.customers.account.reviews.comment.after', ['reviews' => $reviews]) !!}
                                    </div>
                                </a>
                            @endforeach

                            <!-- Pagination -->
                            {{ $reviews->links() }}
                        </div>
                    @else
                        <div class="eco-reviews-empty">
                            <img
                                src="{{ bagisto_asset('images/review.png') }}"
                                alt="@lang('shop::app.customers.account.reviews.empty-review')"
                            >
                            <p>@lang('shop::app.customers.account.reviews.empty-review')</p>
                        </div>
                    @endif

                </template>

                {!! view_render_event('bagisto.shop.customers.account.reviews.list.after', ['reviews' => $reviews]) !!}
            </div>
        </script>

        <script type="module">
            app.component("v-product-reviews", {
                template: '#v-product-reviews-template',

                data() {
                    return {
                        isLoading: true,
                    };
                },

                mounted() {
                    this.get();
                },

                methods: {
                    get() {
                        this.$axios.get("{{ route('shop.customers.account.reviews.index') }}")
                            .then(response => {
                                this.isLoading = false;
                            })
                            .catch(error => {});
                    },
                },
            });
        </script>
    @endpushOnce
</x-shop::layouts.account>
