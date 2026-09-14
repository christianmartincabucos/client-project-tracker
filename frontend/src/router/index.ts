import { createRouter, createWebHistory } from 'vue-router'
import ProjectListView from '../views/ProjectListView.vue'
import ProjectFormView from '../views/ProjectFormView.vue'

export default createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', redirect: '/projects' },
    { path: '/projects', component: ProjectListView },
    { path: '/projects/new', component: ProjectFormView },
    { path: '/projects/:id/edit', component: ProjectFormView },
  ],
})
