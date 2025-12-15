export default function deleteItem(initialData) {
    return {
        isModalOpen: false,
        loading: false,

        title: initialData.title ?? '',
        text: initialData.text ?? '',
        confirmText: initialData.confirmText ?? 'Yes, Delete',
        routeName: initialData.routeName,

        itemId: null,

        get action() {
            if (!this.itemId) return '';
            return this.routeName.replace(':id', this.itemId);
        },

        deleteItem() {
            if (this.loading || !this.action) return;

            this.loading = true;

            axios.delete(this.action)
                .then(response => {
                    this.$dispatch('reload-items');
                    Alpine.store('alert').success(response?.data?.message);
                })
                .catch(error => {
                    Alpine.store('alert').error(error?.response?.data?.message);
                })
                .finally(() => {
                    this.loading = false;
                    this.isModalOpen = false;
                });
        }
    }
}
