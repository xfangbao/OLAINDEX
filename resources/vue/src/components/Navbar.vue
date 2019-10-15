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

				<b-navbar-nav class="ml-auto" v-if="this.$route.name !== 'login'">
					<b-nav-item v-if="access_token === false" :to="{ name: 'login' }">
						<i class="ri-lock-fill"></i> 登陆
					</b-nav-item>
					<b-nav-item-dropdown v-else>
						<!-- Using 'button-content' slot -->
						<template v-slot:button-content>
							<i class="ri-user-5-fill"></i>
							{{ username }}
						</template>
						<b-dropdown-item :to="{ name: 'dashboard' }">后台管理</b-dropdown-item>
						<b-dropdown-item :to="{ name: 'home' }">个人资料</b-dropdown-item>
						<b-dropdown-item :to="{ name: 'home' }">前台</b-dropdown-item>
						<b-dropdown-item @click="logout">退出</b-dropdown-item>
					</b-nav-item-dropdown>
				</b-navbar-nav>
			</b-collapse>
		</b-container>
	</b-navbar>
</template>
<script>
import { mapGetters, mapActions } from 'vuex'
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
	computed: {
		...mapGetters({
			username: 'name',
			access_token: 'token',
		}),
	},
	methods: {
		...mapActions(['handleLogout']),
		logout() {
			let _this = this
			_this
				.handleLogout()
				.then(res => _this.logoutSuccess(res))
				.catch(err => _this.logoutFailed(err))
		},
		logoutSuccess() {
			let _this = this
			_this.$router.push({ name: 'home' })
			// 延迟 1 秒显示欢迎信息
			setTimeout(() => {
				_this.$toasted.success('退出成功')
			}, 1000)
		},
		logoutFailed(err) {
			console.log(err.response)
		},
	},
}
</script>
