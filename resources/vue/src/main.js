import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './utils/filters'

import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'bootswatch/dist/lux/bootstrap.css'
import VueToasted from 'vue-toasted'

Vue.config.productionTip = false

Vue.use(BootstrapVue)
Vue.use(VueToasted, {
	theme: 'toasted-primary',
	position: 'top-center',
	duration: 3000,
	fitToScreen: 'true',
})

new Vue({
	router,
	store,
	render: h => h(App),
}).$mount('#app')
