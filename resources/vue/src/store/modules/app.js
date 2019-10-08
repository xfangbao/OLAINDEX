import Storage from '../../service/store'
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
	actions: {},
}
