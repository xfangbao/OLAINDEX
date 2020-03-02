<template>
	<b-card no-body class="border-light mb-3"> <b-table small hover :items="items"></b-table></b-card>
</template>
<script>
export default {
	name: 'disk-table',
	data: () => ({
		fields: ['name', 'date', 'size', 'options'],
		items: [
			{ name: 'data', date: '2020-01-01', size: '1GB' },
			{ name: 'data', date: '2020-01-01', size: '1GB' },
			{ name: 'data', date: '2020-01-01', size: '1GB' },
			{ name: 'data', date: '2020-01-01', size: '1GB' },
		],
	}),
}
</script>
