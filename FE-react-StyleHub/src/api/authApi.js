import axios from 'axios';
import { API_URL, API_URL_ADMIN } from './apiUrl';
import ResetPassword from '../pages/ResetPassword';
export const auth = {
    register: (data) => axios.post(`${API_URL}/register`, data),
    login: (data) => axios.post(`${API_URL}/login`, data),
    forgot: (email) => axios.post(`${API_URL}/forgot-password`, email),
    confirmToken: (data) => axios.post(`${API_URL}/confirm-token`, data),
    resetPassword: (data) => axios.post(`${API_URL}/reset-password`, data),
    loginGoogle: (data) => axios.post(`${API_URL}/login-google`, data),

}
