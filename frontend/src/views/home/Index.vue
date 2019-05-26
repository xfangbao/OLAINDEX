<template>
	<div id="page-index">
		<v-layout>
			<v-flex xs12 sm8 offset-sm2>
				<v-card flat class="my-3">
					<v-card-title>
						<h1 class="headline">站点公告</h1>
					</v-card-title>
					<v-card-text>
						<p class="body-2">
							本站发布的系统与软件仅为个人学习测试使用，请在下载后24小时内删除，不得用于任何商业用途，否则后果自负，请支持购买正版软件！如侵犯到您的权益,请及时通知我们,我们会及时处理.
						</p>
					</v-card-text>
					<!-- <v-container fluid>
						<v-layout row wrap>
							<h1 class="headline">测试</h1>
							<p class="body-2">测试测试测试测试测试测试测试</p>
						</v-layout>
          </v-container>-->
				</v-card>
				<v-spacer></v-spacer>
				<v-card flat>
					<!-- <v-container fluid>
						<v-layout row wrap>
							<v-breadcrumbs :items="items">
								<template v-slot:item="props">
									<a :href="props.item.href" :class="[props.item.disabled && 'disabled']">
										{{ props.item.text.toUpperCase() }}
									</a>
								</template>
							</v-breadcrumbs>
						</v-layout>
          </v-container>-->
					<v-container grid-list-md fluid>
						<v-layout row wrap>
							<v-flex v-for="n in 24" :key="n" xs4 sm3 md2 lg1>
								<v-card flat class="text-xs-center pa-3">
									<!-- <v x-large>folder</v -icon> -->
									<v-img src="https://share.imwnk.cn/img/folder.png"></v-img>
									<p>文件夹</p>
								</v-card>
							</v-flex>
							<v-flex v-for="n in 24" :key="n" xs4 sm3 md2 lg1>
								<v-card flat class="text-xs-center pa-3">
									<!-- <v x-large>folder</v -icon> -->
									<v-img src="https://share.imwnk.cn/img/text.png"></v-img>
									<p>文件</p>
								</v-card>
							</v-flex>
						</v-layout>
					</v-container>
				</v-card>
			</v-flex>
		</v-layout>
	</div>
</template>

<script>
export default {
	name: 'page-index',
	data: () => ({
		items: [
			{
				text: 'Dashboard',
				disabled: false,
				href: 'breadcrumbs_dashboard',
			},
			{
				text: 'Link 1',
				disabled: false,
				href: 'breadcrumbs_link_1',
			},
			{
				text: 'Link 2',
				disabled: true,
				href: 'breadcrumbs_link_2',
			},
		],
	}),
	computed: {},
	methods: {},
	created() {},
	mounted() {},
}
</script>
<style lang="stylus" scoped>
.disabled {
  color: grey;
  pointer-events: none;
}
</style>
