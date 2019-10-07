const path = require("path");
const CompressionPlugin = require("compression-webpack-plugin");

const resolve = dir => {
    return path.join(__dirname, dir);
};

module.exports = {
    // proxy API requests to Valet during development
    /*devServer: {
        proxy: 'http://laracon.test'
    },*/

    // output built static files to Laravel's public dir.
    // note the "build" script in package.json needs to be modified as well.
    outputDir: "../../public",

    // modify the location of the generated HTML file.
    // make sure to do this only in production.
    indexPath:
        process.env.NODE_ENV === "production"
            ? "../views/index.blade.php"
            : "index.html",

    // eslint-loader 是否在保存的时候检查
    lintOnSave: process.env.NODE_ENV !== "production",
    // 是否使用包含运行时编译器的Vue核心的构建。
    runtimeCompiler: false,
    // 默认情况下babel-loader忽略其中的所有文件node_modules。
    transpileDependencies: [],
    // 生产环境sourceMap
    productionSourceMap: true,

    configureWebpack: () => {
        if (process.env.NODE_ENV === "production") {
            return {
                plugins: [
                    new CompressionPlugin({
                        test: /\.js$|\.html$|.\css/, // 匹配文件名
                        threshold: 10240, // 对超过10k的数据压缩
                        deleteOriginalAssets: false // 不删除源文件
                    })
                ]
            };
        }
    },
    chainWebpack: config => {
        config.resolve.alias
            .set("@", resolve("src"))
            .set("assets", resolve("src/assets"));
        config.optimization.splitChunks({
            cacheGroups: {}
        });
    },

    // css相关配置
    css: {
        // 启用 CSS modules
        modules: false,
        // 是否使用css分离插件
        extract: true,
        // 开启 CSS source maps
        sourceMap: false
        // css预设器配置项
        // loaderOptions: {}
    },
    devServer: {
        proxy: {
            "/api": {
                target: "http://localhost:8001/",
                secure: false,
                changeOrigin: true,
                pathRewrite: {
                    "^/api": ""
                }
            }
        }
    },
    // enabled by default if the machine has more than 1 cores
    parallel: require("os").cpus().length > 1,
    // PWA 插件相关配置
    pwa: {},
    // 第三方插件配置
    pluginOptions: {
        // ...
    }
};
