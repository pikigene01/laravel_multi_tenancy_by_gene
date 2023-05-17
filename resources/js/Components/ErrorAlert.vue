<template>
  <div class="col-md-12 mt-3" v-if="$page.props.flash.message">
    <div
      class="alert alert-warning d-flex align-items-center"
      role="alert"
      style="height: 50px"
    >
      <i class="fa-solid fa-circle-info menu--icon"></i>

      <div>{{ $page.props.flash.message }}</div>
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style>
</style>
