<template>
  <header
    class="sticky top-0 z-50 border-b bg-background/95 backdrop-blur-sm border-border"
  >
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <!-- Logo -->
        <router-link
          :to="{ name: 'Home' }"
          class="flex items-center flex-shrink-0 gap-2"
        >
          <div
            class="flex items-center justify-center w-10 h-10 shadow-sm bg-gradient-to-br from-primary to-primary-light rounded-xl"
          >
            <span class="text-lg font-bold text-white">RF</span>
          </div>
          <span class="hidden text-lg font-bold text-foreground sm:inline">
            Rufars Foods
          </span>
        </router-link>

        <!-- Desktop Navigation -->
        <nav class="items-center hidden gap-8 md:flex">
          <router-link
            :to="{ name: 'Home' }"
            class="font-medium transition-colors duration-200 text-foreground hover:text-primary"
          >
            Home
          </router-link>
          <router-link
            :to="{ name: 'Products' }"
            class="font-medium transition-colors duration-200 text-foreground hover:text-primary"
          >
            Shop
          </router-link>
          <router-link
            :to="{ name: 'Help' }"
            class="font-medium transition-colors duration-200 text-foreground hover:text-primary"
          >
            Help & Support
          </router-link>
        </nav>

        <!-- Right Side Icons -->
        <div class="flex items-center gap-3">
          <!-- Login & Signup Buttons - Desktop -->
          <div
            v-if="is_authenticated"
            class="items-center hidden gap-2 md:flex"
          >
            <router-link
              :to="{ name: 'Profile' }"
              class="flex items-center gap-2 px-4 py-2 font-medium transition-colors rounded-lg text-foreground hover:bg-accent/10"
            >
              <User :size="18" />
              <span>{{ user_name }}</span>
            </router-link>
            <button
              @click="handleLogout"
              class="px-4 py-2 font-medium transition-colors rounded-lg text-foreground hover:text-destructive hover:bg-accent/10"
            >
              Logout
            </button>
          </div>
          <div v-else class="items-center hidden gap-2 md:flex">
            <router-link
              :to="{ name: 'Login' }"
              class="px-4 py-2 font-medium transition-colors text-foreground hover:text-primary"
            >
              Login
            </router-link>
            <router-link
              :to="{ name: 'Signup' }"
              class="px-4 py-2 font-medium text-white transition-colors rounded-lg bg-primary hover:bg-primary/90"
            >
              Sign Up
            </router-link>
          </div>

          <!-- Search Icon - Mobile -->
          <button
            class="p-2 transition-colors md:hidden text-foreground hover:text-primary"
            @click="is_search_open = !is_search_open"
          >
            <Search :size="20" />
          </button>

          <!-- Admin Icon -->
          <router-link
            v-if="is_authenticated"
            :to="{ name: 'AdminDashboard' }"
            class="p-2 transition-colors text-foreground hover:text-primary"
            title="Admin Dashboard"
          >
            <Shield :size="24" />
          </router-link>

          <!-- Profile Icon -->
          <router-link
            v-if="is_authenticated"
            :to="{ name: 'Profile' }"
            class="p-2 transition-colors text-foreground hover:text-primary"
            title="My Profile"
          >
            <User :size="24" />
          </router-link>

          <!-- Cart Icon -->
          <router-link
            :to="{ name: 'Cart' }"
            class="relative p-2 transition-colors text-foreground hover:text-primary"
          >
            <ShoppingCart :size="24" />
            <span
              v-if="cartCount > 0"
              class="absolute flex items-center justify-center w-5 h-5 text-xs font-bold text-white rounded-full shadow-sm -top-1 -right-1 bg-secondary"
            >
              {{ cartCount }}
            </span>
          </router-link>

          <!-- Mobile Menu Button -->
          <button
            class="p-2 transition-colors md:hidden text-foreground hover:text-primary"
            @click="is_open = !is_open"
          >
            <X v-if="is_open" :size="24" />
            <Menu v-else :size="24" />
          </button>
        </div>
      </div>

      <!-- Mobile Search -->
      <div v-if="is_search_open" class="pb-4 md:hidden">
        <div class="relative">
          <input
            type="text"
            placeholder="Search products..."
            class="w-full px-4 py-3 pl-10 border rounded-lg bg-input border-border text-foreground placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
          />
          <Search
            class="absolute w-4 h-4 transform -translate-y-1/2 left-3 top-1/2 text-muted-foreground"
          />
        </div>
      </div>

      <!-- Mobile Navigation -->
      <nav v-if="is_open" class="pt-4 pb-4 border-t md:hidden border-border">
        <div class="flex flex-col gap-3">
          <router-link
            :to="{ name: 'Home' }"
            class="px-3 py-2 font-medium transition-colors rounded-lg text-foreground hover:text-primary hover:bg-accent/10"
            @click="is_open = false"
          >
            Home
          </router-link>
          <router-link
            :to="{ name: 'Products' }"
            class="px-3 py-2 font-medium transition-colors rounded-lg text-foreground hover:text-primary hover:bg-accent/10"
            @click="is_open = false"
          >
            Shop All Products
          </router-link>
          <router-link
            :to="{ name: 'Help' }"
            class="px-3 py-2 font-medium transition-colors rounded-lg text-foreground hover:text-primary hover:bg-accent/10"
            @click="is_open = false"
          >
            Help & Support
          </router-link>

          <!-- Authenticated User Links - Mobile -->
          <template v-if="is_authenticated">
            <router-link
              :to="{ name: 'Profile' }"
              class="flex items-center gap-2 px-3 py-2 font-medium transition-colors rounded-lg text-foreground hover:text-primary hover:bg-accent/10"
              @click="is_open = false"
            >
              <User :size="18" />
              My Profile
            </router-link>
            <router-link
              :to="{ name: 'AdminDashboard' }"
              class="flex items-center gap-2 px-3 py-2 font-medium transition-colors rounded-lg text-foreground hover:text-primary hover:bg-accent/10"
              @click="is_open = false"
            >
              <Shield :size="18" />
              Admin Dashboard
            </router-link>
            <button
              @click="handleLogout"
              class="px-3 py-2 font-medium text-left transition-colors rounded-lg text-destructive hover:bg-accent/10"
            >
              Logout
            </button>
          </template>

          <!-- Login & Signup - Mobile -->
          <div
            v-else
            class="flex flex-col gap-2 pt-3 mt-2 border-t border-border"
          >
            <router-link
              :to="{ name: 'Login' }"
              class="px-3 py-2 font-medium text-center transition-colors rounded-lg text-foreground hover:text-primary hover:bg-accent/10"
              @click="is_open = false"
            >
              Login
            </router-link>
            <router-link
              :to="{ name: 'Signup' }"
              class="px-3 py-2 font-medium text-center text-white transition-colors rounded-lg bg-primary hover:bg-primary/90"
              @click="is_open = false"
            >
              Sign Up
            </router-link>
          </div>
        </div>
      </nav>
    </div>
  </header>
</template>

<script>
import { ShoppingCart, Menu, X, Search, User, Shield } from "lucide-vue-next";
import { useCartStore } from "@/stores/cart";
import { Auth } from "../services/auth";

export default {
  name: "Header",

  components: {
    ShoppingCart,
    Menu,
    X,
    Search,
    User,
    Shield,
  },

  data() {
    return {
      is_open: false,
      is_search_open: false,
      is_authenticated: false,
      user_name: "",
    };
  },

  methods: {
    checkAuthStatus() {
      this.is_authenticated = Auth.is_authenticated();
      if (this.is_authenticated) {
        const user = Auth.get_user();
        this.user_name = user?.name || user?.email || "User";
      }
    },

    async handleLogout() {
      try {
        await Auth.logout();
        this.is_authenticated = false;
        this.user_name = "";
        this.is_open = false;

        this.$router.push({ name: "Home" });
      } catch (error) {
        console.error("Logout failed:", error);
      }
    },
  },

  computed: {
    cartCount() {
      const cartStore = useCartStore();
      return cartStore.cartCount;
    },
  },

  mounted() {
    this.checkAuthStatus();
  },

  watch: {
    $route() {
      this.checkAuthStatus();
    },
  },
};
</script>
