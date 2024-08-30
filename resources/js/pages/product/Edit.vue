<template>
  <TopBar />
  <div class="max-w-xl mx-auto bg-gray-50 p-8 rounded-lg shadow-lg">
    <h1 class="text-3xl font-extrabold mb-8 text-gray-900 text-center">Edit Product</h1>
    <form @submit.prevent="submitForm">
      <!-- Product Name -->
      <div class="mb-6">
        <label class="block text-gray-700 text-sm font-medium mb-2">Product Name</label>
        <input
          v-model="form.name"
          type="text"
          placeholder="Enter product name"
          class="w-full px-4 py-3 border rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500 text-gray-700"
        />
        <div v-if="validationErrors.name">
          <ul>
            <li style="color:red" v-for="error in validationErrors.name" :key="error">{{ error }}</li>
          </ul>
        </div>
      </div>

      <!-- Product Image -->
      <div class="mb-6">
        <label class="block text-gray-700 text-sm font-medium mb-2">Product Image</label>
        <input
          @change="previewImage"
          type="file"
          class="w-full px-4 py-3 border rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500 text-gray-700"
        />
        <img
          v-if="imagePreview"
          :src="imagePreview"
          alt="Image Preview"
          class="mt-4 w-40 h-40 object-cover rounded-lg shadow"
        />
        <div v-if="validationErrors.image">
          <ul>
            <li style="color:red" v-for="error in validationErrors.image" :key="error">{{ error }}</li>
          </ul>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="text-center">
        <button
          type="submit"
          class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg shadow focus:outline-none focus:ring focus:ring-blue-300"
        >
          Update Product
        </button>
      </div>
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
        name: '',
        image: null,
      },
      imagePreview: null,
      validationErrors: {},
      productId: null, 
    };
  },
  mounted() {
    this.productId = this.$route.params.id; 
    this.getProduct();
  },
  methods: {
    getProduct() {
      axios.get(`/api/product/show/${this.productId}`).then(response => {
        this.form.name = response.data.data.name;
        this.imagePreview = `/images/${response.data.data.image}`;
        console.log(this.imagePreview);
      });
    },
    previewImage(event) {
      const file = event.target.files[0];
      this.form.image = file;
      this.imagePreview = URL.createObjectURL(file);
    },
    submitForm() {
      const formData = new FormData();
      formData.append('name', this.form.name);
      if (this.form.image) {
        formData.append('image', this.form.image);
      }

      axios.post(`/api/product/update/${this.productId}`, formData).then(response => {
        this.$router.push({ name: 'ProductList' });
        this.validationErrors = {};
      })
      .catch(error => {
        if (error.response) {
          this.validationErrors = error.response.data.errors || {};
        }
      });
    },
  },
};
</script>
