/*!
 * Star Rating <LANG> Translations
 *
 * This file must be loaded after 'star-rating.js'. Patterns in braces '{}', or
 * any HTML markup tags in the messages must not be converted or translated.
 *
 * @see http://github.com/kartik-v/bootstrap-star-rating
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 */
(function ($) {
    "use strict";
    $.fn.ratingLocales['tw'] = {
        defaultCaption: '{rating} Stars',
        starCaptions: {
            0.5: 'Half Star',
            1: 'so so',
            1.5: 'One & Half Star',
            2: '還好',
            2.5: 'Two & Half Stars',
            3: '還行',
            3.5: 'Three & Half Stars',
            4: '還不錯',
            4.5: 'Four & Half Stars',
            5: '太棒了'
        },
        clearButtonTitle: 'Clear',
        clearCaption: ''
    };
})(window.jQuery);
