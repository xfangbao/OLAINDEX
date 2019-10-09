import Vue from 'vue'
import Router from 'vue-router'
import store from 'vuex'
import routes from './routes'
import Storage from '../../service/store'
import { markTitle } from '../utils'
import { getToken } from '../utils/auth'
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

	// 初始化站点配置
	Storage.defaults({
		app: {},
	})
	/* const app = Storage.get('app')
	if (!app.app_name) {
		store.dispatch('loadAppConfig')
	} else {
		store.commit('setAppConfig', app)
    } */
	const LOGIN_PAGE_NAME = 'login'

	// 初始化登陆信息
	const token = getToken()
	if (token && to.name === LOGIN_PAGE_NAME) {
		// 已登录且要跳转的页面是登录页
		next({
			name: 'dashboard', // 跳转到 Root页
		})
	}
	markTitle(to.meta.title)
	if (to.matched.some(record => record.meta.requiresAuth)) {
		// 初始化数据
		Storage.defaults({
			user: {},
		})
		const user = Storage.get('user')
		if (user.id) {
			store.commit('setUserId', user.id)
			store.commit('setUserName', user.name)
			store.commit('setStatus', user.status)
		} else {
			// store.dispatch('getUserInfo')
		}
		if (token) {
			store.commit('setToken', token)
		}
		// 判断登录
		if (!token && to.name !== LOGIN_PAGE_NAME) {
			// 未登录且要跳转的页面不是登录页
			next({
				name: LOGIN_PAGE_NAME, // 跳转到登录页
			})
		} else if (!token && to.name === LOGIN_PAGE_NAME) {
			// 未登陆且要跳转的页面是登录页
			next() // 跳转
		} else {
			next()
		}
	} else {
		next()
	}
})
router.afterEach(() => {
	// ...
	NProgress.done()
})

export default router
