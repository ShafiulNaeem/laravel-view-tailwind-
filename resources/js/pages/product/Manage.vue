
<template>
  <div>
    <TopBar />
    <div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Product List</h1>
    <router-link to="/product/create" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Product</router-link>
    <table class="table-auto w-full mt-4">
      <thead>
        <tr class="bg-gray-100">
          <th class="px-4 py-2">Title</th>
          <th class="px-4 py-2">Image</th>
          <th class="px-4 py-2">Category</th>
          <th class="px-4 py-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in products" :key="product.id" class="border-t">
          <td class="px-4 py-2 text-center">{{ product.name }}</td>
          <td class="px-4 py-2 text-center">
            <img :src="'/images/' + product.image" alt="Product Image" class="w-16 h-16 object-cover rounded" />
          </td>
          <td class="px-4 py-2 text-center">
            <span v-for="category in product.categories" :key="category.id" class="bg-blue-100 text-blue-800 text-sm font-semibold mr-2 px-2.5 py-0.5 rounded-full">
              {{ category.name }}
            </span>
          </td>
          <td class="px-4 py-2 flex items-center space-x-4 text-center">
            <router-link :to="{ name: 'ProductDetails', params: { id: product.id } }" class="text-blue-500 hover:text-blue-700 font-semibold">
              Details
            </router-link>
            <router-link :to="{ name: 'ProductEdit', params: { id: product.id } }" class="text-green-500 hover:text-green-700 font-semibold">
              Edit
            </router-link>
            <button @click="deleteProduct(product.id)" class="text-red-500 hover:text-red-700 font-semibold">
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import TopBar from '../TopBar.vue';

export default {
  components: {
    TopBar,
  },
  data() {
    return {
      products: [],
    };
  },
  mounted() {
    this.getProducts();
  },
  methods: {
    getProducts() {
      axios.get('/api/product').then(response => {
        this.products = response.data.data;
      });
    },
    deleteProduct(id) {
      axios.delete(`/api/product/delete/${id}`).then(() => {
        this.getProducts();
      });
    },
  },
};
</script>

