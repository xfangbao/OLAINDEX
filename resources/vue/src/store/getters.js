const getters = {
	app_name: state => state.app.app_name,
	name: state => state.user.name,
	token: state => state.user.token,
}
export default getters
