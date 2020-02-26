<template>
	<div>
		<Breadcrumb :items="items"></Breadcrumb>
		<Header :content="`#### HEAD`"></Header>
		<List></List>
		<Readme :content="`#### Readme`"></Readme>
	</div>
</template>

<script>
import 'github-markdown-css'
import Header from '@/components/disk/Header.vue'
import Breadcrumb from '@/components/disk/Breadcrumb.vue'
import Readme from '@/components/disk/Readme.vue'
import List from '@/components/disk/List.vue'
export default {
	name: 'page-list',
	components: { Breadcrumb, Header, Readme, List },
	data: () => ({
		items: [{ text: 'Home' }],
	}),
}
</script>
