const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')
    .addEntry('js/product', './assets/js/product.js')
    .addEntry('js/location', './assets/js/location.js')
    .addEntry('js/modal', './assets/js/modal.js')
    .addStyleEntry('css/app', './assets/styles/app.css')
    .addStyleEntry('css/form', './assets/styles/form.css')
    .addStyleEntry('css/modal', './assets/styles/modal.css')
    .addStyleEntry('css/security', './assets/styles/security.css')
    .addStyleEntry('css/product', './assets/styles/product.css')
    .addStyleEntry('css/location', './assets/styles/location.css')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })
;

module.exports = Encore.getWebpackConfig();
