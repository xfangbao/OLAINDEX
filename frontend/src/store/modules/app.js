import Storage from '@/plugins/store'
import { getAllConfig } from '@/api/setting'
export default {
	state: {
		app_name: 'OLAINDEX',
	},
	mutations: {
		setAppConfig(state, config) {
			for (let value in config) {
				state[value] = config[value]
			}
			Storage.set('app', config)
		},
		clearAppConfig() {
			Storage.set('app', {})
		},
	},
	actions: {
		loadAppConfig({ commit }) {
			return new Promise((resolve, reject) => {
				getAllConfig()
					.then(res => {
						const config = res
						commit('setAppConfig', config)
						resolve(res)
					})
					.catch(err => {
						reject(err)
					})
			})
		},
	},
}
