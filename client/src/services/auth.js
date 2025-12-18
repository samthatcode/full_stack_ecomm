import api from './api'

export const Auth = {
    async login(credentials) {
        const res = await api.post('auth/login', credentials)

        localStorage.setItem('auth_token', res.data.token)
        localStorage.setItem('user', JSON.stringify(res.data.user))
    },

    register(user_data) {
        return api.post('auth/register', user_data)
    },

    async logout() {
        try {
            await api.post('auth/logout')
        } finally {
            localStorage.removeItem('auth_token')
            localStorage.removeItem('user')
        }
    },

    is_authenticated() {
        return !!localStorage.getItem('auth_token')
    },

    get_user() {
        const user = localStorage.getItem('user')
        return user ? JSON.parse(user) : null
    },

    has_role(role) {
        const user = this.get_user()
        return user?.roles?.includes(role) || false
    },

    is_admin() {
        return this.has_role('super_admin')
    },

    has_permission(permission) {
        const user = this.get_user()
        return user?.permissions?.includes(permission) || false
    },
    current_user() {
        return this.get_user()
    }
}