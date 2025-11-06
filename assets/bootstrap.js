// Force asset refresh
import { startStimulusApp } from '@symfony/stimulus-bridge';
import ModalFormController from './controllers/modal_form_controller.js';
import ResourceFormController from './controllers/resource_form_controller.js';
import TabsController from './controllers/tabs_controller.js';

const app = startStimulusApp();

app.register('modal-form', ModalFormController);
app.register('resource-form', ResourceFormController);
app.register('tabs', TabsController);
