import Main from '../views/layouts/Main.vue'
import Admin from '../views/layouts/Admin.vue'
function loadView(view) {
	// return () => import(/* webpackChunkName: "chunk-view-[request]" */ `@/views/${view}.vue`)
	return () => import(`@/views/${view}.vue`)
}
export default [
	{
		path: '/',
		component: Main,
		children: [
			{
				path: '/',
				name: 'home',
				meta: {
					title: 'OLAINDEX',
					requiresAuth: false,
				},
				component: loadView('home/List'),
			},
			{
				path: '/message',
				name: 'message',
				meta: {
					title: '消息',
					requiresAuth: false,
				},
				component: loadView('home/Message'),
			},
		],
	},
	{
		path: '/login',
		name: 'login',
		meta: {
			title: '登陆',
			requiresAuth: false,
		},
		component: loadView('home/Login'),
	},
	{
		path: '/admin',
		component: Admin,
		children: [
			{
				path: '/',
				name: 'dashboard',
				meta: {
					title: '控制台',
					requiresAuth: true,
				},
				component: loadView('admin/Index'),
			},
			{
				path: '/admin/bind',
				name: 'bind',
				meta: {
					title: '绑定',
					requiresAuth: true,
				},
				component: loadView('admin/Bind'),
			},
			{
				path: '/admin/setting',
				name: 'setting',
				meta: {
					title: '设置',
					requiresAuth: true,
				},
				component: loadView('admin/Setting'),
			},
		],
	},
]
