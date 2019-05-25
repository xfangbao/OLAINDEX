<template>
	<div id="app">
		<router-view />
		<toast />
	</div>
</template>

<script>
import AppEvents from './event'
import Toast from './components/widgets/Toast'
export default {
	name: 'App',
	components: { Toast },
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
