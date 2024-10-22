var baseUrl = location.protocol+'//'+location.host+"/admin/";
function readURL(input,img) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var imag = $('#'+img).attr('src', e.target.result);
            if (imag) {
                $(this).hide();
            }else{
                $(this).show();
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$(document).ready(function(){
    $(".checkMebox").on("click", function(){
        var checkbok = $(this).find('input');
        var checkMe = $(this).find('i');
        if($(checkbok).is(':checked')){
            $(checkMe).removeClass("fa fa-square-o");
            $(checkMe).addClass("fa fa-check-square-o");
            
        }else{
            $(checkMe).removeClass("fa fa-check-square-o");
            $(checkMe).addClass("fa fa-square-o");
        }
    });


    $("#choosImg, #blah").click(function(){
        $("#imgInp").click();
    });
    $("#imgInp").change(function(){
        readURL(this, 'blah');
        $("#blah").show();
    });

    $("#form_submitTourType").on('submit', function(e){
        var FormData = $("#form_submitTourType").serialize();     
        e.preventDefault();
        TourUrl = baseUrl+"create/tourtype/";
        insertData(TourUrl, FormData, $("#btnSubmit"));
    });

    $(document).on('click', ".restEditMenu", function(){
        var datatype = $(this).data("type");
        var price    = $(this).data("price");
        var kprice   = $(this).data("kprice");
        var menuName = $(this).data("menu");
        var restId   = $(this).data("id");
        var counId   = $(this).data("country_id");
        var selectedId = $(this).data("rest_name");
        loadService(counId, datatype, $("#dropdown-restaurant"), selectedId);
        $("#country option").each( function(i){
            if($(this).val() == counId){
                $(this).attr("selected", true);
            }else{
                $(this).removeAttr("selected", true);
            }
        });
        $("#restid").val(restId);
        $("#price").val(price);
        $("#kprice").val(kprice);
        $("#menu_name").val(menuName);
    });

    // for sort item by country
    $(".locationchange").on('change', function(){
        $(this).closest("form").submit();
    });
    $('.number_only').bind('keyup paste keypress', function(e){
        if(/\d/.test(String.fromCharCode(e.keyCode))){
        }else{
            // return false;
        }
    });
    $("#data_filter").on('keyup', function(){
        dataFilter($(this).data("url"), $(this).val(), $(this).data("type"));
    });

    $(".attachment").on('click', function(){
        $(this).addClass('selected');
    });

    $(".editprice").on('dblclick', function(){
        $(this).removeAttr('readonly');
        $(this).attr('name', $(this).data('label'));
    });  

    $(".country").on('change', function(){
        var dataName = $(this).val();
        var datatype = $(this).data('type');
        $.ajax({
            method: "GET",
            url: baseUrl+"option/findlocaiton",      
            data: "id=" + dataName+"&datatype="+ datatype,         
            dataType: 'html',
            success: function(html){                
                $("#dropdown-data").html(html);
            },
            error: function(){
                alert("Something Wrong.");
                return false;
            },
            complete: function() {              
                
            }
        });
        return false;
    });
});


// for hotel 
$(document).ready(function(){
    $("#LoadingRow").css({'display':'none'});
    var i=1;
    $(".addHotelRate").on('click', function(){
        var urlRate = $("#urlRate").val();
        var type = $(this).data('type');
        $.ajax({
            method: "GET",
            url: urlRate,
            data: "i=" +i+"&type="+type,
            dataType: 'html',
            beforeSend: function() {
            $(this).attr('disabled','disabled');
                $("#LoadingRow").css({'display':'block'});
                $("#placeholder").addClass('loading');
            },
            success: function(html){
                i++;
                $("#hotel-rate tbody tr:last").after(html);
            },
            error: function(){
                alert("Something Wrong.");
                return false;
            },
            complete: function() {
                $("#LoadingRow").css({'display':'none'});
            }
        });
        return false;
    });

    // this option remove all table row 
    $(document).on("click", ".RemoveHotelRate", function(){
        var remoeRow = $(this).closest("tr");
        var dataId = $(this).data('id');
        var type = $(this).data('type');
        if(confirm("Are you sure you want to delete this?")){       
            $.ajax({
                method: "GET",
                url: baseUrl+"option/remove",    
                data: "type="+ type +"&dataId=" + dataId ,          
                dataType: 'html',
                beforeSend: function() {
                    $(this).css('pointer-events','none');
                },
                success: function(html){
                    console.log(html);
                    remoeRow.css({'background-color':'#9E9E9E'});
                    remoeRow.fadeOut(500, function(){
                        $(remoeRow).remove();
                    });
                    return false;
                },
                error: function(){
                    alert("Something Wrong.");
                    return false;
                },
                complete: function() {
                    $(this).css('pointer-events','auto');
                }
            });
        }else{
            return false;
        }
    });

    $(".checkRoom").on('click', function(){
        var enable = $(this).closest("tr td").find('.check');
        var desable = $(this).closest("tr td").find('.nocheck');
        if($(this).is(':checked')){
            $(enable).css({'display':'block'});
            $(desable).css({'display':'none'});
        } else {
            $(enable).css({'display':'none'});
            $(desable).css({'display':'block'});
        }
    }); 

    // script for cruise 
    $(".choose-option").on('click', function(e){
        var enable = $(this).closest("span").find('i');
        $(enable.removeClass('fa-square-o'));
        if($(this).is(':checked')){
            $(enable).removeClass('fa-square-o');
            $(enable).addClass('fa-check-square');
        }else{
            $(enable).removeClass('fa-check-square');
            $(enable).addClass('fa-square-o');
        }
    }); 

    $(".filterHotel").on('change', function(){
        window.location.href = baseUrl+"hotel/apply/room/"+ $(this).val();
    });
});

// for tour
$(document).ready(function(){
    $("#form_submitTourType").on('submit', function(e){
        e.preventDefault();
        TourUrl = baseUrl+"create/tourtype/";
        insertData(TourUrl, $("#form_submitTourType").serialize(), $("#btnSubmit"));
    });

    $(".editTourType").on("click", function(e){
        var dataId = $(this).data("id");
        var subUrl = $(this).data("url");
        $.ajax({
            method: "GET",
            url: subUrl,
            data: "dataId="+dataId,
            dataType: "json",
            success: function(html){
                $("#eid").val(html.id);
                $("#title").val(html.name);
                $("#bus_ios").val(html.business_iso);               
                $("#meta_keyword").val(html.meta_keyword);
                $("#meta_desc").val(html.meta_description);
                return false;
            },
            error: function(xhr, status, error){
                alert("Error!" + xhr.status);
                return false;
            },
        });
        $("#eid").val(dataId);
    }); 
});

// service section
$(document).ready(function(){
    $("#form_submittransportService").on('submit', function(e){
        e.preventDefault();
        TourUrl = baseUrl+"transport/service/added";
        insertData(TourUrl, $("#form_submittransportService").serialize(), $("#btnSubmit"));
    });

    $("#form_submitVehicle").on('submit', function(e){
        e.preventDefault();
        VhUrl = baseUrl+"transport/added/vehicle";
        insertData(VhUrl, $("#form_submitVehicle").serialize(), $("#btnsave"));
    });

    $(".province").on("change", function(){
        var dataid = $(this).val();
        var datatype = $(this).data('type');
        var location = $("#dropdown-supplier");
        loadService(dataid, datatype, location, 'transport service');
    });

    $("#btnCreateTransport").click(function(){ 
        $("#service_name").val('');
        $("#eid").val('');
        $("#service_code").val('');
        $("#form_title").text("Add Transport Service"); 
    });

    $(".tranEditMenu").on('click', function(){
        $("#form_title").text("Update Transport Service");
        var counId = $(this).data('country');
        var proId  = $(this).data('city');
        var sId    = $(this).data('id');
        var suppId = $(this).data('supplier');
        var title  = $(this).data('title');
        $("#service_name").val(title);
        $("#eid").val(sId);
        $("#service_code").val($(this).data('code'));
        $("#country option").each( function(i){
            if($(this).val() == counId){
                $(this).attr("selected", true);
            }else{
                $(this).removeAttr("selected", true);
            }
        });
        $(".province option").each( function(i){
            if($(this).val() == proId){
                $(this).attr("selected", true);
            }else{
                $(this).removeAttr("selected", true);
            }
        });
        $(".supplier option").each( function(i){
            if($(this).val() == suppId){
                $(this).attr("selected", true);
            }else{
                $(this).removeAttr("selected", true);
            }
        });
    });

    $(".vehicleEdit").on('click', function(){
        $("#vhid").val($(this).data('id'));
        $("#vehicle").val($(this).data('title'));
        $("#price").val($(this).data('price'));
        $("#kprice").val($(this).data('kprice'));
        $("#form_title").text("Update Vehicle");
    });

    $("#btnAddVehicle").click(function(){ 
        $("#vhid").val('');
        $("#form_title").text('Add Vehicle'); 
        $("#vehicle").val('');
        $("#price").val('');
        $("#kprice").val('');
    });

    $(".miscEdit").on('click', function(){
        $("#form_title").text('Update MISC Service');
        $("#title").val($(this).data('title'));
        $("#price").val($(this).data('price'));
        $("#kprice").val($(this).data('kprice'));
        $("#nprice").val($(this).data('nprice'));
        $("#nkprice").val($(this).data('nkprice'));
        $("#eid").val($(this).data("id"));
        var counId = $(this).data('country');
        var proId = $(this).data('province');
        $("#country option").each( function(i){
            if($(this).val() == counId){
                // alert(counId
                $(this).attr("selected", true);
            }else{
                $(this).removeAttr("selected", true);
            }
        });
        $(".province option").each( function(i){
            if($(this).val() == proId){
                // alert(proId);
                $(this).attr("selected", true);
            }else{
                $(this).removeAttr("selected", true);
            }
        });
    });

    $("#addMiscService").on('click', function(){
        $("#form_title").text('Add MISC Service');
        $("input[type='text']").val('');
        $("#eid").val('');
        $("#country option").each( function(i){
            $(this).removeAttr("selected", true);
        });
        $(".province option").each( function(i){
            $(this).removeAttr("selected", true);
        });
    });

    $("#form_submitMISCService").on('submit', function(e){
        e.preventDefault();
        VhUrl = baseUrl+"misc/service/added";
        insertData(VhUrl, $("#form_submitMISCService").serialize(), $("#btnMisc"));
    });

    // Entrance fees service
    $("#form_submitEtrance").on('submit', function(e){
        e.preventDefault();
        VhUrl = baseUrl+"entrance/service/added";
        insertData(VhUrl, $("#form_submitEtrance").serialize(), $("#btnEntrance"));
    });

    // $form_submitMISCService
// add guide service 
    $("#form_submitGuideService").on('submit', function(e){
        e.preventDefault();
        VhUrl = baseUrl + "guide/service/added";
        insertData(VhUrl, $("#form_submitGuideService").serialize(), $("#btnaddTransport"));
    });

    $(".ViewLang").on('click', function(){
        $("#form_lan_title").text("Add Language for "+ $(this).data('title'));
        dataId = $(this).data('id');
        var location = $("table#tableLanuage tbody");
        getData(dataId, location);
        $("input[type='text']").val("");
        $("#service").val(dataId);
    });

    $(document).on('click', '.btnEdit', function(){
        $("#languid").val($(this).data('id'));
        $("#title").val($(this).data('name'));
        $("#price").val($(this).data('price'));
        $("#kprice").val($(this).data('kprice'));
        
    });

    $(document).on("change",".service_type", function(){
        $("#price").val($("option:selected", this).data("price"));
        $("#kprice").val($("option:selected", this).data("kprice"));
    });

    $(document).on("click","#btnSaveLange", function(e){
        dataForm = {'languid':$("#languid").val(), 'service':$("#service").val(),'title':$("#title").val(), 'price': $("#price").val(), 'kprice': $("#kprice").val(), '_token': $("input[name='_token']").val() }
        var title = $("#title");
        if ($("#title").val() != "") {
            $.ajax({
                method: "POST",
                url: $(this).data('url'),
                data: dataForm,
                dataType: "json",               
                beforeSend: function() {
                    $("#btnSaveLange").text("Loading");
                    $("#btnSaveLange").attr("disabled", "disabled");
                },
                success: function(html){
                    console.log(html.message);
                    var location = $("table#tableLanuage tbody");
                    getData($("#service").val(), location);
                    $("input[type='text']").val("");
                    $(".clearValue").val("");
                },
                error: function(xhr, status, error){
                    alert("Error!" + xhr.status);
                    return false;
                },
                complete: function() {
                    $("#btnSaveLange").html("<i class='fa fa-plus-circle'></i>&nbsp;&nbsp;Add");
                    $("#btnSaveLange").removeAttr("disabled", "disabled");
                    
                }
            });
            return false;
            e.preventDefault();
        }else{
            title.focus();   
        }
    });

    $("#btnSubmit").on('click', function(e){
        dataForm = {'fullname':$("#fullname").val(), 'username':$("#username").val(),'email':$("#email").val(), 'phone': $("#phone").val(), 'password': $("#password").val(),'con-password': $("#password").val(), '_token': $("input[name='_token']").val() }
        Url = baseUrl+"user/regiser/new"
        if($("#form_submitUser").submit()){
            insertData(Url, dataForm, $(this));    
        }
        e.preventDefault();
    });
});


$(document).ready(function(){
    // sort for resturant and entrance 
    $(".province").on("change", function(){
        var dataid = $(this).val();
        var datatype = $(this).data('type');
        var location = $("#dropdown-transervice");
        loadService(dataid, datatype, location, 'transport service');
    });


    $(".btnEditTran").on('click', function(){
        $("#tour_id").val($(this).data('id'));
        var counId = $(this).data('country');
        var proId = $(this).data('province');
        var serviceId = $(this).data('service');
        var vehicle  = $(this).data('vehicle');
        var language  = $(this).data('language');
        var tranbook = $(this).data('transport');
        var restName = $(this).data("restname");
        var restMenu = $(this).data("restmenu");
        var datatype = $(this).data('type');
        $("#pax").val($(this).data('bookpax'));
        $("#remark").val($(this).data('remark'));
        $(".start_date").val($(this).data('bookdate'));
        $(".booking_date").val($(this).data('bookingdate'))
        $("#price").val($(this).data('price'));
        $("#kprice").val($(this).data('kprice'));
        $("#phone").val($(this).data('phone'));
        $("#book_pax").val($(this).data('pax'));
        $("#remark").text($(this).data('remark'));
        $("#country option").each( function(i){
            if($(this).val() == counId){
                $(this).attr("selected", true);
            }else{
                $(this).removeAttr("selected", true);
            }
        });
        $(".province option").each( function(i){
            if($(this).val() == proId){
                $(this).attr("selected", true);
            }else{
                $(this).removeAttr("selected", true);
            }
        });
        
        $(".tran_name option").each( function(i){
            if($(this).val() == serviceId){
                $(this).attr("selected", true);
            }else{
                $(this).removeAttr("selected", true);
            }
        });
        $(".vehicle option").each( function(i){
            if($(this).val() == vehicle){
                $(this).attr("selected", true);
            }else{
                $(this).removeAttr("selected", true);
            }
        });
        $(".driver_name option").each( function(i){
            if($(this).val() == tranbook){
                $(this).attr("selected", true);
            }else{
                $(this).removeAttr("selected", true);
            }
        });
        $(".rest_name option").each( function(i){
            if($(this).val() == restName){
                $(this).attr("selected", true);
            }else{
                $(this).removeAttr("selected", true);
            }
        });
        $(".rest_menu option").each( function(i){
            if($(this).val() == restMenu){
                $(this).attr("selected", true);
            }else{
                $(this).removeAttr("selected", true);
            }
        });
        loadService($("option:selected", $(".tran_name")).val(), datatype,  $("#dropdown-vehicle"), language );
    });


    $(document).on("change", ".tran_name", function(){
        var dataid = $(this).val();
        var datatype = $(this).data('type');
        var location = $("#dropdown-vehicle");
        loadService(dataid, datatype, location, 'transport Vehicle');
    });
    $(document).on("change", ".vehicle", function(){
        var price  = $('option:selected', this).data('price');
        var kprice = $('option:selected', this).data('kprice');
        $("#price").val(price);
        $("#kprice").val(kprice);
    });

    $(document).on("change", ".rest_menu", function(){
        var price  = $('option:selected', this).data('price');
        var kprice = $('option:selected', this).data('kprice');
        $("#price").val(price);
        $("#kprice").val(kprice);
    });
    $(document).on('change', '.driver_name ', function(){
        var phone = $("option:selected", this).data('phone');
        var phone2 = $("option:selected", this).data('phone2');
        $("#phone").val(phone +'/ '+ phone2);
    });

    $('.country').on('change', function(){
        var dataid = $(this).val();
        // var datatype = 'driver_data';
        var datatype = $(this).data('type');
        // alert(datatype);
        var location = $("#dropdown-"+datatype);
        loadService(dataid, datatype, location, 'Driver Name');
    });

    // restaurant section
    $(document).on('change', '.rest_name',  function(){
        var dataid = $(this).val();
        var datatype = $(this).data('type');
        var location = $("#dropdown-rest_menu");
        loadService(dataid, datatype, location, 'Restaurant Menu');
    });


    // change booking status request

    $(document).on("click",".changeStatus", function(){
        var ifa = $(this).find("i");
        // var dataForm = {'dataid': $(this).data('id'), '_token': $("input[name='csrf-token']")};
        $.ajax({
            method: "GET",
            url: baseUrl + "changebooking/status",
            data: {'dataid': $(this).data('id'), 'datatype': $(this).data('type')},
            dataType: "json",  
            success: function(html){
                if (html.status == 1) {
                    $(ifa).removeClass("fa fa-warning (alias)");
                    $(ifa).addClass("fa fa-check-circle");
                    
                }
                if (html.status == 0) {
                    $(ifa).removeClass("fa fa-check-circle");
                    $(ifa).addClass("fa fa-warning (alias)");
                }
            },
            error: function(xhr, status, error){
                alert("Error!" + xhr.status);
                return false;
            }        
        });
        return false;
    });

});


function getData(dataid, location){
    $.ajax({
        method: "GET",
        url: baseUrl+"guide/service/"+dataid +"/language", 
        dataType: 'html',
        success: function(data){        
            $(location).html(data);            
        },
        error: function(){
            alert("Something Wrong.");
            return false;
        },
    });
    return false;
}


function loadService(dataid, datatype, location, selectedId){
    $.ajax({
        method: "GET",
        url: baseUrl+"option/findlocaiton",      
        data: "id=" + dataid+"&datatype="+ datatype+ "&selectedid="+ selectedId,    
        dataType: 'html',
        beforeSend: function() {
           $(location).html('<tr> <td colspan="4" class="text-center"><i class="fa fa-spinner fa-spin"></i></td> </tr>');
        },
        success: function(html){
            $(location).html(html);
        },
        error: function(xhr, status, error){
            alert("Error!" + xhr.status);
            return false;
        },
        complete: function() {
           
        }
    });
    return false;
}


function insertData(baseUrl, dataForm, btnSub){     
    $.ajax({
        method: "POST",
        url: baseUrl,
        data: dataForm,
        dataType: "json",               
        beforeSend: function() {
            $(btnSub).text("Loading");
            $(btnSub).attr("disabled", "disabled");
        },
        success: function(html){
            console.log(html);
            // console.log(html.message);
        },
        error: function(xhr, status, error){
            alert("Error!" + xhr.status);
            return false;
        },
        complete: function() {
            $(btnSub).text("Publish");
            $(btnSub).removeAttr("disabled");  
            $('#myModal').modal('hide');
            $(window).load();
        }
    });
    return false;
}