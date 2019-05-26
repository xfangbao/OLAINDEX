<template>
	<v-snackbar
		:timeout="toast.timeout"
		:top="toast.top"
		:bottom="toast.bottom"
		:right="toast.right"
		:left="toast.left"
		:color="toast.color"
		v-model="notification"
	>
		<v-icon mr-3 dark>info</v-icon>
		{{ toast.text }}
		<v-btn dark flat @click.native="notification = false" icon>
			<v-icon>close</v-icon>
		</v-btn>
	</v-snackbar>
</template>
<script>
export default {
	data: () => ({
		toast: {
			text: 'I am a Snackbar !',
			color: 'success',
			timeout: 5000,
			top: true,
			bottom: false,
			right: false,
			left: false,
			multiline: false,
		},
		notificationQueue: [],
		notification: false,
	}),
	computed: {
		hasNotificationsPending() {
			return this.notificationQueue.length > 0
		},
	},
	watch: {
		notification() {
			if (!this.notification && this.hasNotificationsPending) {
				this.toast = this.notificationQueue.shift()
				this.$nextTick(() => {
					this.notification = true
				})
			}
		},
	},
	methods: {
		addNotification(toast) {
			if (typeof toast !== 'object') return
			this.notificationQueue.push(toast)
			if (!this.notification) {
				this.toast = this.notificationQueue.shift()
				this.notification = true
			}
		},
		makeToast(
			text,
			color = 'info',
			timeout = 6000,
			top = true,
			bottom = false,
			right = false,
			left = false,
			multiline = false,
			vertical = false,
		) {
			return {
				text,
				color,
				timeout,
				top,
				bottom,
				right,
				left,
				multiline,
				vertical,
			}
		},
	},
	created() {
		window.getApp.$on('APP_TOAST', params => {
			this.addNotification(params)
		})
	},
}
</script>
