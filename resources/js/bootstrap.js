import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Add X-CSRF-TOKEN only for POST, PUT, PATCH, DELETE
window.axios.interceptors.request.use(config => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (csrfToken && ['post', 'put', 'patch', 'delete'].includes(config.method)) {
        config.headers['X-CSRF-TOKEN'] = csrfToken;
    }

    return config;
}, error => {
    return Promise.reject(error);
});
