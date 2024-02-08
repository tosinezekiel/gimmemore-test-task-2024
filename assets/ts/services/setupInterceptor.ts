import axiosInstance from "./api";
import TokenService from "./token";
import { Store } from 'vuex';
import { IState } from '@/ts/types/application';

const setup = (store: Store<IState>) => {
  axiosInstance.interceptors.request.use(
    (config) => {
      const token = TokenService.getLocalAccessToken();
      if (token) {
        config.headers["Authorization"] = 'Bearer ' + token; 
      }
      return config;
    },
    (error) => {
      return Promise.reject(error);
    }
  );

  axiosInstance.interceptors.response.use(
    (res) => {
      return res;
    },
    async (err) => {
      const originalConfig = err.config;

      if (originalConfig.url !== "/auth/login" && err.response) {
        
        if (err.response.status === 401 && !originalConfig._retry) {
          originalConfig._retry = true;
        }
      }

      return Promise.reject(err);
    }
  );
};

export default setup;