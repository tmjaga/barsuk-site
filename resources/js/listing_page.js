export default (initialData = {}) => {

    return {
        url: initialData.url || '',
        currentPage: initialData.current_page || 1,
        totalPages: initialData.last_page || 1,
        data: initialData.data || [],
        perPage: initialData.per_page || 10,
        totalEntries: initialData.total || 0,
        pagesAroundCurrent: [],
        search: '',
        loading: false,

        init() {
            this.fetchPage(this.currentPage);
            this.updatePagesAroundCurrent();
            this.updateEntries();

            this.$watch('search', () => {
                this.goToPage();
            });

            this.$el.addEventListener('reload-items', () => {
                this.goToPage();
            });
        },

        updatePagesAroundCurrent() {
            const pages = [];

            for (let i = 1; i <= this.totalPages; i++) {
                pages.push(i);
            }

            this.pagesAroundCurrent = pages;
        },

        updateEntries() {
            this.startEntry = (this.currentPage - 1) * this.perPage + 1;
            this.endEntry = Math.min(this.currentPage * this.perPage, this.totalEntries);
        },

        goToPage(page = 1) {
            if (page < 1 || page > this.totalPages) return;
            this.currentPage = page;
            this.loading = true
            this.fetchPage(page);
            this.loading = false
        },

        prevPage() { this.goToPage(this.currentPage - 1); },
        nextPage() { this.goToPage(this.currentPage + 1); },

        fetchPage(page) {
            axios.get(this.url, {
                params: {
                    page: page,
                    search: this.search
                }
            }).then(res => {
                const data = res.data;
                this.data = data.data;

                this.totalPages = data.last_page;
                this.totalEntries = data.total;
                this.perPage = data.per_page;
                this.updatePagesAroundCurrent();
                this.updateEntries();
            }).catch(err => console.error(err));
        }
    };
};
