<template>
    <div class="max-w-7xl mx-auto" v-if="loading">
      <div class="text-center mt-16"><i>Loading...</i></div>
    </div>
    <div class="max-w-7xl mx-auto" v-esle>
        <div class="mt-11 mb-5 flex justify-between">
            <h1 class="text-3xl">{{ fullName }}  <small class="mute text-xs text-gray-50">/ Profile</small></h1>
            <div class="flex-shrink-0">
                <button @click="toggleModal()" class="relative inline-flex items-center rounded-md border border-transparent bg-gray-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>

                    <span class="ml-2">Add Book</span>
                </button>
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
    import EmptyState from '@/ts/components/EmptyState.vue';
    import ListBook from '@/ts/components/ListBook.vue';
    import UserService from "@/ts/services/user";
    import TokenService from "@/ts/services/token";

    const showAddBookModal = ref(false);
        const books = ref([]);
        const title = ref('Completed books');
        const loading = ref(true);
    
    const toggleModal = () => {
        showAddBookModal.value = !showAddBookModal.value;
        console.log("Modal state:", showAddBookModal.value);
    };

    const fullName = computed(() => {
        return TokenService.getUser().full_name || 'User'
    })

    const fetchBooks = async () => {
        await UserService.profile().then(response => {
            books.value = response.data.completed_books;
            loading.value = !loading.value;
        }).catch(error => {
            loading.value = !loading.value;
        });
    };


    onMounted(fetchBooks);
</script>