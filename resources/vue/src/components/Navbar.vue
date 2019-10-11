<template>
	<b-navbar toggleable="md" type="dark" variant="primary" :sticky="true">
		<b-container>
			<b-navbar-brand :to="brand.to">{{ brand.name }}</b-navbar-brand>

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
</template>
<script>
export default {
	name: 'navbar',
	props: {
		brand: {
			type: Object,
			default: function() {
				return {
					name: 'OLAINDEX',
					to: { name: 'home' },
				}
			},
		},
	},
}
</script>
