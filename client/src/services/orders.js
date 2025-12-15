import api from './api'

export const Orders = {
    getAllOrders(params = {}) {
        return api.get('/super_admin/get_all_orders', { params })
    },

    getOrder(order_id) {
        return api.get(`/super_admin/orders/${order_id}/get_single_order`)
    },

    updateStatus(order_id, status) {
        return api.put(`/super_admin/orders/${order_id}/update_order_status`, { status })
    }
}