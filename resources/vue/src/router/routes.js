function loadView(view) {
	return () => import(/* webpackChunkName: "view-[request]" */ `@/views/${view}.vue`)
	// return () => import(/* webpackChunkName: "routes" */ `@/views/${view}.vue`)
}
export default [
	{
		path: '/',
		name: 'home',
		component: loadView('Home'),
	},
	{
		path: '/about',
		name: 'about',
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: loadView('About'),
	},
]
