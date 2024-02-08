import { IAuthUser } from '../types/application';

class TokenService {
    getLocalAccessToken() {
      const userItem = localStorage.getItem('user');
      const user: IAuthUser | null = userItem !== null ? JSON.parse(userItem) : null;
      return user?.token;
    }
  
    updateLocalAccessToken(token: string) {
      const userItem = localStorage.getItem('user');
      const user: IAuthUser | null = userItem !== null ? JSON.parse(userItem) : null;
      if (user !== null) {
        user.token = token;
        localStorage.setItem("user", JSON.stringify(user));
      } 
    }
  
    getUser() {
      const userItem = localStorage.getItem('user');
      const user: IAuthUser | null = userItem !== null ? JSON.parse(userItem) : null;
      return user;
    }
  
    setUser(user: IAuthUser) {
      localStorage.setItem("user", JSON.stringify(user));
    }
  
    removeUser() {
      localStorage.removeItem("user");
    }
  }
  
  export default new TokenService();