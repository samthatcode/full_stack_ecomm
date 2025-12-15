import api from './api'

export const Auth = {
    login(route_fn, credentials) {
        return api.post(route_fn('auth.login'), credentials)
    },

    register(route_fn, user_data) {
        return api.post(route_fn('auth.register'), user_data)
    },

    logout(route_fn) {
        return api.post(route_fn('auth.logout'))
    },

    getCurrentUser(route_fn) {
        return api.get(route_fn('user.profile'))
    },

    is_authenticated() {
        return !!localStorage.getItem('auth_token')
    }
}