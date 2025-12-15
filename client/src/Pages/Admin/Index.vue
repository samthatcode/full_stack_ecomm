<template>
  <div class="">
    <div>
      <h2 class="mb-2 text-2xl font-bold text-gray-900">Dashboard Overview</h2>
      <p class="text-gray-600">Welcome back! Here's what's happening today.</p>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
      <div class="p-6 bg-white border border-gray-200 rounded-xl">
        <div class="flex items-center justify-between mb-4">
          <div
            class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg"
          >
            <DollarSign :size="24" class="text-green-600" />
          </div>
        </div>
        <p class="mb-1 text-sm text-gray-600">Total Revenue</p>
        <p class="text-2xl font-bold text-gray-900">
          ₹{{ stats.totalRevenue?.toLocaleString() || "0" }}
        </p>
      </div>

      <div class="p-6 bg-white border border-gray-200 rounded-xl">
        <div class="flex items-center justify-between mb-4">
          <div
            class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg"
          >
            <ShoppingCart :size="24" class="text-blue-600" />
          </div>
        </div>
        <p class="mb-1 text-sm text-gray-600">Total Orders</p>
        <p class="text-2xl font-bold text-gray-900">
          {{ stats.totalOrders || 0 }}
        </p>
      </div>

      <div class="p-6 bg-white border border-gray-200 rounded-xl">
        <div class="flex items-center justify-between mb-4">
          <div
            class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg"
          >
            <Package :size="24" class="text-purple-600" />
          </div>
        </div>
        <p class="mb-1 text-sm text-gray-600">Total Products</p>
        <p class="text-2xl font-bold text-gray-900">
          {{ stats.totalProducts || 0 }}
        </p>
      </div>

      <div class="p-6 bg-white border border-gray-200 rounded-xl">
        <div class="flex items-center justify-between mb-4">
          <div
            class="flex items-center justify-center w-12 h-12 bg-orange-100 rounded-lg"
          >
            <AlertTriangle :size="24" class="text-orange-600" />
          </div>
        </div>
        <p class="mb-1 text-sm text-gray-600">Low Stock Items</p>
        <p class="text-2xl font-bold text-gray-900">
          {{ stats.lowStockItems || 0 }}
        </p>
      </div>
    </div>

    <div class="grid grid-cols-1 gap-6 mt-4 xl:grid-cols-2">
      <div class="p-6 bg-white border border-gray-200 rounded-xl">
        <h3 class="mb-4 text-lg font-bold text-gray-900">Recent Orders</h3>
        <div class="space-y-4">
          <div
            v-for="order in stats.recentOrders"
            :key="order.id"
            class="flex justify-between pb-4 border-b last:border-0"
          >
            <div>
              <p class="font-medium">#{{ order.id }}</p>
              <p class="text-sm text-gray-600">{{ order.user.name }}</p>
            </div>
            <div class="text-right">
              <p class="font-semibold">₹{{ order.total }}</p>
              <span
                :class="statusClass(order.status)"
                class="px-2 py-1 text-xs rounded-full"
              >
                {{ order.status }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {
  DollarSign,
  ShoppingCart,
  Package,
  AlertTriangle,
} from "lucide-vue-next";
import { Dashboard } from "@/services/dashboard";

export default {
  components: { DollarSign, ShoppingCart, Package, AlertTriangle },
  data() {
    return {
      stats: {
        totalRevenue: 0,
        totalOrders: 0,
        totalProducts: 0,
        lowStockItems: 0,
        recentOrders: [],
      },
      loading: true,
    };
  },
  mounted() {
    this.fetchStats();
  },
  methods: {
    async fetchStats() {
      try {
        const res = await Dashboard.getStats();
        this.stats = res.data.data;
      } catch (err) {
        console.error(err);
      } finally {
        this.loading = false;
      }
    },
    statusClass(status) {
      const classes = {
        delivered: "bg-green-100 text-green-700",
        shipped: "bg-blue-100 text-blue-700",
        processing: "bg-yellow-100 text-yellow-700",
        pending: "bg-gray-100 text-gray-700",
      };
      return classes[status] || "bg-gray-100 text-gray-700";
    },
  },
};
</script>
