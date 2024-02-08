<template>
    <div id="app">
        <Guest v-if="!isLoggedIn">
            <notifications/>
            <router-view></router-view>
        </Guest>
        <Auth v-else>
            <Header></Header>
            <notifications/>
            <router-view></router-view>
            <Footer></Footer>
        </Auth>
    </div>
</template>

<script setup>
    import { computed } from "vue";
    import { useStore } from 'vuex';
    import Header from '@/ts/components/Header.vue'
    import Footer from '@/ts/components/Footer.vue'

    const store = useStore()

    const isLoggedIn = computed(() => {
        return store.getters['auth/isLoggedIn']
    })

</script>
