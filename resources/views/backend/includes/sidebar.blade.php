<?php
$notifications = optional(auth()->user())->unreadNotifications;
$notifications_count = optional($notifications)->count();
$notifications_latest = optional($notifications)->take(5);
?>

<div class="sidebar sidebar-dark sidebar-fixed border-end" id="sidebar">
    <div class="sidebar-header border-bottom">
        <div class="sidebar-brand d-sm-flex justify-content-center">
            <a href="/" class="d-flex align-items-center gap-2 text-decoration-none">
                <img
                    class="sidebar-brand-full"
                    src="{{ asset('img/sohm-logo-original.jpg') }}"
                    alt="Sounds of Harmony Music Centre"
                    style="height:46px; width:auto; object-fit:contain;"
                />
                <span class="sidebar-brand-full d-none d-lg-flex flex-column lh-1 text-start">
                    <span style="font-size:0.82rem; font-weight:700; color:#fff;">SOHMC</span>
                    <span style="font-size:0.64rem; color:#D991CD;">Sounds of Harmony Music Centre</span>
                </span>
                <span class="sidebar-brand-narrow d-inline-flex align-items-center justify-content-center" style="width:34px; height:34px; border-radius:8px; background:#A6128D; color:#fff; font-size:0.68rem; font-weight:700; letter-spacing:0.04em;">SOHMC</span>
            </a>
        </div>
        <button
            class="btn-close d-lg-none"
            data-coreui-dismiss="offcanvas"
            data-coreui-theme="dark"
            type="button"
            aria-label="Close"
            onclick='coreui.Sidebar.getInstance(document.querySelector("#sidebar")).toggle()'
        ></button>
    </div>

    {{-- Dynamic Menu from Database --}}
    <x-backend.dynamic-menu location="admin-sidebar" />

    {{-- Fallback: Load menu items from menu_data.php (in case dynamic menu is empty) --}}
    @php
        $hasMenuItems = \Modules\Menu\Models\Menu::getCachedMenuData("admin-sidebar", auth()->user())->isNotEmpty();
    @endphp

    @if (! $hasMenuItems)
        <x-backend.fallback-sidebar-menu />
    @endif

    <div class="sidebar-footer border-top d-none d-md-flex">
        <button class="sidebar-toggler" data-coreui-toggle="unfoldable" type="button"></button>
    </div>
</div>
