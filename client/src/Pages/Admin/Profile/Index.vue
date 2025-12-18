<template>
  <div class="min-h-screen bg-gray-50">
    <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
      <!-- Profile Header -->
      <div
        class="p-6 mb-6 bg-white border border-gray-200 shadow-sm rounded-xl"
      >
        <div
          class="flex flex-col items-start gap-6 md:flex-row md:items-center"
        >
          <!-- Avatar -->
          <div class="relative">
            <div
              class="flex items-center justify-center w-24 h-24 text-3xl font-bold text-white rounded-full bg-primary"
            >
              {{ userInitials }}
            </div>
            <button
              class="absolute bottom-0 right-0 flex items-center justify-center w-8 h-8 bg-white border-2 border-gray-200 rounded-full shadow-lg hover:bg-gray-50"
            >
              <Camera :size="16" class="text-gray-600" />
            </button>
          </div>

          <!-- User Info -->
          <div class="flex-1">
            <h1 class="text-xl font-bold text-gray-900 sm:text-2xl">
              {{ user.name }}
            </h1>
            <p class="text-sm text-gray-600 sm:text-base">{{ user.email }}</p>
            <div
              class="flex flex-col gap-2 mt-2 sm:flex-row sm:items-center sm:gap-4"
            >
              <span class="flex items-center gap-1 text-sm text-gray-600">
                <Phone :size="14" />
                {{ user.phone }}
              </span>
              <span class="flex items-center gap-1 text-sm text-gray-600">
                <MapPin :size="14" />
                {{ user.location }}
              </span>
            </div>
          </div>

          <!-- Edit Button -->
          <button
            @click="showEditModal = true"
            class="flex items-center justify-center w-full gap-2 px-4 py-2 text-white rounded-lg md:w-auto bg-primary hover:bg-primary/90"
          >
            <Edit2 :size="16" />
            <span>Edit Profile</span>
          </button>
        </div>
      </div>

      <!-- Tabs -->
      <div class="mb-6 bg-white border border-gray-200 shadow-sm rounded-xl">
        <div class="overflow-x-auto border-b border-gray-200">
          <nav class="flex -mb-px min-w-max">
            <button
              v-for="tab in tabs"
              :key="tab.id"
              @click="activeTab = tab.id"
              :class="[
                'flex items-center gap-2 px-4 sm:px-6 py-4 text-sm font-medium border-b-2 transition-colors whitespace-nowrap',
                activeTab === tab.id
                  ? 'border-primary text-primary'
                  : 'border-transparent text-gray-600 hover:text-gray-900 hover:border-gray-300',
              ]"
            >
              <component :is="tab.icon" :size="18" />
              <span class="hidden sm:inline">{{ tab.label }}</span>
            </button>
          </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
          <!-- Orders Tab -->
          <div v-if="activeTab === 'orders'">
            <h2 class="mb-4 text-lg font-bold text-gray-900">Order History</h2>

            <div v-if="userOrders.length === 0" class="py-12 text-center">
              <ShoppingBag :size="48" class="mx-auto mb-4 text-gray-400" />
              <p class="text-gray-600">No orders yet</p>
              <router-link
                to="/"
                class="inline-block px-6 py-2 mt-4 text-white rounded-lg bg-primary hover:bg-primary/90"
              >
                Start Shopping
              </router-link>
            </div>

            <div v-else class="space-y-4">
              <div
                v-for="order in userOrders"
                :key="order.id"
                class="p-4 transition-shadow border border-gray-200 rounded-lg hover:shadow-md"
              >
                <div
                  class="flex flex-col justify-between gap-3 mb-4 sm:flex-row sm:items-center"
                >
                  <div>
                    <h3 class="font-semibold text-gray-900">
                      {{ order.orderNumber }}
                    </h3>
                    <p class="text-sm text-gray-600">
                      {{ formatDate(order.date) }}
                    </p>
                  </div>
                  <div
                    class="flex items-center justify-between gap-3 sm:justify-start sm:gap-4"
                  >
                    <span
                      :class="[
                        'px-3 py-1 text-xs rounded-full font-medium capitalize',
                        order.status === 'delivered'
                          ? 'bg-green-100 text-green-700'
                          : order.status === 'shipped'
                          ? 'bg-blue-100 text-blue-700'
                          : order.status === 'processing'
                          ? 'bg-yellow-100 text-yellow-700'
                          : 'bg-gray-100 text-gray-700',
                      ]"
                    >
                      {{ order.status }}
                    </span>
                    <span class="font-semibold text-gray-900"
                      >₦{{ order.total.toFixed(2) }}</span
                    >
                  </div>
                </div>

                <div class="space-y-2">
                  <div
                    v-for="item in order.items"
                    :key="item.id"
                    class="flex items-center gap-3"
                  >
                    <img
                      :src="item.image"
                      :alt="item.name"
                      class="flex-shrink-0 object-cover w-12 h-12 rounded-lg sm:w-14 sm:h-14"
                    />
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-gray-900 truncate">
                        {{ item.name }}
                      </p>
                      <p class="text-xs text-gray-600">
                        Qty: {{ item.quantity }} × ₦{{ item.price }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Addresses Tab -->
          <div v-if="activeTab === 'addresses'">
            <div
              class="flex flex-col justify-between gap-3 mb-4 sm:flex-row sm:items-center"
            >
              <h2 class="text-lg font-bold text-gray-900">Saved Addresses</h2>
              <button
                @click="showAddressModal = true"
                class="flex items-center justify-center gap-2 px-4 py-2 text-white rounded-lg bg-primary hover:bg-primary/90"
              >
                <Plus :size="16" />
                <span>Add Address</span>
              </button>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
              <div
                v-for="address in addresses"
                :key="address.id"
                class="p-4 transition-shadow border border-gray-200 rounded-lg hover:shadow-md"
              >
                <div class="flex items-start justify-between mb-2">
                  <div>
                    <h3 class="font-semibold text-gray-900">
                      {{ address.label }}
                    </h3>
                    <span
                      v-if="address.isDefault"
                      class="inline-block px-2 py-1 mt-1 text-xs rounded bg-primary/10 text-primary"
                    >
                      Default
                    </span>
                  </div>
                  <button class="p-1 rounded hover:bg-gray-100">
                    <MoreVertical :size="16" class="text-gray-600" />
                  </button>
                </div>
                <p class="text-sm text-gray-600">{{ address.street }}</p>
                <p class="text-sm text-gray-600">
                  {{ address.city }}, {{ address.state }} {{ address.zip }}
                </p>
                <p class="text-sm text-gray-600">{{ address.country }}</p>
              </div>
            </div>
          </div>

          <!-- Payment Methods Tab -->
          <div v-if="activeTab === 'payments'">
            <div
              class="flex flex-col justify-between gap-3 mb-4 sm:flex-row sm:items-center"
            >
              <h2 class="text-lg font-bold text-gray-900">Payment Methods</h2>
              <button
                @click="showPaymentModal = true"
                class="flex items-center justify-center gap-2 px-4 py-2 text-white rounded-lg bg-primary hover:bg-primary/90 whitespace-nowrap"
              >
                <Plus :size="16" />
                <span class="hidden sm:inline">Add Payment Method</span>
                <span class="sm:hidden">Add Payment</span>
              </button>
            </div>

            <div class="space-y-4">
              <div
                v-for="payment in paymentMethods"
                :key="payment.id"
                class="p-4 transition-shadow border border-gray-200 rounded-lg hover:shadow-md"
              >
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-3">
                    <div
                      class="flex items-center justify-center w-12 h-8 bg-gray-100 rounded"
                    >
                      <CreditCard :size="20" class="text-gray-600" />
                    </div>
                    <div>
                      <p class="font-medium text-gray-900">
                        {{ payment.type }}
                      </p>
                      <p class="text-sm text-gray-600">
                        •••• •••• •••• {{ payment.last4 }}
                      </p>
                    </div>
                  </div>
                  <div class="flex items-center gap-2">
                    <span
                      v-if="payment.isDefault"
                      class="px-2 py-1 text-xs rounded bg-primary/10 text-primary"
                    >
                      Default
                    </span>
                    <button class="p-1 rounded hover:bg-gray-100">
                      <MoreVertical :size="16" class="text-gray-600" />
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Settings Tab -->
          <div v-if="activeTab === 'settings'">
            <h2 class="mb-4 text-lg font-bold text-gray-900">
              Account Settings
            </h2>

            <div class="space-y-6">
              <!-- Change Password -->
              <div class="p-4 border border-gray-200 rounded-lg">
                <h3 class="mb-4 font-semibold text-gray-900">
                  Change Password
                </h3>
                <div class="space-y-4">
                  <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700"
                      >Current Password</label
                    >
                    <input
                      type="password"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20"
                    />
                  </div>
                  <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700"
                      >New Password</label
                    >
                    <input
                      type="password"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20"
                    />
                  </div>
                  <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700"
                      >Confirm New Password</label
                    >
                    <input
                      type="password"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20"
                    />
                  </div>
                  <button
                    class="px-6 py-2 text-white rounded-lg bg-primary hover:bg-primary/90"
                  >
                    Update Password
                  </button>
                </div>
              </div>

              <!-- Notifications -->
              <div class="p-4 border border-gray-200 rounded-lg">
                <h3 class="mb-4 font-semibold text-gray-900">
                  Notification Preferences
                </h3>
                <div class="space-y-3">
                  <label class="flex items-center gap-3">
                    <input
                      type="checkbox"
                      checked
                      class="w-4 h-4 rounded text-primary"
                    />
                    <span class="text-sm text-gray-700">Order updates</span>
                  </label>
                  <label class="flex items-center gap-3">
                    <input
                      type="checkbox"
                      checked
                      class="w-4 h-4 rounded text-primary"
                    />
                    <span class="text-sm text-gray-700"
                      >Promotional emails</span
                    >
                  </label>
                  <label class="flex items-center gap-3">
                    <input
                      type="checkbox"
                      class="w-4 h-4 rounded text-primary"
                    />
                    <span class="text-sm text-gray-700">SMS notifications</span>
                  </label>
                </div>
              </div>

              <!-- Delete Account -->
              <div class="p-4 border border-red-200 rounded-lg bg-red-50">
                <h3 class="mb-2 font-semibold text-red-900">Delete Account</h3>
                <p class="mb-4 text-sm text-red-700">
                  Once you delete your account, there is no going back.
                </p>
                <button
                  class="px-6 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700"
                >
                  Delete Account
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Profile Modal -->
    <div
      v-if="showEditModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
    >
      <div
        class="bg-white rounded-xl p-4 sm:p-6 max-w-md w-full max-h-[90vh] overflow-y-auto"
      >
        <h3 class="mb-4 text-lg font-bold text-gray-900 sm:text-xl">
          Edit Profile
        </h3>
        <div class="space-y-4">
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-700"
              >Full Name</label
            >
            <input
              v-model="editForm.name"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20"
            />
          </div>
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-700"
              >Email</label
            >
            <input
              v-model="editForm.email"
              type="email"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20"
            />
          </div>
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-700"
              >Phone</label
            >
            <input
              v-model="editForm.phone"
              type="tel"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20"
            />
          </div>
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-700"
              >Location</label
            >
            <input
              v-model="editForm.location"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20"
            />
          </div>
          <div class="flex gap-3 pt-4">
            <button
              @click="showEditModal = false"
              class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              @click="saveProfile"
              class="flex-1 px-4 py-2 text-white rounded-lg bg-primary hover:bg-primary/90"
            >
              Save Changes
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {
  Camera,
  Phone,
  MapPin,
  Edit2,
  ShoppingBag,
  MapPinIcon,
  CreditCard,
  Settings,
  Plus,
  MoreVertical,
} from "lucide-vue-next";

export default {
  name: "Profile",

  components: {
    Camera,
    Phone,
    MapPin,
    Edit2,
    ShoppingBag,
    MapPinIcon,
    CreditCard,
    Settings,
    Plus,
    MoreVertical,
  },

  data() {
    return {
      activeTab: "orders",
      showEditModal: false,
      showAddressModal: false,
      showPaymentModal: false,

      tabs: [
        { id: "orders", label: "Orders", icon: "ShoppingBag" },
        { id: "addresses", label: "Addresses", icon: "MapPinIcon" },
        { id: "payments", label: "Payment Methods", icon: "CreditCard" },
        { id: "settings", label: "Settings", icon: "Settings" },
      ],

      user: {
        name: "John Doe",
        email: "john.doe@example.com",
        phone: "+1 (555) 123-4567",
        location: "New York, USA",
      },

      editForm: {
        name: "John Doe",
        email: "john.doe@example.com",
        phone: "+1 (555) 123-4567",
        location: "New York, USA",
      },

      userOrders: [
        {
          id: 1,
          orderNumber: "ORD-2024-001",
          date: "2024-01-15",
          status: "delivered",
          total: 2450,
          items: [
            {
              id: 1,
              name: "Premium Almonds",
              quantity: 2,
              price: 599,
              image:
                "https://images.unsplash.com/photo-1508747703725-719777637510?w=400",
            },
            {
              id: 2,
              name: "Dried Apricots",
              quantity: 1,
              price: 450,
              image:
                "https://images.unsplash.com/photo-1623065422902-30a2d299bbe4?w=400",
            },
          ],
        },
        {
          id: 2,
          orderNumber: "ORD-2024-002",
          date: "2024-01-20",
          status: "shipped",
          total: 1850,
          items: [
            {
              id: 3,
              name: "Cashew Nuts",
              quantity: 1,
              price: 799,
              image:
                "https://images.unsplash.com/photo-1585704032915-c3400ca199e7?w=400",
            },
          ],
        },
      ],

      addresses: [
        {
          id: 1,
          label: "Home",
          street: "123 Main Street, Apt 4B",
          city: "New York",
          state: "NY",
          zip: "10001",
          country: "USA",
          isDefault: true,
        },
        {
          id: 2,
          label: "Office",
          street: "456 Business Ave, Suite 200",
          city: "New York",
          state: "NY",
          zip: "10002",
          country: "USA",
          isDefault: false,
        },
      ],

      paymentMethods: [
        {
          id: 1,
          type: "Visa",
          last4: "4242",
          isDefault: true,
        },
        {
          id: 2,
          type: "Mastercard",
          last4: "8888",
          isDefault: false,
        },
      ],
    };
  },

  computed: {
    userInitials() {
      return this.user.name
        .split(" ")
        .map((n) => n[0])
        .join("")
        .toUpperCase();
    },
  },

  methods: {
    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleDateString("en-US", {
        month: "long",
        day: "numeric",
        year: "numeric",
      });
    },

    saveProfile() {
      this.user = { ...this.editForm };
      this.showEditModal = false;
    },
  },
};
</script>
