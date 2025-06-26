const path = require('path')
const VueLoaderPlugin = require('vue-loader/lib/plugin')

module.exports = {
    mode: 'development',
    entry: {
        //product: './vue/Product/ProductAll',
        //coupon: './vue/Coupon/CouponAll',
        report: './vue/Report/ReportAll'
    },
    output: {
        filename: "bundle.[name].min.js",
        path: path.resolve(__dirname, './backend/web/js/vue')
    },
    resolve: {
        extensions: ['.js', '.vue']
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: "babel-loader"
            },
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },
            {
                test: /\.css$/,
                loaders: ['style-loader', 'css-loader'],
            },
        ]
    },
    plugins: [
        new VueLoaderPlugin()
    ]
}
