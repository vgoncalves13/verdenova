@php
    $customer = auth()->guard('customer')->user();
@endphp

@push('styles')
<style>
    .eco-account-nav {
        min-width: 280px;
        max-width: 300px;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        font-family: 'Poppins', sans-serif;
    }

    /* Profile card */
    .eco-nav-profile {
        background: #012b17;
        border-radius: 16px;
        padding: 1.4rem 1.25rem;
        display: flex;
        align-items: center;
        gap: .9rem;
        position: relative;
        overflow: hidden;
    }
    .eco-nav-profile::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 120px; height: 120px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(76,175,80,.25) 0%, transparent 70%);
        pointer-events: none;
    }
    .eco-nav-profile-avatar {
        width: 52px; height: 52px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(76,175,80,.4);
        flex-shrink: 0;
    }
    .eco-nav-profile-name {
        font-size: .95rem;
        font-weight: 600;
        color: #fff;
        margin: 0 0 .15rem;
        line-height: 1.3;
    }
    .eco-nav-profile-email {
        font-size: .72rem;
        color: rgba(255,255,255,.5);
        margin: 0;
        word-break: break-all;
    }

    /* Nav section */
    .eco-nav-section-title {
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: #9ca3af;
        padding: 0 .5rem;
        margin-bottom: .35rem;
    }

    .eco-nav-list {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .eco-nav-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .85rem 1.1rem;
        border-bottom: 1px solid #f3f4f6;
        text-decoration: none;
        transition: background .15s;
        cursor: pointer;
    }
    .eco-nav-item:last-child { border-bottom: none; }
    .eco-nav-item:hover { background: #f9fafb; }
    .eco-nav-item.active { background: #f0fdf4; }

    .eco-nav-item-left {
        display: flex;
        align-items: center;
        gap: .75rem;
    }
    .eco-nav-item-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: #6b7280;
        flex-shrink: 0;
        transition: background .15s, color .15s;
    }
    .eco-nav-item.active .eco-nav-item-icon {
        background: #dcfce7;
        color: #008138;
    }
    .eco-nav-item-label {
        font-size: .85rem;
        font-weight: 500;
        color: #374151;
    }
    .eco-nav-item.active .eco-nav-item-label { color: #008138; font-weight: 600; }

    .eco-nav-item-arrow {
        font-size: 1rem;
        color: #d1d5db;
    }
    .eco-nav-item.active .eco-nav-item-arrow { color: #008138; }

    /* Logout button */
    .eco-nav-logout {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .85rem 1.1rem;
        border-radius: 14px;
        border: 1px solid #fee2e2;
        background: #fff;
        text-decoration: none;
        cursor: pointer;
        transition: background .15s;
        width: 100%;
    }
    .eco-nav-logout:hover { background: #fff5f5; }
    .eco-nav-logout-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        background: #fee2e2;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ef4444;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .eco-nav-logout-label {
        font-size: .85rem;
        font-weight: 500;
        color: #ef4444;
    }

    /* Bottom spacing */
    .eco-account-nav { padding-bottom: 2rem; }

    /* Mobile adjustments */
    @media (max-width: 768px) {
        .eco-account-nav {
            min-width: 100%;
            max-width: 100%;
        }
        .eco-nav-profile { border-radius: 12px; }
        .eco-nav-list { border-radius: 12px; }
    }
</style>
@endpush

<div class="eco-account-nav">

    <!-- Profile card -->
    <div class="eco-nav-profile">
        <img
            src="{{ $customer->image_url ?? bagisto_asset('images/user-placeholder.png') }}"
            class="eco-nav-profile-avatar"
            alt="Profile Image"
        >
        <div v-pre>
            <p class="eco-nav-profile-name">{{ $customer->first_name }} {{ $customer->last_name }}</p>
            <p class="eco-nav-profile-email">{{ $customer->email }}</p>
        </div>
    </div>

    <!-- Navigation items -->
    @foreach (menu()->getItems('customer') as $menuItem)
        @if ($menuItem->haveChildren())
            <div>
                <p class="eco-nav-section-title">{{ $menuItem->getName() }}</p>
                <div class="eco-nav-list">
                    @foreach ($menuItem->getChildren() as $subMenuItem)
                        <a
                            href="{{ $subMenuItem->getUrl() }}"
                            class="eco-nav-item {{ $subMenuItem->isActive() ? 'active' : '' }}"
                        >
                            <div class="eco-nav-item-left">
                                <div class="eco-nav-item-icon">
                                    <span class="{{ $subMenuItem->getIcon() }}"></span>
                                </div>
                                <span class="eco-nav-item-label">{{ $subMenuItem->getName() }}</span>
                            </div>
                            <span class="eco-nav-item-arrow icon-arrow-right"></span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach

    <!-- Logout -->
    @auth('customer')
        <x-shop::form
            method="DELETE"
            action="{{ route('shop.customer.session.destroy') }}"
            id="customerLogoutNav"
        />
        <a
            href="{{ route('shop.customer.session.destroy') }}"
            class="eco-nav-logout"
            onclick="event.preventDefault(); document.getElementById('customerLogoutNav').submit();"
        >
            <div class="eco-nav-logout-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </div>
            <span class="eco-nav-logout-label">
                @lang('shop::app.components.layouts.header.desktop.bottom.logout')
            </span>
        </a>
    @endauth

</div>
