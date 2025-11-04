// Force asset refresh
import { startStimulusApp } from '@symfony/stimulus-bundle';
import ModalFormController from './controllers/modal_form_controller.js';
import ConditionalResourceFormController from './controllers/conditional_resource_form_controller.js';

const app = startStimulusApp();

app.register('modal-form', ModalFormController);
app.register('conditional-resource-form', ConditionalResourceFormController);
