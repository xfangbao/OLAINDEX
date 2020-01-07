<template>
	<b-card bg-variant="light" class="border-0 mb-3" header-class="border-bottom-0">
		<template v-slot:header><i class="ri-quill-pen-fill"></i> Readme</template>
		<b-card-body class="markdown-body">{{ content }}</b-card-body>
	</b-card>
</template>
<script>
export default {
	name: 'disk-readme',
	props: {
		content: String,
	},
}
</script>
