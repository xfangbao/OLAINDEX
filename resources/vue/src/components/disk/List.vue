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
				<b-list-group-item>
					<b-row>
						<b-col cols="8" sm="6"><i class="ri-folder-fill"></i> Data</b-col>
						<b-col sm="2" class="d-none d-md-block d-md-none float-right">Mar 01 16:22</b-col>
						<b-col sm="2" class="d-none d-md-block d-md-none float-right">-</b-col>
						<b-col cols="4" sm="2">操作</b-col>
					</b-row>
				</b-list-group-item>
				<b-list-group-item>
					<b-row>
						<b-col cols="8" sm="6"><i class="ri-folder-fill"></i> Data</b-col>
						<b-col sm="2" class="d-none d-md-block d-md-none">Mar 01 16:22</b-col>
						<b-col sm="2" class="d-none d-md-block d-md-none">-</b-col>
						<b-col cols="4" sm="2">操作</b-col>
					</b-row>
				</b-list-group-item>
				<b-list-group-item>
					<b-row>
						<b-col cols="8" sm="6"><i class="ri-folder-fill"></i> Data</b-col>
						<b-col sm="2" class="d-none d-md-block d-md-none">Mar 01 16:22</b-col>
						<b-col sm="2" class="d-none d-md-block d-md-none">-</b-col>
						<b-col cols="4" sm="2">操作</b-col>
					</b-row>
				</b-list-group-item>
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
}
</script>
