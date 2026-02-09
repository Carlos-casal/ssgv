// webpack.config.js
const Encore = require('@symfony/webpack-encore');
const path = require('path');
const tinymcePath = path.dirname(require.resolve('tinymce/package.json'));

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

    // limpia el directorio 'build' antes de cada compilación
    .cleanupOutputBeforeBuild()

    // habilita notificaciones de compilación
    .enableBuildNotifications()

    // Habilita un único runtime chunk (¡NECESARIO AHORA!)
    .enableSingleRuntimeChunk()

    // Habilita el versionado de assets en producción para romper la caché
    .enableVersioning(Encore.isProduction())

    // PostCSS es necesario para Tailwind CSS
    .enablePostCssLoader()

    // Si estás usando Stimulus y el paquete StimulusBridge
    .enableStimulusBridge('./assets/controllers.json')

    // Copia los assets de TinyMCE
    .copyFiles([
        {
            from: path.join(tinymcePath, 'skins'),
            to: 'tinymce/skins/[path][name].[ext]',
            context: path.join(tinymcePath, 'skins')
        },
        {
            from: path.join(tinymcePath, 'plugins'),
            to: 'tinymce/plugins/[path][name].[ext]',
            context: path.join(tinymcePath, 'plugins')
        },
        {
            from: path.join(tinymcePath, 'themes'),
            to: 'tinymce/themes/[path][name].[ext]',
            context: path.join(tinymcePath, 'themes')
        },
        {
            from: path.join(tinymcePath, 'icons'),
            to: 'tinymce/icons/[path][name].[ext]',
            context: path.join(tinymcePath, 'icons')
        },
        {
            from: path.join(tinymcePath, 'models'),
            to: 'tinymce/models/[path][name].[ext]',
            context: path.join(tinymcePath, 'models')
        }
    ])
    .copyFiles({
        from: './assets/images',
        to: 'images/[path][name].[ext]'
    })
;

module.exports = Encore.getWebpackConfig();