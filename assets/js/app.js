/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import $ from 'jquery';
import 'bootstrap';
import '@fortawesome/fontawesome-free/css/all.min.css';
import '@fortawesome/fontawesome-free/js/all.js';
import '../css/app.scss';

/**
 * Writes some text inside an element with a typewriting effect.
 * @param element: The element in which to write.
 * @param text: The text to write.
 * @param index: The current index in the text to write.
 */
window.typewrite = (element, text, index) => {
    let currentText = element.text();

    if (currentText.length < text.length) {
        currentText += text[index];
        element.text(currentText);
        setTimeout(() => {
            typewrite(element, text, index + 1)
        }, 100);
    }
}

/**
 * Updates the content of the article form content.
 */
window.updateArticleAreaContent = () => {
    let $postContent = $("#post-content");
    let $postArea = $("#article_form_content");

    $postArea.val($postContent.html());
};

/**
 * Changes the style within the article editor.
 */
window.changeStyle = (style, argument) => {
    let $postContent = $("#post-content");

    $postContent.focus();
    document.execCommand(style, false, argument);
    $postContent.focus();
};

/*
 * Preloaded variables.
 */
let $window = $(window);
let $typedElements = $(".element-typed");

$(document).ready(function () {
    let $navigationBar = $(".navbar");

    /*
     * Alerts Removal.
     */

    setTimeout(function () {
        $(".alert").fadeOut("slow");
    }, 5000);

    /*
     * Scroll Handling.
     */

    $window.scroll(() => {
        let $animatedElements = $("[class*='element-fade-in-']:not(.element-animated)");
        let scrollTop = $window.scrollTop();

        /*
         * Navigation Bar Handling.
         */

        if (scrollTop === 0) {
            $navigationBar.css("opacity", 1);
        } else {
            $navigationBar.css("opacity", 0);
        }

        /*
         * Starting typewriting animations.
         */

        $typedElements.map((index) => {
            let $element = $($typedElements[index]);
            let typedElementText = $element.text().replace(/\s+/g, " ");

            if (!$element.hasClass("element-animated") && $element.offset().top < $window.height() + scrollTop) {
                $element.text("");
                typewrite($element, typedElementText, 0);
                $element.addClass("element-animated");
            }
        });

        /*
         * Animations Handling.
         */

        $animatedElements.map((index) => {
                let $element = $($animatedElements[index]);

                if ($element.offset().top < $window.height() + scrollTop) {
                    $element.addClass("element-animated");
                }
            }
        );
    });
    $window.trigger("scroll");
})
;
