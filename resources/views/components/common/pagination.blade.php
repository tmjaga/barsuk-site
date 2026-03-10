<!-- Pagination component must be placed inside listingpage() Alpine component scope -->
<div x-show="totalPages > 1" class="border-t border-gray-100 py-4 pl-[18px] pr-4">
    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between">
        <div class="flex items-center justify-center gap-0.5 pb-4 xl:justify-normal xl:pt-0">
            <button @click="prevPage()"
                    class="mr-2.5 flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-gray-700 shadow-theme-xs hover:bg-gray-50 disabled:opacity-50"
                    :disabled="currentPage === 1" disabled="disabled">
                Previous
            </button>
            <template x-for="page in pagesAroundCurrent" :key="page">
                <button @click="goToPage(page)"
                        :class="currentPage === page ? 'bg-blue-500/[0.08] text-brand-500' : 'text-gray-700'"
                        class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium hover:bg-blue-500/[0.08] hover:text-brand-500">
                    <span x-text="page"></span>
                </button>
            </template>
            <button @click="nextPage()"
                    class="ml-2.5 flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-gray-700 shadow-theme-xs hover:bg-gray-50 disabled:opacity-50"
                    :disabled="currentPage === totalPages">
                Next
            </button>
        </div>

        <p class="border-t border-gray-100 pt-3 text-center text-sm font-medium text-gray-500 xl:border-t-0 xl:pt-0 xl:text-left">
            Showing <span x-text="startEntry">1</span> to
            <span x-text="endEntry">10</span> of
            <span x-text="totalEntries">30</span> entries
        </p>
    </div>
</div>

