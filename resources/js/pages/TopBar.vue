<template>
  <div class="bg-white shadow-md py-4 px-6 flex items-center justify-between">
    <!-- Title -->

    <h1 class="text-2xl font-bold text-gray-800">
    <router-link
       :to="{ name: 'Home'}">
        Product Management Task
      </router-link>
    </h1>
    
    <!-- Navigation Buttons -->
    <div class="flex space-x-4">
      <router-link
        to="/category"
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
      >
        Category
      </router-link>
      <router-link
        to="/product"
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
      >
        Products
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TopBar',
};
</script>

<style scoped>
/* Custom styles if needed */
</style>
