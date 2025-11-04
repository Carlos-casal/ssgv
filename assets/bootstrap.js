import { startStimulusApp } from '@symfony/stimulus-bundle';
import { eager } from '@symfony/stimulus-bundle/webpack/helpers';

const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.(j|t)sx?$/
));

// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);
