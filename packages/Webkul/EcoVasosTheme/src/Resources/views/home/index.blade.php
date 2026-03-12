@php
    $channel = core()->getCurrentChannel();
@endphp

<!-- SEO Meta Content -->
@push ('meta')
    <meta
        name="title"
        content="{{ $channel->home_seo['meta_title'] ?? '' }}"
    />

    <meta
        name="description"
        content="{{ $channel->home_seo['meta_description'] ?? '' }}"
    />

    <meta
        name="keywords"
        content="{{ $channel->home_seo['meta_keywords'] ?? '' }}"
    />
@endPush

@push('scripts')
    @if(! empty($categories))
        <script>
            localStorage.setItem('categories', JSON.stringify(@json($categories)));
        </script>
    @endif
@endpush

<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{  $channel->home_seo['meta_title'] ?? '' }}
    </x-slot>

    {{-- ===== EcoVasos Hero Section ===== --}}
    <section class="relative bg-gradient-to-br from-green-800 via-green-700 to-emerald-600 overflow-hidden">
        <div class="container mx-auto px-6 py-24 max-md:py-16 relative z-10">
            <div class="max-w-2xl">
                <span class="inline-block bg-ecoYellow text-darkGreen text-sm font-semibold
                             px-4 py-1.5 rounded-full mb-6 uppercase tracking-wide">
                    Produtos Naturais e Sustentáveis
                </span>
                <h1 class="text-5xl font-bold text-white leading-tight mb-6
                           max-md:text-4xl max-sm:text-3xl font-poppins">
                    Cuidando da<br>
                    <span class="text-ecoYellow">Natureza</span> com Você
                </h1>
                <p class="text-white/80 text-lg mb-10 max-w-lg leading-relaxed">
                    Vasos, plantas e produtos ecológicos que transformam
                    seu espaço e respeitam o planeta.
                </p>
                <div class="flex gap-4 flex-wrap">
                    <a href="{{ route('shop.search.index', ['query' => '']) }}"
                       class="btn-cta text-base animate-pulse hover:animate-none">
                        <span class="icon-cart text-xl"></span>
                        Ver Produtos
                    </a>
                    <a href="{{ route('shop.home.index') }}#categories"
                       class="inline-flex items-center gap-2 bg-white/10 text-white
                              border-2 border-white/40 font-semibold px-8 py-3.5
                              rounded-2xl hover:bg-white/20 transition-all">
                        Saiba Mais
                    </a>
                </div>
            </div>
        </div>
        {{-- Wave divider --}}
        <div class="absolute bottom-0 left-0 right-0 leading-[0] overflow-hidden">
            <svg viewBox="0 0 1440 60" preserveAspectRatio="none" class="w-full h-[60px] fill-white">
                <path d="M0,30 C360,60 1080,0 1440,30 L1440,60 L0,60 Z"/>
            </svg>
        </div>
    </section>
    {{-- ===== Fim Hero Section ===== --}}

    <!-- Loop over the theme customization -->
    @foreach ($customizations as $customization)
        @php ($data = $customization->options) @endphp

        <!-- Static content -->
        @switch ($customization->type)
            @case ($customization::IMAGE_CAROUSEL)
                <!-- Image Carousel -->
                <x-shop::carousel
                    :options="$data"
                    aria-label="{{ trans('shop::app.home.index.image-carousel') }}"
                />

                @break
            @case ($customization::STATIC_CONTENT)
                <!-- push style -->
                @if (! empty($data['css']))
                    @push ('styles')
                        <style>
                            {{ $data['css'] }}
                        </style>
                    @endpush
                @endif

                <!-- render html -->
                @if (! empty($data['html']))
                    {!! $data['html'] !!}
                @endif

                @break
            @case ($customization::CATEGORY_CAROUSEL)
                <!-- Categories carousel -->
                <x-shop::categories.carousel
                    :title="$data['title'] ?? ''"
                    :src="route('shop.api.categories.index', $data['filters'] ?? [])"
                    :navigation-link="route('shop.home.index')"
                    aria-label="{{ trans('shop::app.home.index.categories-carousel') }}"
                />

                @break
            @case ($customization::PRODUCT_CAROUSEL)
                <!-- Product Carousel -->
                <x-shop::products.carousel
                    :title="$data['title'] ?? ''"
                    :src="route('shop.api.products.index', $data['filters'] ?? [])"
                    :navigation-link="route('shop.search.index', $data['filters'] ?? [])"
                    aria-label="{{ trans('shop::app.home.index.product-carousel') }}"
                />

                @break
        @endswitch
    @endforeach
</x-shop::layouts>
