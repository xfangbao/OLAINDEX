<template>
	<v-app id="login" class="primary">
		<v-content>
			<v-container fluid fill-height>
				<v-layout align-center justify-center>
					<v-flex xs12 sm8 md4 lg3>
						<v-card class="elevation-1 pa-3">
							<v-card-text>
								<div class="layout column align-center">
									<v-avatar size="128" color="grey lighten-4">
										<img :src="require('../../assets/olaindex.png')" alt="OLAINDEX Admin" />
									</v-avatar>
									<h1 class="flex my-4 blue--text">OLAINDEX</h1>
								</div>
								<v-form>
									<v-text-field
										append-icon="email"
										name="email"
										label="邮箱"
										type="text"
										v-validate="'required|email'"
										data-vv-as="邮箱"
										data-vv-validate-on="blur"
										:error="errors.has('email')"
										:error-messages="errors.collect('email')"
										v-model="model.email"
									></v-text-field>
									<v-text-field
										append-icon="lock"
										name="password"
										label="密码"
										type="password"
										v-validate="'required'"
										data-vv-as="密码"
										data-vv-validate-on="blur"
										:error="errors.has('password')"
										:error-messages="errors.collect('password')"
										v-model="model.password"
										@keyup.enter.native="login"
									></v-text-field>
								</v-form>
							</v-card-text>
							<v-card-actions>
								<v-spacer></v-spacer>
								<v-btn block dark color="primary" @click="login" :loading="loading">登录</v-btn>
							</v-card-actions>
						</v-card>
					</v-flex>
				</v-layout>
			</v-container>
		</v-content>
	</v-app>
</template>

<script>
export default {
	name: 'app-login',
	data: () => ({
		loading: false,
		model: {
			email: '',
			password: '',
		},
	}),
	methods: {
		login() {
			let _this = this
			_this.loading = true
			_this.$validator.validateAll().then(result => {
				if (result) {
					// 登录
					_this.$store.dispatch('handleLogin', _this.model).then(() => {
						window.getApp.$emit('APP_LOGIN_SUCCESS')
					})
				}
			})
			_this.loading = false
		},
	},
}
</script>
<style scoped>
#login {
	height: 50%;
	width: 100%;
	position: absolute;
	top: 0;
	left: 0;
	content: '';
	z-index: 0;
}
</style>
