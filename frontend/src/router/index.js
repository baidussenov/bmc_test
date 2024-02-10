import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'новости',
      component: () => import('@/views/NewsView.vue')
    },
    {
      path: '/post/:id',
      name: 'новость',
      component: () => import('@/views/PostView.vue')
    },
    {
      path: '/create',
      name: 'написать новость',
      component: () => import('@/views/CreateView.vue')
    }
  ]
})

export default router
