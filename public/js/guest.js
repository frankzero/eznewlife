$.validator.setDefaults({
    highlight: function(element) {
        $(element).closest('.form-group').addClass('has-error');
    },
    unhighlight: function(element) {
        $(element).closest('.form-group').removeClass('has-error');
    },
    errorElement: 'span',
    errorClass: 'help-block',
    errorPlacement: function(error, element) {
        if(element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        } else {
            error.insertAfter(element);
        }
    }
});
jQuery.validator.addMethod("special", function(value, element) {
    // allow any non-whitespace characters as the host part
    return this.optional( element ) || /^[a-zA-Z0-9-_.]+$/.test( value );
}, "帳號只允許英數字或(-)(.)(_)特殊字元");

var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

$("#forget_password_form").validate({});
$("#login_form").validate({
    rules: {
        name: {
            special:true,
            required: true
        }

    }
});
