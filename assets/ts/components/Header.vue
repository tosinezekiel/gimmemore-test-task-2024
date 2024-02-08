<template>
  <nav class="bg-gray-900">
    <div class="mx-auto max-w-7xl">
        <div class="flex h-16 justify-between">
        <div class="flex">
            <div class="hidden md:flex md:items-center md:space-x-4">
                <router-link to="/dashboard" class="text-white py-2 rounded-md text-sm font-medium">Dashboard</router-link>
            </div>
        </div>
        <div class="flex items-center">
            <div class="flex-shrink-0 text-white">
                <router-link v-if="true" to="/books" >

                    <span class="ml-2">My Books</span>
                </router-link>
            </div>
            <div class="hidden md:ml-4 md:flex md:flex-shrink-0 md:items-center">
            <div class="relative ml-3">
                <div>
                    <button @click="toggle" type="button" class="w-10 h-10 bg-gray-100 rounded-full flex justify-center items-center" aria-expanded="false" aria-haspopup="true">
                            <span class="text-2xl">{{ nameInitial }}</span>
                    </button>
                </div>
                <div v-if="state.toggle" class="absolute left-0 right-2 z-10 mt-2 w-48 origin-top-left rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">

                    <a @click.prevent="gotoProfile" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-1">Profile</a>

                    <a href="#" @click.prevent="handleLogout" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-2">Sign out</a>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
  </nav>
</template>

<script setup>
    import { reactive, computed } from 'vue'
    import { useStore } from 'vuex';
    import { useRouter} from 'vue-router'
    import TokenService from "@/ts/services/token";

    const store = useStore()
    const router = useRouter();

    const state = reactive({toggle:false})
    const toggle = () => {
        state.toggle = !state.toggle
    }

    const nameInitial = computed(() => {
        return TokenService.getUser()?.first_name.toUpperCase().charAt(0)
    })

    const gotoProfile = () => {
        router.push('/profile');
    }

    const handleLogout = () => {
        store.dispatch("auth/logout", {}).then(() => {
            router.push("/");
        });
    }
</script>