import api from "./api";
import TokenService from "./token";
import { ILoginCredentials, IAuthUser, IRegisterCredentials } from "../types/application";

const API_URL: string = 'http://localhost:8000/api';

class Auth {
     async login({ email, password }: ILoginCredentials): Promise<IAuthUser> {
        return await api
          .post(`${API_URL}/login`, {
            email,
            password
          })
          .then((response) => {
            let res = response.data;
            if (res.data.token) {
                TokenService.setUser(res.data.user);
                TokenService.updateLocalAccessToken(res.data.token);
            }
            let token = res.data.token;
            let data = {...res.data.user, token }
            return res.data;
          });
      }

      async register({ email, password, password_confirmation, last_name, first_name }: IRegisterCredentials): Promise<IAuthUser> {
        return await api
          .post(`${API_URL}/register`, 
          { email, password, password_confirmation, last_name, first_name })
          .then((response) => {
            let res = response.data;
            console.log(res);
            return;
            if (res.data.token) {
                TokenService.setUser(res.data.user);
                TokenService.updateLocalAccessToken(res.data.token);
            }
            let token = res.data.token;
            let data = {...res.data.user, token }
            return res.data;
          });
      }

    async logout() {
      TokenService.removeUser();
    }
}

export default new Auth();