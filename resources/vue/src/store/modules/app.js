import Storage from '../../service/store'
import { loadAppConfig } from '../../api/app'
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
				loadAppConfig()
					.then(res => {
						const config = res.data
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
