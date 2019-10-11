import Main from '../views/layouts/Main.vue'
function loadView(view) {
	return () => import(/* webpackChunkName: "chunk-view-[request]" */ `@/views/${view}.vue`)
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
				component: loadView('home/Index'),
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
			{
				path: '/login',
				name: 'login',
				meta: {
					title: '登陆',
					requiresAuth: false,
				},
				component: loadView('home/Login'),
			},
		],
	},
	{
		path: '/admin',
		component: Main,
		children: [],
	},
]
