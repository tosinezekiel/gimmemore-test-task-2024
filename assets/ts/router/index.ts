import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';
import store from '../store';
import Login from '../pages/Login.vue';
import Register from '../pages/Register.vue';
import PageNotFound from '../pages/PageNotFound.vue';
import Profile from '../pages/Auth/Profile.vue';
import Dashboard from '../pages/Auth/Dashboard.vue';
import Books from '../pages/Auth/Books.vue';
import BookDetail from '../pages/Auth/BookDetail.vue';

const routes: Array<RouteRecordRaw> = [
  { path: '/', component: Login, name: 'Login' },
  { path: '/register', component: Register, name: 'Register' },
  { path: '/dashboard', component: Dashboard, name: 'Dashboard' },
  { path: '/profile', component: Profile, name: 'Profile' },
  { path: '/books', name: 'Books', component: Books },
  { path: '/books/:id', name: 'BookDetail', component: BookDetail, props: true },
  { path: '/:pathMatch(.*)*', component: PageNotFound, name: 'PageNotFound' }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const publicPages = ['Login', 'Register']; // Include 'PageNotFound' to allow access to the 404 page
  const authRequired = !publicPages.includes(to.name); // Check if auth is required for the current route
  const loggedIn = store.getters['auth/isLoggedIn']; // Check if user is logged in

  if (loggedIn && publicPages.includes(to.name)) {
      next({ name: 'Dashboard' });
      return;
  }

  if (authRequired && !loggedIn) {
      next({ name: 'Login' });
      return;
  }

  // Otherwise proceed as normal
  next();
});


export default router;
