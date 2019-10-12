<template>
	<div id="login">
		<b-row class="justify-content-center">
			<b-col md="6">
				<b-card bg-variant="light" class="mb-3">
					<template v-slot:header>
						<i class="ri-login-box-fill"></i> 登陆
					</template>
					<b-card-body>
						<b-form @submit="login">
							<b-form-group label="用户名" label-for="name">
								<b-form-input
									id="name"
									v-model="form.name"
									type="text"
									required
									placeholder="请输入用户名"
									ref="name"
								></b-form-input>
							</b-form-group>
							<b-form-group label="请输入密码" label-for="name">
								<b-form-input
									id="password"
									v-model="form.password"
									type="password"
									required
									placeholder="请输入密码"
								></b-form-input>
							</b-form-group>

							<b-button type="submit" variant="primary">登录</b-button>
						</b-form>
					</b-card-body>
				</b-card>
			</b-col>
		</b-row>
	</div>
</template>
<script>
import { mapActions } from 'vuex'
export default {
	name: 'page-login',
	data: () => ({
		form: {
			name: '',
			password: '',
		},
	}),
	methods: {
		...mapActions(['handleLogin']),
		login(e) {
			e.preventDefault()
			//todo:
			let _this = this
			_this
				.handleLogin(_this.form)
				.then(res => _this.loginSuccess(res))
				.catch(err => _this.loginFailed(err))
		},
		loginSuccess(res) {
			console.log(res)
			let _this = this
			_this.$router.push({ name: 'dashboard' })
			// 延迟 1 秒显示欢迎信息
			setTimeout(() => {
				_this.$toasted.success('欢迎回来')
			}, 1000)
		},
		loginFailed(err) {
			console.log(err.response)
		},
	},
}
</script>
