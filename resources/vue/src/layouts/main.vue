<template>
	<div id="page-common">
		<b-navbar toggleable="md" type="dark" variant="primary" sticky="true">
			<b-container>
				<b-navbar-brand href="#">OLAINDEX</b-navbar-brand>

				<b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

				<b-collapse id="nav-collapse" is-nav>
					<b-navbar-nav>
						<b-nav-item href="#">首页</b-nav-item>
					</b-navbar-nav>
				</b-collapse>
			</b-container>
		</b-navbar>
		<b-container class="mt-3">
			<router-view />
			<b-footer id="footer">
				<b-row class="text-center">
					<b-col col lg="12">
						<p>
							Made by
							<a href="http://imwnk.cn">IMWNK</a>.
						</p>
					</b-col>
				</b-row>
			</b-footer>
		</b-container>
	</div>
</template>
<script>
export default {
	name: 'app-main',
	data: () => ({}),
}
</script>
