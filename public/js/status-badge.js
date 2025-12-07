document.addEventListener('alpine:init', () => {
    Alpine.data('statusBadge', () => ({
        getBadge(status) {
            switch (status) {
                case 1:
                    return { text: 'Active', color: 'bg-success-500 text-white' };
                case 0:
                    return { text: 'Inactive', color: 'bg-error-500 text-white' };
                default:
                    return { text: 'Undefined', color: 'bg-gray-400 text-white' };
            }
        }
    }));
});
