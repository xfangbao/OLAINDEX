<template>
	<div id="page-wrapper">
		<transition name="animated-transition" enter-active-class="animated fadeIn">
			<router-view></router-view>
		</transition>
	</div>
</template>
<script>
export default {
	name: 'page-content',
}
</script>
