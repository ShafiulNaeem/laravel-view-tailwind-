
<template>
  <div>
    <TopBar />
    <div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Category List</h1>
    <router-link to="/category/create" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Category</router-link>
    <table class="table-auto w-full mt-4">
      <thead>
        <tr class="bg-gray-100">
          <th class="px-4 py-2">Name</th>
          <th class="px-4 py-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="category in categories" :key="category.id" class="border-t">
          <td class="px-4 py-2 text-center">{{ category.name }}</td>
          <td class="px-4 py-2 flex items-center space-x-4">
            <router-link :to="{ name: 'CategoryEdit', params: { id: category.id } }" class="text-green-500 hover:text-green-700 font-semibold">
              Edit
            </router-link>
            <button @click="deleteCategory(category.id)" class="text-red-500 hover:text-red-700 font-semibold">
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
      categories: [],
    };
  },
  mounted() {
    this.getCategories();
  },
  methods: {
    getCategories() {
      axios.get('/api/category').then(response => {
        this.categories = response.data.data;
        console.log(response.data.data);
      });
    },
    deleteCategory(id) {
      axios.delete(`/api/category/delete/${id}`).then(() => {
        this.getCategories();
      });
    },
  },
};
</script>

