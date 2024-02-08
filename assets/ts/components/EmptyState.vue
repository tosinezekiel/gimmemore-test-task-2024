<template>
<AddBook :isVisible="showAddBookModal" @update:isVisible="showAddBookModal = $event" />
  <div class="text-center mt-11">
    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
      <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
    </svg>
    <h3 class="mt-2 text-sm font-semibold text-gray-900">No Books</h3>
    <p class="mt-1 text-sm text-gray-500">Get started by adding a new project.</p>
    <div class="mt-6">
      <button @click="toggleModal()" type="button" class="inline-flex items-center rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="-ml-0.5 mr-1.5 h-5 w-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        New Book
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from "vue"
import AddBook from '@/ts/components/modals/AddBook.vue';
import TokenService from "@/ts/services/token";

const showAddBookModal = ref(false);
    
const toggleModal = () => {
    showAddBookModal.value = !showAddBookModal.value;
};
</script>