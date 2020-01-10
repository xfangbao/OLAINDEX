<template>
	<b-card bg-variant="light" class="border-0 mb-3" header-class="border-bottom-1">
		<template v-slot:header><i class="ri-quill-pen-fill"></i> Readme</template>
		<b-card-body>
			<div class="markdown-body" v-html="toMarkdown(content)"></div>
		</b-card-body>
	</b-card>
</template>
<script>
import marked from 'marked'
export default {
	name: 'disk-readme',
	props: {
		content: String,
	},
	methods: {
		toMarkdown(data) {
			return marked(data || '')
		},
	},
}
</script>
