(function ($) {
"use strict";

    var ProductBlock = function ($scope, $) {

        $scope.find('.sf-products-block').each(function () {

            console.log('Product Block widget loaded');
            // Js End
        });

    };


    $(window).on('elementor/frontend/init', function () {

        if (elementorFrontend.isEditMode()) {

            elementorFrontend.hooks.addAction('frontend/element_ready/sf-product-block.default', ProductBlock);

        }
        else {

            elementorFrontend.hooks.addAction('frontend/element_ready/sf-product-block.default', ProductBlock);

        }
    });

console.log('UnikForce Js loaded')
})(jQuery);
