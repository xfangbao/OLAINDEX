<template>
	<b-card-group>
		<b-card no-body class="border-light mb-3">
			<template v-slot:header>
				<b-row>
					<b-col cols="8" sm="6"
						>文件 <a href="javascript:void(0);"><i class="ri-arrow-down-line"></i></a
					></b-col>
					<b-col sm="2" class="d-none d-md-block d-md-none"
						>修改日期 <a href="javascript:void(0);"><i class="ri-arrow-down-line"></i></a
					></b-col>
					<b-col sm="2" class="d-none d-md-block d-md-none"
						>大小 <a href="javascript:void(0);"><i class="ri-arrow-down-line"></i></a
					></b-col>
					<b-col cols="4" sm="2">操作</b-col>
				</b-row>
			</template>
			<b-list-group flush class="border-0 item-list">
				<template v-for="item in items">
					<b-list-group-item :key="item.id">
						<b-row>
							<b-col cols="8" sm="6"><i class="ri-folder-fill"></i> {{ item.name }}</b-col>
							<b-col sm="2" class="d-none d-md-block d-md-none float-right">{{ item.date }}</b-col>
							<b-col sm="2" class="d-none d-md-block d-md-none float-right">{{
								item.size | readablizeBytes
							}}</b-col>
							<b-col cols="4" sm="2">操作</b-col>
						</b-row>
					</b-list-group-item>
				</template>
				<b-list-group-item>
					<b-row>
						<b-col cols="8" sm="6" style="text-overflow:ellipsis;overflow:hidden;white-space:nowrap;"
							><span class="text-muted font-weight-light">
								共 7 个项目
							</span>
						</b-col>
					</b-row>
				</b-list-group-item>
			</b-list-group>
		</b-card>
	</b-card-group>
</template>
<script>
export default {
	name: 'disk-list',
	data: () => ({
		items: [
			{
				id: 1,
				name: 'data',
				date: 'Mar 01 16:22',
				size: '456321',
			},
			{
				id: 2,
				name: 'video',
				date: 'Mar 01 16:22',
				size: '123456',
			},
			{
				id: 3,
				name: 'music',
				date: 'Mar 01 16:22',
				size: '111222',
			},
		],
	}),
}
</script>
