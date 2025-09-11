import { startStimulusApp } from '@symfony/stimulus-bridge';
import ServiceFormController from './controllers/service_form_controller.js';
import NewServiceFormController from './controllers/new_service_form_controller.js';

const app = startStimulusApp();
// register any custom, 3rd party controllers here
app.register('service-form', ServiceFormController);
app.register('new-service-form', NewServiceFormController);
