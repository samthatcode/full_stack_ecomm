// client/src/services/category.js
import api from './api'

export const Category = {
    /**
     * Get all categories
     */
    getAllCategories(params = {}) {
        return api.get('/get_all_categories', { params })
    },

    /**
     * Get a single category
     */
    getCategoryById(category_id) {
        return api.get(`/super_admin/category/${category_id}/get_single_category`)
    },

    /**
     * Create a new category
     */
    createCategory(data) {
        return api.post('/super_admin/category/create_product_category', data)
    },

    /**
     * Update a category
     */
    updateCategory(category_id, data) {
        return api.put(`/super_admin/category/${category_id}/update_category`, data)
    },

    /**
     * Delete a category
     */
    deleteCategory(category_id) {
        return api.delete(`/super_admin/category/${category_id}/delete_category`)
    },
}

export default Category
