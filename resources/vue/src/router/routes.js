import Main from '../layouts/main.vue'
function loadView(view) {
	return () => import(/* webpackChunkName: "chunk-view-[request]" */ `@/views/${view}.vue`)
	// return () => import(/* webpackChunkName: "routes" */ `@/views/${view}.vue`)
}
export default [
	{
		path: '/',
		name: 'Root',
		meta: {
			requiresAuth: false,
		},
		component: Main,
		children: [
			{
				path: '/',
				name: 'home',
				component: loadView('Home'),
			},
			{
				path: '/login',
				name: 'login',
				component: loadView('Login'),
			},
		],
	},
]
