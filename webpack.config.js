// webpack.config.js
const Encore = require('@symfony/webpack-encore');
const CopyWebpackPlugin = require('copy-webpack-plugin');

if (!Encore.isProduction()) {
    Encore.enableSourceMaps();
}

Encore
    // el directorio donde Webpack publicará los assets compilados
    .setOutputPath('public/build/')
    // la ruta pública para los assets web después de ser desplegados
    .setPublicPath('/build')

    // añade un "entrypoint" de JavaScript
    .addEntry('app', './assets/app.js') // Este es tu punto de entrada principal para JS y CSS
    .addEntry('tinymce', './node_modules/tinymce/tinymce.min.js')

    // limpia el directorio 'build' antes de cada compilación
    .cleanupOutputBeforeBuild()

    // habilita notificaciones de compilación
    .enableBuildNotifications()

    // Habilita un único runtime chunk (¡NECESARIO AHORA!)
    .enableSingleRuntimeChunk()

    // PostCSS es necesario para Tailwind CSS
    .enablePostCssLoader()

    // Si estás usando Stimulus y el paquete StimulusBridge
    .enableStimulusBridge('./assets/controllers.json')

    .addPlugin(new CopyWebpackPlugin({
        patterns: [
            { from: 'node_modules/tinymce/skins', to: 'tinymce/skins' },
            { from: 'node_modules/tinymce/themes', to: 'tinymce/themes' },
            { from: 'node_modules/tinymce/icons', to: 'tinymce/icons' },
            { from: 'node_modules/tinymce/plugins', to: 'tinymce/plugins' }
        ]
    }))
;

module.exports = Encore.getWebpackConfig();