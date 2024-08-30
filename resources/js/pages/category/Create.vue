<template>
 <TopBar />
   <div class="max-w-xl mx-auto bg-gray-50 p-8 rounded-lg shadow-lg">
    <h1 class="text-3xl font-extrabold mb-8 text-gray-900 text-center">Add New  Category</h1>
    <form @submit.prevent="submitForm" >
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
      generalError: ''       
    };
  },
  
  
  methods: {
  submitForm() {
    const formData = new FormData();
    formData.append('name', this.form.name);

    axios.post('/api/category/create', formData)
      .then(response => {
        console.log('Success:', response.data);
        this.$router.push('/category');
        this.validationErrors = {};
      })
      .catch(error => {
        if (error.response) {
          // Server responded with a status other than 2xx
          console.error('Validation:', error.response.data);

          // Set validation errors
          this.validationErrors = error.response.data.errors || {};
        } 
      });
  }
}


};
</script>
