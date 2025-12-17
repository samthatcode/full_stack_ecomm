<template>
  <div class="min-h-screen p-6 bg-gray-50">
    <div class="mx-auto max-w-7xl">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="flex items-center gap-3 text-3xl font-bold text-gray-900">
          <Users class="text-blue-600" :size="32" />
          User Role & Permission Management
        </h1>
        <p class="mt-2 text-gray-600">
          Manage user roles and permissions across your platform
        </p>
      </div>

      <!-- Search Bar -->
      <div class="p-4 mb-6 bg-white shadow-sm rounded-xl">
        <div class="relative">
          <Search
            class="absolute text-gray-400 transform -translate-y-1/2 left-3 top-1/2"
            :size="20"
          />
          <input
            v-model="searchTerm"
            type="text"
            placeholder="Search users by name or email..."
            class="w-full py-3 pl-10 pr-4 border border-gray-200 rounded-lg focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
      </div>

      <!-- Users Table -->
      <div class="overflow-hidden bg-white shadow-sm rounded-xl">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="border-b border-gray-200 bg-gray-50">
              <tr>
                <th
                  class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase"
                >
                  User
                </th>
                <th
                  class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase"
                >
                  Roles
                </th>
                <th
                  class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase"
                >
                  Permissions
                </th>
                <th
                  class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase"
                >
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr
                v-for="user in filteredUsers"
                :key="user.id"
                class="transition-colors hover:bg-gray-50"
              >
                <td class="px-6 py-4">
                  <div>
                    <div class="font-semibold text-gray-900">
                      {{ user.first_name }} {{ user.last_name }}
                    </div>
                    <div class="text-sm text-gray-500">{{ user.email }}</div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex flex-wrap gap-2">
                    <span
                      v-for="role in user.roles"
                      :key="role.id"
                      class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full"
                    >
                      <Shield :size="12" class="mr-1" />
                      {{ role.name }}
                    </span>
                    <span
                      v-if="user.roles.length === 0"
                      class="text-sm italic text-gray-400"
                    >
                      No roles assigned
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex flex-wrap gap-2">
                    <span
                      v-for="perm in user.permissions.slice(0, 2)"
                      :key="perm.id"
                      class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full"
                    >
                      <Key :size="12" class="mr-1" />
                      {{ perm.name }}
                    </span>
                    <span
                      v-if="user.permissions.length > 2"
                      class="inline-flex items-center px-3 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full"
                    >
                      +{{ user.permissions.length - 2 }} more
                    </span>
                    <span
                      v-if="user.permissions.length === 0"
                      class="text-sm italic text-gray-400"
                    >
                      No permissions
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex gap-2">
                    <button
                      @click="openRoleModal(user)"
                      class="flex items-center gap-1 px-3 py-2 text-sm font-medium text-blue-600 transition-colors rounded-lg hover:bg-blue-50"
                    >
                      <Shield :size="16" />
                      Roles
                    </button>
                    <button
                      @click="openPermissionModal(user)"
                      class="flex items-center gap-1 px-3 py-2 text-sm font-medium text-green-600 transition-colors rounded-lg hover:bg-green-50"
                    >
                      <Key :size="16" />
                      Permissions
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Role Assignment Modal -->
      <div
        v-if="showRoleModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
      >
        <div
          class="max-h-[80vh] w-full max-w-md overflow-y-auto rounded-xl bg-white shadow-2xl"
        >
          <div
            class="flex items-center justify-between p-6 border-b border-gray-200"
          >
            <h2 class="flex items-center gap-2 text-xl font-bold text-gray-900">
              <Shield class="text-blue-600" />
              Assign Roles
            </h2>
            <button
              @click="showRoleModal = false"
              class="text-gray-400 hover:text-gray-600"
            >
              <X :size="24" />
            </button>
          </div>

          <div class="p-6">
            <p class="mb-4 text-sm text-gray-600">
              Managing roles for:
              <span class="font-semibold">
                {{ selectedUser?.first_name }} {{ selectedUser?.last_name }}
              </span>
            </p>

            <div class="space-y-3">
              <label
                v-for="role in roles"
                :key="role.id"
                class="flex items-center p-4 transition-colors border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50"
              >
                <input
                  v-model="selectedRoles"
                  type="checkbox"
                  :value="role.name"
                  class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500"
                />
                <div class="flex-1 ml-3">
                  <div class="font-medium text-gray-900">{{ role.name }}</div>
                  <div class="mt-1 text-xs text-gray-500">
                    {{ role.permissions?.length || 0 }} permissions included
                  </div>
                </div>
              </label>
            </div>
          </div>

          <div class="flex gap-3 p-6 border-t border-gray-200">
            <button
              @click="showRoleModal = false"
              class="flex-1 px-4 py-2 font-medium text-gray-700 transition-colors border border-gray-300 rounded-lg hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              @click="handleAssignRoles"
              class="flex-1 px-4 py-2 font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700"
            >
              Assign Roles
            </button>
          </div>
        </div>
      </div>

      <!-- Permission Assignment Modal -->
      <div
        v-if="showPermissionModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
      >
        <div
          class="max-h-[80vh] w-full max-w-md overflow-y-auto rounded-xl bg-white shadow-2xl"
        >
          <div
            class="flex items-center justify-between p-6 border-b border-gray-200"
          >
            <h2 class="flex items-center gap-2 text-xl font-bold text-gray-900">
              <Key class="text-green-600" />
              Assign Permissions
            </h2>
            <button
              @click="showPermissionModal = false"
              class="text-gray-400 hover:text-gray-600"
            >
              <X :size="24" />
            </button>
          </div>

          <div class="p-6">
            <p class="mb-4 text-sm text-gray-600">
              Managing permissions for:
              <span class="font-semibold">
                {{ selectedUser?.first_name }} {{ selectedUser?.last_name }}
              </span>
            </p>

            <div class="space-y-3">
              <label
                v-for="permission in permissions"
                :key="permission.id"
                class="flex items-center p-4 transition-colors border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50"
              >
                <input
                  v-model="selectedPermissions"
                  type="checkbox"
                  :value="permission.name"
                  class="w-5 h-5 text-green-600 rounded focus:ring-2 focus:ring-green-500"
                />
                <div class="ml-3">
                  <div class="font-medium text-gray-900">
                    {{ permission.name }}
                  </div>
                </div>
              </label>
            </div>
          </div>

          <div class="flex gap-3 p-6 border-t border-gray-200">
            <button
              @click="showPermissionModal = false"
              class="flex-1 px-4 py-2 font-medium text-gray-700 transition-colors border border-gray-300 rounded-lg hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              @click="handleAssignPermissions"
              class="flex-1 px-4 py-2 font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700"
            >
              Assign Permissions
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Users, Shield, Key, Search, X } from "lucide-vue-next";
import { UserRole } from "@/services/user_role";

export default {
  name: "UserManagement",

  components: {
    Users,
    Shield,
    Key,
    Search,
    X,
  },

  data() {
    return {
      users: [],
      roles: [],
      permissions: [],
      selectedUser: null,
      searchTerm: "",
      loading: false,
      showRoleModal: false,
      showPermissionModal: false,
      selectedRoles: [],
      selectedPermissions: [],
    };
  },

  computed: {
    filteredUsers() {
      return this.users.filter((user) =>
        `${user.first_name} ${user.last_name} ${user.email}`
          .toLowerCase()
          .includes(this.searchTerm.toLowerCase())
      );
    },
  },

  methods: {
    async fetchUsers() {
      try {
        this.loading = true;
        const response = await UserRole.getAllUsers();
        this.users = response.data?.data?.data || response.data?.data || [];
      } catch (error) {
        console.error("Error fetching users:", error);
      } finally {
        this.loading = false;
      }
    },

    async fetchRoles() {
      try {
        const response = await UserRole.getAllRoles();
        this.roles = response.data?.data || [];
      } catch (error) {
        console.error("Error fetching roles:", error);
      }
    },

    async fetchPermissions() {
      try {
        const response = await UserRole.getAllPermissions();
        this.permissions = response.data?.data || [];
      } catch (error) {
        console.error("Error fetching permissions:", error);
      }
    },

    openRoleModal(user) {
      this.selectedUser = user;
      this.selectedRoles = user.roles.map((r) => r.name);
      this.showRoleModal = true;
    },

    openPermissionModal(user) {
      this.selectedUser = user;
      this.selectedPermissions = user.permissions.map((p) => p.name);
      this.showPermissionModal = true;
    },

    async handleAssignRoles() {
      try {
        await UserRole.assignRoles(this.selectedUser.id, this.selectedRoles);
        await this.fetchUsers(); // Refresh users
        this.showRoleModal = false;
        alert("Roles assigned successfully!");
      } catch (error) {
        console.error("Error assigning roles:", error);
        alert("Failed to assign roles");
      }
    },

    async handleAssignPermissions() {
      try {
        await UserRole.assignPermissions(
          this.selectedUser.id,
          this.selectedPermissions
        );
        await this.fetchUsers(); // Refresh users
        this.showPermissionModal = false;
        alert("Permissions assigned successfully!");
      } catch (error) {
        console.error("Error assigning permissions:", error);
        alert("Failed to assign permissions");
      }
    },
  },

  mounted() {
    this.fetchUsers();
    this.fetchRoles();
    this.fetchPermissions();
  },
};
</script>
