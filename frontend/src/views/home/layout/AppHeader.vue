<template>
	<v-layout id="page-header" row class="align-center layout px-4 pt-4 app--page-header">
		<div class="page-header-left">
			<h3 class="pr-3">{{ title }}</h3>
		</div>
		<v-breadcrumbs divider="-" :items="breadcrumbs"></v-breadcrumbs>
		<v-spacer></v-spacer>
		<div class="page-header-right">
			<v-btn icon @click="reload">
				<v-icon class="text--secondary">refresh</v-icon>
			</v-btn>
		</div>
	</v-layout>
</template>

<script>
export default {
	name: 'page-header',
	data: () => ({
		title: '',
	}),
	computed: {
		breadcrumbs: function() {
			let _this = this
			_this.title = _this.$route.meta.name
			let breadcrumbs = [
				{
					text: '首页',
					disabled: false,
					to: { name: 'dashboard' },
				},
				// {
				// 	text: _this.title,
				// 	disabled: true,
				// },
			]
			// breadcrumbs.push()

			return breadcrumbs
		},
	},
	methods: {
		reload() {
			window.location.reload(true)
		},
	},
}
</script>
