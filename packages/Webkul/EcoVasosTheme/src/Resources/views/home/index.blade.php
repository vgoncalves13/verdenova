@php
    $channel = core()->getCurrentChannel();
@endphp

<!-- SEO Meta Content -->
@push('meta')
    <meta name="title"       content="{{ $channel->home_seo['meta_title'] ?? '' }}" />
    <meta name="description" content="{{ $channel->home_seo['meta_description'] ?? '' }}" />
    <meta name="keywords"    content="{{ $channel->home_seo['meta_keywords'] ?? '' }}" />
@endPush

@push('styles')
<style>
/* ══════════════════════════════════════════════════════════════
   Verde Nova · Homepage
   Aesthetic: Botanical Editorial · Luxury Natural
══════════════════════════════════════════════════════════════ */
:root {
    --vn-forest:   #014d24;
    --vn-leaf:     #016630;
    --vn-sage:     #52b788;
    --vn-mint:     #b7e4c7;
    --vn-cream:    #f4f1ea;
    --vn-sand:     #e8dcc8;
    --vn-gold:     #fac800;
    --vn-gold-dk:  #d4a800;
    --vn-charcoal: #131a0f;
    --vn-text:     #1c2214;
    --vn-muted:    #6b7264;
}

/* Força fundo da página a ser creme — sem lacunas brancas */
#app, main#main { background: var(--vn-cream) !important; }

/* ── Layout helpers ──────────────────────────────────────── */
/* Seções full-width: o bg vai no <section>, conteúdo no .vn-wrap */
.vn-wrap {
    max-width: 1440px;
    margin: 0 auto;
    padding: 88px 60px;
}
@media (max-width: 1024px) { .vn-wrap { padding: 64px 32px; } }
@media (max-width: 640px)  { .vn-wrap { padding: 48px 20px; } }

.vn-section-hd {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 48px;
}
.vn-eyebrow {
    font-size: 10.5px;
    font-weight: 800;
    letter-spacing: .16em;
    text-transform: uppercase;
    color: var(--vn-sage);
    margin-bottom: 7px;
}
.vn-section-title {
    font-family: 'DM Serif Display', serif;
    font-size: clamp(28px, 3vw, 44px);
    color: var(--vn-text);
    line-height: 1.13;
}
.vn-see-all {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: var(--vn-leaf);
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    border-bottom: 1px solid rgba(82,183,136,.45);
    padding-bottom: 1px;
    white-space: nowrap;
    flex-shrink: 0;
    transition: gap .2s, color .2s;
}
.vn-see-all:hover { color: var(--vn-forest); gap: 10px; }

/* ── Buttons ─────────────────────────────────────────────── */
.vn-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--vn-gold);
    color: var(--vn-charcoal);
    font-size: 14px;
    font-weight: 700;
    padding: 15px 32px;
    border-radius: 100px;
    text-decoration: none;
    transition: all .22s;
    box-shadow: 0 8px 28px rgba(250,200,0,.28);
}
.vn-btn-primary:hover {
    background: var(--vn-gold-dk);
    transform: translateY(-2px);
    box-shadow: 0 14px 36px rgba(250,200,0,.38);
}
.vn-btn-ghost {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: rgba(255,255,255,.75);
    font-size: 14px;
    font-weight: 500;
    padding: 15px 26px;
    border-radius: 100px;
    border: 1px solid rgba(255,255,255,.22);
    text-decoration: none;
    transition: all .22s;
}
.vn-btn-ghost:hover {
    color: #fff;
    border-color: rgba(255,255,255,.45);
    background: rgba(255,255,255,.07);
}


/* ══════════════════════════════════════════════════════════
   HERO
══════════════════════════════════════════════════════════ */
.vn-hero {
    position: relative;
    background: var(--vn-forest);
    min-height: 100vh;
    display: flex;
    align-items: center;
    overflow: hidden;
}
.vn-hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.035'/%3E%3C/svg%3E");
    pointer-events: none;
    z-index: 0;
}
.vn-hero__glow {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}
.vn-hero__glow--1 {
    width: 700px; height: 700px; top: -200px; right: -150px;
    background: radial-gradient(circle, rgba(82,183,136,.14) 0%, transparent 65%);
    animation: vn-float 9s ease-in-out infinite;
}
.vn-hero__glow--2 {
    width: 450px; height: 450px; bottom: -120px; left: -60px;
    background: radial-gradient(circle, rgba(250,200,0,.07) 0%, transparent 65%);
    animation: vn-float 12s ease-in-out infinite 3s;
}
@keyframes vn-float {
    0%, 100% { transform: translateY(0) scale(1); }
    50%       { transform: translateY(-22px) scale(1.03); }
}
.vn-leaf-particle {
    position: absolute;
    opacity: 0;
    animation: vn-leaf linear infinite;
    pointer-events: none;
}
@keyframes vn-leaf {
    0%  { opacity: 0;   transform: translateY(-30px) rotate(0deg) scale(.6); }
    8%  { opacity: .35; }
    92% { opacity: .12; }
    100%{ opacity: 0;   transform: translateY(90vh)  rotate(520deg) scale(1); }
}
.vn-hero__inner {
    position: relative;
    z-index: 1;
    width: 100%;
}
.vn-hero__grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 72px;
    align-items: center;
    padding: 120px 60px 140px;
    max-width: 1440px;
    margin: 0 auto;
}
@media (max-width: 1024px) {
    .vn-hero__grid { grid-template-columns: 1fr; gap: 48px; padding: 80px 32px 120px; }
    .vn-hero__visual { display: none; }
}
@media (max-width: 640px) { .vn-hero__grid { padding: 60px 20px 100px; } }

.vn-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(250,200,0,.12);
    border: 1px solid rgba(250,200,0,.35);
    color: var(--vn-gold);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    padding: 6px 16px;
    border-radius: 100px;
    margin-bottom: 28px;
    animation: vn-fadeup .55s ease both;
}
@keyframes vn-fadeup {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
}
.vn-hero__title {
    font-family: 'DM Serif Display', serif;
    font-size: clamp(54px, 6.5vw, 94px);
    line-height: 1.03;
    color: #fff;
    margin-bottom: 22px;
    animation: vn-fadeup .55s ease .1s both;
}
.vn-hero__title em { font-style: italic; color: var(--vn-sage); }
.vn-hero__subtitle {
    font-size: 17px;
    color: rgba(255,255,255,.6);
    line-height: 1.72;
    max-width: 440px;
    margin-bottom: 40px;
    animation: vn-fadeup .55s ease .18s both;
}
.vn-hero__actions {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 56px;
    animation: vn-fadeup .55s ease .26s both;
}
.vn-hero__stats {
    display: flex;
    gap: 40px;
    animation: vn-fadeup .55s ease .34s both;
}
.vn-stat__num {
    display: block;
    font-family: 'DM Serif Display', serif;
    font-size: 28px;
    color: #fff;
    line-height: 1;
}
.vn-stat__lbl {
    display: block;
    font-size: 11px;
    font-weight: 500;
    color: rgba(255,255,255,.45);
    letter-spacing: .06em;
    text-transform: uppercase;
    margin-top: 4px;
}

/* Hero visual */
.vn-hero__visual {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: vn-fadeup .7s ease .15s both;
}
.vn-botanical-ring { position: relative; width: 480px; height: 480px; }
.vn-ring {
    position: absolute;
    border-radius: 50%;
    border: 1px solid rgba(82,183,136,.25);
}
.vn-ring--1 { inset: 0;    animation: vn-spin 35s linear infinite; }
.vn-ring--2 { inset: 28px; animation: vn-spin 22s linear infinite reverse; border-style: dashed; border-color: rgba(250,200,0,.18); }
.vn-ring--3 { inset: 56px; animation: vn-spin 50s linear infinite; border-color: rgba(255,255,255,.08); }
@keyframes vn-spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.vn-ring__center {
    position: absolute;
    inset: 84px;
    border-radius: 50%;
    background: radial-gradient(circle at 38% 35%, rgba(82,183,136,.22), rgba(1,70,36,.85));
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    box-shadow: inset 0 0 60px rgba(0,0,0,.3);
}
.vn-fcard {
    position: absolute;
    background: rgba(255,255,255,.09);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border: 1px solid rgba(255,255,255,.18);
    border-radius: 14px;
    padding: 13px 18px;
}
.vn-fcard--top { top: 36px;    right: -24px; animation: vn-float 6s ease-in-out infinite; }
.vn-fcard--bot { bottom: 72px; left: -32px;  animation: vn-float 8s ease-in-out infinite 2.5s; }
.vn-fcard__lbl { font-size: 10px; color: rgba(255,255,255,.5); letter-spacing: .08em; text-transform: uppercase; margin-bottom: 3px; }
.vn-fcard__val { font-size: 14px; font-weight: 700; color: var(--vn-gold); }
.vn-hero__wave { position: absolute; bottom: -1px; left: 0; right: 0; z-index: 2; }

/* ══════════════════════════════════════════════════════════
   TRUST BAR
══════════════════════════════════════════════════════════ */
.vn-trust { background: var(--vn-charcoal); }
.vn-trust__row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 56px;
    flex-wrap: wrap;
    max-width: 1440px;
    margin: 0 auto;
    padding: 15px 60px;
}
.vn-trust__item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: rgba(255,255,255,.7);
    font-size: 12.5px;
    font-weight: 500;
    letter-spacing: .02em;
    white-space: nowrap;
}
.vn-trust__icon {
    width: 30px; height: 30px;
    border-radius: 50%;
    background: rgba(82,183,136,.18);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
@media (max-width: 640px) {
    .vn-trust__row { padding: 14px 16px; gap: 24px; }
}

/* ══════════════════════════════════════════════════════════
   MARQUEE
══════════════════════════════════════════════════════════ */
.vn-marquee { background: var(--vn-gold); overflow: hidden; white-space: nowrap; }
.vn-marquee__track {
    display: inline-flex;
    animation: vn-marquee 28s linear infinite;
}
@keyframes vn-marquee { from { transform: translateX(0); } to { transform: translateX(-50%); } }
.vn-marquee__item {
    display: inline-flex;
    align-items: center;
    gap: 14px;
    padding: 12px 28px;
    font-size: 11.5px;
    font-weight: 800;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--vn-charcoal);
}
.vn-marquee__dot { width: 5px; height: 5px; border-radius: 50%; background: rgba(1,70,36,.35); flex-shrink: 0; }

/* ══════════════════════════════════════════════════════════
   CAROUSELS WRAPPER (admin)
   — fundo branco separado do cream para destaque
══════════════════════════════════════════════════════════ */
.vn-carousels { background: #fff; overflow: hidden; }

/* ══════════════════════════════════════════════════════════
   CATEGORY GRID
══════════════════════════════════════════════════════════ */
.vn-cat-section { background: #fff; }

.vn-cat-grid {
    display: grid;
    grid-template-columns: 1.45fr 1fr;
    grid-template-rows: 290px 290px;
    gap: 14px;
}
@media (max-width: 768px) {
    .vn-cat-grid { grid-template-columns: 1fr; grid-template-rows: auto; }
    .vn-cat-card--main { grid-row: auto; min-height: 300px; }
    .vn-cat-card { min-height: 220px; }
}

.vn-cat-card {
    position: relative;
    border-radius: 18px;
    overflow: hidden;
    display: block;
    text-decoration: none;
    cursor: pointer;
}
.vn-cat-card--main { grid-row: 1 / 3; }
.vn-cat-bg {
    position: absolute;
    inset: 0;
    transition: transform .65s cubic-bezier(.25,.46,.45,.94);
}
.vn-cat-card:hover .vn-cat-bg { transform: scale(1.06); }
.vn-cat-card--a .vn-cat-bg { background: linear-gradient(148deg, #013d1b 0%, #016630 55%, #52b788 100%); }
.vn-cat-card--b .vn-cat-bg { background: linear-gradient(148deg, #1a3a10 0%, #2d5a1e 100%); }
.vn-cat-card--c .vn-cat-bg { background: linear-gradient(148deg, #0d3021 0%, #1a5238 100%); }
.vn-cat-noise {
    position: absolute; inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.06'/%3E%3C/svg%3E");
}
.vn-cat-scrim {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.72) 0%, rgba(0,0,0,.08) 55%, transparent 100%);
}
.vn-cat-body {
    position: absolute; bottom: 0; left: 0; right: 0; padding: 28px; color: #fff;
}
.vn-cat-tag {
    display: inline-block;
    background: rgba(255,255,255,.13);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.2);
    color: rgba(255,255,255,.88);
    font-size: 10px; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase;
    padding: 4px 12px; border-radius: 100px; margin-bottom: 10px;
}
.vn-cat-name {
    font-family: 'DM Serif Display', serif;
    font-size: clamp(22px, 2.5vw, 36px);
    line-height: 1.08; margin-bottom: 10px;
}
.vn-cat-card--b .vn-cat-name,
.vn-cat-card--c .vn-cat-name { font-size: 22px; }
.vn-cat-arrow {
    display: inline-flex; align-items: center; gap: 5px;
    color: var(--vn-gold); font-size: 12.5px; font-weight: 700;
    opacity: 0; transform: translateY(8px); transition: all .28s;
}
.vn-cat-card:hover .vn-cat-arrow { opacity: 1; transform: translateY(0); }

/* ══════════════════════════════════════════════════════════
   COMO FUNCIONA
══════════════════════════════════════════════════════════ */
.vn-how { background: #fff; }

.vn-steps {
    display: grid;
    grid-template-columns: 1fr auto 1fr auto 1fr;
    align-items: start;
}
@media (max-width: 768px) {
    .vn-steps { grid-template-columns: 1fr; gap: 24px; }
    .vn-step-arrow { display: none; }
}
.vn-step {
    background: var(--vn-cream);
    border-radius: 20px;
    padding: 36px 28px;
    border: 1px solid rgba(82,183,136,.14);
    transition: transform .25s, box-shadow .25s;
}
.vn-step:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 48px rgba(1,102,48,.1);
}
.vn-step__num {
    font-family: 'DM Serif Display', serif;
    font-size: 48px; color: rgba(82,183,136,.22); line-height: 1; margin-bottom: 10px;
}
.vn-step__icon { font-size: 30px; margin-bottom: 14px; }
.vn-step__title { font-size: 16px; font-weight: 700; color: var(--vn-text); margin-bottom: 10px; }
.vn-step__text  { font-size: 13.5px; color: var(--vn-muted); line-height: 1.68; }
.vn-step-arrow {
    display: flex; align-items: center; justify-content: center;
    padding: 0 20px; margin-top: 56px;
    color: rgba(82,183,136,.4); font-size: 26px;
}

/* ══════════════════════════════════════════════════════════
   POR QUE VERDE NOVA
══════════════════════════════════════════════════════════ */
.vn-why {
    background: var(--vn-forest);
    position: relative;
    overflow: hidden;
}
.vn-why::before {
    content: '';
    position: absolute; top: -80px; right: -80px;
    width: 380px; height: 380px; border-radius: 50%;
    background: radial-gradient(circle, rgba(82,183,136,.1), transparent 65%);
    pointer-events: none;
}
.vn-why .vn-eyebrow     { color: var(--vn-sage); }
.vn-why .vn-section-title { color: #fff; }

.vn-features {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}
@media (max-width: 1024px) { .vn-features { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 640px)  { .vn-features { grid-template-columns: 1fr; } }

.vn-feature {
    background: rgba(255,255,255,.05);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 18px;
    padding: 28px 24px;
    transition: background .25s, transform .25s;
}
.vn-feature:hover {
    background: rgba(255,255,255,.09);
    transform: translateY(-3px);
}
.vn-feature__icon {
    width: 48px; height: 48px; border-radius: 12px;
    background: linear-gradient(135deg, rgba(82,183,136,.35), rgba(1,102,48,.5));
    border: 1px solid rgba(82,183,136,.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; margin-bottom: 16px;
}
.vn-feature__title { font-size: 15px; font-weight: 700; color: #fff; margin-bottom: 8px; }
.vn-feature__text  { font-size: 13px; color: rgba(255,255,255,.55); line-height: 1.68; }

/* ══════════════════════════════════════════════════════════
   DEPOIMENTOS
══════════════════════════════════════════════════════════ */
.vn-testimonials { background: #fff; }

.vn-reviews {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
@media (max-width: 1024px) { .vn-reviews { grid-template-columns: 1fr 1fr; } }
@media (max-width: 640px)  { .vn-reviews { grid-template-columns: 1fr; } }

.vn-review {
    background: #fff;
    border-radius: 18px;
    padding: 28px;
    border: 1px solid rgba(82,183,136,.12);
    transition: transform .25s, box-shadow .25s;
    display: flex;
    flex-direction: column;
}
.vn-review:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 40px rgba(1,102,48,.08);
}
.vn-review__stars { color: var(--vn-gold); font-size: 14px; letter-spacing: 2px; margin-bottom: 14px; }
.vn-review__text  {
    font-size: 14px; color: #555; line-height: 1.72;
    margin-bottom: 20px; font-style: italic; flex: 1;
}
.vn-review__author { display: flex; align-items: center; gap: 12px; }
.vn-review__avatar {
    width: 38px; height: 38px; border-radius: 50%;
    background: linear-gradient(135deg, var(--vn-leaf), var(--vn-sage));
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700; color: #fff; flex-shrink: 0;
}
.vn-review__name     { font-size: 13px; font-weight: 700; color: var(--vn-text); }
.vn-review__location { font-size: 11.5px; color: #999; margin-top: 1px; }

/* ══════════════════════════════════════════════════════════
   CTA BANNER
══════════════════════════════════════════════════════════ */
.vn-cta {
    position: relative;
    background: var(--vn-charcoal);
    overflow: hidden;
    text-align: center;
}
.vn-cta::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 50% -20%, rgba(82,183,136,.18) 0%, transparent 55%);
}
.vn-cta__inner {
    position: relative; z-index: 1;
    max-width: 540px; margin: 0 auto;
    padding: 96px 60px;
}
.vn-cta__title {
    font-family: 'DM Serif Display', serif;
    font-size: clamp(30px, 4vw, 50px);
    color: #fff; line-height: 1.12; margin-bottom: 14px;
}
.vn-cta__title em { color: var(--vn-sage); font-style: italic; }
.vn-cta__sub {
    color: rgba(255,255,255,.55); font-size: 15.5px;
    line-height: 1.65; margin-bottom: 36px;
}
@media (max-width: 640px) { .vn-cta__inner { padding: 64px 20px; } }
</style>
@endPush

@push('scripts')
    @if(! empty($categories))
        <script>
            localStorage.setItem('categories', JSON.stringify(@json($categories)));
        </script>
    @endif
@endpush

<x-shop::layouts>
    <x-slot:title>
        {{ $channel->home_seo['meta_title'] ?? '' }}
    </x-slot>

    {{-- ══════════════════════════════════════════════════════
         HERO
    ══════════════════════════════════════════════════════ --}}
    <section class="vn-hero">
        <div class="vn-hero__glow vn-hero__glow--1"></div>
        <div class="vn-hero__glow vn-hero__glow--2"></div>

        @for ($i = 0; $i < 9; $i++)
            <svg class="vn-leaf-particle"
                 style="left:{{ $i * 11 + 2 }}%;animation-delay:{{ $i * 1.3 }}s;animation-duration:{{ 11 + $i * 1.1 }}s;"
                 width="18" height="18" viewBox="0 0 24 24" fill="none">
                <path d="M12 2C12 2 4 6 4 13a8 8 0 0 0 16 0C20 6 12 2 12 2z" fill="rgba(82,183,136,0.4)"/>
            </svg>
        @endfor

        <div class="vn-hero__inner">
            <div class="vn-hero__grid">

                <div>
                    <span class="vn-badge">🌿 Produtos Ecológicos &amp; Sustentáveis</span>

                    <h1 class="vn-hero__title">
                        Natureza<br>
                        <em>em cada</em><br>
                        detalhe.
                    </h1>

                    <p class="vn-hero__subtitle">
                        Vasos artesanais, plantas selecionadas e produtos
                        ecológicos que transformam seu espaço e respeitam o planeta.
                    </p>

                    <div class="vn-hero__actions">
                        <a href="{{ route('shop.search.index', ['query' => '']) }}" class="vn-btn-primary">
                            Explorar Coleção &nbsp;→
                        </a>
                        <a href="#vn-why" class="vn-btn-ghost">Nossa Filosofia</a>
                    </div>

                    <div class="vn-hero__stats">
                        <div>
                            <span class="vn-stat__num">500+</span>
                            <span class="vn-stat__lbl">Produtos</span>
                        </div>
                        <div>
                            <span class="vn-stat__num">100%</span>
                            <span class="vn-stat__lbl">Sustentável</span>
                        </div>
                        <div>
                            <span class="vn-stat__num">4.9 ★</span>
                            <span class="vn-stat__lbl">Avaliação</span>
                        </div>
                    </div>
                </div>

                <div class="vn-hero__visual">
                    <div class="vn-botanical-ring">
                        <div class="vn-ring vn-ring--1"></div>
                        <div class="vn-ring vn-ring--2"></div>
                        <div class="vn-ring vn-ring--3"></div>
                        <div class="vn-ring__center">
                            <svg viewBox="0 0 300 320" fill="none" style="width:88%;opacity:.65;">
                                <ellipse cx="150" cy="295" rx="62" ry="9" fill="rgba(0,0,0,.25)"/>
                                <path d="M112 240 L118 286 Q150 296 182 286 L188 240Z" fill="rgba(201,168,76,.55)" stroke="rgba(201,168,76,.8)" stroke-width="1.5"/>
                                <rect x="105" y="234" width="90" height="11" rx="5" fill="rgba(201,168,76,.65)" stroke="rgba(201,168,76,.85)" stroke-width="1.2"/>
                                <ellipse cx="150" cy="234" rx="40" ry="6" fill="rgba(60,30,10,.35)"/>
                                <path d="M150 232 L150 185 Q147 162 156 138 Q162 118 150 100" stroke="rgba(82,183,136,.85)" stroke-width="3.5" fill="none" stroke-linecap="round"/>
                                <path d="M150 200 Q122 183 104 155" stroke="rgba(82,183,136,.7)" stroke-width="2.8" fill="none" stroke-linecap="round"/>
                                <path d="M150 168 Q178 152 200 124" stroke="rgba(82,183,136,.7)" stroke-width="2.8" fill="none" stroke-linecap="round"/>
                                <path d="M150 100 Q110 64 92 38 Q116 46 138 76 Q145 88 150 100Z" fill="rgba(82,183,136,.6)" stroke="rgba(52,160,100,.4)" stroke-width="1"/>
                                <path d="M150 100 Q190 60 210 32 Q186 42 162 72 Q156 86 150 100Z" fill="rgba(82,183,136,.5)" stroke="rgba(52,160,100,.4)" stroke-width="1"/>
                                <path d="M104 155 Q68 138 48 110 Q76 122 100 148Z" fill="rgba(82,183,136,.55)" stroke="rgba(52,160,100,.35)" stroke-width="1"/>
                                <path d="M200 124 Q236 104 256 76 Q228 92 202 118Z" fill="rgba(82,183,136,.55)" stroke="rgba(52,160,100,.35)" stroke-width="1"/>
                                <circle cx="92"  cy="38"  r="4.5" fill="rgba(250,200,0,.65)"/>
                                <circle cx="210" cy="32"  r="4.5" fill="rgba(250,200,0,.65)"/>
                                <circle cx="48"  cy="110" r="3.5" fill="rgba(250,200,0,.5)"/>
                                <circle cx="256" cy="76"  r="3.5" fill="rgba(250,200,0,.5)"/>
                            </svg>
                        </div>
                        <div class="vn-fcard vn-fcard--top">
                            <div class="vn-fcard__lbl">Mais Vendido</div>
                            <div class="vn-fcard__val">Vaso Terra Viva</div>
                        </div>
                        <div class="vn-fcard vn-fcard--bot">
                            <div class="vn-fcard__lbl">Satisfação</div>
                            <div class="vn-fcard__val">★ 4.9 / 5.0</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="vn-hero__wave">
            <svg viewBox="0 0 1440 88" preserveAspectRatio="none" style="width:100%;height:88px;display:block;" fill="#131a0f">
                <path d="M0,44 C240,88 480,0 720,44 C960,88 1200,0 1440,44 L1440,88 L0,88 Z"/>
            </svg>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════
         TRUST BAR
    ══════════════════════════════════════════════════════ --}}
    <div class="vn-trust">
        <div class="vn-trust__row">
            <div class="vn-trust__item">
                <span class="vn-trust__icon">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="rgba(82,183,136,.9)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                </span>
                Frete Grátis acima de R$200
            </div>
            <div class="vn-trust__item">
                <span class="vn-trust__icon">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="rgba(82,183,136,.9)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </span>
                Certificado Orgânico
            </div>
            <div class="vn-trust__item">
                <span class="vn-trust__icon">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="rgba(82,183,136,.9)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                    </svg>
                </span>
                Entrega em até 5 dias úteis
            </div>
            <div class="vn-trust__item">
                <span class="vn-trust__icon">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="rgba(82,183,136,.9)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.06-4.43"/>
                    </svg>
                </span>
                Troca fácil em 30 dias
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         MARQUEE
    ══════════════════════════════════════════════════════ --}}
    <div class="vn-marquee" aria-hidden="true">
        <div class="vn-marquee__track">
            @for ($i = 0; $i < 2; $i++)
                <span class="vn-marquee__item">🌿 Verde Nova <span class="vn-marquee__dot"></span></span>
                <span class="vn-marquee__item">Sustentabilidade <span class="vn-marquee__dot"></span></span>
                <span class="vn-marquee__item">🪴 Vasos Artesanais <span class="vn-marquee__dot"></span></span>
                <span class="vn-marquee__item">Plantas Selecionadas <span class="vn-marquee__dot"></span></span>
                <span class="vn-marquee__item">♻️ Eco Friendly <span class="vn-marquee__dot"></span></span>
                <span class="vn-marquee__item">Feito com Amor <span class="vn-marquee__dot"></span></span>
                <span class="vn-marquee__item">🌱 100% Natural <span class="vn-marquee__dot"></span></span>
                <span class="vn-marquee__item">Certificado Orgânico <span class="vn-marquee__dot"></span></span>
            @endfor
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         PRODUTOS — carousels do admin (produto/categoria)
         Fundo branco separado do creme para contraste visual
    ══════════════════════════════════════════════════════ --}}
    <div class="vn-carousels">
        @foreach ($customizations as $customization)
            @php ($data = $customization->options) @endphp
            @switch ($customization->type)
                @case ($customization::CATEGORY_CAROUSEL)
                    <x-shop::categories.carousel
                        :title="$data['title'] ?? ''"
                        :src="route('shop.api.categories.index', $data['filters'] ?? [])"
                        :navigation-link="route('shop.home.index')"
                        aria-label="{{ trans('shop::app.home.index.categories-carousel') }}"
                    />
                    @break
                @case ($customization::PRODUCT_CAROUSEL)
                    <x-shop::products.carousel
                        :title="$data['title'] ?? ''"
                        :src="route('shop.api.products.index', $data['filters'] ?? [])"
                        :navigation-link="route('shop.search.index', $data['filters'] ?? [])"
                        aria-label="{{ trans('shop::app.home.index.product-carousel') }}"
                    />
                    @break
            @endswitch
        @endforeach
    </div>

    {{-- ══════════════════════════════════════════════════════
         GRID DE CATEGORIAS
    ══════════════════════════════════════════════════════ --}}
    <section class="vn-cat-section">
        <div class="vn-wrap">
            <div class="vn-section-hd">
                <div>
                    <p class="vn-eyebrow">Explorar</p>
                    <h2 class="vn-section-title">Coleções em Destaque</h2>
                </div>
                <a href="{{ route('shop.search.index', ['query' => '']) }}" class="vn-see-all">
                    Ver todas as categorias →
                </a>
            </div>

            <div class="vn-cat-grid">
                <a href="{{ route('shop.search.index', ['query' => 'vasos']) }}" class="vn-cat-card vn-cat-card--main vn-cat-card--a">
                    <div class="vn-cat-bg">
                        <svg style="position:absolute;inset:0;width:100%;height:100%;" viewBox="0 0 500 600" preserveAspectRatio="xMidYMid slice" fill="none">
                            <circle cx="420" cy="80"  r="220" fill="white" opacity=".03"/>
                            <circle cx="80"  cy="480" r="180" fill="white" opacity=".02"/>
                            <line x1="0" y1="600" x2="500" y2="0" stroke="white" stroke-width=".6" opacity=".07"/>
                        </svg>
                        <svg style="position:absolute;right:-10px;bottom:50px;width:260px;height:320px;opacity:.16;" viewBox="0 0 200 280" fill="white">
                            <path d="M100 270 Q100 200 100 160" stroke="white" stroke-width="4" fill="none" stroke-linecap="round"/>
                            <path d="M100 200 Q65 178 48 145" stroke="white" stroke-width="3" fill="none" stroke-linecap="round"/>
                            <path d="M100 165 Q138 145 158 112" stroke="white" stroke-width="3" fill="none" stroke-linecap="round"/>
                            <path d="M100 80 Q60 40 40 10 Q65 20 88 56 Q95 68 100 80Z" fill="white"/>
                            <path d="M100 80 Q142 36 164 6 Q138 18 112 54 Q106 67 100 80Z" fill="white" opacity=".8"/>
                            <path d="M48 145 Q14 128 0 100 Q24 116 44 138Z" fill="white" opacity=".7"/>
                            <path d="M158 112 Q196 90 214 62 Q188 78 160 106Z" fill="white" opacity=".7"/>
                            <circle cx="40"  cy="10"  r="5" fill="rgba(250,200,0,.7)"/>
                            <circle cx="164" cy="6"   r="5" fill="rgba(250,200,0,.7)"/>
                            <circle cx="0"   cy="100" r="4" fill="rgba(250,200,0,.5)"/>
                            <circle cx="214" cy="62"  r="4" fill="rgba(250,200,0,.5)"/>
                        </svg>
                    </div>
                    <div class="vn-cat-noise"></div>
                    <div class="vn-cat-scrim"></div>
                    <div class="vn-cat-body">
                        <span class="vn-cat-tag">✦ Destaque</span>
                        <div class="vn-cat-name">Vasos &<br>Cachepôs</div>
                        <div class="vn-cat-arrow">Explorar coleção →</div>
                    </div>
                </a>

                <a href="{{ route('shop.search.index', ['query' => 'plantas']) }}" class="vn-cat-card vn-cat-card--b">
                    <div class="vn-cat-bg">
                        <svg style="position:absolute;inset:0;width:100%;height:100%;" viewBox="0 0 400 290" preserveAspectRatio="xMidYMid slice" fill="none">
                            <circle cx="350" cy="50" r="150" fill="white" opacity=".04"/>
                        </svg>
                        <svg style="position:absolute;right:20px;bottom:44px;width:110px;opacity:.14;" viewBox="0 0 120 150" fill="white">
                            <ellipse cx="60" cy="55" rx="48" ry="50"/>
                            <rect x="44" y="100" width="32" height="44" rx="5"/>
                            <rect x="36" y="96" width="48" height="10" rx="4"/>
                        </svg>
                    </div>
                    <div class="vn-cat-noise"></div>
                    <div class="vn-cat-scrim"></div>
                    <div class="vn-cat-body" style="padding:22px;">
                        <span class="vn-cat-tag">🌱 Natural</span>
                        <div class="vn-cat-name">Plantas<br>Naturais</div>
                        <div class="vn-cat-arrow">Ver plantas →</div>
                    </div>
                </a>

                <a href="{{ route('shop.search.index', ['query' => 'eco']) }}" class="vn-cat-card vn-cat-card--c">
                    <div class="vn-cat-bg">
                        <svg style="position:absolute;inset:0;width:100%;height:100%;" viewBox="0 0 400 290" preserveAspectRatio="xMidYMid slice" fill="none">
                            <circle cx="50" cy="200" r="160" fill="white" opacity=".03"/>
                        </svg>
                        <svg style="position:absolute;right:16px;bottom:40px;width:105px;opacity:.15;" viewBox="0 0 120 150" fill="white">
                            <path d="M60 10 Q20 50 20 95 Q20 140 60 150 Q100 140 100 95 Q100 50 60 10Z"/>
                            <line x1="60" y1="150" x2="60" y2="200" stroke="white" stroke-width="3"/>
                        </svg>
                    </div>
                    <div class="vn-cat-noise"></div>
                    <div class="vn-cat-scrim"></div>
                    <div class="vn-cat-body" style="padding:22px;">
                        <span class="vn-cat-tag">♻️ Eco</span>
                        <div class="vn-cat-name">Produtos<br>Sustentáveis</div>
                        <div class="vn-cat-arrow">Explorar →</div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════
         COMO FUNCIONA
    ══════════════════════════════════════════════════════ --}}
    <section class="vn-how">
        <div class="vn-wrap">
            <div class="vn-section-hd">
                <div>
                    <p class="vn-eyebrow">Simples assim</p>
                    <h2 class="vn-section-title">Como funciona</h2>
                </div>
            </div>

            <div class="vn-steps">
                <div class="vn-step">
                    <div class="vn-step__num">01</div>
                    <div class="vn-step__icon">🔍</div>
                    <h3 class="vn-step__title">Explore a loja</h3>
                    <p class="vn-step__text">Navegue por centenas de vasos artesanais, plantas e produtos sustentáveis selecionados com cuidado.</p>
                </div>
                <div class="vn-step-arrow" aria-hidden="true">→</div>
                <div class="vn-step">
                    <div class="vn-step__num">02</div>
                    <div class="vn-step__icon">🛒</div>
                    <h3 class="vn-step__title">Adicione ao carrinho</h3>
                    <p class="vn-step__text">Escolha seus favoritos, selecione a quantidade e finalize a compra com segurança em poucos cliques.</p>
                </div>
                <div class="vn-step-arrow" aria-hidden="true">→</div>
                <div class="vn-step">
                    <div class="vn-step__num">03</div>
                    <div class="vn-step__icon">🌿</div>
                    <h3 class="vn-step__title">Receba em casa</h3>
                    <p class="vn-step__text">Seu pedido é embalado com cuidado em materiais eco-friendly e entregue rapidamente na sua porta.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════
         POR QUE VERDE NOVA
    ══════════════════════════════════════════════════════ --}}
    <section class="vn-why" id="vn-why">
        <div class="vn-wrap">
            <div class="vn-section-hd">
                <div>
                    <p class="vn-eyebrow">Nossa Filosofia</p>
                    <h2 class="vn-section-title">Por que Verde Nova?</h2>
                </div>
            </div>

            <div class="vn-features">
                <div class="vn-feature">
                    <div class="vn-feature__icon">🌿</div>
                    <h3 class="vn-feature__title">100% Sustentável</h3>
                    <p class="vn-feature__text">Todos os produtos são feitos com materiais naturais e processos que respeitam o meio ambiente desde a origem.</p>
                </div>
                <div class="vn-feature">
                    <div class="vn-feature__icon">🤝</div>
                    <h3 class="vn-feature__title">Comércio Justo</h3>
                    <p class="vn-feature__text">Trabalhamos diretamente com artesãos locais, garantindo renda justa e preservando técnicas tradicionais.</p>
                </div>
                <div class="vn-feature">
                    <div class="vn-feature__icon">📦</div>
                    <h3 class="vn-feature__title">Embalagem Verde</h3>
                    <p class="vn-feature__text">Embalagens 100% recicláveis ou biodegradáveis. Zero plástico desnecessário em todos os pedidos.</p>
                </div>
                <div class="vn-feature">
                    <div class="vn-feature__icon">🌱</div>
                    <h3 class="vn-feature__title">Plante uma Árvore</h3>
                    <p class="vn-feature__text">A cada compra plantamos uma árvore em parceria com projetos de reflorestamento no Brasil.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════
         DEPOIMENTOS
    ══════════════════════════════════════════════════════ --}}
    <section class="vn-testimonials">
        <div class="vn-wrap">
            <div class="vn-section-hd">
                <div>
                    <p class="vn-eyebrow">Clientes</p>
                    <h2 class="vn-section-title">O que dizem sobre nós</h2>
                </div>
            </div>

            <div class="vn-reviews">
                <div class="vn-review">
                    <div class="vn-review__stars">★★★★★</div>
                    <p class="vn-review__text">"Os vasos são lindos e chegaram super bem embalados. A qualidade é excepcional, nota 10 para o cuidado com o produto e com o meio ambiente!"</p>
                    <div class="vn-review__author">
                        <div class="vn-review__avatar">AN</div>
                        <div>
                            <div class="vn-review__name">Ana Nascimento</div>
                            <div class="vn-review__location">São Paulo, SP</div>
                        </div>
                    </div>
                </div>

                <div class="vn-review">
                    <div class="vn-review__stars">★★★★★</div>
                    <p class="vn-review__text">"Comprei três vasos de cerâmica e um cachepô. Qualidade incrível, entrega rápida e ainda plantaram uma árvore em meu nome. Voltarei com certeza!"</p>
                    <div class="vn-review__author">
                        <div class="vn-review__avatar">RC</div>
                        <div>
                            <div class="vn-review__name">Rafael Costa</div>
                            <div class="vn-review__location">Belo Horizonte, MG</div>
                        </div>
                    </div>
                </div>

                <div class="vn-review">
                    <div class="vn-review__stars">★★★★★</div>
                    <p class="vn-review__text">"Finalmente uma loja que une estética e consciência ambiental. As embalagens são lindas e 100% recicláveis. Meu apartamento ficou outro!"</p>
                    <div class="vn-review__author">
                        <div class="vn-review__avatar">JS</div>
                        <div>
                            <div class="vn-review__name">Juliana Santos</div>
                            <div class="vn-review__location">Rio de Janeiro, RJ</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════
         CTA BANNER
    ══════════════════════════════════════════════════════ --}}
    <div class="vn-cta">
        <div class="vn-cta__inner">
            <h2 class="vn-cta__title">
                Leve a natureza<br>
                para <em>dentro de casa.</em>
            </h2>
            <p class="vn-cta__sub">
                Descubra nossa coleção completa de vasos, plantas e produtos
                sustentáveis com entrega rápida para todo o Brasil.
            </p>
            <a href="{{ route('shop.search.index', ['query' => '']) }}" class="vn-btn-primary" style="margin:0 auto;">
                Explorar Toda a Coleção &nbsp;→
            </a>
        </div>
    </div>


</x-shop::layouts>
