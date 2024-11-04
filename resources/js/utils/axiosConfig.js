import axios from 'axios';

const axiosInstance = axios.create({
    baseURL: '/api/v1',
    withCredentials: true, // Ensures cookies are sent with requests
});

// Response interceptor to handle unauthorized access
axiosInstance.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response && error.response.status === 401) {
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

export default axiosInstance;
