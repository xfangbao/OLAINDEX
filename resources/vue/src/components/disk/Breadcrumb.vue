<template>
	<b-breadcrumb :items="items"></b-breadcrumb>
</template>
<script>
export default {
	name: 'disk-breadcrumb',
	props: {
		items: Array,
	},
}
</script>
