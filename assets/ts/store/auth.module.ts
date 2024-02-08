import { ActionContext } from 'vuex';
import AuthService from '../services/auth';
import UserService from '../services/user';
import { IAuthUser } from '../types/application';
import { IState } from '../types/application';



const userItem = localStorage.getItem('user');
const user: IAuthUser | null = userItem !== null ? JSON.parse(userItem) : null;

const initialState: IState = user
  ? { loggedIn: true, user }
  : { loggedIn: false, user: null };

export const auth = {
  namespaced: true,
  state: initialState,
  mutations: {
    loginSuccess(state: IState, payload: { user: IAuthUser }) {
      state.loggedIn = true;
      state.user = payload.user;
    },
    loginFailure(state: IState) {
      state.loggedIn = false;
      state.user = null;
    },
    logout(state: IState) {
      state.loggedIn = false;
      state.user = null;
    }
  },
  actions: {
    login({ commit }: ActionContext<IState, any>, userInfo: { email: string; password: string }) {
      return AuthService.login(userInfo).then(
        (user: IAuthUser) => {
          commit('loginSuccess', { user });
          return Promise.resolve(user);
        },
        (error: any) => {
          commit('loginFailure');
          return Promise.reject(error);
        }
      );
    },
    register({ commit }: ActionContext<IState, any>, userInfo: { email: string; password: string; password_confirmation: string; first_name: string; last_name: string }) {
      return AuthService.register(userInfo).then(
        (user: IAuthUser) => {
          commit('loginSuccess', { user });
          return Promise.resolve(user);
        },
        (error: any) => {
          commit('loginFailure');
          return Promise.reject(error);
        }
      );
    },
    logout({ commit }: ActionContext<IState, any>) {
      AuthService.logout();
      commit('logout');
    },
    fetchUser({ commit }: ActionContext<IState, any>, userId: number) {
      return UserService.profile().then(
        (user: IAuthUser) => {
          commit('loginSuccess', { user });
          return Promise.resolve(user);
        },
        (error: any) => {
          return Promise.reject(error);
        }
      );
    }
  },
  getters: {
    isLoggedIn: (state: IState): boolean => state.loggedIn,
    getUser: (state: IState): IAuthUser | null => state.user
  },
};
