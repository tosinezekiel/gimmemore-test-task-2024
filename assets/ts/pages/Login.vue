<template>
    <div class="max-w-sm mx-auto px-4 py-8">
        <h1 class="text-3xl text-slate-800 font-bold">Login! ✨</h1>
        <span class="font-semibold text-gray-400">So nice to see you again ;)</span>
        <form>
            <div class="space-y-4 mt-2">
                <div class="rounded-md bg-red-50 p-4" v-if="error">
                    <div class="flex">
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">{{ error }}</h3>
                    </div>
                    </div>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium mb-1">
                        Email
                    </label>
                    <input v-model="email" class="pl-3 h-12 block w-full max-w-lg rounded-md border border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 sm:text-sm"/>
                    <span class="text-xs text-red-600" v-if="state?.errors?.hasOwnProperty('email')">
                        {{ state.errors.email }}
                    </span>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium mb-1">
                        Password
                    </label>
                    <input v-model="password" type="password" class="pl-3 h-12 block w-full max-w-lg rounded-md border border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 sm:text-sm"/>
                    <span class="text-xs text-red-600" v-if="state?.errors?.hasOwnProperty('password')">
                        {{ state.errors.password }}
                    </span>
                </div>
                <div class="flex items-center">
                    <button
                    type="button"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest transition ease-in-out duration-150 btn bg-gray-500 active:bg-gray-900 hover:bg-gray-600 text-white"    
                    @click.prevent="handleSubmit"
                    :disabled="state.loading"

                    >
                        {{state.loading ? `Loading...` : `Submit`}}
                    </button>
                    <p class="ml-3 text-xs">
                        Are you new around here? start <span class="text-underline text-indigo-500"><router-link to="/register">here</router-link></span>
                    </p>
                </div>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive } from "vue";
import { useStore } from 'vuex';
import { useRouter} from 'vue-router'
import { useValidation } from '../composables/useValidation';
import { notify } from "@kyvg/vue3-notification";

const store = useStore()
const router = useRouter();

const { email, password, error, validate } = useValidation();


const state = reactive({ errors: {}, loading: false })

const handleSubmit = () => {
    if(validate()){
        state.loading = true;
        state.errors = {};
        error.value = '';

        store.dispatch("auth/login", {
                "email": email.value, 
                "password" : password.value
            }).then(() => {
                state.loading = false;
                notify({
                    title: 'Login successfull',
                    type: 'success',
                    position: 'top right'
                });
                router.push("/dashboard");
            },(error) => {
            state.loading = false;
            if(!error.response.data.hasOwnProperty('data')){
                error.value = error.response.data.error;
                notify({
                    title: error.value,
                    type: 'error',
                    position: 'center'
                });
                return;
            }
            state.errors = error.response.data.data;
        });
    }
    
}
</script>