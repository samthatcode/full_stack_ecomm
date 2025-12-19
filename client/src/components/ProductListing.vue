<template>
  <div class="space-y-6">
    <!-- Mobile Filter Toggle -->
    <div class="flex items-center justify-between md:hidden">
      <h2 class="text-xl font-bold text-foreground">Filter Products</h2>
      <Button
        variant="outline"
        @click="showFilters = !showFilters"
        class="flex items-center gap-2"
      >
        <Filter :size="16" />
        Filters
      </Button>
    </div>

    <!-- Filters -->
    <div :class="['space-y-6', showFilters ? 'block' : 'hidden md:block']">
      <!-- Category Filter -->
      <div class="p-6 bg-white border rounded-xl border-border">
        <h3 class="mb-4 text-lg font-semibold text-foreground">Category</h3>
        <div class="flex flex-wrap gap-3">
          <Button
            :variant="selected_category === null ? 'default' : 'outline'"
            @click="selected_category = null"
            :class="[
              'rounded-xl px-4 py-2',
              selected_category === null
                ? 'bg-primary text-white shadow-md'
                : 'border-primary text-primary hover:bg-primary/5',
            ]"
          >
            All Products
          </Button>
          <Button
            v-for="cat in categories"
            :key="cat"
            :variant="selected_category === cat ? 'default' : 'outline'"
            @click="selected_category = cat"
            v-model="category_id"
            :class="[
              'rounded-xl px-4 py-2',
              selected_category === cat
                ? 'bg-primary text-white shadow-md'
                : 'border-primary text-primary hover:bg-primary/5',
            ]"
          >
            {{ cat.name }}
          </Button>
        </div>
      </div>

      <!-- Sort Filter -->
      <div class="p-6 bg-white border rounded-xl border-border">
        <h3 class="mb-4 text-lg font-semibold text-foreground">Sort By</h3>
        <select
          v-model="sort_by"
          class="w-full px-4 py-3 bg-white border border-border rounded-xl text-foreground focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
        >
          <option value="featured">Featured</option>
          <option value="price-low">Price: Low to High</option>
          <option value="price-high">Price: High to Low</option>
          <option value="rating">Highest Rated</option>
        </select>
      </div>
    </div>

    <!-- Results Count -->
    <div class="flex items-center justify-between">
      <p class="text-muted-foreground">
        Showing {{ total_products }} products
        <span v-if="selected_category"> in {{ selected_category.name }}</span>
      </p>
    </div>

    <!-- Products Grid -->
    <div
      class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
    >
      <ProductCard
        v-for="product in all_products"
        :key="product.id"
        :product="product"
      />
    </div>

    <!-- No Results -->
    <div v-if="total_products === 0" class="py-16 text-center">
      <div
        class="flex items-center justify-center w-24 h-24 mx-auto mb-6 rounded-full bg-muted/20"
      >
        <Filter :size="48" class="text-muted-foreground" />
      </div>
      <h3 class="mb-2 text-xl font-semibold text-foreground">
        No products found
      </h3>
      <p class="mb-6 text-muted-foreground">
        Try adjusting your filters to see more products.
      </p>
      <Button
        @click="clearFilters"
        class="px-6 py-3 text-white bg-primary hover:bg-primary/90 rounded-xl"
      >
        Clear Filters
      </Button>
    </div>
  </div>
</template>

<script>
import { Filter } from "lucide-vue-next";
import ProductCard from "./ProductCard.vue";
import Button from "./ui/Button.vue";
import { Products } from "@/services/products";
import { Category } from "@/services/category";

export default {
  name: "ProductListing",

  components: {
    Filter,
    ProductCard,
    Button,
  },

  data() {
    return {
      selected_category: null,
      sort_by: "",
      category_id: "",
      showFilters: false,
      categories: [],
      all_products: [],
      total_products: 0,
    };
  },

  computed: {},

  methods: {
    getAllProducts() {
      const params = {};

      if (this.selected_category) {
        params.category_id = this.selected_category.id;
      }

      if (this.sort_by) {
        params.sort_by = this.sort_by;
      }
      Products.getAllProducts(params)
        .then((res) => {
          this.all_products = res.data?.data?.data;
          this.total_products =
            res.data?.data?.total || this.all_products.length;
        })
        .catch((err) => {
          console.error("Error fetching products:", err);
          this.all_products = [];
          this.total_products = 0;
        });
    },
    getAllCategories() {
      Category.getAllCategories()
        .then((res) => {
          this.categories = res.data?.data;
        })
        .catch((err) => {
          console.error("Error fetching categories:", err);
          this.categories = [];
        });
    },
    clearFilters() {
      this.selected_category = null;
      this.sort_by = "featured";
    },
  },

  watch: {
    selected_category() {
      this.getAllProducts();
    },
    sort_by() {
      this.getAllProducts();
    },
  },
  mounted() {
    this.getAllProducts();
    this.getAllCategories();
  },
};
</script>
