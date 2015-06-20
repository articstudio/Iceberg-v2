$(document).ready(function(){
    $('#language-select').change(function(){
        $('#form-language').submit();
    });
    $('#form-install').validate({
        rules: {
            username: {
                minlength: 3,
                maxlength: 20,
                required: true
            },
            password: {
                minlength: 3,
                maxlength: 20,
                required: true
            },
            email: {
                email: true,
                required: true
            }
        },
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {}
    });
});