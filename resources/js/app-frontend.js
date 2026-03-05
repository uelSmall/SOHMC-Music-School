import './bootstrap';
import 'flowbite';

let toastContainer = null;

function ensureToastContainer() {
    if (toastContainer) {
        return toastContainer;
    }

    toastContainer = document.createElement('div');
    toastContainer.id = 'soh-toast-container';
    toastContainer.style.position = 'fixed';
    toastContainer.style.top = '1rem';
    toastContainer.style.right = '1rem';
    toastContainer.style.zIndex = '9999';
    toastContainer.style.display = 'flex';
    toastContainer.style.flexDirection = 'column';
    toastContainer.style.gap = '0.5rem';

    document.body.appendChild(toastContainer);

    return toastContainer;
}

function showToast(message, type = 'success') {
    if (!message) {
        return;
    }

    const container = ensureToastContainer();
    const toast = document.createElement('div');
    const isError = type === 'error';

    toast.textContent = message;
    toast.style.minWidth = '260px';
    toast.style.maxWidth = '420px';
    toast.style.padding = '0.75rem 0.95rem';
    toast.style.borderRadius = '0.75rem';
    toast.style.border = isError
        ? '1px solid rgba(220, 38, 38, 0.35)'
        : '1px solid rgba(166, 18, 141, 0.25)';
    toast.style.background = isError ? '#FEF2F2' : '#FFFFFF';
    toast.style.color = isError ? '#991B1B' : 'var(--soh-black)';
    toast.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.12)';
    toast.style.fontSize = '0.9rem';
    toast.style.fontWeight = '600';
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(-6px)';
    toast.style.transition = 'opacity 180ms ease, transform 180ms ease';

    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
    });

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-6px)';

        setTimeout(() => {
            toast.remove();
        }, 220);
    }, 3200);
}

function normalizeNotifyPayload(payload) {
    if (Array.isArray(payload) && payload.length > 0) {
        return payload[0] ?? {};
    }

    return payload ?? {};
}

function bindLivewireNotifyEvents() {
    window.addEventListener('notify', (event) => {
        const payload = normalizeNotifyPayload(event.detail);
        showToast(payload.message, payload.type ?? 'success');
    });

    document.addEventListener('livewire:init', () => {
        if (typeof Livewire !== 'undefined' && typeof Livewire.on === 'function') {
            Livewire.on('notify', (payload) => {
                const normalizedPayload = normalizeNotifyPayload(payload);
                showToast(normalizedPayload.message, normalizedPayload.type ?? 'success');
            });
        }
    });
}

/**
 * Frontend Theme Switcher
 * ------------------------------------------------------------------
 */

// On page load, set the theme
function setInitialTheme() {
    if (
        localStorage.getItem('color-theme') === 'dark' ||
        (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
    ) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

// Update the toggle icons
function updateThemeToggleIcons() {
    const darkIcon = document.getElementById('theme-toggle-dark-icon');
    const lightIcon = document.getElementById('theme-toggle-light-icon');
    if (!darkIcon || !lightIcon) return;

    if (
        localStorage.getItem('color-theme') === 'dark' ||
        (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
    ) {
        lightIcon.classList.remove('hidden');
        darkIcon.classList.add('hidden');
    } else {
        darkIcon.classList.remove('hidden');
        lightIcon.classList.add('hidden');
    }
}

// Initialize theme toggle
function initThemeToggle() {
    const themeToggleBtn = document.getElementById('theme-toggle');
    if (!themeToggleBtn) return;
    
    // Remove any existing listeners to prevent duplicates
    const newBtn = themeToggleBtn.cloneNode(true);
    themeToggleBtn.parentNode.replaceChild(newBtn, themeToggleBtn);
    
    newBtn.addEventListener('click', function () {
        // Toggle theme
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
        }
        updateThemeToggleIcons();
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    setInitialTheme();
    updateThemeToggleIcons();
    initThemeToggle();
    bindLivewireNotifyEvents();
});

// Re-initialize Flowbite components after Livewire navigation (SPA-like page transitions)
document.addEventListener('livewire:navigated', () => {
    initFlowbite();
    updateThemeToggleIcons();
    initThemeToggle();
});

// Re-initialize Flowbite components after Livewire updates the DOM (for dynamic content)
document.addEventListener('livewire:update', () => {
    initFlowbite();
});