<template>
  <div
    class="flex flex-col h-full overflow-hidden transition-all duration-300 bg-white border cursor-pointer rounded-2xl border-border hover:shadow-xl group"
  >
    <!-- Image -->
    <div class="relative h-56 overflow-hidden bg-background">
      <router-link :to="`/product/${product.id}`">
        <img
          :src="product.image"
          :alt="product.name"
          class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-105"
        />
      </router-link>

      <!-- Category Badge -->
      <div
        class="absolute px-3 py-1 text-xs font-semibold text-white rounded-full top-3 left-3 bg-secondary/90 backdrop-blur-sm"
      >
        {{ product.category }}
      </div>

      <!-- Wishlist Button -->
      <button
        class="absolute p-2 transition-colors rounded-full shadow-sm top-3 right-3 bg-white/90 backdrop-blur-sm hover:bg-white"
      >
        <Heart :size="16" class="text-muted-foreground hover:text-red-500" />
      </button>
    </div>

    <!-- Content -->
    <div class="flex flex-col flex-1 p-5">
      <router-link :to="`/product/${product.id}`">
        <h3
          class="mb-2 text-lg font-bold transition-colors text-foreground line-clamp-2 hover:text-primary"
        >
          {{ product.name }}
        </h3>
      </router-link>

      <!-- Description -->
      <p
        v-if="product.description"
        class="mb-3 text-sm text-muted-foreground line-clamp-2"
      >
        {{ product.description }}
      </p>

      <!-- Nutritional Info -->
      <div
        v-if="product.nutritionalInfo"
        class="flex items-center gap-4 mb-3 text-xs text-muted-foreground"
      >
        <div class="flex items-center gap-1">
          <Leaf :size="12" class="text-secondary" />
          <span>{{ product.nutritionalInfo.calories }} cal</span>
        </div>
        <div class="flex items-center gap-1">
          <span class="w-2 h-2 rounded-full bg-primary"></span>
          <span>{{ product.nutritionalInfo.protein }} protein</span>
        </div>
        <div class="flex items-center gap-1">
          <span class="w-2 h-2 rounded-full bg-accent"></span>
          <span>{{ product.nutritionalInfo.fiber }} fiber</span>
        </div>
      </div>

      <!-- Rating -->
      <div class="flex items-center gap-2 mb-4">
        <div class="flex items-center">
          <Star
            v-for="i in 5"
            :key="i"
            :size="16"
            :class="
              i <= Math.floor(product.rating)
                ? 'fill-yellow-400 text-yellow-400'
                : 'text-gray-300'
            "
          />
        </div>
        <span class="text-sm text-muted-foreground">
          {{ product.rating }} ({{ product.reviews }} reviews)
        </span>
      </div>

      <!-- Price and Button -->
      <div class="flex items-center justify-between mt-auto">
        <div>
          <span class="text-2xl font-bold text-primary">
            ₦{{ product.price }}
          </span>
          <span class="ml-1 text-sm text-muted-foreground">/pack</span>
        </div>
        <Button
          size="sm"
          class="px-4 py-2 text-white transition-all duration-200 shadow-sm bg-primary hover:bg-primary/90 rounded-xl hover:shadow-md"
          @click="handleAddToCart"
        >
          <ShoppingCart :size="16" class="mr-2" />
          Add
        </Button>
      </div>
    </div>
  </div>
</template>

<script>
import { Star, ShoppingCart, Heart, Leaf } from "lucide-vue-next";
import Button from "./ui/Button.vue";
import { useCartStore } from "@/stores/cart";

export default {
  name: "ProductCard",

  components: {
    Star,
    ShoppingCart,
    Heart,
    Leaf,
    Button,
  },

  props: {
    product: {
      type: Object,
      required: true,
    },
  },

  methods: {
    handleAddToCart() {
      const cartStore = useCartStore();
      cartStore.addItem({
        id: this.product.id,
        name: this.product.name,
        price: this.product.price,
        image: this.product.image,
        quantity: 1,
      });
    },
  },
};
</script>
