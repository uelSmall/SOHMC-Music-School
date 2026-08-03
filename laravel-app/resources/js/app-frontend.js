import './bootstrap';
import 'flowbite';
import { initializeLessonCalendars } from './calendar/lesson-calendar';

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

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    bindLivewireNotifyEvents();
    initializeLessonCalendars();
});

// Re-initialize Flowbite components after Livewire navigation (SPA-like page transitions)
document.addEventListener('livewire:navigated', () => {
    initFlowbite();
    initializeLessonCalendars();
});

// Re-initialize Flowbite components after Livewire updates the DOM (for dynamic content)
document.addEventListener('livewire:update', () => {
    initFlowbite();
    initializeLessonCalendars();
});