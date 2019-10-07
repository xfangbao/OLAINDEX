<template>
	<div class="about">
		<h1>This is an about page</h1>
	</div>
</template>
<script>
export default {
	created() {
		window.getApp.$bvToast.toast('test', {
			title: 'toast',
			variant: 'secondary',
			solid: true,
		})
	},
}
</script>
