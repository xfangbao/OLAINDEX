<template>
	<div id="page-admin">
		<Navbar :menus="menus"></Navbar>
		<b-container class="mt-4 page-wraaper">
			<div class="page-content"><router-view /></div>
			<div class="page-footer"><Footer /></div>
		</b-container>
	</div>
</template>
<script>
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'
import { AdminMenu } from '@/api/menus'
export default {
	name: 'page-admin',
	components: { Navbar, Footer },
	data: () => ({
		menus: AdminMenu,
	}),
	created() {},
}
</script>
