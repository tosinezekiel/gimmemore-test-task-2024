import api from './api';
import TokenService from "./token";
const API_URL: string = 'http://localhost:8000/api/auth';

class User {
  async profile() {
    return await api.get(`${API_URL}/profile`)
      .then((response) => {
          return response.data;
    })
  }
}

export default new User();