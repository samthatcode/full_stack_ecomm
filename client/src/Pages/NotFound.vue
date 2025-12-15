<template>
  <div class="flex items-center justify-center min-h-screen px-4 bg-gray-50">
    <div class="w-full max-w-md text-center">
      <!-- Icon -->
      <div
        class="flex items-center justify-center w-20 h-20 mx-auto mb-6 bg-indigo-100 rounded-full"
      >
        <svg
          class="w-10 h-10 text-indigo-600"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M9.172 16.172a4 4 0 005.656 0M12 8v4m0 4h.01"
          />
        </svg>
      </div>

      <!-- Title -->
      <h1 class="mb-2 text-5xl font-extrabold text-gray-900">404</h1>

      <!-- Subtitle -->
      <p class="mb-4 text-lg font-medium text-gray-700">Page not found</p>

      <!-- Description -->
      <p class="mb-8 text-sm text-gray-500">
        Sorry, the page you’re looking for doesn’t exist or has been moved.
      </p>

      <!-- Actions -->
      <div class="flex items-center justify-center gap-3">
        <button
          @click="goBack"
          class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-100"
        >
          Go Back
        </button>

        <button
          @click="goHome"
          class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
        >
          Go to Dashboard
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "NotFound",

  methods: {
    goBack() {
      this.$router.back();
    },

    goHome() {
      // Adjust if you want role-based redirect later
      this.$router.push({ name: "AdminDashboard" });
    },
  },
};
</script>
