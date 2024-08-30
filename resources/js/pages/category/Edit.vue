<template>
  <TopBar />
  <div class="max-w-xl mx-auto bg-gray-50 p-8 rounded-lg shadow-lg">
    <h1 class="text-3xl font-extrabold mb-8 text-gray-900 text-center">Edit Category</h1>
    <form @submit.prevent="submitForm">
      <div class="mb-4">
        <label class="block text-gray-700">Name</label>
        <input v-model="form.name" type="text" class="w-full px-4 py-2 border rounded" />
        <div v-if="validationErrors.name">
          <ul>
            <li style="color:red" v-for="error in validationErrors.name" :key="error">{{ error }}</li>
          </ul>
        </div>
      </div>
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
    </form>
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
      form: {
        name: ''
      },
      validationErrors: {},
      generalError: '',
      categoryId: null,  // To store the ID of the category being edited
    };
  },
  mounted() {
    this.categoryId = this.$route.params.id;  // Get the category ID from the route
    this.getCategory();  // Fetch category data
  },
  methods: {
    getCategory() {
      axios.get(`/api/category/show/${this.categoryId}`)
        .then(response => {
          this.form.name = response.data.data.name;  // Populate the form with category data
        })
        .catch(error => {
          console.error('Error fetching category:', error);
        });
    },
    submitForm() {
      const formData = new FormData();
      formData.append('name', this.form.name);

      axios.post(`/api/category/update/${this.categoryId}`, formData)  // Send update request
        .then(response => {
          console.log('Success:', response.data);
          this.$router.push('/category');  // Redirect to category list page after successful update
          this.validationErrors = {};
        })
        .catch(error => {
          if (error.response) {
            console.error('Validation:', error.response.data);
            this.validationErrors = error.response.data.errors || {};
          } 
        });
    }
  }
};
</script>
