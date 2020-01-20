import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import * as filters from './utils/filters'

import BootstrapVue from 'bootstrap-vue'
import 'bootswatch/dist/cosmo/bootstrap.min.css'
import 'bootstrap-vue/dist/bootstrap-vue.min.css'

import 'remixicon/fonts/remixicon.css'

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
