(function ($) {
    'use strict';

    $('.checkbo').checkBo();
    var $validator = $('#wizardForm').validate({
        rules: {
            //'Inquiry[name]': {required: true}
        }
    });

    function checkValidation() {
        var $valid = $('#wizardForm').valid();
        if (!$valid) {
            $validator.focusInvalid();
            return false;
        }
    }

    $('#rootwizard').bootstrapWizard({
        tabClass: '',
        'nextSelector': '.button-next',
        'previousSelector': '.button-previous',
        onNext: checkValidation,
        onLast: checkValidation,
        onTabClick: checkValidation
    });
})(jQuery);