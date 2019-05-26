<template>
	<v-app id="404">
		<v-container fluid fill-height>
			<v-layout align-center justify-center row>
				<div class="text-md-center">
					<h1 class="error-code">500</h1>
					<h2 class="my-3 headline">Sorry, internal server error.</h2>
					<div>
						<v-btn color="primary" @click="goHome">Go Home</v-btn>
					</div>
				</div>
			</v-layout>
		</v-container>
	</v-app>
</template>

<script>
export default {
	methods: {
		goHome() {
			this.$router.push({ path: '/' })
		},
	},
	mounted() {},
}
</script>
