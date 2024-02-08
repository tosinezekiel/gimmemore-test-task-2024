import api from "./api";
import TokenService from "./token";
import { IBook, IReadBook, IRequestBook, IReviewBook } from '../types/application';

const API_URL = 'http://localhost:8000/api/auth';

class Book {

    async getStats() {
      return await api.get(`${API_URL}/dashboard`)
        .then((response) => {
            return response.data;
        })
    }

    async getBooks() {
        return await api.get(`${API_URL}/books`)
          .then((response) => {
              return response.data;
          })
      }

    async getBook(bookId: number) {
        return await api.get(`${API_URL}/books/${bookId}`)
          .then((response) => {
                return response.data;
          });
      }

    async addBook(data: IRequestBook) {
      return await api.post(`${API_URL}/books`, JSON.stringify(data))
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            return Promise.reject(error);
        });
    }

    async readBook(bookId: number, data: IReadBook) {
        return await api.patch(`${API_URL}/books/${bookId}/read`, JSON.stringify(data))
            .then((response) => {
            return response.data;
        })
        .catch((error) => {
            return Promise.reject(error);
        });
    }

    async addReview(bookId: number, data: IReviewBook) {
        return await api.post(`${API_URL}/books/${bookId}/review`, JSON.stringify(data))
            .then((response) => {
            return response.data;
        })
        .catch((error) => {
            return Promise.reject(error);
        });
    }
}

export default new Book();