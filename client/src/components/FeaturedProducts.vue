<template>
  <section class="py-16 bg-white md:py-20">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="mb-12 text-center">
        <h2
          class="mb-4 text-3xl font-bold md:text-4xl lg:text-5xl text-foreground"
        >
          Featured Superfoods
        </h2>
        <p
          class="max-w-3xl mx-auto text-lg leading-relaxed md:text-xl text-muted-foreground"
        >
          Handpicked selections of our most popular superfoods, carefully
          sourced for maximum nutrition and flavor
        </p>
      </div>

      <div
        class="grid grid-cols-1 gap-6 mb-12 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 lg:gap-8"
      >
        <ProductCard
          v-for="product in allProducts"
          :key="product.id"
          :product="product"
        />
      </div>

      <div class="text-center">
        <router-link to="/products">
          <Button
            class="px-8 py-4 text-lg font-semibold text-white transition-all duration-300 shadow-lg bg-primary hover:bg-primary/90 rounded-xl hover:shadow-xl"
          >
            View All Products
          </Button>
        </router-link>
      </div>
    </div>
  </section>
</template>

<script>
import { Products } from "../services/products";
import ProductCard from "./ProductCard.vue";
import Button from "./ui/Button.vue";

export default {
  name: "FeaturedProducts",

  components: {
    ProductCard,
    Button,
  },

  data() {
    return {
      allProducts: [],
    };
  },
  methods: {
    getAllProducts() {
      Products.getAllProducts()
        .then((res) => {
          let products = res.data?.data?.data;

          const featured_products = products.filter(
            (p) => p.is_featured === true
          );

          this.allProducts =
            featured_products.length > 0
              ? featured_products.slice(0, 12)
              : products.slice(0, 12);
        })
        .catch((err) => {
          console.error("Error fetching products:", err);
          this.allProducts = [];
        });
    },
    formatPrice(price) {
      return price.toFixed(2);
    },
  },

  mounted() {
    this.getAllProducts();
  },
};
</script>
