<template>
	<b-card bg-variant="light" class="mb-3" no-body>
		<template v-slot:header>
			<i class="ri-window-fill"></i> 控制台
		</template>
		<b-card-body>
			<div class="mb-3">
				<p>网盘容量</p>
				<b-progress :max="total" class="mb-3">
					<b-progress-bar variant="danger" :value="values[0]"></b-progress-bar>
					<b-progress-bar variant="success" :value="values[1]"></b-progress-bar>
					<b-progress-bar variant="info" :value="values[2]"></b-progress-bar>
				</b-progress>
			</div>
			<div class="mb-3">
				<b-form @submit="onSubmit">
					<b-form-group id="input-group" label="已绑定账号" label-for="bind_account">
						<b-form-input id="bind_account" v-model="account" type="text" disabled></b-form-input>
					</b-form-group>
					<b-button type="submit" variant="primary">解绑/绑定账户</b-button>
				</b-form>
			</div>
		</b-card-body>
	</b-card>
</template>
<script>
export default {
	name: 'page-index',
	data: () => ({
		values: [15, 30, 20],
		total: 100,
		account: 'i@ningkai.wang',
	}),
	methods: {
		onSubmit(e) {
			let _this = this
			e.preventDefault()
			// alert(JSON.stringify(this.form))
			_this.$router.push({ name: 'bind' })
		},
	},
}
</script>
