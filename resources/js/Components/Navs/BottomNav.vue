<template>
  <footer class="footer mt-auto">


  </footer>
</template>

<script>
import { Inertia } from "@inertiajs/inertia";
import { Head, Link } from "@inertiajs/inertia-vue3";

export default {

  methods: {
    logout() {
      Inertia.post(route("logout"));
    },
  },

  components: {
    Link,
  },
};
</script>

<style>
</style>
