export default [
	{
		name: 'APP_LOGIN_SUCCESS',
		callback: function() {
			this.$router.push({
				name: 'Dashboard',
			})
		},
	},
	{
		name: 'APP_LOGOUT_SUCCESS',
		callback: function() {
			this.$store.commit('clearAll')
			this.$router.push({
				name: 'Login',
			})
		},
	},
]
