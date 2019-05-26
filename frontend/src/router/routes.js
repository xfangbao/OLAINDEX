import HomeMain from '@/views/home/Main'
import AdminMain from '@/views/admin/Main'

function loadView(view) {
	return () => import(/* webpackChunkName: "view-[request]" */ `@/views/${view}.vue`)
	// return () => import(/* webpackChunkName: "routes" */ `@/views/${view}.vue`)
}
export default [
	{
		path: '/admin',
		meta: {
			requiresAuth: true,
		},
		name: 'Root',
		component: AdminMain,
		redirect: {
			name: 'Dashboard',
		},
		children: [
			{
				path: '/dashboard',
				meta: {
					requiresAuth: true,
				},
				name: 'Dashboard',
				component: loadView('admin/Dashboard'),
			},
		],
	},
	{
		path: '/',
		meta: {
			requiresAuth: false,
		},
		name: 'HomeRoot',
		component: HomeMain,
		redirect: {
			name: 'Home',
		},
		children: [
			{
				path: '/',
				meta: {
					requiresAuth: false,
				},
				name: 'Home',
				component: loadView('home/Index'),
			},
		],
	},

	{
		path: '/login',
		meta: {
			public: true,
		},
		name: 'Login',
		component: loadView('admin/Login'),
	},
	{
		path: '/404',
		meta: {
			public: true,
		},
		name: 'NotFound',
		component: loadView('errors/404'),
	},
	{
		path: '/403',
		meta: {
			public: true,
		},
		name: 'AccessDenied',
		component: loadView('errors/403'),
	},
	{
		path: '/500',
		meta: {
			public: true,
		},
		name: 'ServerError',
		component: loadView('errors/500'),
	},
	{
		path: '*',
		meta: {
			public: true,
		},

		redirect: {
			path: '/404',
		},
	},
]
