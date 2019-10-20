<template>
	<b-card bg-variant="light" class="mb-3" no-body>
		<template v-slot:header>
			<i class="ri-settings-fill"></i> 设置
		</template>
		<b-card-body>
			<b-tabs pills content-class="mt-3">
				<b-tab title="基本设置" active>
					<b-form @submit="onSubmit">
						<b-form-group id="input-basic-0" label="站点名称" label-for="basic-0">
							<b-form-input
								id="basic-0"
								v-model="form.site_name"
								type="text"
								placeholder="站点名称"
							></b-form-input>
						</b-form-group>
						<b-form-group id="input-basic-1" label="本地模式（二选一）" label-for="basic-1">
							<b-form-radio-group v-model="form.is_local" name="radio-is_local" id="basic-1">
								<b-form-radio value="0">关闭</b-form-radio>
								<b-form-radio value="1">开启</b-form-radio>
							</b-form-radio-group>
						</b-form-group>
						<b-form-group id="input-basic-2" label="显示目录" label-for="basic-2">
							<b-form-input
								id="basic-2"
								v-model="form.root_path"
								required
								placeholder="显示根目录"
							></b-form-input>
						</b-form-group>
						<b-form-group
							id="input-basic-3"
							label="缓存时间(秒)"
							label-for="basic-3"
							description="建议缓存时间小于60分钟，否则会导致缓存失效"
						>
							<b-form-input id="basic-3" v-model="form.expires" placeholder="缓存时间"></b-form-input>
						</b-form-group>
						<b-form-group id="input-basic-4" label="开启搜索" label-for="basic-4">
							<b-form-radio-group v-model="form.open_search" name="radio-open_search" id="basic-4">
								<b-form-radio value="0">关闭</b-form-radio>
								<b-form-radio value="1">开启</b-form-radio>
							</b-form-radio-group>
						</b-form-group>
						<b-form-group
							id="input-basic-5"
							label="搜索频率"
							label-for="basic-5"
							description="重试等待时间默认是1分钟（格式：5,10，每10分钟最多上传5次；5 每分钟最多上传5次）"
						>
							<b-form-input
								id="basic-5"
								v-model="form.search_limit"
								type="text"
								placeholder="搜索频率"
							></b-form-input>
						</b-form-group>

						<b-button type="submit" variant="primary">
							<b-spinner small v-show="loading"></b-spinner>
							<span class="mx-2">保存</span>
						</b-button>
					</b-form>
				</b-tab>
				<b-tab title="显示设置">
					<b-form @submit="onSubmit">
						<b-form-group
							id="input-show-1"
							label="图片"
							label-for="show-1"
							description="文件展示类型（扩展名）以空格分开"
						>
							<b-form-input id="show-1" v-model="form.show.image" type="text"></b-form-input>
						</b-form-group>
						<b-form-group
							id="input-show-2"
							label="视频"
							label-for="show-2"
							description="文件展示类型（扩展名）以空格分开"
						>
							<b-form-input id="show-2" v-model="form.show.video" type="text"></b-form-input>
						</b-form-group>
						<b-form-group
							id="input-show-3"
							label="dash视频"
							label-for="show-3"
							description="文件展示类型（扩展名）以空格分开"
						>
							<b-form-input id="show-3" v-model="form.show.dash" type="text"></b-form-input>
						</b-form-group>
						<b-form-group
							id="input-show-4"
							label="音频"
							label-for="show-4"
							description="文件展示类型（扩展名）以空格分开"
						>
							<b-form-input id="show-4" v-model="form.show.audio" type="text"></b-form-input>
						</b-form-group>
						<b-form-group
							id="input-show-5"
							label="文档"
							label-for="show-5"
							description="文件展示类型（扩展名）以空格分开"
						>
							<b-form-input id="show-5" v-model="form.show.doc" type="text"></b-form-input>
						</b-form-group>
						<b-form-group
							id="input-show-6"
							label="代码"
							label-for="show-6"
							description="文件展示类型（扩展名）以空格分开"
						>
							<b-form-input id="show-6" v-model="form.show.code" type="text"></b-form-input>
						</b-form-group>
						<b-form-group
							id="input-show-7"
							label="文件流"
							label-for="show-7"
							description="文件展示类型（扩展名）以空格分开"
						>
							<b-form-input id="show-7" v-model="form.show.stream" type="text"></b-form-input>
						</b-form-group>

						<b-button type="submit" variant="primary">
							<b-spinner small v-show="loading"></b-spinner>
							<span class="mx-2">保存</span>
						</b-button>
					</b-form>
				</b-tab>
				<b-tab title="图床设置">
					<b-form @submit="onSubmit">
						<b-form-group id="input-image-1" label="开启图床" label-for="image-1">
							<b-form-radio-group
								v-model="form.open_image_hosting"
								name="radio-sub-component"
								id="image-1"
							>
								<b-form-radio value="0">关闭</b-form-radio>
								<b-form-radio value="1">开启</b-form-radio>
								<b-form-radio value="2">仅管理员开启</b-form-radio>
							</b-form-radio-group>
						</b-form-group>
						<b-form-group id="input-image-2" label="图床保存地址" label-for="image-2">
							<b-form-input
								id="image-2"
								v-model="form.image_hosting_path"
								type="text"
								placeholder="图床保存地址"
							></b-form-input>
						</b-form-group>
						<b-form-group
							id="input-image-3"
							label="图床上传频率"
							label-for="image-3"
							description="重试等待时间默认是1分钟（格式：5,10，每10分钟最多上传5次；5 每分钟最多上传5次）"
						>
							<b-form-input
								id="image-3"
								v-model="form.image_hosting_limit"
								type="text"
								placeholder="图床上传频率"
							></b-form-input>
						</b-form-group>
						<b-button type="submit" variant="primary">
							<b-spinner small v-show="loading"></b-spinner>
							<span class="mx-2">保存</span>
						</b-button>
					</b-form>
				</b-tab>
				<b-tab title="其他设置">
					<b-form @submit="onSubmit">
						<b-form-group id="input-other-1" label="防盗链" label-for="other-1">
							<b-form-input
								id="other-1"
								v-model="form.whitelist"
								type="text"
								placeholder="防盗链白名单"
							></b-form-input>
						</b-form-group>
						<b-form-group id="input-other-2" label="自定义版权" label-for="other-2">
							<b-form-input
								id="other-2"
								v-model="form.copyright"
								type="text"
								placeholder="自定义版权"
							></b-form-input>
						</b-form-group>
						<b-form-group id="input-other-3" label="统计代码" label-for="other-3">
							<b-form-input
								id="other-3"
								v-model="form.statistics"
								type="text"
								placeholder="统计代码"
							></b-form-input>
						</b-form-group>
						<b-form-group id="input-other-4" label="接口token" label-for="other-4">
							<b-form-input
								id="other-4"
								v-model="form.token"
								type="text"
								placeholder="接口token"
							></b-form-input>
						</b-form-group>
						<b-button type="submit" variant="primary">
							<b-spinner small v-show="loading"></b-spinner>
							<span class="mx-2">保存</span>
						</b-button>
					</b-form>
				</b-tab>
			</b-tabs>
		</b-card-body>
	</b-card>
</template>
<script>
import { getAllConfig, updateConfig } from '@/api/setting'
export default {
	name: 'page-setting',
	data: () => ({
		loading: false,
		form: {
			site_name: 'OLAINDEX',
			is_local: 0,
			root_path: '/share',
			expires: '1800',
			open_search: 0,
			search_limit: '5,5',
			show: {
				image: 'png',
				video: 'mp4',
				dash: 'avi',
				audio: 'mp3',
				doc: 'doc xls',
				code: 'php js',
				stream: 'txt log',
			},
			open_image_hosting: 0,
			image_hosting_path: '/image',
			image_hosting_limit: '5,5',
			whitelist: 'imwnk.cn',
			copyright: '',
			statistics: '',
			token: '123456',
		},
	}),
	methods: {
		init() {
			let _this = this
			getAllConfig().then(res => {
				let data = Object.assign(_this.form, res.data)
				console.log(data)
			})
		},
		onSubmit(e) {
			e.preventDefault()
			let _this = this
			_this.loading = true
			updateConfig(_this.form)
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
	created() {
		this.init()
	},
}
</script>
