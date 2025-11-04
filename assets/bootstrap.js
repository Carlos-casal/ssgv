import { startStimulusApp } from '@symfony/stimulus-bundle';
import ModalFormController from './controllers/modal_form_controller.js';
import ModalController from './controllers/modal_controller.js';

const app = startStimulusApp();

app.register('modal-form', ModalFormController);
app.register('modal', ModalController);
