import { createRouter, createWebHistory } from 'vue-router'
import Home from '../Pages/Home.vue'
import Products from '../Pages/Products.vue'
import ProductDetail from '../Pages/ProductDetail.vue'
import Cart from '../Pages/Cart.vue'
import Checkout from '../Pages/Checkout.vue'
import Login from '../Pages/Login.vue'
import Signup from '../Pages/Signup.vue'
import Help from '../Pages/Help.vue'
import { Auth } from '../services/auth'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/products',
    name: 'Products',
    component: Products
  },
  {
    path: '/product/:id',
    name: 'ProductDetail',
    component: ProductDetail
  },
  {
    path: '/cart',
    name: 'Cart',
    component: Cart
  },
  {
    path: '/checkout',
    name: 'Checkout',
    component: Checkout,
    meta: { requiresAuth: true }
  },
  {
    path: '/login-page',
    name: 'Login',
    component: Login
  },
  {
    path: '/signup-page',
    name: 'Signup',
    component: Signup
  },
  {
    path: '/help',
    name: 'Help',
    component: Help
  },
  {
    path: '/dashboard',
    component: () => import('../Layout/AdminLayout.vue'),
    meta: { requiresAuth: true, },
    children: [
      {
        path: '',
        name: 'AdminDashboard',
        component: () => import('../Pages/Admin/Index.vue')
      },
      {
        path: 'products',
        name: 'AdminProducts',
        component: () => import('../Pages/Admin/Product/Index.vue')
      },
      {
        path: 'orders',
        name: 'AdminOrders',
        component: () => import('../Pages/Admin/Orders/Index.vue')
      },
      {
        path: 'customers',
        name: 'AdminCustomers',
        component: () => import('../Pages/Admin/Customers/Index.vue')
      },
      {
        path: 'categories',
        name: 'AdminCategories',
        component: () => import('../Pages/Admin/Categories/Index.vue')
      },
      {
        path: 'analytics',
        name: 'AdminAnalytics',
        component: () => import('../Pages/Admin/Analytics/Index.vue')
      },
      {
        path: 'profile',
        name: 'Profile',
        component: () => import('../Pages/Admin/Profile/Index.vue')
      },
      {
        path: 'settings',
        name: 'AdminSettings',
        component: () => import('../Pages/Admin/Settings/Index.vue')
      },
      {
        path: 'users',
        name: 'AdminUsers',
        component: () => import('../Pages/Admin/Users/Index.vue')
      },
    ],

  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('../Pages/NotFound.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const requires_auth = to.matched.some(record => record.meta.requiresAuth)
  const requires_admin = to.matched.some(record => record.meta.requiresAdmin);

  const is_authenticated = Auth.is_authenticated()
  const user = Auth.is_admin();

  if (requires_auth && !is_authenticated) {
    next({
      name: 'Login',
      query: { redirect: to.fullPath }
    })

  } else if (requires_admin) {
    // Option 1: Allow any authenticated user (recommended for unified dashboard)
    next();

    // Option 2: Keep strict — only allow specific roles
    // if (!user?.roles?.includes('super_admin') && !user?.roles?.includes('admin')) {
    //   next('/');
    // } else {
    //   next();
    // }
  } else {
    next()
  }
})

export default router
