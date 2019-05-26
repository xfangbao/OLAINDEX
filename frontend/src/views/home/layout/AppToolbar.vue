<template>
	<v-toolbar id="page-toolbar" color="primary" fixed dark app xs12 sm10 offset-sm1>
		<v-icon class="mx-3">fas fa-cloud</v-icon>
		<v-toolbar-title class="mr-5 align-center">
			<span class="title">OLAINDEX</span>
		</v-toolbar-title>
		<v-spacer></v-spacer>
		<v-btn icon @click.stop="handleListToggle">
			<v-icon>{{ list ? `list` : `apps` }}</v-icon>
		</v-btn>
		<v-btn icon @click="handleFullScreen()">
			<v-icon>fullscreen</v-icon>
		</v-btn>
	</v-toolbar>
</template>
<script>
import Utils from '@/utils'
export default {
	name: 'page-toolbar',
	data: () => ({
		list: true,
		icon: '',
	}),

	methods: {
		handleListToggle() {
			window.getApp.$emit('APP_VIEW_LIST', () => {
				this.list = !this.list
			})
		},
		handleFullScreen() {
			Utils.toggleFullScreen()
		},
	},
	created() {
		window.getApp.$on('APP_VIEW_LIST', () => {
			this.list = !this.list
		})
	},
}
</script>
