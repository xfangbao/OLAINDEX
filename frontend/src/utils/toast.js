function showSnackBar(params) {
	window.getApp.$emit('APP_TOAST', params)
}
const Toast = {
	success: params => {
		showSnackBar({
			text: params.msg,
			color: '#4CAF50',
			top: true,
			right: true,
			timeout: 3000,
		})
	},
	info: params => {
		showSnackBar({
			text: params.msg,
			color: '#2196F3',
			top: true,
			right: true,
			timeout: 3000,
		})
	},
	warning: params => {
		showSnackBar({
			text: params.msg,
			color: '#FB8C00',
			top: true,
			right: true,
			timeout: 3000,
		})
	},
	error: params => {
		showSnackBar({
			text: params.msg,
			color: '#FF5252',
			top: true,
			right: true,
			timeout: 3000,
		})
	},
	show: params => {
		showSnackBar({
			text: params.msg,
			color: params.color,
			top: params.top,
			right: params.right,
			timeout: params.timeout,
		})
	},
}
export default Toast
