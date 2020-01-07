<template>
	<b-card bg-variant="light" class="mb-3" no-body>
		<template v-slot:header> <i class="ri-window-fill"></i> 控制台 </template>
		<b-card-body>
			<div class="mb-3">
				<p>
					网盘容量(<span class="text-info">Total:{{ this.driver.total | readablizeBytes }}</span> /
					<span class="text-success">Used:{{ this.driver.used | readablizeBytes }}</span> )
				</p>
				<b-progress :max="this.driver.total" class="mb-3" striped>
					<b-progress-bar variant="info" :value="this.driver.used"></b-progress-bar>
					<b-progress-bar variant="success" :value="this.driver.remaining"></b-progress-bar>
					<b-progress-bar variant="danger" :value="this.driver.deleted"></b-progress-bar>
				</b-progress>
			</div>
			<div class="mb-3">
				<b-form @submit="onSubmit">
					<b-form-group id="input-group" label="已绑定账号" label-for="bind_account">
						<b-form-input id="bind_account" v-model="driver.account" type="text" disabled></b-form-input>
					</b-form-group>
					<b-button type="submit" variant="primary">解绑/绑定账户</b-button>
				</b-form>
			</div>
		</b-card-body>
	</b-card>
</template>
<script>
import { info } from '@/api/account'
export default {
	name: 'page-index',
	data: () => ({
		driver: {
			used: 0,
			total: 0,
			remaining: 0,
			deleted: 0,
			account: '',
		},
	}),
	methods: {
		onSubmit(e) {
			let _this = this
			e.preventDefault()
			// alert(JSON.stringify(this.form))
			_this.$router.push({ name: 'bind' })
		},
		getDriverInfo() {
			let _this = this
			info().then(res => {
				// let data = Object.assign(_this.driver, res.data)
				let data = res.data
				_this.driver.total = data.quota.total
				_this.driver.used = data.quota.used
				_this.driver.remaining = data.quota.remaining
				_this.driver.deleted = data.quota.deleted
				_this.driver.account = data.owner.user.email

				console.log(data)
			})
		},
	},
	created() {
		this.getDriverInfo()
	},
}
</script>
