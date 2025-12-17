<template>
  <div class="space-y-6">
    <h2 class="text-2xl font-bold text-gray-900">Orders</h2>

    <div class="overflow-x-auto bg-white border rounded-xl">
      <table class="w-full">
        <thead class="border-b bg-gray-50">
          <tr>
            <th
              class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase"
            >
              Order #
            </th>
            <th
              class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase"
            >
              Customer
            </th>
            <th
              class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase"
            >
              Date
            </th>
            <th
              class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase"
            >
              Total
            </th>
            <th
              class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase"
            >
              Status
            </th>
            <th
              class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase"
            >
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="order in orders" :key="order.id">
            <td class="px-6 py-4">#{{ order.id }}</td>
            <td class="px-6 py-4">
              <div>
                <p class="font-medium">{{ order.user.name }}</p>
                <p class="text-sm text-gray-600">{{ order.user.email }}</p>
              </div>
            </td>
            <td class="px-6 py-4 text-sm">
              {{ formatDate(order.created_at) }}
            </td>
            <td class="px-6 py-4 font-semibold">₦{{ order.total }}</td>
            <td class="px-6 py-4">
              <select
                @change="updateStatus(order.id, $event.target.value)"
                :value="order.status"
                class="px-3 py-1 text-xs border-0 rounded-full"
                :class="statusClass(order.status)"
              >
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="shipped">Shipped</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </td>
            <td class="px-6 py-4">
              <button
                @click="viewOrder(order)"
                class="text-primary hover:underline"
              >
                View
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { Orders } from "@/services/orders";

export default {
  data() {
    return {
      orders: [],
    };
  },
  mounted() {
    this.fetchOrders();
  },
  methods: {
    async fetchOrders() {
      try {
        const res = await Orders.getAllOrders();
        this.orders = res.data.data.data;
      } catch (err) {
        console.error(err);
      }
    },
    async updateStatus(id, status) {
      try {
        await Orders.updateStatus(id, status);
        this.fetchOrders();
      } catch (err) {
        alert("Failed to update status");
      }
    },
    viewOrder(order) {
      alert(
        `View order details for #${order.id}\nTotal: ₦${order.total}\nStatus: ${order.status}`
      );
      // You can open a modal here later
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString();
    },
    statusClass(status) {
      const map = {
        delivered: "bg-green-100 text-green-700",
        shipped: "bg-blue-100 text-blue-700",
        processing: "bg-yellow-100 text-yellow-700",
        pending: "bg-gray-100 text-gray-700",
        cancelled: "bg-red-100 text-red-700",
      };
      return map[status] || "bg-gray-100 text-gray-700";
    },
  },
};
</script>
