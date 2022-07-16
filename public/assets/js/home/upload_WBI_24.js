var main = function(){
    controller = "/Upload_schedule";
    
    var datatable = function(){
        
        $("button#pf").removeClass("header-btn-aktif");
        $("button#pf").addClass("header-btn-tidak");
        $("button#us").removeClass("header-btn-tidak");
        $("button#us").addClass("header-btn-aktif");

    };
    
    return{
        init:function(){datatable();},
    };
}();