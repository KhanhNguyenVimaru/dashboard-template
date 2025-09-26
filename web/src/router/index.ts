import { createRouter, createWebHistory } from 'vue-router'
import DashBoardView from '@/views/DashBoardView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: DashBoardView,
    }
  ],
})

export default router
