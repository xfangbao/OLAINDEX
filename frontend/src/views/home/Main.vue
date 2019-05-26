<template>
	<div id="page-root">
		<template v-if="!$route.meta.public">
			<v-app class="app">
				<app-toolbar></app-toolbar>

				<v-content>
					<!-- header -->
					<app-header></app-header>
					<!-- content -->
					<app-content></app-content>
					<!-- footer -->
					<app-footer></app-footer>
				</v-content>
				<!-- returnTopButton -->
				<app-fab></app-fab>
			</v-app>
		</template>
		<template v-else>
			<transition>
				<keep-alive>
					<router-view :key="$route.fullpath"></router-view>
				</keep-alive>
			</transition>
		</template>
	</div>
</template>
<script>
import AppToolbar from './layout/AppToolbar'
import AppHeader from './layout/AppHeader'
import AppFooter from './layout/AppToolbar'
import AppContent from './layout/AppContent'
import AppFab from '@/components/widgets/AppFab'
export default {
	name: 'app-main',
	data: () => ({}),
	components: { AppToolbar, AppHeader, AppFooter, AppContent, AppFab },
	methods: {},
}
</script>
