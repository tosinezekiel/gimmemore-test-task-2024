<template>
    <div class="max-w-sm mx-auto px-4 py-8">
        <h1 class="text-3xl text-slate-800 font-bold">Register</h1>
        <span class="font-semibold text-gray-400">We can't wait to have you ;)</span>
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
                    <label for="first_name" class="block text-sm font-medium mb-1">
                        First Name
                    </label>
                    <input v-model="firstName" id="first-name" class="pl-3 h-12 block w-full max-w-lg rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                    <span class="text-xs text-red-600" v-if="state?.errors?.hasOwnProperty('firstName')">
                        {{ state.errors.firstName }}
                    </span>
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium mb-1">
                        Last Name
                    </label>
                    <input v-model="lastName" id="first-name" class="pl-3 h-12 block w-full max-w-lg rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                    <span class="text-xs text-red-600" v-if="state?.errors?.hasOwnProperty('lastName')">
                        {{ state.errors.lastName }}
                    </span>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium mb-1">
                        Email
                    </label>
                    <input v-model="email" class="pl-3 h-12 block w-full max-w-lg rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                    <span class="text-xs text-red-600" v-if="state?.errors?.hasOwnProperty('email')">
                        {{ state.errors.email }}
                    </span>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium mb-1">
                        Password
                    </label>
                    <input v-model="password" type="password" class="pl-3 h-12 block w-full max-w-lg rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                    <span class="text-xs text-red-600" v-if="state?.errors?.hasOwnProperty('password')">
                        {{ state.errors.password }}
                    </span>
                </div>
                <div>
                    <label for="password-confirmation" class="block text-sm font-medium mb-1">
                        Retype Password
                    </label>
                    <input v-model="passwordConfirmation" type="password" class="pl-3 h-12 block w-full max-w-lg rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                    <span class="text-xs text-red-600" v-if="state?.errors?.hasOwnProperty('passwordConfirmation')">
                        {{ state.errors.passwordConfirmation }}
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
                        I'm not new around here, I want to <span class="text-underline text-indigo-500"><router-link to="/">login</router-link></span>
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

const state = reactive({ errors: {}, loading: false })

const { email, password, passwordConfirmation, firstName, lastName, error, validate } = useValidation();

const handleSubmit = (isRegister) => {
  if (validate(isRegister)) {
        state.loading = true;
            state.errors = {};
            error.value = '';

            store.dispatch("auth/register", {
                    email: email.value, 
                    password : password.value,
                    password_confirmation : passwordConfirmation.value,
                    first_name: firstName.value,
                    last_name: lastName.value
                }).then(() => {
                    state.loading = false;
                    notify({
                        title: 'Register successfull',
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
                console.log(error.response.data.data);
                state.errors = error.response.data.data;
        });
  } 
};

</script>