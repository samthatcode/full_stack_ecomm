<template>
  <div class="min-h-screen bg-background">
    <Header v-if="!isAdmin && !isAuthPage" />
    <main>
      <router-view />
    </main>
    <Footer v-if="!isAdmin && !isHelp && !isAuthPage" />
  </div>
</template>

<script>
import Header from "./components/Header.vue";
import Footer from "./components/Footer.vue";

export default {
  name: "App",

  components: {
    Header,
    Footer,
  },

  data() {
    return {};
  },

  computed: {
    isAdmin() {
      return this.$route.path.startsWith("/dashboard");
    },
    isHelp() {
      return this.$route.name === "Help";
    },
    isAuthPage() {
      return this.$route.name === "Login" || this.$route.name === "Signup";
    },
  },
};
</script>
