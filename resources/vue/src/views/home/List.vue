<template>
	<div>
		<Header :content="`#### 标题`"></Header>
		<b-jumbotron>
			<h1 class="display-4">OLAINDEX</h1>
			<p class="lead">Another OneDrive Directory Index.</p>
			<hr class="my-4" />
			<p class="lead">
				<b-btn variant="primary" size="btn-lg">返回首页</b-btn>
			</p>
		</b-jumbotron>
		<Readme :content="`#### Readme`"></Readme>
	</div>
</template>

<script>
import 'github-markdown-css'
import Header from '@/components/disk/Header.vue'
import Readme from '@/components/disk/Readme.vue'
export default {
	name: 'page-list',
	components: { Header, Readme },
}
</script>
