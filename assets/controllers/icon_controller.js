import { Controller } from '@hotwired/stimulus';

/*
 * This controller is responsible for rendering Lucide icons.
 * It should be attached to the <body> element.
 */
import { createIcons } from 'lucide';

export default class extends Controller {
    connect() {
        createIcons();
    }
}
