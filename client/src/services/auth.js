import api from './api'

export const Auth = {
    login(credentials) {
        return api.post('auth/login', credentials)
    },

    register(user_data) {
        return api.post('auth/register', user_data)
    },

    logout() {
        return api.post('auth/logout')
    },

    getCurrentUser() {
        return api.get('auth/profile')
    },

    is_authenticated() {
        return !!localStorage.getItem('auth_token')
    }
}