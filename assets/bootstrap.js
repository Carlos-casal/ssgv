// Force asset refresh
import { startStimulusApp } from '@symfony/stimulus-bridge';
import ModalController from './controllers/modal_controller.js';
import ModalFormController from './controllers/modal_form_controller.js';
import ResourceFormController from './controllers/resource_form_controller.js';
import ServiceFormController from './controllers/service_form_controller.js';
import TabsController from './controllers/tabs_controller.js';
import HelloModalController from './controllers/hello_modal_controller.js';
import PasswordVisibilityController from './controllers/password_visibility_controller.js';
import ForgotPasswordController from './controllers/forgot_password_controller.js';
import PasswordValidationController from './controllers/password_validation_controller.js';
import BulkUnitController from './controllers/bulk_unit_controller.js';
import UniformityGridController from './controllers/uniformity_grid_controller.js';

const app = startStimulusApp();

app.register('hello-modal', HelloModalController);
app.register('modal', ModalController);
app.register('modal-form', ModalFormController);
app.register('resource-form', ResourceFormController);
app.register('service-form', ServiceFormController);
app.register('tabs', TabsController);
app.register('password-visibility', PasswordVisibilityController);
app.register('forgot-password', ForgotPasswordController);
app.register('password-validation', PasswordValidationController);
app.register('bulk-unit', BulkUnitController);
app.register('uniformity-grid', UniformityGridController);
