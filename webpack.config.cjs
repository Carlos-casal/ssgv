// webpack.config.js
const Encore = require('@symfony/webpack-encore');

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
    .addEntry('asistencia', './assets/js/asistencia.js')

    // limpia el directorio 'build' antes de cada compilación
    .cleanupOutputBeforeBuild()

    // habilita notificaciones de compilación
    .enableBuildNotifications()

    // Habilita un único runtime chunk (¡NECESARIO AHORA!)
    .enableSingleRuntimeChunk()

    // Enable React preset
    .enableReactPreset()

    .enableTypeScriptLoader((options) => {
        options.transpileOnly = true;
        options.configFile = 'tsconfig.app.json';
    })

    // PostCSS es necesario para Tailwind CSS
    .enablePostCssLoader()

    // Si estás usando Stimulus y el paquete StimulusBridge
    .enableStimulusBridge('./assets/controllers.json')
;

module.exports = Encore.getWebpackConfig();