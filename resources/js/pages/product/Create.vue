<template>
  <TopBar />
  <div class="max-w-xl mx-auto bg-gray-50 p-8 rounded-lg shadow-lg">
    <h1 class="text-3xl font-extrabold mb-8 text-gray-900 text-center">Add New Product</h1>
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

      <!-- Categories -->
      <div class="mb-6">
        <label class="block text-gray-700 text-sm font-medium mb-2">Categories</label>
        <multiselect
          v-model="form.categories"
          :options="categories"
          track-by="id"
          label="name"
          tag-placeholder="Add this as new category"
          placeholder="Select or add a category"
          :multiple="true"
          :taggable="true"
          @tag="addCategoryTag"
          class="w-full px-4 py-3 border rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500 text-gray-700"
        ></multiselect>
        <div v-if="validationErrors.categories">
          <ul>
            <li style="color:red" v-for="error in validationErrors.categories" :key="error">{{ error }}</li>
          </ul>
        </div>

      </div>
      <div class="mb-6">
        <label class="block text-gray-700 text-sm font-medium mb-2">Features</label>
        <div v-for="(description, index) in form.descriptions" :key="index" class="flex items-center mb-2">
          <input
            v-model="description.text"
            type="text"
            placeholder="Enter product description"
            class="w-full px-4 py-3 border rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500 text-gray-700"
          />
          <button
            type="button"
            @click="removeDescription(index)"
            class="ml-3 text-red-600 hover:text-red-800"
          >
            Remove
          </button>
        </div>
        <button
          type="button"
          @click="addDescription"
          class="mt-2 text-blue-600 hover:text-blue-800"
        >
          + Add More
        </button>
      </div>

      <!-- Submit Button -->
      <div class="text-center">
        <button
          type="submit"
          class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg shadow focus:outline-none focus:ring focus:ring-blue-300"
        >
          Add Product
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import axios from 'axios';
import TopBar from '../TopBar.vue';
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.css';

export default {
  components: {
    TopBar,
    Multiselect,
  },
  data() {
    return {
      form: {
        name: '',
        image: null,
        categories: [], // Adjusted to store selected categories
        descriptions: [{ text: '' }],
      },
      categories: [], // Categories fetched from API
      imagePreview: null,
      validationErrors: {}
    };
  },
  mounted() {
    this.getCategories();
  },
  methods: {
    getCategories() {
      axios.get('/api/category').then(response => {
        this.categories = response.data.data;
      });
    },
    previewImage(event) {
      const file = event.target.files[0];
      this.form.image = file;
      this.imagePreview = URL.createObjectURL(file);
    },
    addDescription() {
      this.form.descriptions.push({ text: '' });
    },
    removeDescription(index) {
      this.form.descriptions.splice(index, 1);
    },
    addCategoryTag(tag) {
      // Add a new category to the list of options and selected values
      const newCategory = { id: tag, name: tag }; // Adjust according to your tag structure
      this.categories.push(newCategory);
      this.form.categories.push(newCategory.id);
    },
    submitForm() {
      const formData = new FormData();
      console.log(this.form.categories);
      formData.append('name', this.form.name);
      formData.append('image', this.form.image);
      
       // Append categories as an array
      this.form.categories.forEach((cat, index) => {
        formData.append(`categories[${index}]`, cat.id); // Assuming categories are objects with an 'id' field
      });

      // Append descriptions as an array
      this.form.descriptions.forEach((description, index) => {
        formData.append(`features[${index}]`, description.text);
      });

      axios.post('/api/product/create', formData).then(response => {
        this.$router.push({ name: 'ProductList' });
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
    },
  },
};
</script>
