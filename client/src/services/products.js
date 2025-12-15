import api from './api'

export const Products = {
    getAllProducts(params = {}) {
        return api.get('/products/get_all_products', { params })
    },

    getProductById(product_id) {
        return api.get(`/products/${product_id}/get_single_product`)
    },

    createProduct(data) {
        return api.post('/super_admin/products/create_products', data)
    },

    updateProduct(product_id, data) {
        return api.put(`/super_admin/products/${product_id}/update_product`, data)
    },

    deleteProduct(product_id) {
        return api.delete(`/super_admin/products/${product_id}/delete_product`)
    },
}
