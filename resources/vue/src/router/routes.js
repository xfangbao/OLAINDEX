import Main from '../layouts/main.vue'
function loadView(view) {
	return () => import(/* webpackChunkName: "chunk-view-[request]" */ `@/views/${view}.vue`)
	// return () => import(/* webpackChunkName: "routes" */ `@/views/${view}.vue`)
}
export default [
	{
		path: '/',
		name: 'Root',

		component: Main,
		children: [
			{
				path: '/',
				name: 'home',
				meta: {
					title: 'OLAINDEX',
					requiresAuth: false,
				},
				component: loadView('Home'),
			},
			{
				path: '/login',
				name: 'login',
				meta: {
					title: '登陆',
					requiresAuth: false,
				},
				component: loadView('Login'),
			},
		],
	},
	{
		path: '/admin',
		name: 'AdminRoot',
		component: Main,
		children: [],
	},
]
