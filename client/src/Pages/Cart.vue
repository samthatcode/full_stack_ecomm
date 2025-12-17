<template>
  <div class="min-h-screen bg-background">
    <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
      <!-- Empty Cart -->
      <div v-if="items.length === 0" class="py-12 text-center">
        <div
          class="flex items-center justify-center w-24 h-24 mx-auto mb-6 rounded-full bg-muted/20"
        >
          <ShoppingBag :size="48" class="text-muted-foreground" />
        </div>
        <h1 class="mb-4 text-3xl font-bold md:text-4xl text-foreground">
          Your Cart is Empty
        </h1>
        <p class="max-w-md mx-auto mb-8 text-lg text-muted-foreground">
          Looks like you haven't added any superfoods to your cart yet. Start
          exploring our premium collection!
        </p>
        <router-link to="/products">
          <Button
            class="px-8 py-4 text-lg font-semibold text-white transition-all duration-300 shadow-lg bg-primary hover:bg-primary/90 rounded-xl hover:shadow-xl"
          >
            Start Shopping
          </Button>
        </router-link>
      </div>

      <!-- Cart with Items -->
      <div v-else>
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
          <router-link
            to="/products"
            class="flex items-center gap-2 transition-colors text-muted-foreground hover:text-primary"
          >
            <ArrowLeft :size="20" />
            <span class="hidden sm:inline">Continue Shopping</span>
          </router-link>
          <h1 class="text-3xl font-bold md:text-4xl text-foreground">
            Shopping Cart
          </h1>
        </div>

        <div class="grid gap-8 lg:grid-cols-3">
          <!-- Cart Items -->
          <div class="space-y-4 lg:col-span-2">
            <div
              v-for="item in items"
              :key="item.id"
              class="p-6 transition-all duration-200 bg-white border shadow-sm rounded-2xl border-border hover:shadow-md"
            >
              <div class="flex gap-4">
                <div class="relative">
                  <img
                    :src="item.image || '/placeholder.svg'"
                    :alt="item.name"
                    class="object-cover w-20 h-20 sm:w-24 sm:h-24 rounded-xl"
                  />
                  <div
                    class="absolute flex items-center justify-center w-6 h-6 text-xs font-bold text-white rounded-full -top-2 -right-2 bg-secondary"
                  >
                    {{ item.quantity }}
                  </div>
                </div>

                <div class="flex-1 min-w-0">
                  <h3
                    class="mb-1 text-lg font-bold text-foreground line-clamp-2"
                  >
                    {{ item.name }}
                  </h3>
                  <p class="mb-3 text-xl font-bold text-primary">
                    ₦{{ item.price }}
                  </p>

                  <!-- Quantity Controls -->
                  <div class="flex items-center gap-3">
                    <div
                      class="flex items-center gap-2 p-1 bg-background rounded-xl"
                    >
                      <button
                        @click="
                          updateQuantity(
                            item.id,
                            Math.max(1, item.quantity - 1)
                          )
                        "
                        class="p-2 transition-colors rounded-lg hover:bg-muted"
                        :disabled="item.quantity <= 1"
                      >
                        <Minus :size="16" />
                      </button>
                      <span
                        class="px-3 py-1 font-semibold min-w-[2rem] text-center"
                      >
                        {{ item.quantity }}
                      </span>
                      <button
                        @click="updateQuantity(item.id, item.quantity + 1)"
                        class="p-2 transition-colors rounded-lg hover:bg-muted"
                      >
                        <Plus :size="16" />
                      </button>
                    </div>

                    <div class="text-right">
                      <p class="text-sm text-muted-foreground">Total</p>
                      <p class="text-lg font-bold text-primary">
                        ₦{{ item.price * item.quantity }}
                      </p>
                    </div>
                  </div>
                </div>

                <button
                  @click="removeItem(item.id)"
                  class="self-start p-3 transition-colors text-destructive hover:bg-destructive/10 rounded-xl"
                >
                  <Trash2 :size="20" />
                </button>
              </div>
            </div>
          </div>

          <!-- Order Summary -->
          <div
            class="sticky p-6 bg-white border shadow-sm rounded-2xl border-border h-fit top-8"
          >
            <h2 class="mb-6 text-2xl font-bold text-foreground">
              Order Summary
            </h2>

            <div class="pb-6 mb-6 space-y-4 border-b border-border">
              <div class="flex justify-between">
                <span class="text-muted-foreground">Subtotal</span>
                <span class="font-semibold">₦{{ subtotal }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-muted-foreground">Shipping</span>
                <span class="font-semibold">
                  <span v-if="shipping === 0" class="font-bold text-secondary"
                    >Free</span
                  >
                  <span v-else>₦{{ shipping }}</span>
                </span>
              </div>
              <div class="flex justify-between">
                <span class="text-muted-foreground">Tax (18%)</span>
                <span class="font-semibold">₦{{ tax }}</span>
              </div>
            </div>

            <div class="flex justify-between mb-6">
              <span class="text-xl font-bold text-foreground">Total</span>
              <span class="text-3xl font-bold text-primary">₦{{ total }}</span>
            </div>

            <!-- Free Shipping Banner -->
            <div
              v-if="subtotal < 1000"
              class="p-4 mb-6 border bg-secondary/10 border-secondary/20 rounded-xl"
            >
              <p class="text-sm font-medium text-center text-secondary">
                Add ₦{{ 1000 - subtotal }} more for free shipping!
              </p>
            </div>

            <div class="space-y-3">
              <Button
                class="w-full py-4 text-lg font-semibold text-white transition-all duration-300 shadow-lg bg-primary hover:bg-primary/90 rounded-xl hover:shadow-xl"
                @click="goToCheckout"
              >
                Proceed to Checkout
              </Button>
              <router-link :to="{ name: 'Products' }" class="w-full">
                <Button
                  variant="outline"
                  class="w-full py-3 font-semibold bg-transparent border-2 border-primary text-primary hover:bg-primary/5 rounded-xl"
                >
                  Continue Shopping
                </Button>
              </router-link>
            </div>

            <!-- Security Badge -->
            <div class="pt-6 mt-6 border-t border-border">
              <div
                class="flex items-center justify-center gap-2 text-sm text-muted-foreground"
              >
                <div
                  class="flex items-center justify-center w-4 h-4 rounded-full bg-secondary"
                >
                  <div class="w-2 h-2 bg-white rounded-full"></div>
                </div>
                <span>Secure Checkout</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Trash2, Plus, Minus, ArrowLeft, ShoppingBag } from "lucide-vue-next";
import Button from "@/components/ui/Button.vue";
import { useCartStore } from "@/stores/cart";
import { Auth } from "../services/auth";

export default {
  name: "Cart",

  components: {
    Trash2,
    Plus,
    Minus,
    ArrowLeft,
    ShoppingBag,
    Button,
  },

  computed: {
    items() {
      const cartStore = useCartStore();
      return cartStore.items;
    },

    subtotal() {
      return this.items.reduce(
        (sum, item) => sum + item.price * item.quantity,
        0
      );
    },

    shipping() {
      return this.subtotal > 1000 ? 0 : 100;
    },

    tax() {
      return Math.round(this.subtotal * 0.18);
    },

    total() {
      return this.subtotal + this.shipping + this.tax;
    },
  },

  methods: {
    goToCheckout() {
      if (!Auth.isAuthenticated()) {
        this.$router.push({
          name: "Login",
          query: { redirect: "/checkout" },
        });
      } else {
        this.$router.push("/checkout");
      }
    },
    removeItem(id) {
      const cartStore = useCartStore();
      cartStore.removeItem(id);
    },

    updateQuantity(id, quantity) {
      const cartStore = useCartStore();
      cartStore.updateQuantity(id, quantity);
    },
  },
};
</script>
