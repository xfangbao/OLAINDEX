export const isString = o => {
	// 是否字符串
	return Object.prototype.toString.call(o).slice(8, -1) === 'String'
}

export const isNumber = o => {
	// 是否数字
	return Object.prototype.toString.call(o).slice(8, -1) === 'Number'
}

export const isBoolean = o => {
	// 是否boolean
	return Object.prototype.toString.call(o).slice(8, -1) === 'Boolean'
}

export const isFunction = o => {
	// 是否函数
	return Object.prototype.toString.call(o).slice(8, -1) === 'Function'
}

export const isNull = o => {
	// 是否为null
	return Object.prototype.toString.call(o).slice(8, -1) === 'Null'
}

export const isUndefined = o => {
	// 是否undefined
	return Object.prototype.toString.call(o).slice(8, -1) === 'Undefined'
}

export const isObj = o => {
	// 是否对象
	return Object.prototype.toString.call(o).slice(8, -1) === 'Object'
}

export const isArray = o => {
	// 是否数组
	return Object.prototype.toString.call(o).slice(8, -1) === 'Array'
}

export const isDate = o => {
	// 是否时间
	return Object.prototype.toString.call(o).slice(8, -1) === 'Date'
}

export const isRegExp = o => {
	// 是否正则
	return Object.prototype.toString.call(o).slice(8, -1) === 'RegExp'
}

export const isError = o => {
	// 是否错误对象
	return Object.prototype.toString.call(o).slice(8, -1) === 'Error'
}

export const isSymbol = o => {
	// 是否Symbol函数
	return Object.prototype.toString.call(o).slice(8, -1) === 'Symbol'
}

export const isPromise = o => {
	// 是否Promise对象
	return Object.prototype.toString.call(o).slice(8, -1) === 'Promise'
}

export const isSet = o => {
	// 是否Set对象
	return Object.prototype.toString.call(o).slice(8, -1) === 'Set'
}

export const isFalse = o => {
	if (!o || o === 'null' || o === 'undefined' || o === 'false' || o === 'NaN') return true
	return false
}

export const isTrue = o => {
	return !this.isFalse(o)
}

export const isIos = () => {
	let u = navigator.userAgent
	if (u.indexOf('Android') > -1 || u.indexOf('Linux') > -1) {
		// 安卓手机
		// return "Android";
		return false
	} else if (u.indexOf('iPhone') > -1) {
		// 苹果手机
		// return "iPhone";
		return true
	} else if (u.indexOf('iPad') > -1) {
		// iPad
		// return "iPad";
		return false
	} else if (u.indexOf('Windows Phone') > -1) {
		// winphone手机
		// return "Windows Phone";
		return false
	} else {
		return false
	}
}

export const isPC = () => {
	// 是否为PC端
	const userAgentInfo = navigator.userAgent
	let Agents = ['Android', 'iPhone', 'SymbianOS', 'Windows Phone', 'iPad', 'iPod']
	let flag = true
	for (var v = 0; v < Agents.length; v++) {
		if (userAgentInfo.indexOf(Agents[v]) > 0) {
			flag = false
			break
		}
	}
	return flag
}

export const browserType = () => {
	let userAgent = navigator.userAgent // 取得浏览器的userAgent字符串
	let isOpera = userAgent.indexOf('Opera') > -1 // 判断是否Opera浏览器
	let isIE = userAgent.indexOf('compatible') > -1 && userAgent.indexOf('MSIE') > -1 && !isOpera // 判断是否IE浏览器
	let isIE11 = userAgent.indexOf('Trident') > -1 && userAgent.indexOf('rv:11.0') > -1
	let isEdge = userAgent.indexOf('Edge') > -1 && !isIE // 判断是否IE的Edge浏览器
	let isFF = userAgent.indexOf('Firefox') > -1 // 判断是否Firefox浏览器
	let isSafari = userAgent.indexOf('Safari') > -1 && userAgent.indexOf('Chrome') === -1 // 判断是否Safari浏览器
	let isChrome = userAgent.indexOf('Chrome') > -1 && userAgent.indexOf('Safari') > -1 // 判断Chrome浏览器

	if (isIE) {
		let reIE = new RegExp('MSIE (\\d+\\.\\d+);')
		reIE.test(userAgent)
		let fIEVersion = parseFloat(RegExp['$1'])
		if (fIEVersion === 7) return 'IE7'
		else if (fIEVersion === 8) return 'IE8'
		else if (fIEVersion === 9) return 'IE9'
		else if (fIEVersion === 10) return 'IE10'
		else return 'IE7以下' // IE版本过低
	}
	if (isIE11) return 'IE11'
	if (isEdge) return 'Edge'
	if (isFF) return 'FF'
	if (isOpera) return 'Opera'
	if (isSafari) return 'Safari'
	if (isChrome) return 'Chrome'
}

export const readablizeBytes = bytes => {
	let s = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB']
	let e = Math.floor(Math.log(bytes) / Math.log(1024))
	return (bytes / Math.pow(1024, Math.floor(e))).toFixed(2) + ' ' + s[e]
}
