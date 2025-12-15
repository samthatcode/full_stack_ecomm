<template>
  <div
    class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto bg-black bg-opacity-50"
  >
    <div
      class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[95vh] overflow-y-auto"
    >
      <!-- Header -->
      <div
        class="flex items-center justify-between p-6 border-b border-gray-200"
      >
        <h3 class="text-2xl font-bold text-gray-900">Edit Product</h3>
        <button
          @click="$emit('close')"
          class="p-2 transition-colors rounded-lg hover:bg-gray-100"
        >
          <X :size="24" class="text-gray-500" />
        </button>
      </div>

      <!-- Form -->
      <form @submit.prevent="updateProduct" class="p-6 space-y-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <!-- Product Name -->
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">
              Product Name <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.name"
              type="text"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
            />
          </div>

          <!-- SKU -->
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">
              SKU <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.sku"
              type="text"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
            />
          </div>

          <!-- Price -->
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">
              Price (₹) <span class="text-red-500">*</span>
            </label>
            <input
              v-model.number="form.price"
              type="number"
              step="0.01"
              min="0"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
            />
          </div>

          <!-- Stock -->
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">
              Stock Quantity <span class="text-red-500">*</span>
            </label>
            <input
              v-model.number="form.stock_quantity"
              type="number"
              min="0"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
            />
          </div>

          <!-- Category -->
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">
              Category <span class="text-red-500">*</span>
            </label>
            <select
              v-model="form.category_id"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
            >
              <option value="">Select Category</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                {{ cat.name }}
              </option>
            </select>
          </div>

          <!-- Weight -->
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-700"
              >Weight</label
            >
            <input
              v-model="form.weight"
              type="text"
              placeholder="e.g. 250g"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
            />
          </div>

          <!-- Status -->
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-700"
              >Status</label
            >
            <select
              v-model="form.is_active"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
            >
              <option :value="1">Active</option>
              <option :value="0">Inactive</option>
            </select>
          </div>
        </div>

        <!-- Image Upload -->
        <div>
          <label class="block mb-2 text-sm font-medium text-gray-700"
            >Product Image</label
          >
          <div
            class="p-8 text-center border-2 border-gray-300 border-dashed rounded-xl bg-gray-50"
          >
            <input
              type="file"
              accept="image/*"
              @change="onFileChange"
              ref="fileInput"
              class="hidden"
              id="edit-product-image"
            />
            <label for="edit-product-image" class="block cursor-pointer">
              <div v-if="!previewImage" class="text-gray-500">
                <Package class="w-16 h-16 mx-auto mb-4 text-gray-400" />
                <p class="font-medium">Click to change image</p>
                <p class="mt-1 text-sm text-gray-400">
                  PNG, JPG, WebP up to 2MB
                </p>
              </div>
              <img
                v-else
                :src="previewImage"
                alt="New preview"
                class="object-contain mx-auto rounded-lg shadow-md max-h-80"
              />
            </label>
          </div>

          <!-- Current image (if no new upload) -->
          <p
            v-if="!previewImage && current_product.image"
            class="mt-4 text-sm text-center text-gray-600"
          >
            Current image:
            <a
              :href="current_product.image"
              target="_blank"
              class="ml-1 text-primary hover:underline"
            >
              View Current Image
            </a>
          </p>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
          <button
            type="button"
            @click="$emit('close')"
            class="px-8 py-3 font-medium border border-gray-300 rounded-lg hover:bg-gray-50"
          >
            Cancel
          </button>
          <button
            type="submit"
            :disabled="loading"
            class="px-8 py-3 font-medium text-white rounded-lg bg-primary hover:bg-primary/90 disabled:opacity-70"
          >
            {{ loading ? "Updating..." : "Update Product" }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { X, Package } from "lucide-vue-next";
import { Products } from "@/services/products";
import { Category } from "@/services/category";

export default {
  components: { X, Package },
  props: {
    current_product: {
      type: Object,
      required: true,
    },
    categories: {
      type: Array,
      required: true,
    },
  },
  emits: ["close", "saved"],
  data() {
    return {
      form: {
        name: "",
        sku: "",
        price: 0,
        stock_quantity: 0,
        category_id: "",
        weight: "",
        is_active: 1,
        image: null, // File object if changed
      },
      previewImage: null,
      loading: false,
    };
  },
  methods: {
    onFileChange(e) {
      const file = e.target.files[0];
      if (file) {
        this.form.image = file;
        this.previewImage = URL.createObjectURL(file);
      }
    },
    async updateProduct() {
      this.loading = true;
      try {
        const formData = new FormData();

        formData.append("name", this.form.name);
        formData.append("sku", this.form.sku);
        formData.append("price", this.form.price);
        formData.append("stock_quantity", this.form.stock_quantity);
        formData.append("category_id", this.form.category_id);
        formData.append("weight", this.form.weight || "");
        formData.append("is_active", this.form.is_active);

        if (this.form.image instanceof File) {
          formData.append("image", this.form.image);
        }

        await Products.updateProduct(this.current_product.id, formData);

        this.$emit("product_updated");
        this.$emit("close");
      } catch (err) {
        console.error(err);
        alert(err.response?.data?.message || "Failed to update product");
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
  },

  created() {
    this.form.name = this.current_product.name || "";
    this.form.sku = this.current_product.sku || "";
    this.form.price = parseFloat(this.current_product.price) || 0;
    this.form.stock_quantity = this.current_product.stock_quantity || 0;
    this.form.category_id = this.current_product.category_id || "";
    this.form.weight = this.current_product.weight || "";
    this.form.is_active = this.current_product.is_active ? 1 : 0;

    // Show current image preview
    this.previewImage = this.current_product.image || null;
  },

  mounted() {
    this.fetchCategories();
  },
};
</script>
