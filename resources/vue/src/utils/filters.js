export function capitalize(value) {
	if (!value) return ''
	value = value.toString()
	return value.charAt(0).toUpperCase() + value.slice(1)
}

export function readablizeBytes(bytes, si = true) {
	const thresh = si ? 1000 : 1024
	if (Math.abs(bytes) < thresh) {
		return bytes + ' B'
	}
	const units = si
		? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
		: ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB']
	let u = -1
	do {
		bytes /= thresh
		++u
	} while (Math.abs(bytes) >= thresh && u < units.length - 1)
	return bytes.toFixed(1) + ' ' + units[u]
}

export function NumberFormat(value) {
	if (!value) {
		return '0'
	}
	const intPartFormat = value.toString().replace(/(\d)(?=(?:\d{3})+$)/g, '$1,') // 将整数部分逢三一断
	return intPartFormat
}
