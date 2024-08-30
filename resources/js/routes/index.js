
import { createRouter, createWebHistory } from 'vue-router';
import Home from '../pages/Home.vue';
import CategoryList from '../pages/category/Manage.vue';
import CategoryCreate from '../pages/category/Create.vue';
import CategoryEdit from '../pages/category/Edit.vue';
import ProductList from '../pages/product/Manage.vue';
import ProductCreate from '../pages/product/Create.vue';
import ProductEdit from '../pages/product/Edit.vue';
import ProductDetails from '../pages/product/Details.vue';

const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/category', name: 'CategoryList', component: CategoryList },
  { path: '/category/create', name: 'CategoryCreate', component: CategoryCreate },
  { path: '/category/edit/:id', name: 'CategoryEdit', component: CategoryEdit },

  { path: '/product', name: 'ProductList', component: ProductList },
  { path: '/product/create', name: 'ProductCreate', component: ProductCreate },
  { path: '/product/edit/:id', name: 'ProductEdit', component: ProductEdit },
  { path: '/product/detalis/:id', name: 'ProductDetails', component: ProductDetails },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
