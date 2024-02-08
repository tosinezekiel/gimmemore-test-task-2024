<template>
    <div class="max-w-7xl mx-auto" v-if="loading">
      <div class="text-center mt-16"><i>Loading...</i></div>
    </div>
    <div class="max-w-7xl mx-auto" v-else>
        <AddBook :isVisible="showAddBookModal" @update:isVisible="showAddBookModal = $event" />
        <ReadBook :isVisible="showReadBookModal" @update:isVisible="showReadBookModal = $event" :bookId="id" :pagesLeft="book?.pages_left"/>
        <div class="mt-11 mb-5 flex justify-end">
            <div class="flex-shrink-0">
                <button @click="toggleModal()" class="relative inline-flex items-center rounded-md border border-transparent bg-gray-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>

                    <span class="ml-2">Add Book</span>
                </button>
            </div>
        </div>
        <section aria-labelledby="summary-heading" class="mt-24">
            <h2 id="summary-heading" class="sr-only">Book Detail</h2>

            <div class="rounded-lg bg-gray-50 px-6 py-6 lg:grid lg:grid-cols-12 lg:gap-x-8 lg:px-0 lg:py-8">
            <dl class="grid grid-cols-1 gap-6 text-sm sm:grid-cols-2 md:gap-x-8 lg:col-span-5 lg:pl-8">
                <div>
                    <dt class="font-medium text-gray-900">Book Detail</dt>
                    <dd class="mt-3 text-gray-500">
                        <h1 class="text-gray-600"><strong>{{ book?.title }}</strong></h1>
                        <span class="block">Genre: {{ book?.genre }}</span>
                        <span class="block">Author: {{ book?.author }}</span>
                    </dd>
                    <div class="mt-11 mb-5 flex" v-if="!completedBook">
                        <div class="flex-shrink-0">
                            <button @click="toggleReadModal()" class="relative inline-flex items-center rounded-md border border-transparent bg-gray-700 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                </svg>
                                <span class="ml-2">Read Book</span>
                            </button>
                        </div>
                    </div>
                    <div v-else class="mt-5 text-green-600 text-xl">Congratulations! 🎉</div>
                </div>
                <div>
                <dt class="font-medium text-gray-900">Reading information</dt>
                <dd class="mt-3 flex">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                        <p class="sr-only">Update</p>
                        </div>
                        <div class="ml-4">
                        <p class="text-gray-900">{{ book?.total_read }} / {{ book?.page_count}} </p>
                        <p class="text-gray-600">Visited {{ book?.read_times }} times</p>
                    </div>
                </dd>
                </div>
            </dl>

            <dl class="mt-8 divide-y divide-gray-200 text-sm lg:col-span-7 lg:mt-0 lg:pr-8">
                <div class="flex items-center justify-between pb-4">
                <dt class="text-gray-600">Total pages</dt>
                <dd class="font-medium text-gray-900">{{ book?.page_count }}</dd>
                </div>
                <div class="flex items-center justify-between py-4">
                <dt class="text-gray-600">Pages read</dt>
                <dd class="font-medium text-gray-900">{{ book?.total_read}}</dd>
                </div>
                <div class="flex items-center justify-between py-4">
                <dt class="text-gray-600">Pages left</dt>
                <dd class="font-medium text-gray-900">{{ book?.pages_left }}</dd>
                </div>
                <div class="flex items-center justify-between pt-4">
                <dt class="font-medium text-gray-900">Status</dt>
                <dd :class="{
                'font-medium text-indigo-600': book.status === 'in-progress',
                'font-medium text-green-600': book.status === 'completed',
                'font-medium text-gray-600': book.status === 'fresh'
                }">
                    {{ book?.status }}
                </dd>

                </div>
            </dl>
            </div>
        </section>
        <section class="mt-11 flex justify-between" v-if="completedBook">
            <div>
                <div class="flex-col">
                    <h2>
                        Leave a review
                    </h2>
                    <small>Kindly let us know how you feel about this book</small>
                </div>
                <vue3-star-ratings v-model="rating" class="mt-5"/>
                <div class="mt-2">
                <textarea id="about" v-model="comment" style="resize: none;" rows="3" class="block px-3 w-64 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                </div>
                <div class="flex-shrink-0 mt-3">
                    <button @click="submitReview(book?.id)" class="relative inline-flex items-center rounded-md border border-transparent bg-gray-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-800">
                        <span class="ml-2">Submit Review</span>
                    </button>
                </div>
            </div>
            <div>
                <section aria-labelledby="reviews-heading" class="" v-if="book?.reviews.length">
                    <h2 id="reviews-heading" class="text-lg font-medium text-gray-900">Recent reviews</h2>

                    <div class="mt-6 space-y-10 divide-y divide-gray-200 border-b border-t border-gray-200 pb-10">
                        <div v-for="review in book?.reviews" :key="review.id" class="flex justify-between">
                            <div class="lg:col-span-8 xl:col-span-9 xl:grid xl:grid-cols-3 xl:items-center xl:gap-x-8">
                                <div class="flex justify-between">
                                    <div class="flex items-center">
                                        <vue3-star-ratings v-model="review.rating"/>
                                    </div>
                                    <p class="sr-only text-sm text-gray-700">{{ review.rating }}<span class="sr-only"> out of 5 stars</span></p>
                                </div>

                                <div class="mt-4 lg:mt-6 xl:col-span-2 xl:mt-0">
                                    <div class="mt-3 space-y-6 text-sm text-gray-500" v-html="review.comment" />
                                </div>
                            </div>

                            <div class="flex justify-end items-center space-x-4">
                                <a :href="`https://twitter.com/intent/tweet?text=${encodeURIComponent(review.comment + ' - ' + book.title + ' by ' + book.author)}&hashtags=Gimmemore`" target="_blank">
                                    <font-awesome-icon :icon="['fab', 'twitter']" />
                                </a>
                            </div>

                        </div>
                        
                    </div>
                </section>
                <section class="text-center pt-40" v-else>
                    No reviews yet!
                </section>
            </div>
        </section>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter} from 'vue-router'
import { notify } from "@kyvg/vue3-notification";
import StarRating from 'vue-star-rating';
import AddBook from '@/ts/components/modals/AddBook.vue';
import ReadBook from '@/ts/components/modals/ReadBook.vue';
import BookService from "@/ts/services/book";

const props = defineProps({
  id: Number | String
});

const router = useRouter();


const book = ref(null);
const loading = ref(true);
const rating = ref(0);
const comment = ref('');

const showAddBookModal = ref(false);
const showReadBookModal = ref(false);

const completedBook = computed(() => {
    return book.value.pages_left === 0;
});

const toggleModal = () => {
    showAddBookModal.value = !showAddBookModal.value;
};

const toggleReadModal = () => {
    showReadBookModal.value = !showReadBookModal.value;
};

const submitReview = (bookId) => {
  if (rating.value < 1 || !comment.value) {
        notify({
            title: 'Please reviews are required before submission.',
            type: 'error',
            position: 'top right'
        });
        return false;
    }

    BookService.addReview(bookId, { rating:rating.value, comment: comment.value }).then((response) => {
            notify({
                title: "Review submitted successful",
                type: 'success',
                position: 'top right'
            });
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
        })
};

const fetchBook = async () => {
    await BookService.getBook(props.id).then(response => {
        book.value = response.data;
        loading.value = !loading.value;
    }).catch(error => {
        loading.value = !loading.value;
    });
};


onMounted(fetchBook);
</script>
