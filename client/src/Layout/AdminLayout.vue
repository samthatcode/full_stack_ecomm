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
import {
  Bell,
  ChevronDown,
  Menu,
  FolderTree,
  LayoutDashboard,
  Package,
  ShoppingCart,
  Users,
  BarChart3,
  User,
  Settings,
} from "lucide-vue-next";
import { Auth } from "../services/auth";

export default {
  components: { Bell, ChevronDown, Menu },
  data() {
    return {
      mobileSidebarOpen: false,
      showNotifications: false,
      showProfileMenu: false,
      unreadNotifications: 3,
    };
  },
  computed: {
    current_user() {
      return Auth.current_user();
    },
    tabs() {
      const allTabs = [
        {
          id: "dashboard",
          label: "Dashboard",
          icon: LayoutDashboard,
          roles: [],
          permissions: [],
        },

        // Visible to admins & staff
        {
          id: "products",
          label: "Products",
          icon: Package,
          permissions: ["manage_products"],
        },
        {
          id: "orders",
          label: "Orders",
          icon: ShoppingCart,
          permissions: ["manage_orders"],
        },
        {
          id: "customers",
          label: "Customers",
          icon: Users,
          permissions: ["manage_users"],
        },
        {
          id: "users",
          label: "Users Management",
          icon: Users,
          roles: ["super_admin"],
        }, // Only super admins
        {
          id: "categories",
          label: "Categories",
          icon: FolderTree,
          permissions: ["manage_categories"],
        },
        {
          id: "analytics",
          label: "Analytics",
          icon: BarChart3,
          permissions: ["manage_dashboard"],
        },
        {
          id: "profile",
          label: "My Profile",
          icon: User,
          roles: [],
          permissions: [],
        },
        {
          id: "settings",
          label: "Settings",
          icon: Settings,
          roles: [],
          permissions: [],
        },
      ];

      const user = Auth.current_user();

      // Safety: if no user or no roles/permissions, show only basic tabs
      if (!user) {
        return allTabs.filter(
          (tab) => tab.roles.length === 0 && tab.permissions.length === 0
        );
      }

      const user_roles = Array.isArray(user.roles) ? user.roles : [];
      const user_permissions = Array.isArray(user.permissions)
        ? user.permissions
        : [];

      return allTabs.filter((tab) => {
        const roles = tab.roles ?? [];
        const perms = tab.permissions ?? [];

        return (
          (roles.length === 0 || roles.some((r) => user_roles.includes(r))) &&
          (perms.length === 0 ||
            perms.some((p) => user_permissions.includes(p)))
        );
      });
    },

    activeTab() {
      const map = {
        AdminDashboard: "dashboard",
        AdminProducts: "products",
        AdminOrders: "orders",
        AdminCustomers: "customers",
        AdminUsers: "users",
        AdminAnalytics: "analytics",
        AdminCategories: "categories",
        AdminProfile: "profile",
        AdminSettings: "settings",
      };

      return map[this.$route.name] || "dashboard";
    },
  },

  methods: {
    selectTab(tab_id) {
      const routes = {
        dashboard: "",
        products: "products",
        orders: "orders",
        customers: "customers",
        users: "users",
        analytics: "analytics",
        categories: "categories",
        profile: "profile",
        settings: "settings",
      };

      this.mobileSidebarOpen = false;
      this.$router.push(`/dashboard/${routes[tab_id]}`);
    },

    handleLogout() {
      Auth.logout().finally(() => {
        this.$router.replace({ name: "Login" });
      });
    },
  },
};
</script>
