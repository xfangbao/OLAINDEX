<template>
	<div id="home">
		<b-jumbotron>
			<h1 class="display-4">OLAINDEX</h1>
			<p class="lead">Another OneDrive Directory Index.</p>
			<hr class="my-4" />
			<p class="lead">
				<b-btn variant="primary" size="btn-lg">返回首页</b-btn>
			</p>
		</b-jumbotron>
	</div>
</template>

<script>
export default {
	name: 'home',
	created() {
		this.$toasted.success('测试信息')
		this.$toasted.error('测试错误')
	},
}
</script>
