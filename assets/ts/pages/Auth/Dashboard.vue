<template>
    <AddBook :isVisible="showAddBookModal" @update:isVisible="showAddBookModal = $event" />
    <div class="max-w-7xl mx-auto" v-if="loading">
      <div class="text-center mt-16"><i>Loading...</i></div>
    </div>
    <div class="max-w-7xl mx-auto" v-else>
        <div class="mt-11 mb-5 flex justify-between">
            <h1 class="text-3xl">Hi {{ displayName }}, Welcome home!😊</h1>

            <div class="flex-shrink-0">
                <button @click="toggleModal()" class="relative inline-flex items-center rounded-md border border-transparent bg-gray-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>

                    <span class="ml-2">Add Book</span>
                </button>
            </div>
        </div>

        <div class="bg-gray-900">
            <div class="mx-auto max-w-7xl">
            <div class="grid grid-cols-1 gap-px bg-white/5 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-gray-900 px-4 py-6 sm:px-6 lg:px-8">
                  <p class="text-sm font-medium leading-6 text-gray-400">Total Books</p>
                  <p class="mt-2 flex items-baseline gap-x-2">
                      <span class="text-4xl font-semibold tracking-tight text-white">{{ totalBooks }}</span>
                  </p>
                </div>

                <div class="bg-gray-900 px-4 py-6 sm:px-6 lg:px-8">
                  <p class="text-sm font-medium leading-6 text-gray-400">Book read this month</p>
                  <p class="mt-2 flex items-baseline gap-x-2">
                      <span class="text-4xl font-semibold tracking-tight text-white">{{ totalReadInMonth }}</span>
                  </p>
                </div>

                <div class="bg-gray-900 px-4 py-6 sm:px-6 lg:px-8">
                  <p class="text-sm font-medium leading-6 text-gray-400">Book read this year</p>
                  <p class="mt-2 flex items-baseline gap-x-2">
                      <span class="text-4xl font-semibold tracking-tight text-white">40</span>
                  </p>
                </div>

                <div class="bg-gray-900 px-4 py-6 sm:px-6 lg:px-8">
                  <p class="text-sm font-medium leading-6 text-gray-400">Last entry</p>
                  <p class="mt-2 flex items-baseline gap-x-2">
                      <span class="text-4xl font-semibold tracking-tight text-white">{{ lastReadDay || 'N/A' }}</span>
                  </p>
                </div>
            </div>
            </div>
        </div>

        <div v-if="books">
          <ListBook :title="title" :books="books"/>
        </div>
        <EmptyState v-else/>
    </div>

    
   
</template>

<script setup>
import { computed, ref, onMounted } from "vue"
import AddBook from '@/ts/components/modals/AddBook.vue';
import EmptyState from '@/ts/components/EmptyState.vue';
import ListBook from '@/ts/components/ListBook.vue';
import TokenService from "@/ts/services/token";
import BookService from "@/ts/services/book";

const showAddBookModal = ref(false);
const books = ref([]);
const totalReadInMonth = ref(0);
const totalReadInYear = ref(0);
const totalBooks = ref(0);
const lastReadDay = ref('N/A');
const title = ref('Previously read books');
const loading = ref(true);

    
const toggleModal = () => {
    showAddBookModal.value = !showAddBookModal.value;
};

const displayName = computed(() => {
    return TokenService.getUser().first_name || 'User'
})

const fetchBooks = async () => {
  await BookService.getStats().then(response => {
    books.value = response.data.latest;
    totalReadInMonth.value = response.data.total_pages_read_in_month;
    totalReadInYear.value = response.data.total_pages_read_in_year;
    totalBooks.value = response.data.total_books;
    lastReadDay.value = response.data.last_seen;
    loading.value = !loading.value;
  }).catch(error => {
    loading.value = !loading.value;
  });
};


onMounted(fetchBooks);
</script>