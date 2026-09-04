<div class="sidebar-toggle-btn-container" x-data="{}">
    <!-- Collapse button (shown when desktop sidebar is expanded) -->
    <button
        type="button"
        x-show="!$store.sidebar || !$store.sidebar.isCollapsed"
        x-on:click="
            if ($store.sidebar) { $store.sidebar.isCollapsed = true; if ($store.sidebar.close) $store.sidebar.close(); }
            const closeBtn = document.querySelector('.fi-topbar-close-collapse-sidebar-btn');
            if (closeBtn) closeBtn.click();
        "
        title="Susutkan Sidebar"
        class="custom-sidebar-toggle-btn"
    >
        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>

    <!-- Expand button (shown when desktop sidebar is collapsed) -->
    <button
        type="button"
        x-show="$store.sidebar && $store.sidebar.isCollapsed"
        x-on:click="
            if ($store.sidebar) { $store.sidebar.isCollapsed = false; if ($store.sidebar.open) $store.sidebar.open(); }
            const openBtn = document.querySelector('.fi-topbar-open-collapse-sidebar-btn');
            if (openBtn) openBtn.click();
        "
        title="Buka Sidebar"
        class="custom-sidebar-toggle-btn"
    >
        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
        </svg>
    </button>
</div>
