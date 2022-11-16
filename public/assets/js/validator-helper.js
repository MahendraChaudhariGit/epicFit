
$.validator.setDefaults({
    errorElement : "span", // contain the error msg in a small tag
    errorClass : 'help-block',
    errorPlacement : function(error, element) {// render error placement for each input type
        if (element.attr("type") == "radio" || element.attr("type") == "checkbox") {// for chosen elements, need to insert the error after the chosen container
            error.insertAfter($(element).closest('.form-group').children('div').children().last());
        } else if (element.attr("name") == "card_expiry_mm" || element.attr("name") == "card_expiry_yyyy") {
            error.appendTo($(element).closest('.form-group').children('div'));
        } else {
            error.insertAfter(element);
            // for other inputs, just perform default behavior
        }
    },
    ignore : ':hidden',
    success : function(label, element) {
        label.addClass('help-block valid');
        // mark the current input as valid and display OK icon
        $(element).closest('.form-group').removeClass('has-error');
    },
    highlight : function(element) {
        $(element).closest('.help-block').removeClass('valid');
        // display OK icon
        $(element).closest('.form-group').addClass('has-error');
        // add the Bootstrap error class to the control group
    },
    unhighlight : function(element) {// revert the change done by hightlight
        $(element).closest('.form-group').removeClass('has-error');
        // set error class to the control group
    }
});