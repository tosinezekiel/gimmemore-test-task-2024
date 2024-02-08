<template>
     <BaseModal :isVisible="isVisible" @update:isVisible="updateVisibility">
          <template #header>
            Create Book
          </template>
          <template #default>
              <div class="space-y-4 mt-2">
                <div>
                  <label for="title" class="block text-sm font-medium mb-1">Title</label>
                  <div class="mt-2">
                    <input type="text" v-model="book.title" id="title" autocomplete="title" class="pl-3 h-12 block w-full max-w-lg rounded-md border border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 sm:text-sm"/>
                    <span class="text-xs text-red-600" v-if="state?.errors?.hasOwnProperty('title')">
                        {{ state.errors.title }}
                    </span>
                  </div>
                </div>

                <div>
                  <label for="author" class="block text-sm font-medium mb-1">Author</label>
                  <div class="mt-2">
                    <input type="text" v-model="book.author" id="author" autocomplete="author" class="pl-3 h-12 block w-full max-w-lg rounded-md border border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 sm:text-sm"/>
                    <span class="text-xs text-red-600" v-if="state?.errors?.hasOwnProperty('author')">
                        {{ state.errors.author }}
                    </span>
                  </div>
                </div>

                <div>
                  <label for="genre" class="block text-sm font-medium mb-1">Genre</label>
                  <div class="mt-2">
                    <input type="text" v-model="book.genre" id="genre" autocomplete="genre" class="pl-3 h-12 block w-full max-w-lg rounded-md border border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 sm:text-sm"/>
                    <span class="text-xs text-red-600" v-if="state?.errors?.hasOwnProperty('genre')">
                        {{ state.errors.genre }}
                    </span>
                  </div>
                </div>

                <div>
                  <label for="page-count" class="block text-sm font-medium mb-1">Page Count</label>
                  <div class="mt-2">
                    <input type="number" v-model="book.pageCount" id="page-count" autocomplete="page-count" class="pl-3 h-12 block w-full max-w-lg rounded-md border border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 sm:text-sm"/>
                    <span class="text-xs text-red-600" v-if="state?.errors?.hasOwnProperty('pageCount')">
                        {{ state.errors.pageCount }}
                    </span>
                  </div>
                </div>
              </div>
          </template>
          <template #footer>
            <div class="flex justify-between">
              <button class="mr-2 px-4 py-2 text-gray-700" @click="isVisible = false">Cancel</button>
              <button class="px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-700" @click="addBook()">Submit</button>
            </div>
          </template>
      </BaseModal>
</template>

<script setup>
import { ref, defineProps, defineEmits, reactive } from 'vue';
import { useRouter} from 'vue-router'
import { notify } from "@kyvg/vue3-notification";
import BookService from "@/ts/services/book";
import BaseModal from '@/ts/components/BaseModal.vue';

const router = useRouter();

    const props = defineProps({
        isVisible: Boolean
    });

    const state = reactive({ errors: {}})

    const emit = defineEmits(['update:isVisible']);

    const book = ref({ title: '', author: '', genre: '', pageCount: '' });
    const loading = ref(false);

    const updateVisibility = (value) => {
        emit('update:isVisible', value);
    };

    const closeModal = () => {
        updateVisibility(false);
    };

    const addBook = () => {
        if (!book.value.title || !book.value.author || !book.value.genre || !book.value.pageCount) {
          notify({
              title: 'All fields fields are required.',
              type: 'error',
              position: 'top right'
          });
          return false;
        }
        loading.value = !loading.value;
        BookService.addBook(book.value).then((response) => {
                loading.value = !loading.value;
                const book = response.data.data;
                notify({
                    title: response.message,
                    type: 'success',
                    position: 'top right'
                });
                closeModal();
                router.go();
                router.push(`/books/${book.id}`);
            },(error) => {
                loading.value = !loading.value;
                if(!error.response.data.hasOwnProperty('data')){
                    notify({
                        title: error.response.data.error,
                        type: 'error',
                        position: 'center'
                    });
                    return;
                }
                state.errors = error.response.data.data;
        })
    };
</script>