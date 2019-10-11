<template>
	<div id="page-main">
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
import { HomeMenu } from '@/api/menus'
export default {
	name: 'page-main',
	components: { Navbar, Footer },
	data: () => ({
		menus: HomeMenu,
	}),
	created() {
		console.log(this.$route.name)
	},
}
</script>
