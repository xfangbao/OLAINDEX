const Menu = [
	{
		name: '设置',
		icon: 'settings',
		to: { name: 'setting' },
	},
	{
		name: '文件管理',
		icon: 'folders',
		children: [
			{
				name: '文件管理',
				to: {
					name: 'home',
				},
			},
			{
				name: '其它',
				to: {
					name: 'home',
				},
			},
		],
	},
	{
		name: '缓存',
		icon: 'stack',
		children: [
			{
				name: '清理',
				to: {
					name: 'home',
				},
			},
			{
				name: '刷新',
				to: {
					name: 'home',
				},
			},
		],
	},
]
export default Menu
