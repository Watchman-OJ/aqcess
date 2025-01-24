
import axios from 'axios';

export function login(email, password) {
    return axios.post('/login', {
        email: email,
        password: password
    }).then(response => {
        const token = response.data.token;
        localStorage.setItem('authToken', token);
        console.log('Token:', token);
    }).catch(error => {
        console.error('Login failed:', error.response.data.message);
    });
}
