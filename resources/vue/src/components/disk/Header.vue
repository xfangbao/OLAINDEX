<template>
	<b-card bg-variant="light" class="border-0 mb-3" header-class="border-bottom-0">
		<template v-slot:header><i class="ri-information-fill"></i> Header</template>
		<b-card-body class="markdown-body">{{ content }}</b-card-body>
	</b-card>
</template>
<script>
export default {
	name: 'disk-header',
	props: {
		content: String,
	},
}
</script>
