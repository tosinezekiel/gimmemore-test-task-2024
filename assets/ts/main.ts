import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import Notifications from '@kyvg/vue3-notification'
import setup from './services/setupInterceptor';
import vue3StarRatings from "vue3-star-ratings";
import { library } from '@fortawesome/fontawesome-svg-core';
import { faUserSecret } from '@fortawesome/free-solid-svg-icons';
import { faTwitter, faFacebookF } from '@fortawesome/free-brands-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faUserSecret, faTwitter, faFacebookF);

import '../styles/app.css';

const app = createApp(App);

app.component("vue3-star-ratings", vue3StarRatings);
app.component('font-awesome-icon', FontAwesomeIcon);

setup(store);
app.use(store);
app.use(Notifications)
app.use(router);
app.mount('#app');
