import api from './api'

export const Dashboard = {
    getStats() {
        return api.get('/super_admin/dashboard')
    }
}