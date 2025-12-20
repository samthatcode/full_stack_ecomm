import api from './api'

export const Orders = {
    getAllOrders(params = {}) {
        return api.post('/dashboard/orders/get_customer_orders', { params })
    },

    getOrder(order_id) {
        return api.post(`/dashboard/orders/${order_id}/get_single_order`)
    },

    updateStatus(order_id, status) {
        return api.put(`/dashboard/orders/${order_id}/update_order_status`, { status })
    }
}