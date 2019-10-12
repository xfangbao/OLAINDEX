<template>
	<div id="message">
		<b-jumbotron>
			<h1 class="display-4">{{ code }}</h1>
			<p class="lead">{{ message }}</p>
			<hr class="my-4" />
			<p class="lead">
				<b-btn variant="primary" size="btn-lg" :to="{ name: 'home' }">返回首页</b-btn>
			</p>
		</b-jumbotron>
	</div>
</template>

<script>
export default {
	name: 'page-message',
	data: () => ({
		code: '500',
		message: 'Another OneDrive Directory Index.',
	}),
}
</script>
