'use strict'
const path = require('path')
const defaultSettings = require('./src/config/index.js')
const name = defaultSettings.title || 'OLAINDEX' // page title
const isProduction = process.env.NODE_ENV === 'production'

const resolve = dir => {
	return path.join(__dirname, dir)
}

module.exports = {
	// output built static files to Laravel's public dir.
	// note the "build" script in package.json needs to be modified as well.
	outputDir: '../../public',
	// modify the location of the generated HTML file.
	// make sure to do this only in production.
	indexPath: isProduction ? '../resources/views/index.blade.php' : 'index.html',
	// eslint-loader 是否在保存的时候检查
	lintOnSave: !isProduction,
	// 生产环境sourceMap
	productionSourceMap: false,
	/* css: {
		// 是否使用css分离插件 ExtractTextPlugin
		extract: false,
		// 开启 CSS source maps?
		sourceMap: false,
		// css预设器配置项
		loaderOptions: {},
		// 启用 CSS modules for all css / pre-processor files.
		modules: false,
	}, */
	// proxy API requests to Valet during development
	devServer: {
		proxy: {
			'/api': {
				target: 'http://localhost:8001/',
				secure: false,
				changeOrigin: true,
				pathRewrite: {
					'^/api': '',
				},
			},
		},
	},
	configureWebpack: {
		name: name,
		resolve: {
			alias: {
				'@': resolve('src'),
			},
		},
	},
	chainWebpack(config) {
		config.when(isProduction, config => {
			config
				.plugin('ScriptExtHtmlWebpackPlugin')
				.after('html')
				.use('script-ext-html-webpack-plugin', [
					{
						// `runtime` must same as runtimeChunk name. default is `runtime`
						inline: /runtime\..*\.js$/,
					},
				])
				.end()
			config
				.plugin('CompressionWebpackPlugin')
				.use('compression-webpack-plugin')
				.tap(() => {
					return [
						{
							test: /\.js$|\.html$|\.css/,
							threshold: 10240,
							deleteOriginalAssets: false,
						},
					]
				})
				.end()
			config.optimization.splitChunks({
				chunks: 'all',
				cacheGroups: {
					libs: {
						name: 'chunk-libs',
						test: /[\\/]node_modules[\\/]/,
						priority: 10,
						chunks: 'initial', // only package third parties that are initially dependent
					},
					bootstrap: {
						name: 'chunk-bootstrap', // split bootstrap into a single package
						priority: 20, // the weight needs to be larger than libs and app or it will be packaged into libs or app
						test: /[\\/]node_modules[\\/]_?bootstrap(.*)/, // in order to adapt to cnpm
					},
					bootswatch: {
						name: 'chunk-bootswatch', // split bootstrap into a single package
						priority: 20, // the weight needs to be larger than libs and app or it will be packaged into libs or app
						test: /[\\/]node_modules[\\/]_?bootswatch(.*)/, // in order to adapt to cnpm
					},
					commons: {
						name: 'chunk-commons',
						test: resolve('src/components'), // can customize your rules
						minChunks: 3, //  minimum common number
						priority: 5,
						reuseExistingChunk: true,
					},
				},
			})
			config.optimization.runtimeChunk('single')
		})
	},
}
