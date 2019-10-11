<template>
	<div id="page-admin">
		<Navbar :menus="menus"></Navbar>
		<b-container class="mt-3">
			<router-view />
			<Footer></Footer>
		</b-container>
	</div>
</template>
<script>
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'
import menus from '@/api/menus'
export default {
	name: 'page-admin',
	components: { Navbar, Footer },
	data: () => ({
		menus: menus,
	}),
	created() {
		console.log(this.$route.name)
	},
}
</script>
