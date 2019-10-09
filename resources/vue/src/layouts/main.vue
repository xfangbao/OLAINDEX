<template>
	<div id="page-common">
		<b-navbar toggleable="md" type="dark" variant="primary" :sticky="true">
			<b-container>
				<b-navbar-brand :to="{ name: 'home' }">OLAINDEX</b-navbar-brand>

				<b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

				<b-collapse id="nav-collapse" is-nav>
					<b-navbar-nav>
						<b-nav-item :to="{ name: 'home' }"> <i class="ri-home-fill"></i> 首页 </b-nav-item>
					</b-navbar-nav>
					<b-navbar-nav v-show="this.$route.name !== 'login'" class="ml-auto">
						<b-nav-item :to="{ name: 'login' }"> <i class="ri-login-box-fill"></i> 登陆 </b-nav-item>
					</b-navbar-nav>
				</b-collapse>
			</b-container>
		</b-navbar>
		<b-container class="mt-3">
			<router-view />
			<footer id="footer">
				<b-row class="text-center">
					<b-col col lg="12">
						<p>
							Made by
							<a href="https://imwnk.cn">IMWNK</a>.
						</p>
					</b-col>
				</b-row>
			</footer>
		</b-container>
	</div>
</template>
<script>
export default {
	name: 'app-main',
	data: () => ({}),
	created() {
		console.log(this.$route.name)
	},
}
</script>
