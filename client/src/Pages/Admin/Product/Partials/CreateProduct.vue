<template>
  <div
    class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto bg-black bg-opacity-50"
  >
    <div
      class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[95vh] overflow-y-auto"
    >
      <div class="flex items-center justify-between p-6 border-b">
        <h3 class="text-2xl font-bold text-gray-900">Add New Product</h3>
        <button
          @click="$emit('close')"
          class="p-2 rounded-lg hover:bg-gray-100"
        >
          <X :size="24" class="text-gray-500" />
        </button>
      </div>

      <form @submit.prevent="submitForm" class="p-6 space-y-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <!-- Name -->
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-700"
              >Product Name <span class="text-red-500">*</span></label
            >
            <input
              v-model="form.name"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
              placeholder="e.g. Organic Almonds"
            />
          </div>

          <!-- SKU -->
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-700"
              >SKU <span class="text-red-500">*</span></label
            >
            <input
              v-model="form.sku"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
              placeholder="e.g. ALM-001"
            />
          </div>

          <!-- Price -->
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-700"
              >Price (₦) <span class="text-red-500">*</span></label
            >
            <input
              v-model.number="form.price"
              type="number"
              step="0.01"
              min="0"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
            />
          </div>

          <!-- Stock -->
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-700"
              >Stock Quantity <span class="text-red-500">*</span></label
            >
            <input
              v-model.number="form.stock_quantity"
              type="number"
              min="0"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
            />
          </div>

          <!-- Category -->
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-700"
              >Category <span class="text-red-500">*</span></label
            >
            <select
              v-model="form.category_id"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
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
              placeholder="e.g. 250g, 1kg"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
            />
          </div>

          <!-- Status -->
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-700"
              >Status</label
            >
            <select
              v-model="form.is_active"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
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
            class="p-6 text-center border-2 border-gray-300 border-dashed rounded-lg"
          >
            <input
              type="file"
              accept="image/*"
              @change="onFileChange"
              class="hidden"
              ref="fileInput"
              id="product-image"
            />
            <label for="product-image" class="cursor-pointer">
              <div v-if="!previewImage" class="text-gray-500">
                <Package class="w-12 h-12 mx-auto mb-3 text-gray-400" />
                <p class="text-sm">Click to upload image</p>
                <p class="mt-1 text-xs text-gray-400">
                  PNG, JPG, WebP up to 2MB
                </p>
              </div>
              <img
                v-else
                :src="previewImage"
                alt="Preview"
                class="object-contain mx-auto rounded-lg max-h-64"
              />
            </label>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-4 pt-6 border-t">
          <button
            type="button"
            @click="$emit('close')"
            class="px-6 py-3 font-medium border border-gray-300 rounded-lg hover:bg-gray-50"
          >
            Cancel
          </button>
          <button
            type="submit"
            :disabled="saving"
            class="px-6 py-3 font-medium text-white rounded-lg bg-primary hover:bg-primary/90 disabled:opacity-70"
          >
            {{ saving ? "Creating..." : "Create Product" }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Package, X } from "lucide-vue-next";
import { Products } from "@/services/products";

export default {
  components: { Package, X },
  props: {},
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
        image: null,
      },
      previewImage: null,
      categories: [],
      saving: false,
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
    resetForm() {
      this.form = {
        name: "",
        sku: "",
        price: 0,
        stock_quantity: 0,
        category_id: "",
        weight: "",
        is_active: 1,
        image: null,
      };
      this.previewImage = null;
      if (this.$refs.fileInput) this.$refs.fileInput.value = "";
    },
    async fetchCategories() {
      try {
        const res = await Category.getAllCategories();
        this.categories = res.data.data || res.data;
      } catch (err) {
        console.error("Failed to fetch categories:", err);
      }
    },

    async submitForm() {
      this.saving = true;
      try {
        const formData = new FormData();

        // Append all form fields
        Object.keys(this.form).forEach((key) => {
          if (this.form[key] !== null && this.form[key] !== "") {
            formData.append(key, this.form[key]);
          }
        });
        await Products.createProduct(formData);

        this.$emit("product_created");
        this.$emit("close");
      } catch (err) {
        console.error(err);
        alert(err.response?.data?.message || "Failed to save product");
      } finally {
        this.saving = false;
      }
    },
  },

  mounted() {
    this.fetchCategories();
  },
};
</script>
