<template>
    <div class="mt-5">
            <h1 class="text-xl">{{ props.title }}</h1>
            <ul role="list" class="divide-y divide-gray-100 border-2 px-5 mt-2">
                <li v-for="book in books" :key="book.slug" class="relative flex justify-between gap-x-6 py-5">
                <div class="flex min-w-0 gap-x-4">
                    <span class="w-10 h-10 bg-gray-100 rounded-full flex justify-center items-center">
                        {{ titleInitial(book.title) }}
                    </span>
                    <div class="min-w-0 flex-auto">
                    <p class="text-sm font-semibold leading-6 text-gray-900">
                        <router-link :to="`/books/${book.id}`">
                        <span class="absolute inset-x-0 -top-px bottom-0" />
                        {{ book.title }}
                        </router-link>
                    </p>
                    <p class="mt-1 flex text-xs leading-5 text-gray-500">
                        <router-link :to="`/books/${book.id}`" class="relative truncate hover:underline">{{ book.author }}</router-link>
                    </p>
                    </div>
                </div>
                <div class="flex shrink-0 items-center gap-x-4">
                    <div class="hidden sm:flex sm:flex-col sm:items-end">
                    <p class="text-sm leading-6 text-gray-900">{{ book.genre }}</p>
                    <p v-if="book.created_at" class="mt-1 text-xs leading-5 text-gray-500">
                        Added on: <time :datetime="book.created_at">{{ book.created_at }}</time>
                    </p>
                    <div v-else class="mt-1 flex items-center gap-x-1.5">
                        <div class="flex-none rounded-full bg-emerald-500/20 p-1">
                        <div class="h-1.5 w-1.5 rounded-full bg-emerald-500" />
                        </div>
                        <p class="text-xs leading-5 text-gray-500">Online</p>
                    </div>
                    </div>
                </div>
                </li>
            </ul>
        </div>
</template>
<script setup lang="ts">
import { computed } from 'vue';
const props = defineProps({
    books: Array,
    title: String
});

const titleInitial = (title: string) => title.toUpperCase().charAt(0);
</script>
