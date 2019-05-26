<template>
	<div id="page-index">
		<v-layout>
			<v-flex xs12 sm8 offset-sm2>
				<v-card>
					<v-card-actions>
						<v-select v-model="size" :items="items" label="Size"></v-select>
						<v-spacer></v-spacer>
					</v-card-actions>
					<v-container v-bind="{ [`grid-list-${size}`]: true }" fluid>
						<v-layout row wrap>
							<v-flex v-for="n in 24" :key="n" xs12 sm6 md3 lg2>
								<v-card flat tile>
									<v-img
										:src="
											`https://unsplash.it/150/300?image=${Math.floor(Math.random() * 100) + 1}`
										"
										height="150px"
									></v-img>
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
		size: 'sm',
		items: [
			{ text: 'Extra small (2px)', value: 'xs' },
			{ text: 'Small (4px)', value: 'sm' },
			{ text: 'Medium (8px)', value: 'md' },
			{ text: 'Large (16px)', value: 'lg' },
			{ text: 'Extra large (24px)', value: 'xl' },
		],
	}),
	computed: {},
	methods: {},
	created() {},
	mounted() {},
}
</script>
