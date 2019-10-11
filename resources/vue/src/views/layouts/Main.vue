<template>
	<div id="page-common">
		<Navbar></Navbar>
		<b-container class="mt-3">
			<router-view />
			<Footer></Footer>
		</b-container>
	</div>
</template>
<script>
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'
export default {
	name: 'app-main',
	components: { Navbar, Footer },
	data: () => ({}),
	created() {
		console.log(this.$route.name)
	},
}
</script>
