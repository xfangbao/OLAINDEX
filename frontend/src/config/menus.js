const Menu = [
	{
		title: '新建项目',
		group: 'new',
		icon: 'add_circle',
		items: [
			{
				name: 'new/file',
				title: '上传文件',
				route: 'file_add',
			},
			{
				name: 'new/folder',
				title: '新建目录',
				route: 'folder_add',
			},
		],
	},
	{
		header: '应用',
	},
	{
		title: '首页',
		group: 'apps',
		icon: 'dashboard',
		name: 'dashboard',
	},
	{
		title: '图片',
		group: 'apps',
		icon: 'photo_library',
		name: 'image',
	},
	{
		title: '视频',
		group: 'apps',
		icon: 'video_library',
		name: 'video',
	},
	{
		title: '音频',
		group: 'apps',
		icon: 'library_music',
		name: 'audio',
	},
	{
		title: '文件',
		group: 'apps',
		icon: 'file_copy',
		name: 'file',
	},
	{
		header: '分享',
	},
	{
		title: '分享',
		group: 'share',
		icon: 'share',
		items: [
			{
				name: 'share_my',
				title: '我的分享',
				route: 'share_my',
			},
			{
				name: 'share_other',
				title: '其他分享',
				route: 'share_other',
			},
		],
	},

	{
		divider: true,
	},
	{
		header: '其它',
	},
	{
		title: '页面',
		group: 'pages',
		icon: 'list',
		items: [
			{
				name: 'dashboard',
				title: '首页',
				route: 'dashboard',
			},
			{
				name: '404',
				title: '404',
				route: 'NotFound',
			},
			{
				name: '403',
				title: '403',
				route: 'AccessDenied',
			},
			{
				name: '500',
				title: '500',
				route: 'ServerError',
			},
		],
	},
]
// reorder menu
Menu.forEach(item => {
	if (item.items) {
		item.items.sort((x, y) => {
			let textA = x.title.toUpperCase()
			let textB = y.title.toUpperCase()
			return textA < textB ? -1 : textA > textB ? 1 : 0
		})
	}
})

export default Menu
