<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h2 class="text-2xl font-bold text-gray-900">Categories</h2>
      <button @click="openModal()" class="btn-primary">Add Category</button>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
      <div
        v-for="cat in categories"
        :key="cat.id"
        class="p-6 bg-white border rounded-xl"
      >
        <div class="flex justify-between mb-4">
          <h3 class="text-lg font-bold">{{ cat.name }}</h3>
          <button @click="edit(cat)">Edit</button>
        </div>
        <p class="mb-4 text-gray-600">
          {{ cat.description || "No description" }}
        </p>
        <span
          :class="
            cat.is_active
              ? 'bg-green-100 text-green-700'
              : 'bg-gray-100 text-gray-700'
          "
          class="px-2 py-1 text-xs rounded-full"
        >
          {{ cat.is_active ? "Active" : "Inactive" }}
        </span>
      </div>
    </div>

    <!-- Simple Modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
      <div class="w-full max-w-md p-6 bg-white rounded-xl">
        <h3 class="mb-4 text-xl font-bold">
          {{ editing ? "Edit" : "Add" }} Category
        </h3>
        <input
          v-model="form.name"
          placeholder="Name"
          class="w-full px-4 py-2 mb-3 border rounded-lg"
        />
        <textarea
          v-model="form.description"
          placeholder="Description"
          class="w-full px-4 py-2 mb-3 border rounded-lg"
        ></textarea>
        <div class="flex gap-3">
          <button
            @click="showModal = false"
            class="flex-1 py-2 border rounded-lg"
          >
            Cancel
          </button>
          <button
            @click="save"
            class="flex-1 py-2 text-white rounded-lg bg-primary"
          >
            {{ editing ? "Update" : "Create" }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Category } from "@/services/category";

export default {
  data() {
    return {
      categories: [],
      showModal: false,
      editing: null,
      form: { name: "", description: "" },
    };
  },
  mounted() {
    this.fetchCategories();
  },
  methods: {
    async fetchCategories() {
      try {
        const res = await Category.getAllCategories();
        this.categories = res.data.data || res.data;
      } catch (err) {
        console.error(err);
      }
    },
    openModal() {
      this.editing = null;
      this.form = { name: "", description: "" };
      this.showModal = true;
    },
    edit(cat) {
      this.editing = cat;
      this.form = { name: cat.name, description: cat.description || "" };
      this.showModal = true;
    },
    async save() {
      try {
        if (this.editing) {
          await Category.updateCategory(this.editing.id, this.form);
        } else {
          await Category.createCategory(this.form);
        }
        this.showModal = false;
        this.fetchCategories();
      } catch (err) {
        alert("Error saving category");
      }
    },
  },
};
</script>
