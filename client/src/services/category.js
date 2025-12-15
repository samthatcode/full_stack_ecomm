import api from './api'

export const Category = {
    /**
     * Get all categories
     */
    getAllCategories(params = {}) {
        return api.get('/category/get_all_categories', { params })
    },

    /**
     * Get a single category
     */
    getCategoryById(category_id) {
        return api.get(`/category/${category_id}/get_single_category`)
    },

    /**
     * Create a new category
     */
    createCategory(data) {
        return api.post('/category/create_product_category', data)
    },

    /**
     * Update a category
     */
    updateCategory(category_id, data) {
        return api.put(`/category/${category_id}/update_category`, data)
    },

    /**
     * Delete a category
     */
    deleteCategory(category_id) {
        return api.delete(`/category/${category_id}/delete_category`)
    },
}

export default Category
