const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')
    .addEntry('js/barcode', './assets/js/barcode.js')
    .addEntry('js/product', './assets/js/product.js')
    .addEntry('js/location', './assets/js/location.js')
    .addEntry('js/modal', './assets/js/modal.js')
    .addEntry('js/report', './assets/js/report.js')
    .addEntry('js/user', './assets/js/user.js')
    .addStyleEntry('css/app', './assets/styles/app.css')
    .addStyleEntry('css/imports', './assets/styles/imports.scss')
    .addStyleEntry('css/form', './assets/styles/form.css')
    .addStyleEntry('css/modal', './assets/styles/modal.css')
    .addStyleEntry('css/security', './assets/styles/security.css')
    .addStyleEntry('css/product', './assets/styles/product.css')
    .addStyleEntry('css/location', './assets/styles/location.css')
    .addStyleEntry('css/report', './assets/styles/report.css')
    .addStyleEntry('css/user_control', './assets/styles/user_control.css')
    .addStyleEntry('css/select2', './assets/styles/select2.css')
    .copyFiles({
        from: './assets/image',
        includeSubdirectories: true,
        to: 'image/[folder]/[name].[ext]',
        pattern: /.*/
    })
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
    .enableSassLoader()
    .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
