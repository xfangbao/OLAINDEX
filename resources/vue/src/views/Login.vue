<template>
	<div id="login">
		<b-row class="justify-content-center">
			<b-col md="6">
				<b-card bg-variant="light" class="mb-3">
					<template v-slot:header>
						<i class="ri-login-box-fill"></i> 登陆
					</template>
					<b-card-body>
						<b-form action method="post">
							<b-form-group label="用户名" label-for="name">
								<b-form-input
									id="name"
									v-model="form.name"
									type="text"
									required
									placeholder="请输入用户名"
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

							<b-button type="submit" variant="primary" @submit="handleSubmit">登录</b-button>
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
	name: 'login',
	data: () => ({
		form: {
			name: '',
			password: '',
		},
	}),
	methods: {
		...mapActions(['handleLogin']),
		handleSubmit(e) {
            e.preventDefault()
            //todo:
		},
	},
}
</script>
