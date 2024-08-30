<template>
<TopBar />
  <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden my-8">
    <div class="bg-cover bg-center h-56 sm:h-96" :style="{ backgroundImage: `url(${imagePreview})` }"></div>
    <div class="p-8">
      <!-- Product Name -->
      <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ product.name }}</h1>

      <!-- Categories -->
      <div class="flex flex-wrap items-center mb-6">
        <span v-for="category in product.category" :key="category.id" class="bg-blue-100 text-blue-800 text-sm font-semibold mr-2 px-2.5 py-0.5 rounded-full">
          {{ category.name }}
        </span>
      </div>

      <!-- Features -->
      <div>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Features</h2>
        <ul class="list-disc pl-5">
          <li v-for="feature in product.feature" :key="feature.id" class="text-gray-600 mb-2">{{ feature.description }}</li>
        </ul>
      </div>
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
      productId: null,
      product: {
        name: '',
        image: '',
        category: [],
        feature: [],
      },
      imagePreview: null,
    };
  },
  mounted() {
    this.productId = this.$route.params.id;
    this.getProduct();
  },
  methods: {
    getProduct() {
      axios.get(`/api/product/show/${this.productId}`).then(response => {
        this.product = response.data.data;
        this.imagePreview = `/images/${this.product.image}`;
      });
    },
  },
};
</script>

