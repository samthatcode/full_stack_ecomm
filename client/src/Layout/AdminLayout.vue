<template>
  <div class="min-h-screen bg-gray-50">
    <header class="sticky top-0 z-30 bg-white border-b border-gray-200">
      <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <!-- Left -->
          <div class="flex items-center gap-4">
            <button
              @click="mobileSidebarOpen = !mobileSidebarOpen"
              class="p-2 rounded-lg lg:hidden hover:bg-gray-100"
            >
              <Menu :size="24" />
            </button>

            <router-link
              :to="{ name: 'Home' }"
              class="items-center hidden gap-2 px-3 py-2 text-sm rounded-lg sm:flex hover:bg-gray-50"
            >
              <h1 class="text-xl font-bold text-gray-900">
                Rufars Foods Admin
              </h1>
            </router-link>
          </div>

          <!-- Right -->
          <div class="flex items-center gap-3">
            <!-- Notifications -->
            <div class="relative">
              <button
                @click="showNotifications = !showNotifications"
                class="relative p-2 rounded-lg hover:bg-gray-100"
              >
                <Bell :size="20" />
                <span
                  v-if="unreadNotifications"
                  class="absolute flex items-center justify-center w-4 h-4 text-xs text-white bg-red-500 rounded-full top-1 right-1"
                >
                  {{ unreadNotifications }}
                </span>
              </button>
            </div>

            <!-- Profile -->
            <div class="relative">
              <button
                @click="showProfileMenu = !showProfileMenu"
                class="flex items-center gap-2 p-1 rounded-lg hover:bg-gray-100"
              >
                <div
                  class="flex items-center justify-center w-8 h-8 rounded-full bg-primary"
                >
                  <span class="text-sm font-semibold text-white">A</span>
                </div>
                <ChevronDown :size="16" />
              </button>

              <div
                v-if="showProfileMenu"
                class="absolute right-0 w-56 mt-2 bg-white border rounded-lg shadow-lg"
              >
                <button
                  @click="selectTab('profile')"
                  class="block w-full px-4 py-2 text-left hover:bg-gray-50"
                >
                  Profile
                </button>
                <button
                  @click="selectTab('settings')"
                  class="block w-full px-4 py-2 text-left hover:bg-gray-50"
                >
                  Settings
                </button>
                <button
                  @click="handleLogout"
                  class="block w-full px-4 py-2 text-left text-red-600 hover:bg-red-50"
                >
                  Logout
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="flex">
      <aside
        :class="[
          'fixed inset-y-0 left-0 z-20 w-64 bg-white border-r border-gray-200 transform transition-transform duration-200 ease-in-out lg:sticky lg:top-16 lg:h-[calc(100vh-4rem)]',
          mobileSidebarOpen
            ? 'translate-x-0'
            : '-translate-x-full lg:translate-x-0',
        ]"
      >
        <nav class="p-4 space-y-2">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            @click="selectTab(tab.id)"
            :class="[
              'w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left transition-colors',
              activeTab === tab.id
                ? 'bg-primary text-white'
                : 'text-gray-700 hover:bg-gray-100',
            ]"
          >
            <component :is="tab.icon" :size="20" />
            <span class="font-medium">{{ tab.label }}</span>
          </button>
        </nav>
      </aside>

      <main class="flex-1 p-4 ml-0 md:p-6">
        <router-view />
      </main>
    </div>

    <div
      v-if="mobileSidebarOpen"
      @click="mobileSidebarOpen = false"
      class="fixed inset-0 z-10 bg-black bg-opacity-50 lg:hidden"
    ></div>
  </div>
</template>

<script>
import { Bell, ChevronDown, Menu } from "lucide-vue-next";
import { Auth } from "../services/auth";

export default {
  components: { Bell, ChevronDown, Menu },
  data() {
    return {
      mobileSidebarOpen: false,
      showNotifications: false,
      showProfileMenu: false,
      unreadNotifications: 3,
      tabs: [
        { id: "dashboard", label: "Dashboard", icon: "LayoutDashboard" },
        { id: "products", label: "Products", icon: "Package" },
        { id: "orders", label: "Orders", icon: "ShoppingCart" },
        { id: "customers", label: "Customers", icon: "Users" },
        { id: "analytics", label: "Analytics", icon: "BarChart3" },
        { id: "categories", label: "Categories", icon: "FolderTree" },
        { id: "profile", label: "Profile", icon: "User" },
        { id: "settings", label: "Settings", icon: "Settings" },
      ],
    };
  },
  computed: {
    activeTabLabel() {
      const tab = this.tabs.find((t) => t.id === this.activeTab);
      return tab ? tab.label : "";
    },
    activeTab() {
      const map = {
        AdminDashboard: "dashboard",
        AdminProducts: "products",
        AdminOrders: "orders",
        AdminCustomers: "customers",
        AdminAnalytics: "analytics",
        AdminCategories: "categories",
        AdminProfile: "profile",
        AdminSettings: "settings",
      };

      return map[this.$route.name] || "dashboard";
    },
  },

  methods: {
    selectTab(tab) {
      const routes = {
        dashboard: "",
        products: "products",
        orders: "orders",
        customers: "customers",
        analytics: "analytics",
        categories: "categories",
        profile: "profile",
        settings: "settings",
      };

      this.mobileSidebarOpen = false;
      this.$router.push(`/dashboard/${routes[tab]}`);
    },

    handleLogout() {
      Auth.logout();
      this.$router.push("/login");
    },
  },
};
</script>
