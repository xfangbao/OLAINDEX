import Vue from 'vue'

Vue.filter('capitalize', function(value) {
	if (!value) return ''
	value = value.toString()
	return value.charAt(0).toUpperCase() + value.slice(1)
})

Vue.filter('readablizeBytes', function(bytes) {
	if (!bytes) return 0
	let s = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB']
	let e = Math.floor(Math.log(bytes) / Math.log(1024))
	return (bytes / Math.pow(1024, Math.floor(e))).toFixed(2) + ' ' + s[e]
})
