// Force asset refresh
import { startStimulusApp } from '@symfony/stimulus-bundle';
import ModalFormController from './controllers/modal_form_controller.js';
import ResourceFormController from './controllers/resource_form_controller.js';

const app = startStimulusApp();

app.register('modal-form', ModalFormController);
app.register('resource-form', ResourceFormController);
