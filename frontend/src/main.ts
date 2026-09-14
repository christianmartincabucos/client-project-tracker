import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { RouterView } from 'vue-router'
import router from './router'
import './styles.css'
import App from './App.vue'

createApp(App).use(createPinia()).use(router).component('RouterView', RouterView).mount('#app')
