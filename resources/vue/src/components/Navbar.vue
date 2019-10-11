<template>
	<b-navbar toggleable="md" type="dark" variant="primary" :sticky="true">
		<b-container>
			<b-navbar-brand :to="brand.to">{{ brand.name }}</b-navbar-brand>

			<b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

			<b-collapse id="nav-collapse" is-nav>
				<b-navbar-nav>
					<template v-for="item in menus">
						<b-nav-item-dropdown
							v-if="item.children !== undefined && item.children.length > 0"
							:text="item.name"
							:key="item.name"
						>
							<template v-slot:text>
								<i :class="`ri-${item.icon}-fill`"></i>
								{{ item.name }}
							</template>
							<b-dropdown-item
								v-for="item_child in item.children"
								:key="item_child.name"
								:to="item_child.to"
								>{{ item_child.name }}</b-dropdown-item
							>
						</b-nav-item-dropdown>

						<b-nav-item v-else :to="item.to" :key="item.name">
							<i :class="`ri-${item.icon}-fill`"></i>
							{{ item.name }}
						</b-nav-item>
					</template>
				</b-navbar-nav>

				<b-navbar-nav v-show="this.$route.name !== 'login'" class="ml-auto">
					<b-nav-item :to="{ name: 'login' }"> <i class="ri-login-box-fill"></i> 登陆 </b-nav-item>
				</b-navbar-nav>
			</b-collapse>
		</b-container>
	</b-navbar>
</template>
<script>
export default {
	name: 'page-navbar',
	props: {
		brand: {
			type: Object,
			default: function() {
				return {
					name: 'OLAINDEX',
					to: { name: 'home' },
				}
			},
		},
		menus: Array,
	},
}
</script>
