<template>
	<b-card bg-variant="light" class="mb-3" no-body>
		<template v-slot:header> <i class="ri-window-fill"></i> 绑定设置 </template>
		<b-card-body>
			<b-form @submit="onSubmit">
				<b-form-group id="input-basic-0" label="账号类型" label-for="basic-0">
					<b-form-select
						v-model="form.account_type"
						:options="[
							{ value: 0, text: '国内版（世纪互联）' },
							{ value: 1, text: '通用版' },
						]"
					>
						<template v-slot:first>
							<option :value="null" disabled>-- 请选择账号类型 --</option>
						</template>
					</b-form-select>
				</b-form-group>
				<b-form-group id="input-basic-1" label="client_id" label-for="basic-1" description="申请可不填写">
					<b-form-input id="basic-1" v-model="form.client_id" type="text"></b-form-input>
				</b-form-group>
				<b-form-group id="input-basic-2" label="client_secret" label-for="basic-2" description="申请可不填写">
					<b-form-input id="basic-2" v-model="form.client_secret" type="text"></b-form-input>
				</b-form-group>
				<b-form-group id="input-basic-3" label="回调地址" label-for="basic-3">
					<b-form-input id="basic-3" v-model="form.redirect_uri" type="text"></b-form-input>
				</b-form-group>

				<b-button type="submit" variant="primary" class="mr-3">
					<b-spinner small v-show="loading"></b-spinner>
					<span class="mx-2">保存</span>
				</b-button>
				<b-button variant="info" @click="onApply">申请</b-button>
			</b-form>
		</b-card-body>
	</b-card>
</template>
<script>
import { bind, apply } from '@/api/account'
import { getAllConfig } from '@/api/setting'
export default {
	name: 'page-bind',
	data: () => ({
		loading: false,
		form: {
			client_id: '',
			client_secret: '',
			redirect_uri: 'https://olaindex.github.io/callback.html',
			account_type: 1,
		},
	}),
	methods: {
		init() {
			let _this = this
			let params = { params: { filter: 'account_client' } }
			getAllConfig(params).then(res => {
				let data = Object.assign(_this.form, res.data.account_client)
				console.log(data)
			})
		},
		// todo:已绑定账号，跳转首页
		onSubmit(e) {
			e.preventDefault()
			let _this = this
			_this.loading = true
			_this.form.redirect = window.location.origin
			bind(_this.form)
				.then(res => {
					let redirect = res.data.redirect
					_this.loading = false
					window.open(redirect)
				})
				.catch(err => {
					console.log(err)
					_this.loading = false
				})
		},
		onApply(e) {
			e.preventDefault()
			let _this = this
			apply(_this.form)
				.then(res => {
					let redirect = res.data.redirect
					window.open(redirect)
				})
				.catch(err => {
					console.log(err)
				})
		},
	},
	created() {
		this.init()
	},
}
</script>
