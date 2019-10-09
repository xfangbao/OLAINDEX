import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import filters from './utils/filters'

import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'bootswatch/dist/lux/bootstrap.css'
import VueToasted from 'vue-toasted'

Vue.use(BootstrapVue)

Vue.use(VueToasted, {
	theme: 'toasted-primary',
	position: 'top-right',
	duration: 3000,
	fitToScreen: 'true',
})

// register global utility filters
Object.keys(filters).forEach(key => {
	Vue.filter(key, filters[key])
})

Vue.config.productionTip = false

new Vue({
	router,
	store,
	render: h => h(App),
}).$mount('#app')
