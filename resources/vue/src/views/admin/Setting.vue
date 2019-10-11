<template>
	<div id="setting">
		<b-card bg-variant="light" class="mb-3" no-body>
			<template v-slot:header>
				<i class="ri-settings-fill"></i> 设置
			</template>
			<b-card-body>
				<b-tabs pills content-class="mt-3">
					<b-tab title="基本设置" active>
						<b-form @submit="onSubmit">
							<b-form-group id="input-group-1" label="站点名称" label-for="input-1">
								<b-form-input
									id="input-1"
									v-model="form.site_name"
									type="text"
									placeholder="站点名称"
								></b-form-input>
							</b-form-group>

							<b-form-group id="input-group-2" label="显示目录" label-for="input-2">
								<b-form-input
									id="input-2"
									v-model="form.root_path"
									required
									placeholder="显示根目录"
								></b-form-input>
							</b-form-group>
							<b-form-group
								id="input-group-3"
								label="缓存时间(秒)"
								label-for="input-3"
								description="建议缓存时间小于60分钟，否则会导致缓存失效"
							>
								<b-form-input id="input-3" v-model="form.expires" placeholder="缓存时间"></b-form-input>
							</b-form-group>

							<b-button type="submit" variant="primary">保存</b-button>
						</b-form>
					</b-tab>
					<b-tab title="显示设置">
						<b-form @submit="onSubmit">
							<b-form-group
								id="input-group-4"
								label="图片"
								label-for="input-4"
								description="文件展示类型（扩展名）以空格分开"
							>
								<b-form-input id="input-4" v-model="form.show.image" type="text"></b-form-input>
							</b-form-group>
							<b-form-group
								id="input-group-5"
								label="视频"
								label-for="input-5"
								description="文件展示类型（扩展名）以空格分开"
							>
								<b-form-input id="input-5" v-model="form.show.video" type="text"></b-form-input>
							</b-form-group>
							<b-form-group
								id="input-group-6"
								label="dash视频"
								label-for="input-6"
								description="文件展示类型（扩展名）以空格分开"
							>
								<b-form-input id="input-6" v-model="form.show.dash" type="text"></b-form-input>
							</b-form-group>
							<b-form-group
								id="input-group-7"
								label="音频"
								label-for="input-7"
								description="文件展示类型（扩展名）以空格分开"
							>
								<b-form-input id="input-7" v-model="form.show.audio" type="text"></b-form-input>
							</b-form-group>
							<b-form-group
								id="input-group-8"
								label="文档"
								label-for="input-8"
								description="文件展示类型（扩展名）以空格分开"
							>
								<b-form-input id="input-8" v-model="form.show.doc" type="text"></b-form-input>
							</b-form-group>
							<b-form-group
								id="input-group-9"
								label="代码"
								label-for="input-9"
								description="文件展示类型（扩展名）以空格分开"
							>
								<b-form-input id="input-9" v-model="form.show.code" type="text"></b-form-input>
							</b-form-group>
							<b-form-group
								id="input-group-10"
								label="文件流"
								label-for="input-10"
								description="文件展示类型（扩展名）以空格分开"
							>
								<b-form-input id="input-9" v-model="form.show.stream" type="text"></b-form-input>
							</b-form-group>

							<b-button type="submit" variant="primary">保存</b-button>
						</b-form>
					</b-tab>
					<b-tab title="其他设置">
						<b-form @submit="onSubmit">
							<b-form-group
								id="input-group-1"
								label="Email address:"
								label-for="input-1"
								description="We'll never share your email with anyone else."
							>
								<b-form-input
									id="input-1"
									v-model="form.email"
									type="email"
									required
									placeholder="Enter email"
								></b-form-input>
							</b-form-group>
							<b-button type="submit" variant="primary">保存</b-button>
						</b-form>
					</b-tab>
				</b-tabs>
			</b-card-body>
		</b-card>
	</div>
</template>
<script>
export default {
	name: 'page-setting',
	data: () => ({
		form: {
			site_name: '',
			root_path: '',
			expires: '',
			show: {
				image: '',
				video: '',
				dash: '',
				audio: '',
				doc: '',
				code: '',
				stream: '',
			},
		},
	}),
	methods: {
		onSubmit(evt) {
			evt.preventDefault()
			alert(JSON.stringify(this.form))
		},
	},
}
</script>
