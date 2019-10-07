import Vue from 'vue'
import Router from 'vue-router'
import routes from './routes'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'

Vue.use(Router)

const router = new Router({
	mode: 'history',
	base: process.env.BASE_URL,
	routes,
})
router.beforeEach((to, from, next) => {
	NProgress.start()
	next()
})
router.afterEach(() => {
	// ...
	NProgress.done()
})

export default router
