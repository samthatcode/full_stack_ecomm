<!-- src/pages/admin/Products.vue -->
<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <h2 class="text-2xl font-bold text-gray-900">Products</h2>
      <button @click="openProductModal()" class="btn-primary">
        Add Product
      </button>
    </div>

    <!-- Search & Filter -->
    <div class="flex flex-col gap-4 sm:flex-row">
      <div class="flex-1">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search products..."
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
          @input="handleSearch"
        />
      </div>
      <select
        v-model="selectedCategory"
        @change="handleFilter"
        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
      >
        <option value="">All Categories</option>
        <option
          v-for="category in categories"
          :key="category.id"
          :value="category.id"
        >
          {{ category.name }}
        </option>
      </select>
      <select
        v-model="selectedStatus"
        @change="handleFilter"
        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
      >
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
      </select>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="py-8 text-center">
      <p class="text-gray-500">Loading products...</p>
    </div>

    <!-- Products Table (Desktop) -->
    <div
      v-else
      class="hidden overflow-x-auto bg-white rounded-lg shadow lg:block"
    >
      <table class="w-full">
        <thead class="border-b border-gray-200 bg-gray-50">
          <tr>
            <th
              class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"
            >
              Image
            </th>
            <th
              class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"
            >
              Name
            </th>
            <th
              class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"
            >
              SKU
            </th>
            <th
              class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"
            >
              Category
            </th>
            <th
              class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"
            >
              Price
            </th>
            <th
              class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"
            >
              Stock
            </th>
            <th
              class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"
            >
              Status
            </th>
            <th
              class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase"
            >
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr
            v-for="product in products"
            :key="product.id"
            class="hover:bg-gray-50"
          >
            <td class="px-6 py-4 whitespace-nowrap">
              <img
                :src="product.image || '/placeholder.svg'"
                :alt="product.name"
                class="object-cover w-12 h-12 rounded-lg"
              />
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900">
                {{ product.name }}
              </div>
              <div class="text-sm text-gray-500">
                {{ product.short_description }}
              </div>
            </td>
            <td
              class="px-6 py-4 font-mono text-sm text-gray-500 whitespace-nowrap"
            >
              {{ product.sku }}
            </td>
            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
              {{ product.category?.name || "N/A" }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900">
                ₦{{ parseFloat(product.price).toFixed(2) }}
              </div>
              <div
                v-if="
                  product.sale_price && product.sale_price !== product.price
                "
                class="text-xs text-green-600"
              >
                Sale: ₦{{ parseFloat(product.sale_price).toFixed(2) }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span
                :class="[
                  'text-sm font-medium',
                  product.stock_quantity > 50
                    ? 'text-green-600'
                    : product.stock_quantity > 10
                    ? 'text-yellow-600'
                    : 'text-red-600',
                ]"
              >
                {{ product.stock_quantity }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span
                :class="[
                  'px-2 py-1 rounded-full text-xs font-medium',
                  product.is_active
                    ? 'bg-green-100 text-green-700'
                    : 'bg-red-100 text-red-700',
                ]"
              >
                {{ product.is_active ? "Active" : "Inactive" }}
              </span>
              <span
                v-if="product.is_featured"
                class="px-2 py-1 ml-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full"
              >
                Featured
              </span>
            </td>
            <td
              class="px-6 py-4 space-x-2 text-sm font-medium whitespace-nowrap"
            >
              <button
                @click="editProduct(product)"
                class="text-primary hover:text-primary/80"
              >
                Edit
              </button>
              <button
                @click="confirmDelete(product.id)"
                class="text-red-600 hover:text-red-800"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Empty State -->
      <div v-if="products.length === 0" class="py-12 text-center">
        <p class="text-gray-500">No products found</p>
      </div>
    </div>

    <!-- Mobile Cards -->
    <div v-if="products.length > 0" class="space-y-4 lg:hidden">
      <div
        v-for="product in products"
        :key="product.id"
        class="p-4 space-y-3 bg-white rounded-lg shadow"
      >
        <div class="flex items-start gap-4">
          <img
            :src="product.image || '/placeholder.svg'"
            :alt="product.name"
            class="flex-shrink-0 object-cover w-20 h-20 rounded-lg"
          />
          <div class="flex-1 min-w-0">
            <h3 class="font-medium text-gray-900 truncate">
              {{ product.name }}
            </h3>
            <p class="font-mono text-sm text-gray-500">{{ product.sku }}</p>
            <p class="text-sm text-gray-500">{{ product.category?.name }}</p>
          </div>
        </div>

        <div class="flex items-center justify-between">
          <div>
            <p class="text-lg font-bold text-primary">
              ₦{{ parseFloat(product.price).toFixed(2) }}
            </p>
            <p class="text-sm text-gray-500">
              Stock: {{ product.stock_quantity }}
            </p>
          </div>
          <div class="text-right">
            <span
              :class="[
                'px-2 py-1 rounded-full text-xs font-medium',
                product.is_active
                  ? 'bg-green-100 text-green-700'
                  : 'bg-red-100 text-red-700',
              ]"
            >
              {{ product.is_active ? "Active" : "Inactive" }}
            </span>
          </div>
        </div>

        <div class="flex gap-2 pt-2 border-t border-gray-200">
          <button
            @click="editProduct(product)"
            class="flex-1 px-4 py-2 text-sm font-medium border rounded-lg text-primary border-primary hover:bg-primary/5"
          >
            Edit
          </button>
          <button
            @click="confirmDelete(product.id)"
            class="flex-1 px-4 py-2 text-sm font-medium text-red-600 border border-red-600 rounded-lg hover:bg-red-50"
          >
            Delete
          </button>
        </div>
      </div>

      <!-- Empty State -->
      <div
        v-if="products.length === 0"
        class="py-12 text-center bg-white rounded-lg"
      >
        <p class="text-gray-500">No products found</p>
      </div>
    </div>

    <!-- Pagination -->
    <div
      v-if="pagination.total > pagination.per_page"
      class="flex items-center justify-center gap-2"
    >
      <button
        @click="changePage(pagination.current_page - 1)"
        :disabled="pagination.current_page === 1"
        class="px-4 py-2 border border-gray-300 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
      >
        Previous
      </button>
      <span class="text-sm text-gray-600">
        Page {{ pagination.current_page }} of {{ pagination.last_page }}
      </span>
      <button
        @click="changePage(pagination.current_page + 1)"
        :disabled="pagination.current_page === pagination.last_page"
        class="px-4 py-2 border border-gray-300 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
      >
        Next
      </button>
    </div>

    <!-- Product Modal -->
    <CreateProduct
      v-if="showModal"
      @close="closeModal"
      @product_created="fetchProducts"
    />

    <EditProduct
      v-if="showEditModal"
      :current_product="current_product"
      :categories="categories"
      @close="closeEditModal"
      @product_updated="fetchProducts"
    />
  </div>
</template>

<script>
import { Products } from "@/services/products";
import { Category } from "@/services/category";
import CreateProduct from "./Partials/CreateProduct.vue";
import EditProduct from "./Partials/EditProduct.vue";

export default {
  components: {
    CreateProduct,
    EditProduct,
  },

  data() {
    return {
      products: [],
      categories: [],
      showModal: false,
      showEditModal: false,
      loading: false,
      searchQuery: "",
      selectedCategory: "",
      selectedStatus: "",
      pagination: {
        current_page: 1,
        last_page: 1,
        per_page: 10,
        total: 0,
      },
      current_product: null,
    };
  },

  computed: {
    filteredProducts() {
      let filtered = [...this.products];

      // Search filter
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase();
        filtered = filtered.filter(
          (product) =>
            product.name.toLowerCase().includes(query) ||
            product.sku.toLowerCase().includes(query) ||
            product.description?.toLowerCase().includes(query)
        );
      }

      // Category filter
      if (this.selectedCategory) {
        filtered = filtered.filter(
          (product) => product.category_id === parseInt(this.selectedCategory)
        );
      }

      // Status filter
      if (this.selectedStatus) {
        const isActive = this.selectedStatus === "active";
        filtered = filtered.filter((product) => product.is_active === isActive);
      }

      return filtered;
    },
  },

  methods: {
    async fetchProducts(page = 1) {
      this.loading = true;
      try {
        const res = await Products.getAllProducts({ page });
        this.products = res.data.data?.data;

        // Handle pagination metadata
        if (res.data.current_page) {
          this.pagination = {
            current_page: data.current_page,
            last_page: data.last_page,
            per_page: data.per_page,
            total: data.total,
          };
        }
      } catch (err) {
        console.error("Failed to fetch products:", err);
      } finally {
        this.loading = false;
      }
    },

    async fetchCategories() {
      try {
        const res = await Category.getAllCategories();
        this.categories = res.data.data || res.data;
      } catch (err) {
        console.error("Failed to fetch categories:", err);
      }
    },

    openProductModal() {
      this.current_product = null;
      this.showModal = true;
    },

    editProduct(product) {
      this.current_product = product;
      this.showEditModal = true;
    },

    closeModal() {
      this.showModal = false;
    },
    closeEditModal() {
      this.showEditModal = false;
      this.current_product = null;
    },

    async confirmDelete(id) {
      if (!confirm("Are you sure you want to delete this product?")) {
        return;
      }

      try {
        await Products.deleteProduct(id);
        this.fetchProducts(this.pagination.current_page);
      } catch (err) {
        console.error("Failed to delete product:", err);
      }
    },

    handleSearch() {
      // Debounce search if needed
      // For now, the computed property handles it
    },

    handleFilter() {
      // Filters are handled by computed property
    },

    changePage(page) {
      if (page >= 1 && page <= this.pagination.last_page) {
        this.fetchProducts(page);
      }
    },
  },

  mounted() {
    this.fetchProducts();
    this.fetchCategories();
  },
};
</script>

<style scoped>
.btn-primary {
  @apply px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary/90 transition-colors;
}
</style>
