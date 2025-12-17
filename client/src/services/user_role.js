import api from './api'

export const UserRole = {
    getAllUsers(params = {}) {
        return api.get('/dashboard/users/get_all_users', { params })
    },

    getSingleUser(user_id) {
        return api.get(`/dashboard/users/${user_id}/get_single_user`)
    },

    getAllRoles() {
        return api.get('/dashboard/roles')
    },

    getAllPermissions() {
        return api.get('/dashboard/permissions')
    },

    assignRoles(user_id, roles) {
        return api.post(`/dashboard/users/${user_id}/assign_roles`, { roles })
    },

    assignPermissions(user_id, permissions) {
        return api.post(`/dashboard/users/${user_id}/assign_permissions`, { permissions })
    },

    removeRole(user_id, role) {
        return api.delete(`/dashboard/users/${user_id}/remove_role`, { data: { role } })
    },

    removePermission(user_id, permission) {
        return api.delete(`/dashboard/users/${user_id}/remove_permission`, { data: { permission } })
    }
}