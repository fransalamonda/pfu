var main = function(){
    controller = "/Wbi";
    
    var datatable = function(){    
        function ms_plant(){
            loading.show();
            ajax_url = controller+"/ms_plant";
            ajax_data="id='salamonda'";
            ajax_data+="&"+csrf_name+"="+$("#csrf").val();
            jQuery.ajax({
                type: "POST",
                url: base_url+ajax_url,
                dataType:"text",
                data:ajax_data,
                success:function(response){
                    var obj = null;
                    try
                    {
                        obj = $.parseJSON(response);  
                    }catch(e)
                    {}

                    if(obj)
                    {
                        $("#csrf").val(obj.csrf_hash);
                        if(obj.status === 1){

                            $("#idplant").html(obj.str_plant);
                            //loading.hide();
                            
                        }
                        else if(obj.status === 0){
                            loading.hide();
                            sweetAlert("Error", obj.msg, "error");
                        }
                        else if(obj.status === 2){
                            sweetAlert("Caution", obj.msg, "warning");
                            // window.setTimeout(function(){
                            //     window.location.href = base_url+"/home";
                            // }, 2000);
                        }
                        list_data();
                    }
                    else{
                        sweetAlert("Caution", response, "error");
                        loading.hide();
                        // window.setTimeout(function(){
                        //     window.location.href = base_url+"/home";
                        // }, 2000);
                        // return false;
                    }
                },
                error:function (xhr, ajaxOptions, thrownError){
                    loading.hide(); 
                    alert(thrownError);
                    return false;
                }
            });
        }ms_plant();
        /*
            tabel waiting for assgn
        */
        function list_data(){
            var idplant = $("#idplant").val();
            loading.show();
            ajax_url = controller+"/list_data";
            ajax_data="id="+idplant;
            ajax_data+="&"+csrf_name+"="+$("#csrf").val();
            jQuery.ajax({
                type: "POST",
                url: base_url+ajax_url,
                dataType:"text",
                data:ajax_data,
                success:function(response){
                    var obj = null;
                    try
                    {
                        obj = $.parseJSON(response);  
                    }catch(e)
                    {}

                    if(obj)
                    {
                        $("#csrf").val(obj.csrf_hash);
                        if(obj.status === 1){
                            $("#t_waiting > tbody").html(obj.wfa);
                            $("#t_in_progress > tbody").html(obj.ips);
                            $("#t_need_riview > tbody").html(obj.nrw);

                            $("#idplant").html(obj.str_plant);

                            $("button#nots").html(obj.btn_wfa);
                            $("button#prog").html(obj.btn_inp);
                            $("button#comp").html(obj.btn_nrw);
                            loading.hide();
                        }
                        else if(obj.status === 0){
                            loading.hide();
                            sweetAlert("Error", obj.msg, "error");
                        }
                        else if(obj.status === 2){
                            sweetAlert("Caution", obj.msg, "warning");
                            // window.setTimeout(function(){
                            //     window.location.href = base_url+"/home";
                            // }, 2000);
                        }

                    }
                    else{
                        sweetAlert("Caution", response, "error");
                        loading.hide();
                        // window.setTimeout(function(){
                        //     window.location.href = base_url+"/home";
                        // }, 2000);
                        // return false;
                    }
                },
                error:function (xhr, ajaxOptions, thrownError){
                    loading.hide(); 
                    alert(thrownError);
                    return false;
                }
            });
        };
        $("#idplant").change(function(e){
                var idplant = $("#idplant").val();
                list_data(); 
        });

        var table   = $("#t_bahan");
        var table_d  = $("#t_detail");
        var table_co = $("#t_completed");

        var msg         = $("#msg");
        /*
            actin form
            tabel pending action
            *Target Completion Date & time
        */
        
        //$('.inp_sd').datepicker({ 
            //startDate: new Date(),
            //minDate: 0,
            //  format: 'dd MM yyyy ',
            //  //showDropdowns: false,
            //  autoclose: true,
             //autoApply:true,

        //});
        
        $("#sd_time").timepicker({
            defaultTIme:!1,
            icons:{
                up:"mdi mdi-chevron-up",down:"mdi mdi-chevron-down"
            },
            autoclose:true,
        });

        //*Request Date & time
        $('#req_dt').datepicker({ 
            format: 'dd MM yyyy ',
            autoclose:true,
        });
        $("#req_tm").timepicker({
            defaultTIme:!1,
            icons:{
                up:"mdi mdi-chevron-up",down:"mdi mdi-chevron-down"
            },
            autoclose:true,
        });
        // End Date & end time
        $('.inp_ed').datepicker({ 
            format: 'dd-mm-yyyy',
            autoclose:true,
        });
        $("#en_time").timepicker({
            defaultTIme:!1,
            icons:{
                up:"mdi mdi-chevron-up",down:"mdi mdi-chevron-down"
            },
            autoclose:true,
        });

        // Spare Part Arrived (ETA) & end time
        $('#spa').datepicker({ 
            //dateFormat: 'dd-mm-yy' 
            format: 'dd MM yyyy ',
            autoclose:true,
        });
       
        

        window.history.pushState(null, "", window.location.href);        
        window.onpopstate = function() {
            window.history.pushState(null, "", window.location.href);
            // list_action();
            // $("._wrapper_info").show();
            //                 $("._wrapper_sindi").hide();
        };
        
        $(".nots").click(function(e){
            e.preventDefault();
            loading.show();
            $("div#_nots").removeClass("tombol-tidakaktif");
            $("div#_nots").addClass("tombol-aktif");
            $("div#_prog").removeClass("tombol-aktif");
            $("div#_prog").addClass("tombol-tidakaktif");
            $("div#_comp").removeClass("tombol-aktif");
            $("div#_comp").addClass("tombol-tidakaktif");

            // $("button#nots").addClass("b-tombol-aktif");
            // $("button#prog").removeClass("b-tombol-aktif");
            // $("button#comp").removeClass("b-tombol-aktif");
            $("._not_started").show();
            $("._t_in_progress").hide();
            $("._t_completed").hide();  
            $("._t_casehistory").hide();
            loading.hide();
        });
         $(".prog").click(function(e){
            e.preventDefault();
            loading.show();
            $("div#_nots").removeClass("tombol-aktif");
            $("div#_nots").addClass("tombol-tidakaktif");
            $("div#_prog").removeClass("tombol-tidakaktif");
            $("div#_prog").addClass("tombol-aktif");
            $("div#_comp").removeClass("tombol-aktif");
            $("div#_comp").addClass("tombol-tidakaktif");

            

            $("._not_started").hide();
            $("._t_in_progress").show();
            $("._t_completed").hide();  
            $("._t_casehistory").hide();
            loading.hide();
         });
     
        $(".comp").click(function(e){
            e.preventDefault();
            loading.show();
            $("div#_nots").removeClass("tombol-aktif");
            $("div#_nots").addClass("tombol-tidakaktif");
            $("button#nots").removeClass("b-tombol-aktif");
            $("button#nots").addClass("b-tombol-tidak-aktif");


            $("div#_prog").removeClass("tombol-aktif");
            $("div#_prog").addClass("tombol-tidakaktif");
            $("button#prog").removeClass("b-tombol-aktif");
            $("button#prog").addClass("b-tombol-tidak-aktif");

            $("div#_comp").removeClass("tombol-tidakaktif");
            $("div#_comp").addClass("tombol-aktif");
            $("button#comp").removeClass("b-tombol-aktif");
            $("button#comp").addClass("b-tombol-aktif");
            

            $("._not_started").hide();
            $("._t_in_progress").hide();
            $("._t_completed").show();

            $("._t_casehistory").hide();
            loading.hide();
        });

        $(".back_r").click(function(e){
            e.preventDefault();
            loading.show();
            $("div#_nots").removeClass("tombol-tidakaktif");
            $("div#_nots").addClass("tombol-aktif");
            $("div#_prog").removeClass("tombol-aktif");
            $("div#_prog").addClass("tombol-tidakaktif");
            $("div#_comp").removeClass("tombol-aktif");
            $("div#_comp").addClass("tombol-tidakaktif");
            $("._not_started").show();
            $("._t_in_progress").hide();
            $("._t_completed").hide();  
            $("._t_casehistory").hide();
            $("._t_report").show();
            $("._t_casehistory").hide();
            loading.hide();
        });
        /*
          *table Unassigned
          *Work Status 
          * Spare Part Arrived (ETA) 
          * button Submit
        */
        var table_wfa           = $("#t_waiting");      //tabel Unassigned 
        table_wfa.on('click', 'button.btn-next-1',function(e){
            e.preventDefault();
            loading.show();
            $("div#_nots").removeClass("tombol-aktif");
            $("div#_nots").addClass("tombol-tidakaktif");
            $("div#_prog").removeClass("tombol-tidakaktif");
            $("div#_prog").addClass("tombol-aktif");
            $("div#_comp").removeClass("tombol-aktif");
            $("div#_comp").addClass("tombol-tidakaktif");
            $("._not_started").hide();
            $("._t_in_progress").show();
            $("._t_completed").hide();  
            loading.hide();
        });
        table_wfa.on('click', 'a.btnRes',function(e){
            e.preventDefault();
            var _self       = $(this);
            var ind        = _self.data("ind");
            var id        = _self.data("id");
            var con        = _self.data("con");
            var tbl        = _self.data("tbl");
            var tg        = _self.data("tg");
            var ec        = _self.data("ec");
            var cp        = _self.data("cp");
            var cd        = _self.data("cd");
            var cn        = _self.data("cn");
            var pl      = _self.data("pl");
            var pl_d    = _self.data("pl_d");

            ajax_url = controller+"/view_action";
            ajax_data="id="+_self.data("id")+"&con="+con;
            ajax_data+="&"+csrf_name+"="+$("#csrf").val();
            ajax_data+="&tbl="+tbl;
            ajax_data+="&pl_d="+pl_d;
            loading.show();
            jQuery.ajax({
                type: "POST",
                url: base_url+ajax_url,
                dataType:"text", 
                data:ajax_data, 
                success:function(response){
                    var obj = null;
                    try {
                        obj = $.parseJSON(response);  
                    }
                    catch(e){}
                    if(obj){
                        $("#csrf").val(obj.csrf_hash);
                        if(obj.status === 1){
                            $("#icdepp").val("");
                            $("#1addm").val("");
                            // $("#2addm").val("");
                            // $("#3addm").val("");
                            // $("#4addm").val("");
                            // $("#5addm").val("");
                            $("textarea#detpro").val("");
                            $("textarea#maint").val("");             
                            $("#inp_sd").val("");
                            $("textarea#area_select").val("");
                            $("#d_sel").val("");

                            $("#cdn").val(con);
                            $("#ind").val(ind);
                            $("#idw").val(id);
                            $("#ckt").val(cp);
                            $("#ckt_d").val(cd);
                            $("#plant").val(pl);
                            $("#plant_d").val(pl_d);
                            $("#note_c").val(cn);
                            //kiri satu
                            $("#kon").html(obj.conditions);
                            $("li#tgl").html(tg);
                            $("li#ec").html(ec);
                            $("li#cp").html(cp);
                            $("li#cd").html(cd);
                            $("li#cn").html(cn);

                            //$("div#sh").html(obj.str_log);
                            //kanan satu
                            $("#icdepp").html(obj.str_dept);
                            //kanan dua
                            $("#sttwork").html(obj.str_status);
                            $("#sud").val(obj.today);
                            //$("strong#equipment_code").html(obj.Equipment_Code);
                            //$("button#btnKirim").html(obj.b_button);

                            $("req_dt").val("");
                            $("req_tm").val("");
                            $(".area_select").hide();
                            $(".date_status").hide();
                            $(".date_arrived").hide();
                            $("area_select").val("");

                            $('#modal_add2').modal('show');
                            loading.hide();
                            window.history.pushState(null, "", window.location.href);        
                            window.onpopstate = function() {
                                //list_action();
                                // $("._wrapper_info").hide();
                                // $("._wrapper_sindi").show();    
                                $('#modal_add2').modal('toggle');
                            }
                        }
                        else if(obj.status === 0){
                            loading.hide();
                            Swal.fire(
                                'Error!',
                                obj.msg,
                                'error'
                              );
                        }
                        else if(obj.status === 2){
                            Swal.fire(
                                'Error!',
                                obj.msg,
                                'error'
                              );

                            // window.setTimeout(function(){
                            //     window.location.href =base_url+default_controller;
                            // }, 2000);
                        }
                        else{
                            loading.hide();
                            Swal.fire(
                                'Error!',
                                obj.msg,
                                'error'
                              );
                        }
                    }
                    else
                    {
                        sweetAlert("Error", "An Error Occured", "error");
                        loading.hide();
                        return false;
                    }
                },
                error:function (x, status, error){
                    loading.hide(); 
                    if (x.status == 403) {
                        sweetAlert("Error", "Sorry, your session has expired. Please login again to continue", "warning");
                      //  window.location.href =base_url+default_controller;
                    }
                    else {
                        alert("An error occurred: " + status + "nError: " + error);
                       // window.location.href =base_url+default_controller;
                    }
                }
            });
        });
        //Target Completion Date * 
        $(".inp_sd").datepicker({
            language: 'en',
            format: 'dd MM yyyy ',
            //weekStart: 1,
            startDate: new Date(), // Set min Date
            //endDate: "2020-12-31", // Set max Date
             autoclose: 1,
             //startView: 2,
             //minView: 0,
             //maxView: 4,
             todayBtn: 1,
             todayHighlight: 1,
            // keyboardNavigation: true,
            // forceParse: 0,
            // minuteStep: 1,
            // pickerPosition: 'bottom-right',
            // viewSelect: 0,
             showMeridian: 1,
            // inline: false,
            // sideBySide: true,
            //initialDate: new Date()
        }).on('changeDate', function (ev) {
        });
        // Work Status 
        $("#sttwork").change(function(e){
            var stt_kerja = $("#sttwork").val();
            if(stt_kerja=='1'){
                // $("label#input_detail").html('Spare Part Detail <span class="text-danger"> *</span>');
                // $("label#Date_sparepart").html('Spare Part Arrived (ETA)<span class="text-danger"> *</span>');
                $(".date_status").show();
                $(".area_select").hide();
                $(".date_select").hide();
                $(".date_arrived").hide();
                // $("#spa").val("");
                // $("#d_sel").attr("disabled", true);
                // $("#spa").attr("disabled", true);
            }else if(stt_kerja=='2'){
                $(".date_status").show();
                $(".area_select").show();
                $(".date_select").show();
                $(".date_arrived").show();
                // $("label#input_detail").html('Spare Part Detail <span class="text-danger"> *</span>');
                // $("label#Date_sparepart").html('Spare Part (Estimated Arrival Time)<span class="text-danger"> *</span>');
            }
            else if(stt_kerja=='3'){
                $(".date_status").show();
                $(".area_select").show();
                $(".date_select").show();
                $(".date_arrived").show();
                // $("label#input_detail").html('Spare Part Detail <span class="text-danger"> *</span>');
                // $("label#Date_sparepart").html('Spare Part (Estimated Arrival Time)<span class="text-danger"> *</span>');
            }
            else if(stt_kerja=='4'){
                $(".date_status").show();
                $(".area_select").show();
                $(".date_select").show();
                $(".date_arrived").show();
                // $("label#input_detail").html('Reason For No Need Action <span class="text-danger"> *</span>');
                // $(".date_select").hide();
            }
            else if(stt_kerja=='5'){
                $(".date_status").show();
                $(".area_select").show();
                $(".date_select").show();
                $(".date_arrived").hide();
                // $("label#input_detail").html('Spare Part Detail <span class="text-danger"> *</span>');
                // $("label#Date_sparepart").html('Spare Part (Estimated Arrival Time)<span class="text-danger"> *</span>');
                // $(".date_select").show();
            }
            else if(stt_kerja=='6'){
                $(".date_status").show();
                $(".area_select").show();
                $(".date_select").show();
                $(".date_arrived").show();
                // $("label#input_detail").html('Spare Part Detail <span class="text-danger"> *</span>');
                // $("label#Date_sparepart").html('Spare Part (Estimated Arrival Time)<span class="text-danger"> *</span>');
                // $(".date_select").show();
            }
            else if(stt_kerja==''){
                $(".date_status").hide();
                $(".area_select").hide();
                $(".date_select").hide();
                $(".date_arrived").hide();
                // $("label#input_detail").html('Spare Part Detail <span class="text-danger"> *</span>');
                // $("label#Date_sparepart").html('Spare Part (Estimated Arrival Time)<span class="text-danger"> *</span>');
                // $(".date_select").show();
            }
        });

        //Spare Part Arrived (ETA) 
        $("#d_sel").datepicker({
            language: 'en',
            format: 'dd MM yyyy ',
            //weekStart: 1,
            startDate: new Date(), // Set min Date
            //endDate: "2020-12-31", // Set max Date
             autoclose: 1,
            // startView: 2,
            // minView: 0,
            // maxView: 4,
            // todayBtn: 1,
            // todayHighlight: 1,
            // keyboardNavigation: true,
            // forceParse: 0,
            // minuteStep: 1,
            // pickerPosition: 'bottom-right',
            // viewSelect: 0,
            // showMeridian: 1,
            // inline: false,
            // sideBySide: true,
            //initialDate: new Date()
        }).on('changeDate', function (ev) {
        });
        //button Submit
        var forml2       = $("#form_add2");
        forml2.submit(function(e){
            e.preventDefault();
            var cdn       = $("#cdn").val();   //conditions
            var ind       = $("#ind").val(); //inspection_date
            var idw       = $("#idw").val();      //Equipment Code
            var ckt       = $("#ckt").val();      //Checkpoint
            var ckt_d       = $("#ckt_d").val();  //Checkpoint Detail
            var plant       = $("#plant").val();  //plat
            var plant_d       = $("#plant_d").val();    //plat d

            var icdepp        = $("#icdepp").val(); //Departemen PIC

            var emali1       = $("#1addm").val(); //email
            var emali2       = $("#2addm").val();
            var emali3       = $("#3addm").val();
            var emali4       = $("#4addm").val();
            var emali5       = $("#5addm").val();

            var detpro      = $("#detpro").val(); //Problem Detail
            var maint       = $("#maint").val();    //Required Action 
            var inp_sd      = $("#inp_sd").val(); //Target Completion Date
            var sttwork     = $("#sttwork").val(); //Work Status
            var area_select = $("#area_select").val(); //Spare Part Detail
            var d_sel       = $("#d_sel").val();
            var note_c       = $("#note_c").val(); //ceklist note
            
            if(icdepp === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("Departemen PIC * ");
                document.getElementById("icdepp").focus();
                return false;
               }            
            if(emali1 === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("Input Email * ");
                document.getElementById("icdepp").focus();
                return false;
               }
               if(detpro === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"](" Problem Detail * ");
                document.getElementById("detpro").focus();
                return false;
               }
               if(maint === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("Required Action * ");
                document.getElementById("maint").focus();
                return false;
            }
            if(inp_sd === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("Target Completion Date * ");
                document.getElementById("inp_sd").focus();
                return false;
            }
            if(sttwork=== ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"](" Work Status * ");
                document.getElementById("sttwork").focus();
                return false;
            }
            
            Swal.fire({
                //title: 'Are you sure?',
                text: "Continue to submit this case?",
                //icon: 'warning',
                cancelButtonText: 'Not Yet',
                showCancelButton: true,
                confirmButtonText: 'Continue',
                confirmButtonColor: '#535687',
                cancelButtonColor: '#F5F5F5',
                
              }).then((result) => {
                if (result.isConfirmed) {
                    loading.show();
                    ajax_url = "/Wbi/add_act";
                    ajax_data="idw="+idw;
                    ajax_data+="&cdn="+cdn+"&ind="+ind;
                    ajax_data+="&ckt="+ckt+"&ckt_d="+ckt_d+"&plant="+plant+"&plant_d="+plant_d;
                    ajax_data+="&icdepp="+icdepp+"&detpro="+detpro+"&maint="+maint;
                    ajax_data+="&inp_sd="+inp_sd;
                    ajax_data+="&sttwork="+sttwork+"&spare_part_detail="+area_select+"&d_sel="+d_sel;
                    ajax_data+="&email="+emali1;
                    ajax_data+="&note_c="+note_c;
                    ajax_data+="&"+csrf_name+"="+$("#csrf").val();
                  
                    jQuery.ajax({
                        type: "POST",url: base_url+ajax_url,dataType:"text",data:ajax_data,
                            success:function(response){
                                var obj = jQuery.parseJSON(response);
                                if(obj.status === 1){
                                    $('#modal_add2').modal('toggle');
                                    //loading.hide();
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
                                            forml2[0];
                                            list_data();
                                }
                                else if(obj.status === 0){
                                    loading.hide();
                                    Swal.fire(
                                        'Error!',
                                        obj.msg,
                                        'error'
                                        );
                                }
                                //loading.hide();
                            },
                            error:function (xhr, ajaxOptions, thrownError){
                                loading.hide(); 
                                alert(thrownError);
                            }
                    });
                  }
              })
        });
        /*
          * tabel pending action
          * Work Status pending action
          * Target Completion Date & 
          * time
          * date Spare Part Arrived (ETA) 
        */
        //  tabel pending action
        var table_p_action   = $("#t_in_progress"); 
        table_p_action.on('click', 'button.btn-next-2',function(e){
            e.preventDefault();
            loading.show();
                    $("div#_nots").removeClass("tombol-aktif");
                    $("div#_nots").addClass("tombol-tidakaktif");
                    $("button#nots").removeClass("b-tombol-aktif");
                    $("button#nots").addClass("b-tombol-tidak-aktif");


                    $("div#_prog").removeClass("tombol-aktif");
                    $("div#_prog").addClass("tombol-tidakaktif");
                    $("button#prog").removeClass("b-tombol-aktif");
                    $("button#prog").addClass("b-tombol-tidak-aktif");

                    $("div#_comp").removeClass("tombol-tidakaktif");
                    $("div#_comp").addClass("tombol-aktif");
                    $("button#comp").removeClass("b-tombol-aktif");
                    $("button#comp").addClass("b-tombol-aktif");
                    

                    $("._not_started").hide();
                    $("._t_in_progress").hide();
                    $("._t_completed").show();
                    loading.hide();
        });
        table_p_action.on('click', 'a.btnRes',function(e){
            e.preventDefault();
            var _self     = $(this);
            var con       = _self.data("con");
            var ind       = _self.data("ind");
            var id        = _self.data("id");
            var idpending = _self.data("idpending");
            var cp        = _self.data("cp");
            var cd        = _self.data("cd");
            var pl        = _self.data("nmpl");
            var pl_d      = _self.data("pl_d");
            var picdep              = _self.data("picdep");
            var pro_d               = _self.data("pro_d");
            var maintenance_plan    = _self.data("maintenance_plan");
            var date_created        = _self.data("date_created");
            var kondisi             = _self.data("con");
            var status_work         = _self.data("st");
            var spare_part_detail   = _self.data("spd");
            var spare_part_arrived  = _self.data("spa");

            var tbl        = _self.data("tbl");
            var tg        = _self.data("tg");
            var ec        = _self.data("ec");
            var cn        = _self.data("cn");
            var dpc       = _self.data("dep");
           // var ema       = _self.data("ema");

            var edj       = _self.data("edj");
            var edm       = _self.data("edm");
            var eml = _self.data("eml");
            // var enddatehapus= _self.data("enddatehapus");
            
            // alert(enddatehapus);
            ajax_url = controller+"/view_action_pending";
            ajax_data="id="+_self.data("id");
            ajax_data+="&"+csrf_name+"="+$("#csrf").val();
            ajax_data+="&tbl="+tbl;
            ajax_data+="&status_work="+status_work;
            ajax_data+="&pl_d="+pl_d;
            ajax_data+="&picdep="+picdep;
            ajax_data+="&kon="+kondisi;
            ajax_data+="&cekpoin="+cp;
            ajax_data+="&cekpoin_d="+cd;
            loading.show();
            jQuery.ajax({
                type: "POST",
                url: base_url+ajax_url,
                dataType:"text", 
                data:ajax_data, 
                success:function(response){
                    var obj = null;
                    try
                    {
                        obj = $.parseJSON(response);  
                    }catch(e)
                    {}
                    if(obj)
                    {
                        $("#csrf").val(obj.csrf_hash);
                        if(obj.status === 1){
                            loading.hide();
                            $("#cdn2").val(con);
                            $("#ind2").val(ind);
                            $("#idpending").val(idpending);
                            $("#idw2").val(id);
                            $("#ckt2").val(cp);
                            $("#ckt_d2").val(cd);
                            $("#plt").val(pl);
                            $("#nmplant").val(pl_d);
                            $("#1addm2").val(eml);
//                            
                            $("div#kon").html(obj.kondi);
                            $("li#tgl").html(tg);
                            $("li#ec").html(ec);
                            $("li#cp").html(cp);
                            $("li#cd").html(cd);
                            $("li#cn").html(cn);

                            $("#dep_pic").html(obj.str_dept);
                            $("#detpro2").val(pro_d);
                            $("#maint2").val(maintenance_plan);

                            $("#pa_inp_sd").val(dpc);
                            $("#req_dt2").val(date_created);

                            $("#pa_inp_sd").val(edj);
                            $("#pa_sd_time").val(edm);
                            
                            $("#pa_work_s").html(obj.str_status);
                            $("#sud2").val(obj.today);
                            $("#apa_spd2").val(spare_part_detail);
                            $("#pa_spa2").val(spare_part_arrived);
                            $("div#sh").html(obj.str_log);

                            if(status_work=='1'){
                                $(".date_status").hide();
                                                        $(".sp_detail").hide();
                                                        $(".date_arrived").hide();
                            }else{
                                $(".date_status").show();
                                $(".sp_detail").show();
                                $(".date_arrived").show();
                            }

                            $('#modal_add3').modal('show');
                            window.history.pushState(null, "", window.location.href);        
                            window.onpopstate = function() {
                                //list_action();
                                // $("._wrapper_info").hide();
                                // $("._wrapper_sindi").show();    
                                $('#modal_add3').modal('toggle');
                            }
                        }
                        else if(obj.status === 0){
                            loading.hide();
                            Swal.fire(
                                'Error!',
                                obj.msg,
                                'error'
                              );
                        }
                        else if(obj.status === 2){
                            Swal.fire(
                                'Error!',
                                obj.msg,
                                'error'
                              );

                            // window.setTimeout(function(){
                            //     window.location.href =base_url+default_controller;
                            // }, 2000);
                        }
                        else{
                            loading.hide();
                            Swal.fire(
                                'Error!',
                                obj.msg,
                                'error'
                              );
                        }
                    }
                    else
                    {
                        sweetAlert("Error", "An Error Occured", "error");
                        loading.hide();
                        return false;
                    }
                },
                error:function (x, status, error){
                    loading.hide(); 
                    if (x.status == 403) {
                        sweetAlert("Error", "Sorry, your session has expired. Please login again to continue", "warning");
                      //  window.location.href =base_url+default_controller;
                    }
                    else {
                        alert("An error occurred: " + status + "nError: " + error);
                       // window.location.href =base_url+default_controller;
                    }
                }
            });
        });
         // Work Status pending action
         $("#pa_work_s").change(function(e){
            var pa_work_s = $("#pa_work_s").val();
            if(pa_work_s=='1'){
                $(".date_status").hide();
                $(".sp_detail").hide();
                $(".date_arrived").hide();
            }else if(pa_work_s=='2'){
                $(".date_status").show();
                $(".sp_detail").show();
                $(".date_arrived").show();
            }else{
                $(".date_status").hide();
                $(".sp_detail").hide();
                $(".date_arrived").hide();
            }
        }); 
        //*Target Completion Date & time
        $('#pa_inp_sd').datepicker({ 
            format: 'dd M yyyy ',
            viewMode: 'months',
            autoclose:true,
        });
        //*time
        $("#pa_sd_time").timepicker({
            defaultTIme:!1,
            icons:{
                up:"mdi mdi-chevron-up",down:"mdi mdi-chevron-down"
            },
            autoclose:true,
        });
         //  date Spare Part Arrived (ETA) 
         $('#pa_spa2').datepicker({ 
            format: 'dd M yyyy ',
            viewMode: 'months',
            autoclose:true,
        });
        var form_pending_action = $("#form_add3");
        form_pending_action.submit(function(e){
            e.preventDefault();
            var idpending       = $("#idpending").val();  //Id Pending
            var cdn       = $("#cdn2").val();   //conditions
            var ind       = $("#ind2").val(); //inspection_date
            var idw       = $("#idw2").val();      //Equipment Code
            var ckt       = $("#ckt2").val();      //Checkpoint
            
            var ckt_d       = $("#ckt_d2").val();  //Checkpoint Detail
            var plant       = $("#plt").val();  //plat nm
            var plant_d       = $("#nmplant").val();    //plat dept

            var icdepp        = $("#dep_pic").val(); //Departemen PIC
            var emali1       = $("#1addm2").val(); //email
            var emali2       = $("#2addm2").val();
            var emali3       = $("#3addm2").val();
            var emali4       = $("#4addm2").val();
            var emali5       = $("#5addm2").val();
            var detpro      = $("#detpro2").val(); //Problem Detail
            
            var maint       = $("#maint2").val();    //Required Action
            var req_dt      = $("#req_dt2").val();//Request Date
            
            var inp_sd      = $("#pa_inp_sd").val(); //Target Completion Date
            var inp_tm      = $("#pa_sd_time").val(); //Target Completion Time

            var sttwork     = $("#pa_work_s").val(); //Work Status
            var area_select = $("#apa_spd2").val(); //Spare Part Detail
            var d_sel       = $("#pa_spa2").val();  //Spare Part Arrived (ETA)
            
            if(icdepp === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("Departemen PIC * ");
                document.getElementById("icdepp").focus();
                return false;
               }            
            if(emali1 === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("Input Email * ");
                document.getElementById("icdepp").focus();
                return false;
               }
            if(detpro === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"](" Problem Detail * ");
                document.getElementById("detpro").focus();
                return false;
               }
            if(maint === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("Required Action * ");
                document.getElementById("maint").focus();
                return false;
            }
            if(req_dt === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("Request Date * ");
                document.getElementById("req_dt").focus();
                return false;
            }
            if(inp_sd === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("Target Completion Date * ");
                document.getElementById("inp_sd").focus();
                return false;
            }
            if(sttwork=== ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"](" Work Status * ");
                document.getElementById("sttwork").focus();
                return false;
            }
            Swal.fire({
                //title: 'Are you sure?',
                text: "Continue to submit this case?",
                //icon: 'warning',
                cancelButtonText: 'Not Yet',
                showCancelButton: true,
                confirmButtonText: 'Continue',
                confirmButtonColor: '#535687',
                cancelButtonColor: '#F5F5F5',
                
              }).then((result) => {
                if (result.isConfirmed) {
                    loading.show();
                    ajax_url = "/Wbi/add_act_pending";
                    ajax_data="plant="+plant+"&plant_d="+plant_d;
                    ajax_data+="&idw="+idw+"&ckt="+ckt+"&ckt_d="+ckt_d;
                    ajax_data+="&icdepp="+icdepp+"&detpro="+detpro+"&maint="+maint;
                    ajax_data+="&req_dt="+req_dt;
                    ajax_data+="&inp_sd="+inp_sd;
                    ajax_data+="&inp_tm="+inp_tm;
                    ajax_data+="&sttwork="+sttwork+"&spare_part_detail="+area_select+"&d_sel="+d_sel;
                    ajax_data+="&cdn="+cdn+"&ind="+ind;
                    ajax_data+="&idpending="+idpending;
                    ajax_data+="&email="+emali1;
                    ajax_data+="&"+csrf_name+"="+$("#csrf").val();
                    jQuery.ajax({
                        type: "POST",url: base_url+ajax_url,dataType:"text",data:ajax_data,
                            success:function(response){
                                var obj = jQuery.parseJSON(response);
                                if(obj.status === 1){
                                    $('#modal_add3').modal('toggle');
                                    //loading.hide();
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
                                            form_pending_action[0];
                                            list_data();
                                }
                                else if(obj.status === 0){
                                    loading.hide();
                                    Swal.fire(
                                        'Error!',
                                        obj.msg,
                                        'error'
                                        );
                                }
                                //loading.hide();
                            },
                            error:function (xhr, ajaxOptions, thrownError){
                                loading.hide(); 
                                alert(thrownError);
                            }
                    });
                }
              })
        });
        /*
          *tabel In progress
          *Work Status In progress
          *Target Completion Date & time
          *time
          *Spare Part Arrived (ETA) 
        */
        var table_inp   = $("#t_need_riview"); 
        table_inp.on('click', 'a.btnRes',function(e){
            e.preventDefault();
            var _self       = $(this);
            
            var con        = _self.data("con");
            var ind        = _self.data("ind");
            var id        = _self.data("id");
            var idip        = _self.data("idip");
            var cp        = _self.data("cp");
            var cd        = _self.data("cd");
            var pl_d      = _self.data("pl_d");
            var pl        = _self.data("nmpl");
            var picdep = _self.data("picdep");
            var pro_d               = _self.data("pro_d");
            var maintenance_plan    = _self.data("maintenance_plan");
            var date_created        = _self.data("date_created");
            var kondisi             = _self.data("con");
            
            var status_work         = _self.data("st");
            var spare_part_detail   = _self.data("spd");
            var spare_part_arrived  = _self.data("spa");
            var idpending           = _self.data("idpending");
            
            var tbl        = _self.data("tbl");
            var tg        = _self.data("tg");
            var ec        = _self.data("ec");
            var cn        = _self.data("cn");
            
            var edj       = _self.data("edj");
            var edm       = _self.data("edm");
            var email       = _self.data("ema");

            ajax_url = controller+"/view_action_inprogress";
            ajax_data="id="+_self.data("id");
            ajax_data+="&"+csrf_name+"="+$("#csrf").val();
            ajax_data+="&tbl="+tbl;
            ajax_data+="&status_work="+status_work;
            ajax_data+="&pl_d="+pl_d;
            ajax_data+="&picdep="+picdep;
            ajax_data+="&con="+kondisi;
            ajax_data+="&cekpoin="+cp;
            ajax_data+="&cekpoin_d="+cd;
            loading.show();
            jQuery.ajax({
                type: "POST",
                url: base_url+ajax_url,
                dataType:"text", 
                data:ajax_data, 
                success:function(response){
                    var obj = null;
                    try
                    {
                        obj = $.parseJSON(response);  
                    }catch(e)
                    {}
                    if(obj)
                    {
                        $("#csrf").val(obj.csrf_hash);
                        if(obj.status === 1){
                            loading.hide();
                            if(status_work=='2'){
                                $(".sts_ud").hide();
                                $(".part_detail3").show();
                                $(".date_arrived3").show();
                            }
                            else {
                                $(".sts_ud").hide();
                                $(".part_detail3").hide();
                                $(".date_arrived3").hide();
                            }
                            //hidden
                            $("#idpending3").val(idpending);
                            $("#cdn3").val(con);
                            $("#ind3").val(ind);
                            $("#idip").val(idip);
                            $("#idw3").val(id);
                            $("#ckt3").val(cp);
                            $("#ckt_d3").val(cd);
                            $("#nmplant3").val(pl_d);
                            $("#plt3").val(pl);
                            //kiri satu
                            $("div#kon3").html(obj.kondi);
                            $("li#tgl3").html(tg);
                            $("li#ec3").html(ec);
                            $("li#cp3").html(cp);
                            $("li#cd3").html(cd);
                            $("li#cn3").html(cn);
                            //kanan satu
                            $("#dep_pic3").html(obj.str_dept);
                            $("#1addm3").val(email);
                            $("#detpro3").val(pro_d);
                             $("#maint3").val(maintenance_plan);
                             
                             //kanan dua
                             $("#req_dt3").val(date_created);
                             $("#pa_inp_sd3").val(edj);
                             $("#pa_sd_time3").val(edm);
                             $("#pa_work_s3").html(obj.str_status);
                             $("#apa_spd3").html(spare_part_detail);
                             $("#pa_spa3").val(spare_part_arrived);
                             $("div#sh_3").html(obj.str_log);

                            $('#modal_add4').modal('show');
                            window.history.pushState(null, "", window.location.href);        
                            window.onpopstate = function() {  
                                $('#modal_add4').modal('toggle');
                            }
                        }
                        else if(obj.status === 0){
                            loading.hide();
                            Swal.fire(
                                'Error!',
                                obj.msg,
                                'error'
                              );
                        }
                        else if(obj.status === 2){
                            Swal.fire(
                                'Error!',
                                obj.msg,
                                'error'
                              );

                            // window.setTimeout(function(){
                            //     window.location.href =base_url+default_controller;
                            // }, 2000);
                        }
                        else{
                            loading.hide();
                            Swal.fire(
                                'Error!',
                                obj.msg,
                                'error'
                              );
                        }
                    }
                    else
                    {
                        sweetAlert("Error", "An Error Occured", "error");
                        loading.hide();
                        return false;
                    }
                },
                error:function (x, status, error){
                    loading.hide(); 
                    if (x.status == 403) {
                        sweetAlert("Error", "Sorry, your session has expired. Please login again to continue", "warning");
                      //  window.location.href =base_url+default_controller;
                    }
                    else {
                        alert("An error occurred: " + status + "nError: " + error);
                       // window.location.href =base_url+default_controller;
                    }
                }
            });
        });
        // Work Status //In progress
        $("#pa_work_s3").change(function(e){
            var stt_kerja = $("#pa_work_s3").val();
            if(stt_kerja=='2'){
                // $(".date_status").show();
                // $(".area_select").show();
                // $(".date_select").show();
                // $(".date_arrived").show();
                $(".sts_ud").hide();
                $(".part_detail3").show();
                $(".date_arrived3").show();
            }
            else if(stt_kerja=='3'){
                // $(".date_status").show();
                // $(".area_select").show();
                // $(".date_select").show();
                // $(".date_arrived").show();
                $(".sts_ud").hide();
                $(".part_detail3").hide();
                $(".date_arrived3").hide();
            }
            else if(stt_kerja=='4'){
                $(".sts_ud").hide();
                $(".part_detail3").hide();
                $(".date_arrived3").hide();
            }
            else if(stt_kerja=='5'){
                $(".sts_ud").hide();
                $(".part_detail3").hide();
                $(".date_arrived3").hide();
            }
            else if(stt_kerja=='6'){
                $(".sts_ud").hide();
                $(".part_detail3").hide();
                $(".date_arrived3").hide();
            }
            else if(stt_kerja==''){
                $(".sts_ud").hide();
                $(".part_detail3").hide();
                $(".date_arrived3").hide();
            }
        });
        //*Target Completion Date & time
        $('#pa_inp_sd3').datepicker({ 
            format: 'dd MM yyyy ',
            autoclose:true,
        });
        //*time
        $("#pa_sd_time3").timepicker({
            defaultTIme:!1,
            icons:{
                up:"mdi mdi-chevron-up",down:"mdi mdi-chevron-down"
            },
            autoclose:true,
        });
        //* Spare Part Arrived (ETA) 
        $('#pa_spa3').datepicker({ 
            format: 'dd MM yyyy ',
            autoclose:true,
        });
        var form_in_progress = $("#form_add4");
        form_in_progress.submit(function(e){
            e.preventDefault();
            var idpending       = $("#idpending3").val();   //Id Pending
            var cdn       = $("#cdn3").val();   //conditions
            var ind       = $("#ind3").val(); //inspection_date
            var idw       = $("#idw3").val();      //Equipment Code
            var ckt       = $("#ckt3").val();      //Checkpoint
            
            var ckt_d       = $("#ckt_d3").val();  //Checkpoint Detail
            var plant       = $("#plt3").val();  //plat nm
            var plant_d       = $("#nmplant3").val();    //plat dept

            var icdepp        = $("#dep_pic3").val(); //Departemen PIC
            var emali1       = $("#1addm3").val(); //email
            var emali2       = $("#2addm3").val();
            var emali3       = $("#3addm3").val();
            var emali4       = $("#4addm3").val();
            var emali5       = $("#5addm3").val();
            var detpro      = $("#detpro3").val(); //Problem Detail
            
            var maint       = $("#maint3").val();    //Required Action
            var req_dt      = $("#req_dt3").val();//Request Date
            
            var inp_sd      = $("#pa_inp_sd3").val(); //Target Completion Date
            var inp_tm      = $("#pa_sd_time3").val(); //Target Completion Time

            var sttwork     = $("#pa_work_s3").val(); //Work Status
            var area_select = $("#apa_spd3").val(); //Spare Part Detail
            var d_sel       = $("#pa_spa3").val();  //Spare Part Arrived (ETA)

            if(icdepp === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("Departemen PIC * ");
                document.getElementById("icdepp").focus();
                return false;
               }            
            if(emali1 === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("Input Email * ");
                document.getElementById("icdepp").focus();
                return false;
               }
               if(detpro === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"](" Problem Detail * ");
                document.getElementById("detpro").focus();
                return false;
               }
               if(maint === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("Required Action * ");
                document.getElementById("maint").focus();
                return false;
            }
            if(req_dt === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("Request Date * ");
                document.getElementById("req_dt").focus();
                return false;
            }
            if(inp_sd === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("Target Completion Date * ");
                document.getElementById("inp_sd").focus();
                return false;
            }
            if(sttwork=== ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"](" Work Status * ");
                document.getElementById("sttwork").focus();
                return false;
            }
            
           
                
        
            Swal.fire({
                //title: 'Are you sure?',
                text: "Continue to submit this case?",
                //icon: 'warning',
                cancelButtonText: 'Not Yet',
                showCancelButton: true,
                confirmButtonText: 'Continue',
                confirmButtonColor: '#535687',
                cancelButtonColor: '#F5F5F5',
                
              }).then((result) => {
                if (result.isConfirmed) {
                loading.show();
                ajax_url = "/Wbi/add_act_in_progress";
                ajax_data="plant="+plant+"&plant_d="+plant_d;
                ajax_data+="&idw="+idw+"&ckt="+ckt+"&ckt_d="+ckt_d;
                ajax_data+="&icdepp="+icdepp+"&detpro="+detpro+"&maint="+maint;
                ajax_data+="&req_dt="+req_dt;
                ajax_data+="&inp_sd="+inp_sd;
                ajax_data+="&inp_tm="+inp_tm;
                ajax_data+="&sttwork="+sttwork+"&spare_part_detail="+area_select+"&d_sel="+d_sel;
                ajax_data+="&cdn="+cdn+"&ind="+ind;
                ajax_data+="&idpending="+idpending;
                ajax_data+="&email="+emali1;
                ajax_data+="&"+csrf_name+"="+$("#csrf").val();
                jQuery.ajax({
                    type: "POST",url: base_url+ajax_url,dataType:"text",data:ajax_data,
                        success:function(response){
                            var obj = jQuery.parseJSON(response);
                            if(obj.status === 1){
                                $('#modal_add4').modal('toggle');
                                //loading.hide();
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
                                        form_in_progress[0];
                                        list_data();
                            }
                            else if(obj.status === 0){
                                loading.hide();
                                Swal.fire(
                                    'Error!',
                                    obj.msg,
                                    'error'
                                    );
                            }
                            //loading.hide();
                        },
                        error:function (xhr, ajaxOptions, thrownError){
                            loading.hide(); 
                            alert(thrownError);
                        }
                });
            }

              })
        });

        //Button History
        $(".hist").click(function(e){
            e.preventDefault();
            loading.show(); 
            list_history();
            $("._t_report").hide();
            $("._t_casehistory").show();
            loading.hide();
        });
        //tabel Case History
        function list_history(){
            loading.show();
            var idplant = $("#idplant").val();
            ajax_url = controller+"/list_history";
            ajax_data="id="+idplant;
            ajax_data+="&"+csrf_name+"="+$("#csrf").val();
            jQuery.ajax({
                type: "POST",
                url: base_url+ajax_url,
                dataType:"text",
                data:ajax_data,
                success:function(response){
                    var obj = null;
                    try
                    {
                        obj = $.parseJSON(response);  
                    }catch(e)
                    {}

                    if(obj)
                    {
                        $("#csrf").val(obj.csrf_hash);
                        if(obj.status === 1){
                            $("#t_casehistory > tbody").html(obj.case_history);
                            loading.hide();
                        }
                        else if(obj.status === 0){
                            loading.hide();
                            sweetAlert("Error", obj.msg, "error");
                        }
                        else if(obj.status === 2){
                            sweetAlert("Caution", obj.msg, "warning");
                            // window.setTimeout(function(){
                            //     window.location.href = base_url+"/home";
                            // }, 2000);
                        }

                    }
                    else{
                        sweetAlert("Caution", response, "error");
                        loading.hide();
                        // window.setTimeout(function(){
                        //     window.location.href = base_url+"/home";
                        // }, 2000);
                        // return false;
                    }
                },
                error:function (xhr, ajaxOptions, thrownError){
                    loading.hide(); 
                    alert(thrownError);
                    return false;
                }
            });
        }
        var table_his   = $("#t_casehistory");
        table_his.on('click', 'a.btnHis',function(e){
            e.preventDefault();
            var _self       = $(this);
            
            var pln        = _self.data("pln");
            var id        = _self.data("id");
            var con        = _self.data("con");
            var ind        = _self.data("ind");
            var equ        = _self.data("equ");
            var cpt        = _self.data("cpt");
            var cpd        = _self.data("cpd");
            var cln = _self.data("cln");

            var dpc = _self.data("dpc");
            var prd = _self.data("prd");
            var mph = _self.data("mph");

            var rqd = _self.data("rqd");
            var tcd = _self.data("tcd");
            var spd = _self.data("spd");
            var spa = _self.data("spa");
            var ema = _self.data("ema");
            var wsi = _self.data("wsi");

           
            ajax_url = controller+"/view_action_history";
            ajax_data="id="+id;
            //ajax_data+="&pln="+pln;
            ajax_data+="&pln="+pln+"&eq_id="+equ+"&cpt="+cpt+"&cpd="+cpd;
            ajax_data+="&"+csrf_name+"="+$("#csrf").val();
            loading.show();
            jQuery.ajax({
                type: "POST",
                url: base_url+ajax_url,
                dataType:"text",
                data:ajax_data,
                success:function(response){
                    var obj = null;
                    try
                    {
                        obj = $.parseJSON(response);  
                    }catch(e)
                    {}

                    if(obj)
                    {
                        $("#csrf").val(obj.csrf_hash);
                        if(obj.status === 1){
                            $("div#con_h").html(con);
                        if(con == 'need action' || con == 'Need Action'){
                            $("div#con_h").addClass("font-tabel-na");
                        }
                        if (con == 'monitoring' || con == 'Monitoring') {
                            $("div#con_h").addClass("font-tabel-mt");
                        }
                        if (con == 'abnormal' || con == 'Abnormal') {
                            $("div#con_h").addClass("font-tabel-an");
                        }
           
                        $("li#ind_h").html(ind);
                             $("li#ec").html(equ);
                             $("li#cp_h").html(cpt);
                             $("li#cpd_h").html(cpd);
                             $("li#cn_h").html(cln);
            
                             $("li#dp_h2").html(dpc);
                             $("li#eml_h2").html(ema);
                             $("li#prd").html(prd);
                             $("li#mp_h2").html(mph);
                             $("li#rd_h").html(rqd);
                             $("li#tcd_h").html(tcd);
                             $("li#spd_h").html(spd);   
                             if(wsi=='2'){
                                $("li#spa_h").html(spa);
                            }
                             else{                             
                                $("li#spa_h").html('');
                            }
                             $("#t_history > tbody").html(obj.tbl_h);
                             $('#modal_history').modal('show');
                            loading.hide();
                        }
                        else if(obj.status === 0){
                            loading.hide();
                            sweetAlert("Error", obj.msg, "error");
                        }
                        else if(obj.status === 2){
                            sweetAlert("Caution", obj.msg, "warning");
                            // window.setTimeout(function(){
                            //     window.location.href = base_url+"/home";
                            // }, 2000);
                        }

                    }
                    else{
                        sweetAlert("Caution", response, "error");
                        loading.hide();
                        // window.setTimeout(function(){
                        //     window.location.href = base_url+"/home";
                        // }, 2000);
                        // return false;
                    }
                },
                error:function (xhr, ajaxOptions, thrownError){
                    loading.hide(); 
                    alert(thrownError);
                    return false;
                }
            });





            
              
           
        });
        /*
        Action form

        */
       // add email
        var max_fields      = 5; //maximum input boxes allowed
        var wrapper         = $(".input_fields_wrap"); //Fields wrapper
        var add_button      = $(".add_field_button"); //Add button ID
        var x = 1; //initlal text box count
        $(add_button).click(function(e){ //on add input button click
            e.preventDefault();
            if(x < max_fields){ //max input box allowed
                x++; //text box increment
                var nilai = x+"addm";
                $(wrapper).append('<div class="input_fields_wrap1" style="height: 42px;"><input type="email" placeholder="Input email" class="int-pass int-text"  id="'+nilai+'" name="'+nilai+'"/><i class=" ion ion-md-close  remove_field" style="margin-left: -30px; cursor: pointer;"></i></div>'); //add input box
            }
        });
    
        $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
            e.preventDefault(); $(this).parent('div').remove(); x--;
        });
        
      

        var forml1       = $("#form_add1");       
        forml1.submit(function(e){
            e.preventDefault();
            var idw       = $("#idw").val();
            var ckt       = $("#ckt").val();
            var ckt_d       = $("#ckt_d").val();
            var plant       = $("#plant").val();
            var plant_d       = $("#plant_d").val();

            var icdepp    = $("#icdepp").val();
            var icpic    = $("#icpic").val();
            var detpro    = $("#detpro").val();
            var maint    = $("#maint").val();
            var inp_sd = $("#inp_sd").val();
            var inp_ed = $("#sd_time").val();
            var sd_time = $("#sd_time").val();
            var en_time = $("#en_time").val();

            var sttwork     = $("#sttwork").val();
            var area_select = $("#area_select").val();
            var d_sel       = $("#d_sel").val();
            var spa       = $("#spa").val();
            if(icdepp === ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("Departemen PIC * ");
                document.getElementById("icdepp").focus();
                return false;
               }
            // if(icpic == ''){
            //     toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("Staffs PIC * ");
            //     document.getElementById("icpic").focus();
            //     return false;
            // }
            if(detpro == ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("Problem Detail *");
                document.getElementById("detpro").focus();
                return false;
            } 
            if(maint=== ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"](" Work Status * ");
                document.getElementById("maint").focus();
                return false;
            } 
            if(inp_sd=== ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("Star Date * ");
                document.getElementById("inp_sd").focus();
                return false;
            }
            if(inp_ed=== ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("End Date * ");
                document.getElementById("inp_ed").focus();
                return false;
            }
            if(en_time=== ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"]("End Date * ");
                document.getElementById("en_time").focus();
                return false;
            }
            if(sttwork=== ''){
                toastr.options = {"closeButton": true,"debug": false, "newestOnTop": false, "progressBar": true, "positionClass": "toast-top-right", "preventDuplicates": false, "onclick": null, "showDuration": "300", "hideDuration": "1000", "timeOut": "5000", "extendedTimeOut": "1000", "showEasing": "swing", "hideEasing": "linear", "showMethod": "fadeIn", "hideMethod": "fadeOut" }; toastr["warning"](" Work Status * ");
                document.getElementById("sttwork").focus();
                return false;
            }  

            Swal.fire({
                title: 'Continue to Submit this case?',  
                showCancelButton:!0,
                confirmButtonText:"Save",
                cancelButtonText: `Don't save`,
                confirmButtonColor:"#348cd4",
                cancelButtonColor:"#6c757d",
                showLoaderOnConfirm:!0,
                preConfirm:function(t){
                    return fetch(t).then(
                        function(t){
                            // if(!t.ok)throw new Error(t.statusText);
                            // return t.json()
                            //alert('1');
                        }).catch(
                                // function(t){
                                //     Swal.showValidationMessage("Request failed: "+t)
                                // }
                                )
                            }
                                ,
                                allowOutsideClick:function(){
                                        Swal.isLoading()
                                    }}).then(
                                        function(t){
                                            //loading.show();
                                            ajax_url = "/Wbi/add_act";
                                            ajax_data="idw="+idw+"&ckt="+ckt+"&ckt_d="+ckt_d+"&plant="+plant+"&plant_d="+plant_d;
                                            ajax_data+="&icdepp="+icdepp+"&detpro="+detpro+"&maint="+maint;
                                            ajax_data+="&inp_sd="+inp_sd+"&sd_time="+sd_time+"&inp_ed="+inp_ed+"&en_time="+en_time;
                                            ajax_data+="&sttwork="+sttwork+"&spare_part_detail="+area_select+"&d_sel="+d_sel+"&spa="+spa;
                                            ajax_data+="&"+csrf_name+"="+$("#csrf").val();
                                            jQuery.ajax({
                                                type: "POST", url: base_url+ajax_url, dataType:"text", data:ajax_data,
                                                success:function(response){
                                                    var obj = jQuery.parseJSON(response);
                                                    if(obj.status === 1){
                                                        $('#modal_add1').modal('toggle');
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
                                                              list_data();
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
                                    }
                                        )
        });


        $(".btnX").click(function(e){
            e.preventDefault();
            $(this).closest('.modal').modal('toggle');
             //$(".mdl_dok_add").modal('toggle');
            // loading.hide();
        });




        var formAction = $(".ActionForm");
        formAction.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            rules:{
                id:{required:true},
                deppic:{required:true},
                pic:{required:true},
                detpro:{required:false},
                maint:{required:false}
            }
            ,
            errorPlacement: function(error, element) {
                if (element.attr("name") === "tgl_isi" || element.attr("name") === "tgl_lahir" ) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // hightlight error inputs
                $(element)
                .closest('.has-error').removeClass('has-error'); // set error class to the control group
            },
            submitHandler: function(form) {
                var url = controller+"/add_act";
                var data = formAction.serialize();
                data+="&"+csrf_name+"="+$("#csrf").val();                
                loading.show();
                //msg_obj.hide();
                jQuery.ajax({
                    type: "POST", // HTTP method POST or GET
                    url: base_url+url, //Where to make Ajax calls
                    dataType:"text", // Data type, HTML, json etc.
                    data:data, //Form variables
                    success:function(response){
                        var obj = null;
                        try{
                            obj = $.parseJSON(response);  
                        }catch(e)
                        {}
                        //var obj = jQuery.parseJSON(response);

                        if(obj)//if json data
                        {
                            loading.hide();
                            $("#csrf").val(obj.csrf_hash);
                            //success msg
                            if(obj.status === 1){
                                //sweetAlert("Success", obj.msg, "success");
                                toastr.options = {
                                  "closeButton": true,
                                  "debug": false,
                                  "newestOnTop": false,
                                  "progressBar": true,
                                  "positionClass": "toast-bottom-left",
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
                                
                                forml[0].reset();
                                $('#modal_add').modal('hide');
                            }

                            //error msg
                            else if(obj.status === 0){
                                sweetAlert("Error", obj.msg, "error");
                            }
                            datatable.ajax.reload();
                        }
                        else
                        {
                            sweetAlert("Error", response, "error");
                            loading.hide();
                        }
                    },
                    error:function (xhr, ajaxOptions, thrownError){
                        loading.hide(); 
                        sweetAlert("FATAL ERROR", thrownError, "error");
                    }
                });
                return false;
            }
        });







        

        function list_action(){
            loading.show();
            ajax_url = controller+"/list_action";
            ajax_data="id="+$("#search").val();
            ajax_data+="&"+csrf_name+"="+$("#csrf").val();
            jQuery.ajax({
                type: "POST",
                url: base_url+ajax_url,
                dataType:"text",
                data:ajax_data,
                success:function(response){
                    var obj = null;
                    try
                    {
                        obj = $.parseJSON(response);  
                    }catch(e)
                    {}

                    if(obj)
                    {
                        $("#csrf").val(obj.csrf_hash);
                        if(obj.status === 1){
                            $("#t_waiting > tbody").html(obj.str);
                            $("a#v-pills-home-tab").html(obj.not_started);
                            $("p#nt").html(obj.not_action);
                            //$("._wrapper_bahan").show();
                            //$(".list_kabko").fadeIn();
                            loading.hide();
                        }
                        else if(obj.status === 0){
                            loading.hide();
                            sweetAlert("Error", obj.msg, "error");
                        }
                        else if(obj.status === 2){
                            sweetAlert("Caution", obj.msg, "warning");
                            // window.setTimeout(function(){
                            //     window.location.href = base_url+"/home";
                            // }, 2000);
                        }

                    }
                    else{
                        sweetAlert("Caution", response, "error");
                        loading.hide();
                        // window.setTimeout(function(){
                        //     window.location.href = base_url+"/home";
                        // }, 2000);
                        // return false;
                    }
                },
                error:function (xhr, ajaxOptions, thrownError){
                    loading.hide(); 
                    alert(thrownError);
                    return false;
                }
            });
        }
        //list_action();


        table.on('click', 'a.btnRes',function(e){
            e.preventDefault();
            var _self       = $(this);
            var id        = _self.data("id");
            ajax_url = controller+"/open_detail";
            ajax_data="id="+_self.data("id");
            ajax_data+="&"+csrf_name+"="+$("#csrf").val();
            loading.show();
            jQuery.ajax({
                type: "POST",
                url: base_url+ajax_url,
                dataType:"text", 
                data:ajax_data, 
                success:function(response){
                    var obj = null;
                    try
                    {
                        obj = $.parseJSON(response);  
                    }catch(e)
                    {}
                    if(obj)
                    {
                        $("#csrf").val(obj.csrf_hash);
                        if(obj.status === 1){
                            loading.hide();
                            $("#inp_indi").val(id);//inp_indi
                            $("._wrapper_list").hide();
                            $("b#equipment_code").html(obj.eq_code);
                            $("p#waitingcase").html(obj.waitingcase);
                            
                            $("button#nots").html(obj.not_started);
                            $("#t_detail > tbody").html(obj.str);
                            $("button#prog").html(obj.in_progress);
                            $("#t_in_progress> tbody").html(obj.str_2);
                            $("button#comp").html(obj.completed);
                            $("#t_completed> tbody").html(obj.str_3);

                            $("._wrapper_info").hide();
                            $("._wrapper_sindi").show();
                            window.history.pushState(null, "", window.location.href);        
                            window.onpopstate = function() {
                                list_action();
                                $("._wrapper_info").show();
                                $("._wrapper_sindi").hide();    
                            }                        

                        }
                        else if(obj.status === 0){
                            loading.hide();
                            Swal.fire(
                                'Error!',
                                obj.msg,
                                'error'
                              );
                        }
                        else if(obj.status === 2){
                            Swal.fire(
                                'Error!',
                                obj.msg,
                                'error'
                              );

                            // window.setTimeout(function(){
                            //     window.location.href =base_url+default_controller;
                            // }, 2000);
                        }
                        else{
                            loading.hide();
                            Swal.fire(
                                'Error!',
                                obj.msg,
                                'error'
                              );
                        }
                    }
                    else
                    {
                        sweetAlert("Error", "An Error Occured", "error");
                        loading.hide();
                        return false;
                    }
                },
                error:function (x, status, error){
                    loading.hide(); 
                    if (x.status == 403) {
                        sweetAlert("Error", "Sorry, your session has expired. Please login again to continue", "warning");
                      //  window.location.href =base_url+default_controller;
                    }
                    else {
                        alert("An error occurred: " + status + "nError: " + error);
                       // window.location.href =base_url+default_controller;
                    }
                }
            });            
        });
        function list_open_detail(){
            var id = $("#inp_indi").val();
            ajax_url = controller+"/open_detail";
            ajax_data="id="+id;
            ajax_data+="&"+csrf_name+"="+$("#csrf").val();
            loading.show();
            jQuery.ajax({
                type: "POST",
                url: base_url+ajax_url,
                dataType:"text", 
                data:ajax_data, 
                success:function(response){
                    var obj = null;
                    try
                    {
                        obj = $.parseJSON(response);  
                    }catch(e)
                    {}
                    if(obj)
                    {
                        $("#csrf").val(obj.csrf_hash);
                        if(obj.status === 1){
                            loading.hide();
                            $("#inp_indi").val(id);//inp_indi
                            $("._wrapper_list").hide();
                            $("b#equipment_code").html(obj.eq_code);
                            $("p#waitingcase").html(obj.waitingcase);
                            
                            $("button#nots").html(obj.not_started);
                            $("#t_detail > tbody").html(obj.str);
                            $("button#prog").html(obj.in_progress);
                            $("#t_in_progress> tbody").html(obj.str_2);
                            $("button#comp").html(obj.completed);
                            $("#t_completed> tbody").html(obj.str_3);

                            $("._wrapper_info").hide();
                            $("._wrapper_sindi").show();
                            window.history.pushState(null, "", window.location.href);        
                            window.onpopstate = function() {
                                list_action();
                                $("._wrapper_info").show();
                                $("._wrapper_sindi").hide();    
                            }                        

                        }
                        else if(obj.status === 0){
                            loading.hide();
                            Swal.fire(
                                'Error!',
                                obj.msg,
                                'error'
                              );
                        }
                        else if(obj.status === 2){
                            Swal.fire(
                                'Error!',
                                obj.msg,
                                'error'
                              );

                            // window.setTimeout(function(){
                            //     window.location.href =base_url+default_controller;
                            // }, 2000);
                        }
                        else{
                            loading.hide();
                            Swal.fire(
                                'Error!',
                                obj.msg,
                                'error'
                              );
                        }
                    }
                    else
                    {
                        sweetAlert("Error", "An Error Occured", "error");
                        loading.hide();
                        return false;
                    }
                },
                error:function (x, status, error){
                    loading.hide(); 
                    if (x.status == 403) {
                        sweetAlert("Error", "Sorry, your session has expired. Please login again to continue", "warning");
                      //  window.location.href =base_url+default_controller;
                    }
                    else {
                        alert("An error occurred: " + status + "nError: " + error);
                       // window.location.href =base_url+default_controller;
                    }
                }
            });   
        }

        table_d.on('click', 'a.btnCase',function(e){
            e.preventDefault();
            var _self       = $(this);
            var id        = _self.data("id");

            ajax_url = controller+"/view_action";
            ajax_data="id="+_self.data("id");
            ajax_data+="&"+csrf_name+"="+$("#csrf").val();
            loading.show();
            jQuery.ajax({
                type: "POST",
                url: base_url+ajax_url,
                dataType:"text", 
                data:ajax_data, 
                success:function(response){
                    var obj = null;
                    try
                    {
                        obj = $.parseJSON(response);  
                    }catch(e)
                    {}
                    if(obj)
                    {
                        $("#csrf").val(obj.csrf_hash);
                        if(obj.status === 1){
                            loading.hide();
                            $('#mdl_dok_add').modal('show');
                            $("#deppic").html(obj.str_dept);
                            $("#sttwork").html(obj.str_status);
                            $("strong#equipment_code").html(obj.Equipment_Code);
                            window.history.pushState(null, "", window.location.href);        
                            window.onpopstate = function() {
                                //list_action();
                                $("._wrapper_info").hide();
                                $("._wrapper_sindi").show();    
                                $('#mdl_dok_add').modal('toggle');
                            }
                        }
                        else if(obj.status === 0){
                            loading.hide();
                            Swal.fire(
                                'Error!',
                                obj.msg,
                                'error'
                              );
                        }
                        else if(obj.status === 2){
                            Swal.fire(
                                'Error!',
                                obj.msg,
                                'error'
                              );

                            // window.setTimeout(function(){
                            //     window.location.href =base_url+default_controller;
                            // }, 2000);
                        }
                        else{
                            loading.hide();
                            Swal.fire(
                                'Error!',
                                obj.msg,
                                'error'
                              );
                        }
                    }
                    else
                    {
                        sweetAlert("Error", "An Error Occured", "error");
                        loading.hide();
                        return false;
                    }
                },
                error:function (x, status, error){
                    loading.hide(); 
                    if (x.status == 403) {
                        sweetAlert("Error", "Sorry, your session has expired. Please login again to continue", "warning");
                      //  window.location.href =base_url+default_controller;
                    }
                    else {
                        alert("An error occurred: " + status + "nError: " + error);
                       // window.location.href =base_url+default_controller;
                    }
                }
            });

            
        });

        // table_in.on('click', 'a.btnCase',function(e){
        //     e.preventDefault();
        //     var _self       = $(this);
        //     var id        = _self.data("id");

        //     ajax_url = controller+"/view_action";
        //     ajax_data="id="+_self.data("id");
        //     ajax_data+="&"+csrf_name+"="+$("#csrf").val();
        //     loading.show();
        //     jQuery.ajax({
        //         type: "POST",
        //         url: base_url+ajax_url,
        //         dataType:"text", 
        //         data:ajax_data, 
        //         success:function(response){
        //             var obj = null;
        //             try
        //             {
        //                 obj = $.parseJSON(response);  
        //             }catch(e)
        //             {}
        //             if(obj)
        //             {
        //                 $("#csrf").val(obj.csrf_hash);
        //                 if(obj.status === 1){
        //                     loading.hide();
        //                     $('#mdl_dok_add').modal('show');
        //                     $("#deppic").html(obj.str_dept);
        //                     $("#sttwork").html(obj.str_status);
        //                     $("strong#equipment_code").html(obj.Equipment_Code);
        //                     window.history.pushState(null, "", window.location.href);        
        //                     window.onpopstate = function() {
        //                         //list_action();
        //                         $("._wrapper_info").hide();
        //                         $("._wrapper_sindi").show();    
        //                         $('#mdl_dok_add').modal('toggle');
        //                     }
        //                 }
        //                 else if(obj.status === 0){
        //                     loading.hide();
        //                     Swal.fire(
        //                         'Error!',
        //                         obj.msg,
        //                         'error'
        //                       );
        //                 }
        //                 else if(obj.status === 2){
        //                     Swal.fire(
        //                         'Error!',
        //                         obj.msg,
        //                         'error'
        //                       );

        //                     // window.setTimeout(function(){
        //                     //     window.location.href =base_url+default_controller;
        //                     // }, 2000);
        //                 }
        //                 else{
        //                     loading.hide();
        //                     Swal.fire(
        //                         'Error!',
        //                         obj.msg,
        //                         'error'
        //                       );
        //                 }
        //             }
        //             else
        //             {
        //                 sweetAlert("Error", "An Error Occured", "error");
        //                 loading.hide();
        //                 return false;
        //             }
        //         },
        //         error:function (x, status, error){
        //             loading.hide(); 
        //             if (x.status == 403) {
        //                 sweetAlert("Error", "Sorry, your session has expired. Please login again to continue", "warning");
        //               //  window.location.href =base_url+default_controller;
        //             }
        //             else {
        //                 alert("An error occurred: " + status + "nError: " + error);
        //                // window.location.href =base_url+default_controller;
        //             }
        //         }
        //     });

            
        // });

        table_co.on('click', 'a.btnCase',function(e){
            e.preventDefault();
            var _self       = $(this);
            var id        = _self.data("id");

            ajax_url = controller+"/view_action";
            ajax_data="id="+_self.data("id");
            ajax_data+="&"+csrf_name+"="+$("#csrf").val();
            loading.show();
            jQuery.ajax({
                type: "POST",
                url: base_url+ajax_url,
                dataType:"text", 
                data:ajax_data, 
                success:function(response){
                    var obj = null;
                    try
                    {
                        obj = $.parseJSON(response);  
                    }catch(e)
                    {}
                    if(obj)
                    {
                        $("#csrf").val(obj.csrf_hash);
                        if(obj.status === 1){
                            loading.hide();
                            $('#mdl_dok_add').modal('show');
                            $("#deppic").html(obj.str_dept);
                            $("#sttwork").html(obj.str_status);
                            $("strong#equipment_code").html(obj.Equipment_Code);
                            window.history.pushState(null, "", window.location.href);        
                            window.onpopstate = function() {
                                //list_action();
                                $("._wrapper_info").hide();
                                $("._wrapper_sindi").show();    
                                $('#mdl_dok_add').modal('toggle');
                            }
                        }
                        else if(obj.status === 0){
                            loading.hide();
                            Swal.fire(
                                'Error!',
                                obj.msg,
                                'error'
                              );
                        }
                        else if(obj.status === 2){
                            Swal.fire(
                                'Error!',
                                obj.msg,
                                'error'
                              );

                            // window.setTimeout(function(){
                            //     window.location.href =base_url+default_controller;
                            // }, 2000);
                        }
                        else{
                            loading.hide();
                            Swal.fire(
                                'Error!',
                                obj.msg,
                                'error'
                              );
                        }
                    }
                    else
                    {
                        sweetAlert("Error", "An Error Occured", "error");
                        loading.hide();
                        return false;
                    }
                },
                error:function (x, status, error){
                    loading.hide(); 
                    if (x.status == 403) {
                        sweetAlert("Error", "Sorry, your session has expired. Please login again to continue", "warning");
                      //  window.location.href =base_url+default_controller;
                    }
                    else {
                        alert("An error occurred: " + status + "nError: " + error);
                       // window.location.href =base_url+default_controller;
                    }
                }
            });

            
        });
        $("._search").change(function(){
            var sea =$("#search").val();
            list_action();
        });

        $(".btnBack").click(function(e){
            e.preventDefault();
            dua_loading.show();
            list_action();
            $("._wrapper_info").show();
            $("._wrapper_sindi").hide();
            dua_loading.hide();
        });
        
        // table.on('click', 'a.btnRes',function(e){
        //     e.preventDefault();
        //     var _self       = $(this);
        //     var id        = _self.data("id");
        //     ajax_url = controller+"/open_detail";
        //     ajax_data="id="+_self.data("id");
        //     ajax_data+="&"+csrf_name+"="+$("#csrf").val();
        //     loading.show();
        //     $("._wrapper_info").hide();
        //     $("._wrapper_sindi").show();
        //     loading.hide();
        // });
    };
    
    return{
        init:function(){datatable();},
    };
}();