<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-5 right-5 z-[100] flex flex-col gap-3 pointer-events-none"></div>

<style>
    .toast-enter {
        transform: translateX(100%);
        opacity: 0;
    }

    .toast-enter-active {
        transform: translateX(0);
        opacity: 1;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .toast-exit {
        transform: translateX(0);
        opacity: 1;
    }

    .toast-exit-active {
        transform: translateX(100%);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>

<script>
    /**
     * Show a Toast Notification
     * @param {string} message - Message to display
     * @param {string} type - 'success', 'error', 'info'
     * @param {number} duration - Duration in ms (default 3000)
     */
    function showToast(message, type = 'success', duration = 3000) {
        const container = document.getElementById('toast-container');

        // Config based on type
        const config = {
            success: {
                bg: 'bg-[#1e293b]', // Dark background
                border: 'border-green-500',
                icon: `<svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`,
                textColor: 'text-white'
            },
            error: {
                bg: 'bg-[#1e293b]',
                border: 'border-red-500',
                icon: `<svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`,
                textColor: 'text-white'
            },
            info: {
                bg: 'bg-[#1e293b]',
                border: 'border-blue-500',
                icon: `<svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`,
                textColor: 'text-white'
            }
        };

        const theme = config[type] || config.info;

        // Create Element
        const toast = document.createElement('div');
        toast.className = `pointer-events-auto flex items-center gap-3 px-5 py-4 rounded-xl shadow-2xl border-l-4 ${theme.border} ${theme.bg} min-w-[300px] max-w-sm toast-enter`;

        toast.innerHTML = `
            <div class="flex-shrink-0">${theme.icon}</div>
            <div class="flex-1">
                <p class="text-sm font-medium ${theme.textColor}">${message}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-slate-500 hover:text-slate-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        `;

        // Add to DOM
        container.appendChild(toast);

        // Animate In
        requestAnimationFrame(() => {
            toast.classList.remove('toast-enter');
            toast.classList.add('toast-enter-active');
        });

        // Auto Remove
        setTimeout(() => {
            toast.classList.remove('toast-enter-active');
            toast.classList.add('toast-exit-active');
            setTimeout(() => {
                if (toast.parentElement) toast.remove();
            }, 300); // Wait for exit animation
        }, duration);
    }
</script>