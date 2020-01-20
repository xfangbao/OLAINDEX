<template>
	<b-card bg-variant="light" class="mb-3" no-body>
		<template v-slot:header> <i class="ri-settings-fill"></i> 资料修改 </template>
		<b-card-body>
			<b-form @submit="onSubmit">
				<b-form-group id="input-basic-0" label="旧密码" label-for="basic-0">
					<b-form-input id="basic-0" v-model="form.old_password" type="password" required></b-form-input>
				</b-form-group>
				<b-form-group id="input-basic-1" label="新密码" label-for="basic-2">
					<b-form-input id="basic-1" v-model="form.password" type="password" required></b-form-input>
				</b-form-group>
				<b-form-group id="input-basic-2" label="再次输入密码" label-for="basic-2">
					<b-form-input id="basic-2" v-model="form.password_confirm" type="password" required></b-form-input>
				</b-form-group>
				<b-button type="submit" variant="primary">
					<b-spinner small v-show="loading"></b-spinner>
					<span class="mx-2">更新</span>
				</b-button>
			</b-form>
		</b-card-body>
	</b-card>
</template>
<script>
import { updateProfile } from '@/api/user'
export default {
	name: 'page-profile',
	data: () => ({
		loading: false,
		form: {
			password: '',
			password_confirm: '',
			old_password: '',
		},
	}),
	methods: {
		onSubmit(e) {
			e.preventDefault()
			let _this = this
			_this.loading = true
			updateProfile(_this.form)
				.then(res => {
					console.log(res)
					_this.loading = false
					_this.$toasted.success('保存成功')
				})
				.catch(err => {
					console.log(err)
					_this.loading = false
				})
		},
	},
}
</script>
