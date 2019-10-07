<template>
	<div id="app">
		<router-view />
	</div>
</template>
<script>
import AppEvents from './event'
export default {
	name: 'App',
	data: () => ({}),
	created() {
		AppEvents.forEach(item => {
			this.$on(item.name, item.callback)
		})
		window.getApp = this
	},
}
</script>
<style src="@/style/app.css"></style>
