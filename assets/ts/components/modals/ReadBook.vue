<template>
     <BaseModal :isVisible="isVisible" @update:isVisible="updateVisibility">
          <template #header>
            Read Book
          </template>
          <template #default>
              <div class="space-y-4 mt-2">
                <div>
                  <label for="pages-read" class="block text-sm font-medium mb-1">How many pages did you read today?</label>
                  <div class="mt-2">
                    <input type="number" v-model="entry.pagesRead" id="pages-read" autocomplete="pages-read" class="pl-3 h-12 block w-full max-w-lg rounded-md border border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 sm:text-sm"/>
                    <span class="text-xs text-red-600" v-if="state?.errors?.hasOwnProperty('pagesRead')">
                        {{ state.errors.pagesRead }}
                    </span>
                  </div>
                </div>
              </div>
          </template>
          <template #footer>
            <div class="flex justify-between">
              <button class="mr-2 px-4 py-2 text-gray-700" @click="isVisible = false">Cancel</button>
              <button class="px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-700" @click="read()">Submit</button>
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
        isVisible: Boolean,
        bookId: Number | String,
        pagesLeft: Number | String
    });

    const emit = defineEmits(['update:isVisible']);

     const state = reactive({ errors: {}})

    const entry = ref({ pagesRead: ''});
    const loading = ref(false);

    const updateVisibility = (value) => {
        emit('update:isVisible', value);
    };

    const closeModal = () => {
        updateVisibility(false);
    };

    const read = () => {
      const bookId = props.bookId
        if (!entry.value.pagesRead) {
          notify({
              title: 'Pages read field is required.',
              type: 'error',
              position: 'top right'
          });
          return false;
        }

        if (entry.value.pagesRead > props.pagesLeft) {
          notify({
              title: 'You cannot enter pages greater than the pages left to read for this book.',
              type: 'error',
              position: 'top right'
          });
          return false;
        }
        loading.value = !loading.value;
        BookService.readBook(bookId, entry.value).then((response) => {
            loading.value = !loading.value;
            notify({
                title: "Success",
                type: 'success',
                position: 'top right'
            });
            closeModal();
            router.go();
            router.push(`/books/${bookId}`);
        },(error) => {
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