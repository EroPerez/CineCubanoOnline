var Encore = require('@symfony/webpack-encore');
//var CopyWebpackPlugin = require('copy-webpack-plugin');

Encore
        .setOutputPath('public/build/')
        .setPublicPath('/build')
        .cleanupOutputBeforeBuild()
        .enableSourceMaps(!Encore.isProduction())
        .autoProvidejQuery()
        .enableSassLoader()
        .enableVersioning(true)
        .copyFiles({
            from: './assets/img',
            to: 'images/[path][name].[ext]'
        })
        .copyFiles({
            from: './assets/css/',
            to: 'css/[path][name].[ext]'
        })
        .copyFiles({
            from: './assets/fonts/',
            to: 'fonts/[path][name].[ext]'
        })
        .addEntry('js/app', './assets/js/app.js')
        .addStyleEntry('css/app', ['./assets/scss/app.scss']);

module.exports = Encore.getWebpackConfig();