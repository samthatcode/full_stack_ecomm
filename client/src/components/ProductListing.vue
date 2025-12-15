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
            :variant="selectedCategory === null ? 'default' : 'outline'"
            @click="selectedCategory = null"
            :class="[
              'rounded-xl px-4 py-2',
              selectedCategory === null
                ? 'bg-primary text-white shadow-md'
                : 'border-primary text-primary hover:bg-primary/5',
            ]"
          >
            All Products
          </Button>
          <Button
            v-for="cat in categories"
            :key="cat"
            :variant="selectedCategory === cat ? 'default' : 'outline'"
            @click="selectedCategory = cat"
            :class="[
              'rounded-xl px-4 py-2',
              selectedCategory === cat
                ? 'bg-primary text-white shadow-md'
                : 'border-primary text-primary hover:bg-primary/5',
            ]"
          >
            {{ cat }}
          </Button>
        </div>
      </div>

      <!-- Sort Filter -->
      <div class="p-6 bg-white border rounded-xl border-border">
        <h3 class="mb-4 text-lg font-semibold text-foreground">Sort By</h3>
        <select
          v-model="sortBy"
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
        Showing {{ allProducts.length }} products
        <span v-if="selectedCategory"> in {{ selectedCategory }}</span>
      </p>
    </div>

    <!-- Products Grid -->
    <div
      class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
    >
      <ProductCard
        v-for="product in allProducts"
        :key="product.id"
        :product="product"
      />
    </div>

    <!-- No Results -->
    <div v-if="allProducts.length === 0" class="py-16 text-center">
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
      selectedCategory: null,
      sortBy: "featured",
      showFilters: false,
      categories: [],
      allProducts: [],
    };
  },

  computed: {},

  methods: {
    getAllProducts() {
      Products.getAllProducts()
        .then((res) => {
          this.allProducts = res.data?.data?.data;
        })
        .catch((err) => {
          console.error("Error fetching products:", err);
          this.allProducts = [];
        });
    },
    getAllCategories() {
      Category.getAllCategories()
        .then((res) => {
          this.categories = res.data?.data?.data;
        })
        .catch((err) => {
          console.error("Error fetching categories:", err);
          this.categories = [];
        });
    },
    clearFilters() {
      this.selectedCategory = null;
      this.sortBy = "featured";
    },
  },

  mounted() {
    this.getAllProducts();
    this.getAllCategories();
  },
};
</script>
