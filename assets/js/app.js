/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import $ from 'jquery';
import 'bootstrap';
import '../css/app.scss';

/*
 * TODO: Remove Logs.
 */

let $window = $(window);

/*
 * TODO: Add Bootstrap Loader while $window is loading.
 */

$(document).ready(function () {
    let $navigationBar = $(".navbar");

    $window.scroll(() => {
        let $animatedElements = $("[class*='element-fade-in-']:not(.element-animated)");
        let scrollTop = $window.scrollTop();

        if (scrollTop === 0) {
            $navigationBar.css("opacity", 1);
        } else {
            $navigationBar.css("opacity", 0);
        }
        $animatedElements.map((index) => {
                let $element = $($animatedElements[index]);

                if ($element.offset().top < $window.height() + scrollTop) {
                    $element.addClass("element-animated");
                }
            }
        )
        ;
    });
    $window.trigger("scroll");
});
