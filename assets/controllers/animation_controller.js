import {Controller} from 'stimulus';
import $ from 'jquery';

export default class extends Controller {
    $window = $(window);


    connect() {
        this.update()
    }

    update() {
        if (!this.element.classList.contains('nf-animated') &&
            $(this.element).offset().top < this.$window.height() + this.$window.scrollTop()) {
            this.element.classList.add('nf-animated');
        }
    }
}
