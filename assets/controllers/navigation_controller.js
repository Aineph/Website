import {Controller} from 'stimulus';

export default class extends Controller {
    connect() {
        this.element.style.opacity = "1";
    }

    update() {
        if (window.scrollY === 0) {
            this.element.style.opacity = "1";
        } else {
            this.element.style.opacity = null;
        }
    }
}
