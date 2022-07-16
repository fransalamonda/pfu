var login = function(){
    
    var login_init = function(){
        controller = "Home";
        msg_obj = $("#msg");
        //$("button#_log").prop('disabled', true);
        $("button#btn-reg1").click(function(e){
            e.preventDefault();
            var name_id    = $("#name_id").val();
            var userid    = $("#userid").val();
            var email_id    = $("#email_id").val();
            if(name_id === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"](" User Name  * ");
                document.getElementById("name_id").focus();
                return false;
               }
               if(userid === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"](" Employee ID  * ");
                document.getElementById("userid").focus();
                return false;
               }
               if(email_id === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("Email  * ");
                document.getElementById("email_id").focus();
                return false;
               }
               ajax_url = controller+"/register_auth"; 
               ajax_data="name_id="+name_id+"&userid="+userid+"&email_id="+email_id;
               ajax_data+="&"+csrf_name+"="+$("#csrf").val();
                jQuery.ajax({
                    type: "POST", url: base_url+ajax_url, dataType:"text", data:ajax_data,
                    success:function(response){
                        var obj = jQuery.parseJSON(response);
                        if(obj.status === 1){
                            //$("#vrcode").val(obj.str_code);
                            $(".vericode").show();
                        }
                        else if(obj.status === 0){
                            loading.hide();
                            Swal.fire(
                                'Error!',
                                obj.msg,
                                'error'
                            );
                        }
                        loading.hide();
                    },
                    error:function (xhr, ajaxOptions, thrownError){
                        loading.hide(); 
                        alert(thrownError);
                    }
                
                });
            

        });
        $("button#btn-trouble").click(function(e){
            e.preventDefault();
            var name_id    = $("#name_id").val();
            var userid    = $("#userid").val();
            var email_id    = $("#email_id").val();
            if(name_id === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"](" User Name  * ");
                document.getElementById("name_id").focus();
                return false;
               }
               if(userid === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"](" Employee ID  * ");
                document.getElementById("userid").focus();
                return false;
               }
               if(email_id === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("Email  * ");
                document.getElementById("email_id").focus();
                return false;
               }
               ajax_url = controller+"/register_auth"; 
               ajax_data="name_id="+name_id+"&userid="+userid+"&email_id="+email_id;
               ajax_data+="&"+csrf_name+"="+$("#csrf").val();
               jQuery.ajax({
                type: "POST", url: base_url+ajax_url, dataType:"text", data:ajax_data,
                success:function(response){
                    var obj = jQuery.parseJSON(response);
                    if(obj.status === 1){
                        //$("#vrcode").val(obj.str_code);
                        $(".vericode").show();
                    }
                    else if(obj.status === 0){
                        loading.hide();
                        Swal.fire(
                            'Error!',
                            obj.msg,
                            'error'
                          );
                    }
                    loading.hide();
                },
                error:function (xhr, ajaxOptions, thrownError){
                    loading.hide(); 
                    alert(thrownError);
                }
            });
        });
        $(".vrcode").change(function(e){
            e.preventDefault();
            var vrcode = $(".vrcode").val();
            var len = vrcode.length;
            if(len<='5'){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("Please provide the 6-digit code");
                document.getElementById("vrcode").focus();
                return false;
            }
                ajax_url = controller+"/auth_cek"; 
               ajax_data="vrcode="+vrcode;
               ajax_data+="&"+csrf_name+"="+$("#csrf").val();
               jQuery.ajax({
                type: "POST", url: base_url+ajax_url, dataType:"text", data:ajax_data,
                success:function(response){
                    var obj = jQuery.parseJSON(response);
                    if(obj.status === 1){
                        $(".btn-ver").hide();
                        $(".btn-login").show();
                    }
                    else if(obj.status === 0){
                        loading.hide();
                        Swal.fire(
                            'Error!',
                            obj.msg,
                            'error'
                        );
                    }
                    loading.hide();
                },
                error:function (xhr, ajaxOptions, thrownError){
                    loading.hide(); 
                    alert(thrownError);
                }
            
            });
        });
        var forml = $("#frm_registrasi");
        forml.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error text-left', // default input error message class
            rules:{
                name_id:{required:true},
                userid:{required:true},
                email_id:{required:true},
                vrcode:{required:true},
            }
            ,
            highlight: function (element) { // hightlight error inputs
                $(element)
                .closest('.input_wrapper').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // hightlight error inputs
                $(element)
                .closest('.has-error').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                var url = controller+"/register_pwu";
                var data = forml.serialize();
                loading.show();
                msg_obj.hide();
                jQuery.ajax({
                    type: "POST", // HTTP method POST or GET
                    url: base_url+url, //Where to make Ajax calls
                    dataType:"text", // Data type, HTML, json etc.
                    data:data, //Form variables
                    headers: {'X-Requested-With': 'XMLHttpRequest'},
                    success:function(response){
                        var obj = null;
                        try{
                            obj = $.parseJSON(response);  
                        }catch(e)
                        {}
                        //var obj = jQuery.parseJSON(response);

                        if(obj)//if json data
                        {
                            //success msg
                            if(obj.status === 1){
                                //sweetAlert("Well Done", obj.msg, "success");
                                loading.hide();
                                toastr.options = {
                                    "closeButton": true,
                                    "debug": false,
                                    "newestOnTop": false,
                                    "progressBar": true,
                                    "positionClass": "toast-bottom-right",
                                    "preventDuplicates": false,
                                    "onclick": null,
                                    "showDuration": "300",
                                    "hideDuration": "1000",
                                    "timeOut": "5000",
                                    "extendedTimeOut": "1000",
                                    "showEasing": "swing",
                                    "hideEasing": "linear",
                                    "showMethod": "fadeIn",
                                    "hideMethod": "fadeOut"
                                  };
                                  toastr["success"](obj.msg);
                                window.location.href = base_url+"Home";
                            }
                            //error msg
                            else if(obj.status === 0){
                                sweetAlert("Caution", obj.msg, "error");;
                                //$("#csrf").val(obj.csrf_hash);
                                //$("#captcha_wrapper").html(obj.captcha_img);
                                //$("#captcha").val('');
                            }
                            loading.hide();
                        }
                        else
                        {
                            show_alert_ms(msg_obj,99,response);loading.hide();
                        }
                    },
                    error:function (xhr, ajaxOptions, thrownError){
                        loading.hide(); 
                        alert(thrownError);
                    }
                });
                return false;
            }
        });
        
        $(".userid").change(function(e){
            e.preventDefault();
            var userid = $(".userid").val();
            var pass = $(".pass").val();
            if(userid !='' && pass!=''){
                $("button#_log").removeClass("btn-login-non");
                $("button#_log").addClass("btn-login-akt");
                $("button#_log").prop('disabled', false);
            }
        }); 

        $(".pass").change(function(e){
            e.preventDefault();
            var userid = $(".userid").val();
            var pass = $(".pass").val();
            if(userid !='' && pass!=''){
                $("button#_log").removeClass("btn-login-non");
                $("button#_log").addClass("btn-login-akt");
                $("button#_log").prop('disabled', false);
            }
        }); 

         $("#btn-trb").click(function(e){
             e.preventDefault();
             alert();
        //     var url = controller+"/refresh_captcha";
        //         var data = forml.serialize();
        //         loading.show();
        //         jQuery.ajax({
        //             type: "POST", // HTTP method POST or GET
        //             url: base_url+url, //Where to make Ajax calls
        //             dataType:"text", // Data type, HTML, json etc.
        //             data:data, //Form variables
        //             success:function(response){
        //                 var obj = null;
        //                 try{
        //                     obj = $.parseJSON(response);  
        //                 }catch(e)
        //                 {}
        //                 //var obj = jQuery.parseJSON(response);

        //                 if(obj)//if json data
        //                 {
        //                     //success msg
        //                     if(obj.status === 1){
        //                         $("#csrf").val(obj.csrf_hash);
        //                         $("#captcha_wrapper").html(obj.captcha_img);
        //                         $("#captcha").val('');
        //                     }

        //                     //error msg
        //                     else if(obj.status === 0){
        //                         sweetAlert("Caution", obj.msg, "error");;
        //                         $("#csrf").val(obj.csrf_hash);
        //                         $("#captcha_wrapper").html(obj.captcha_img);
        //                         $("#captcha").val('');
        //                     }
        //                     loading.hide();
        //                 }
        //                 else
        //                 {
        //                     show_alert_ms(msg_obj,99,response);loading.hide();
        //                 }
        //             },
        //             error:function (xhr, ajaxOptions, thrownError){
        //                 loading.hide(); 
        //                 alert(thrownError);
        //             }
        //         });
         });
    };
    return{
        init:function(){login_init();}
    };
}();