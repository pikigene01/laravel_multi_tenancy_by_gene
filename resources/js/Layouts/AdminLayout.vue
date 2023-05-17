<template>
  <TopNav></TopNav>

  <SideNav></SideNav>
  <slot></slot>
</template>

<script>
import TopNav from "../Components/Admin/TopNav.vue";
import SideNav from "../Components/Admin/SideNav.vue";
export default { components: { TopNav, SideNav }, props: ["page"] };
</script>

<style scoped>

.wrapper-body {
  padding: 20px 0 !important;
}

</style>
