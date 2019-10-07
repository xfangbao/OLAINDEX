'use strict '

import axios from 'axios'
import store from '../store'
import router from '../router'
import setting from '../config'

// Full config:  https://github.com/axios/axios#request-config
// axios.defaults.baseURL = process.env.baseURL || process.env.apiUrl || '';
// axios.defaults.headers.common['Authorization'] = AUTH_TOKEN;
// axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';

let config = {
	baseURL: process.env.NODE_ENV === 'production' ? setting.build.baseUrl : setting.dev.baseUrl,
	// timeout: 3600, // Timeout
	// withCredentials: true, // Check cross-site Access-Control
}

const _axios = axios.create(config)

_axios.interceptors.request.use(
	function(config) {
		const token = store.getters.token
		if (token) {
			config.headers.common['Authorization'] = `${token}`
		}
		// Do something before request is sent
		return config
	},
	function(error) {
		// Do something with request error
		return Promise.reject(error)
	},
)

// Add a response interceptor
_axios.interceptors.response.use(
	function(response) {
		// Do something with response data
		const token = response.headers.authorization
		console.log(response)
		if (token) {
			// 如果 header 中存在 token，那么触发 refreshToken 方法，替换本地的 token
			store.commit('setToken', token)
		}
		return response.data
	},
	function(error) {
		if (error && error.response) {
			const token = error.response.headers.authorization
			console.log(error.response)
			if (token) {
				// 如果 header 中存在 token，那么触发 refreshToken 方法，替换本地的 token
				store.commit('setToken', token)
			}
			switch (error.response.status) {
				case 400:
					if (error.response.data.message) {
						error.message = error.response.data.message
					} else {
						error.message = '请求错误'
					}
					break

				case 401:
					error.message = '登录失效'
					// 退出登录 todo
					store.commit('clearAll')
					router.push({
						name: 'Login',
					})
					break

				case 403:
					error.message = '拒绝访问'
					router.push({
						name: '403',
					})
					break

				case 404:
					if (error.response.data.message) {
						error.message = error.response.data.message
					} else {
						error.message = '未找到内容'
					}
					break

				case 408:
					error.message = '请求超时'
					break

				case 422:
					error.message = error.response.data.errors[0]['code']
					break

				case 429:
					error.message = error.response.data.message
					break
				case 500:
					error.message = '服务器内部错误'
					break

				case 501:
					error.message = '服务未实现'
					break

				case 502:
					error.message = '网关错误'
					break

				case 503:
					error.message = '服务不可用'
					break

				case 504:
					error.message = '网关超时'
					break

				case 505:
					error.message = 'HTTP版本不受支持'
					break

				default:
			}
		}
		// Do something with response error

		return Promise.reject(error)
	},
)

export default _axios
