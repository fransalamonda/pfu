!function(t){"use strict";
var e=function(){
    this.$commentForm=t("#commentForm"),
    this.$signupForm=t("#signupForm")
};
e.prototype.init=function(){
    t.validator.setDefaults({
        submitHandler:function(){
            alert("submitted!")}
        }),
        this.$commentForm.validate(),
        this.$signupForm.validate({
            rules:{
                firstname:"required",
                lastname:"required",
                username:{required:!0,minlength:2},
                password:{required:!0,minlength:5},
                confirm_password:{
                    required:!0,
                    minlength:5,
                    equalTo:"#password"},
                    email:{required:!0,email:!0},
                    topic:{required:"#newsletter:checked",minlength:2},
                    agree:"required"},
                    messages:{
                        firstname:"Please enter your firstname",
                        lastname:"Please enter your lastname",
                        username:{
                            required:"Please enter a username",
                            minlength:"Your username must consist of at least 2 characters"
                        },
                        password:{required:"Please provide a password",minlength:"Your password must be at least 5 characters long"},confirm_password:{required:"Please provide a password",minlength:"Your password must be at least 5 characters long",equalTo:"Please enter the same password as above"},email:"Please enter a valid email address",agree:"Please accept our policy"}}),t("#username").focus(function(){var e=t("#firstname").val(),a=t("#lastname").val();e&&a&&!this.value&&(this.value=e+"."+a)});var e=t("#newsletter"),a=e.is(":checked"),r=t("#newsletter_topics")[a?"removeClass":"addClass"]("gray"),s=r.find("input").attr("disabled",!a);e.click(function(){r[this.checked?"removeClass":"addClass"]("gray"),s.attr("disabled",!this.checked)})},t.FormValidator=new e,t.FormValidator.Constructor=e}(window.jQuery),function(e){"use strict";window.jQuery.FormValidator.init()}();