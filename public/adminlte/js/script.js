var baseUrl = location.protocol+'//'+location.host+"/";
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
    $(document).on("click", "#preview_layout", function(){
        var preview = $(this).find('input');
        if ($(preview).val() == "standard") {
            $(preview).val("wide");
            $("div.container").addClass("container-fluid");
            $("div.container-fluid").removeClass("container");
            $(this).html("<input type='hidden' name='preview-type' value='wide'><p>Standard View <i class='fa fa-share'></i></p>");
        }else if ( $(preview).val() == "wide") {
            $(preview).val("standard");
            $("div.container-fluid").addClass("container");
            $("div.container").removeClass("container-fluid");

            $(this).html("<input type='hidden' name='preview-type' value='standard'><p>Wide View <i class='fa fa-reply'></i></p>");
        }
    });

    $(document).on("click",".BtnHotelRate",function(t){
        btnSave=$(this);
        var e=$(this).data("id"),
        a=$(this).closest("tr"),
        o=$(a).find("td div .from_date").val(),
        i=$(a).find("td div .to_date").val(),
        n=$(a).find("td .ssingle").val(),
        r=$(a).find("td .stwin").val(),
        s=$(a).find("td .sdouble").val(),
        l=$(a).find("td .sextra").val(),
        d=$(a).find("td .schextra").val(),
        c=$(a).find("td .nsingle").val(),
        p=$(a).find("td .ntwin").val(),
        u=$(a).find("td .ndouble").val(),
        h=$(a).find("td .nextra").val(),
        f=$(a).find("td .nchextra").val();
        $("[name='_token']").val();
        dataFormHotel={eid:e,hotelId:$("#hotelId").val(),
        roomId:$("#roomId").val(),from_date:o,to_date:i,
        ssingle:n,stwin:r,sdouble:s,sextra:l,
        schextra:d,nsingle:c,ntwin:p,ndouble:u,
        nextra:h,nchextra:f},
        $.ajax({
            method:"GET",
            url:$("#updateRoomRate").val(),
            data:dataFormHotel,
            dataType:"json",
            beforeSend:function(){
                $(btnSave).html('<i class="fa fa-spinner fa-spin loading"></i>')
            },success:function(t){},
            error:function(t,e,a){
                return alert("Error!"+t.status),!1
            },complete:function(){
                $(btnSave).text("Save")
            }
        });
    });


    $(document).on("click", ".btnSaveFlight", function(e){
        urlFlight = $(this).data('url');
        btnSave = $(this);
        eid = $(this).data("id");
        form_token = $("input[name='_token']").val();
        oneway_price = $(this).closest("tr").find("td .oneway_price").val();      
        return_price = $(this).closest("tr").find("td .return_price").val();      
        oneway_nprice = $(this).closest("tr").find("td .oneway_nprice").val();      
        return_nprice = $(this).closest("tr").find("td .return_nprice").val();      
        oneway_kprice = $(this).closest("tr").find("td .oneway_kprice").val();      
        return_kprice = $(this).closest("tr").find("td .return_kprice").val();  
        dataForm = {'eid': eid, 'oneway_price':oneway_price, 'return_price':return_price, 'oneway_nprice':oneway_nprice, 'return_nprice':return_nprice, 'oneway_kprice':oneway_kprice, 'return_kprice':return_kprice};   
        e.preventDefault();
        $.ajax({
            method: "GET",
            url: $(this).data('url'),
            data: dataForm,
            dataType: "json",               
            beforeSend: function() {
                $(btnSave).text("Loading");
            },
            success: function(html){
                
            },
            error: function(xhr, status, error){
                alert("Error!" + xhr.status);
                return false;
            },
            complete: function() {
                $(btnSave).text("Save");
            }
        });
        return false;
        e.preventDefault();
    });

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

    $(document).on("input",".number_only", function(evt) {
       var self = $(this);
       self.val(self.val().replace(/[^0-9\.]/g, ''));
       if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
       {
         evt.preventDefault();
       }
    });


    $(".attachment").on('click', function(){
        $(this).addClass('selected');
    });

    $(".editprice").on('dblclick', function(){
        $(this).removeAttr('readonly');
        $(this).attr('name', $(this).data('label'));
    });  

    $(document).on("click", ".tranEditDriver", function(){
        $.ajax({
            method: "GET",
            url: baseUrl+"/transport/driver/add" ,      
            data: "eid=" + $(this).data("id"),
            dataType: 'json',
            success: function(html){                
                $("#eid").val(html.data_load.id);
                $("#driver_name").val(html.data_load.driver_name);
                $("#phone").val(html.data_load.phone);
                $("#phone2").val(html.data_load.phone2);
                $("#email").val(html.data_load.email);
                $("#email2").val(html.data_load.email2);
                $("#address").val(html.data_load.address);
                $("#intro").val(html.data_load.intro);
                loadService(html.data_load.province_id, "transport_service", $("#dropdown-supplier"), 'transport service');

                $("#country option").each( function(i){
                    if($(this).val() == html.data_load.country_id){
                        $(this).attr("selected", true);
                    }else{
                        $(this).removeAttr("selected", true);
                    }
                });
                loadService(html.data_load.country_id, "country-driver", $("select#dropdown-data"), html.data_load.province_id);
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
  

    $(document).on("change", "#addVehicleTransport", function(){
        getVehicle($(this).val(), $("option:selected", this).data("transport"));
        $("#transport_id").val($("option:selected", this).data("transport"));
    });

    $(document).on("click", ".btnEditVehicle", function(){
        var bus_type = $(this).data('bus_type');
        loadService($(this).data('id'), $(this).data("type"), $("select#addVehicleTransport"), "Vehicle", bus_type);
    });

    $(document).on("click", ".btnAddVehicle", function(){
        $.ajax({
            method: "GET",
            url: baseUrl+"/transport/edit" ,      
            data: "eid=" + $(this).data("id"),
            dataType: 'json',
            success: function(html){ 
                loadService(html.data_load.province_id, "transport_service", $("ul#dropdown-supplier"), html.serviceID);
                loadService(html.data_load.country_id, "country", $("select#dropdown-data"), html.data_load.province_id);
                $("#eid").val(html.data_load.id);
                $("#service_name").val(html.data_load.title);
                $("#country option").each( function(i){
                    if($(this).val() == html.data_load.country_id){
                        $(this).attr("selected", true);
                    }else{
                        $(this).removeAttr("selected", true);
                    }
                });                
            },
            error: function(){
                alert("Something Wrong.");
                return false;
            }
        });
        return false;
    });

    $(document).on("click",".btnTranUpdate",  function(){
        $.ajax({
            method: "GET",
            url: baseUrl+"transport/edit" ,      
            data: "eid=" + $(this).data("id"),
            dataType: 'json',
            success: function(html){ 
                loadService(html.data_load.province_id, "transport_service", $("#dropdown-transport_service"), html.serviceID);

                loadService(html.data_load.country_id, "country", $("select#dropdown-data"), html.data_load.province_id);
                $("#eid").val(html.data_load.id);
                $("#service_name").val(html.data_load.title);
                $("#country option").each( function(i){
                    if($(this).val() == html.data_load.country_id){
                        $(this).attr("selected", true);
                    }else{
                        $(this).removeAttr("selected", true);
                    }
                });
            },
            error: function(){
                alert("Sometshing Wrong.");
                return false;
            }
        });
        return false;
    });

    $('.country').on('change', function(){
        var dataid = $(this).val();
        var datatype = $(this).data('type');
        var location = $("#dropdown-"+datatype);
        var title = $(this).data('title');
        var bus_type = $(this).data('bus_type')
        loadService(dataid, datatype, location, title, bus_type);
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
                dataType: 'json',
                beforeSend: function() {
                    $(this).css('pointer-events','none');
                },
                success: function(html){
                    console.log(html);
                    remoeRow.css({'background-color':'#9E9E9E'});
                    remoeRow.fadeOut(500, function(){
                        $(remoeRow).remove();
                    });                 
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

    $(document).on("click", ".btnRefresh", function(){
        if(confirm("Your want to clear this service?")){       
            $.ajax({
                method: "GET",
                url: baseUrl+"option/remove",    
                data: "type="+ $(this).data("type") +"&dataId=" + $(this).data('id') ,          
                dataType: 'json',
                beforeSend: function() {
                    $(this).css('pointer-events','none');
                },
                success: function(html){
                
                    location.reload();
                },
                error: function(){
                    alert("Something Wrong.");
                    return false;
                },
                complete: function() {
                    $(this).css('pointer-events','auto');
                    location.reload();
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
        TourUrl = baseUrl+"create/tourtype";
        e.preventDefault();
        insertData(TourUrl, $("#form_submitTourType").serialize(), $("#btnTourTypeSave"));
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
        insertData(TourUrl, $("#form_submittransportService").serialize(), $("#btnSubmitTran"));
    });

    $("#form_submitVehicle").on('submit', function(e){
        e.preventDefault();
        VhUrl = baseUrl+"transport/added/vehicle";
        insertData(VhUrl, $("#form_submitVehicle").serialize(), $("#btnAddLanguage"));
        getVehicle($("option:selected",$("#addVehicleTransport")).val(), $("#transport_id").val());
    });

    

    $(".businessType").on("change", function(){
        var dataid = $(this).val();
        var datatype = $(this).data('type');
        var location = $("select#CategoryType_data");
        loadService(dataid, datatype, location, 'get category type for create document');
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
        $(".wrapper-filter-item ul#MainUl").html('<li><input type="hidden" name="golf_name" value="'+$(this).data('entrance_id')+'"><span>'+$(this).data('entrance')+'</span></li>');
        $("#form_title").text($(this).data('title'));
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
        $("#form_title").text($(this).data('title'));
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
        var conID = $(this).data("con");
        getData(dataId, location);
        $("input[type='text']").val("");
        $("#service").val(dataId);


        loadService(conID, "guide_by_country", $("#dropdown-guide_by_country"), "get sort guide update");
        $("#country-guide option").each( function(i){
            if($(this).val() == conID){
                $(this).attr("selected", true);
            }else{
                $(this).removeAttr("selected", true);
            }
        });
    });

    $(document).on('click', '.btnEdit', function(){
        $("#languid").val($(this).data('id'));
        $("#title").val($(this).data('name'));
        $("#price").val($(this).data('price'));
        $("#kprice").val($(this).data('kprice'));
        loadService($("option:selected", $("#country-guide")).val(), "guide_by_country", $("#dropdown-guide_by_country"), $(this).data("sup"));
    });

    $(document).on("change", ".language", function(){
        var datatype = $(this).data("type");
        $("#phone").val("");
        $("#price").val("");
        $("#kprice").val("");
        var guideServiceId = $("option:selected", this).data('sup');
        var dataid = $("option:selected", this).val();
        loadService(dataid, datatype, $("#dropdown-language-data"), guideServiceId);
    });

    $(document).on("change", ".guide_name", function(){
        $("#price").val($("option:selected", this).data("price"));
        $("#kprice").val($("option:selected", this).data("kprice"));
        var phone = $("option:selected", this).data('phone');
        var phone2 = $("option:selected", this).data('phone2');
        $("#phone").val(phone +'/ '+ phone2);
    });

    $(document).on("change",".service_type", function(){
        $("#price").val($("option:selected", this).data("price"));
        $("#kprice").val($("option:selected", this).data("kprice"));
    });

    $(document).on("submit","#form_submitGlanguage", function(e){
        dataForm = $(this).serialize();
        if ($("#title").val() != "") {
            $.ajax({
                method: "POST",
                url: $(this).attr('action'),
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
    $(".province").on("change", function(){
        var type = $(this).data('type');
        var title = $(this).data('title');        
        if (type == 'entrance_fee') {
            loadService($(this).val(), "transport_service", $("#dropdown-transport_service"), "transportation");
        }
        loadService($(this).val(), type, $("#dropdown-"+type), title);
    });

    $(".province_transport").on("change", function(){
        Url_filter = baseUrl+"option/find";
        dataFilter(Url_filter, $(this).val(), "transport_service", $("#dropdown-transport_service"), "transport_service_by_province");
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
        var bus_type = $(this).data('bus_type');
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
        loadService(counId, "country", $("#dropdown-data"), proId, "apply_transport", bus_type);

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
        if (datatype == 'entrance_fee') {
            loadService($(this).data("province"), 'transport_service',  $("#dropdown-transport_service"), restName, bus_type);
        }

        if (datatype == "rest_name") {
            loadService($(this).data("province"), datatype,  $("#dropdown-transervice"), restName, bus_type);
            loadService(restName, "rest_menu", $("#dropdown-rest_menu"), restMenu);
        }else{
            loadService($(this).data("province"), datatype,  $("#dropdown-transervice"), restMenu, bus_type);
            loadService(restMenu, "apply_language", $("#dropdown-rest_menu"), language);
            loadService(language, "language-supplier", $("#dropdown-language-data"), tranbook, bus_type);
            
        }

        // loadService($("option:selected", $(".tran_name")).val(), datatype,  $("#dropdown-vehicle"), language );
    });

    $(document).on("change", ".tran_name", function(){
        var dataid = $(this).val();
        var datatype = $(this).data('type');
        var location = $("#dropdown-rest_menu");
        
        loadService(dataid, datatype, location, "transport service");
    });

    $(document).on("change", ".tran_service", function(){
        var dataid = $(this).val();
        var datatype = $(this).data('type');
        var location = $("#dropdown-vehicle");
        var transportID  = $("option:selected", $(".transport")).val();
        loadService(dataid, datatype, location, transportID);
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

    $(document).on("change", ".transport", function(){
        var phone = $("option:selected", this).data('phone');
        var phone2 = $("option:selected", this).data('phone2');
        var type = $(this).data('type');
        var title = $(this).data('title');
        var bus_type = $(this).data('bus_type');
        $("#phone").val(phone +'/ '+ phone2);
        loadService($(this).val(), type, $("#dropdown-"+type), title);
        loadService($(this).val(), 'tran_service', $("#dropdown-transervice"), title, bus_type);
    });

    $(document).on("change", ".driver_name", function(){
        var phone = $("option:selected", this).data('phone');
        var phone2 = $("option:selected", this).data('phone2');
        $("#phone2").val(phone +'/ '+ phone2);
    });


    $(document).on('change', '.rest_name',  function(){
        var type = $(this).data('type');
        loadService($(this).val(), type, $("#dropdown-"+type), 'Restaurant Menu');
    });

    $(document).on("click",".changeStatus", function(){
        var ifa = $(this).find("i");
        // var dataForm = {'dataid': $(this).data('id'), '_token': $("input[name='csrf-token']")};
        $.ajax({
            method: "GET",
            url: baseUrl + "changebooking/status",
            data: {'dataid': $(this).data('id'), 'datatype': $(this).data('type')},
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            cache: false,
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

    $("#company_form").submit( function(e){
        e.preventDefault();
        var CompanyUrl = baseUrl+"company/";
        insertData(CompanyUrl, $("#company_form").serialize(), $("#btnAddCompany"));
    });

    $(document).on("keyup change", "#data_filter", function(){
        var Url_filter = $(this).data("url");
        var datatype = $(this).data("type");
        var location = $("#dropdown-"+datatype);
        if(datatype == "guide_by_country"){
            var conlocationId = $("option:selected", $("#country-guide")).val();
        }else{
            var conlocationId = $("option:selected", $(".province_transport")).val();
        }
        dataFilter(Url_filter, $(this).val(), datatype, location, conlocationId);
    });


    $(".submitDiscount").on("click", function(e){
        
        var row = $(this).closest("tr").find("td.addOption .wrapping-discount");
        var discount = row.find("div div.value").find('input').val();
        var eid = row.find("div div.value").find("input[name='eid']").val();
        var token = row.find("div div.value").find("input[name='_token']").val();
        var current_amount = row.find("div div.value").find("input[name='current_amount']").val();
        var book_day = row.find("div div.value").find("input[name='book_day']").val();
        var no_of_room = row.find("div div.value").find("input[name='no_of_room']").val();
        var dataForm = {
            "_token": token, 
            "eid": eid, "discount": discount, 
            "current_amount": current_amount, 
            'book_day': book_day,
            'no_of_room': no_of_room
        }

        $.ajax({
            method: "POST",
            url: baseUrl+"AddHotelDiscount",
            data: dataForm,
            dataType: "json",  
            beforeSend: function() {
                $(this).text("saving");
                // $(btnSub).attr("disabled", "disabled");
            },
            success: function(html){
                // row.fadeOut();
                // $("html, body").animate({ scrollTop: 0 }, "slow");
                // messageData = '<div class="col-md-12"> <div class="alert alert-dismissible fade show '+html.messagetype+'" role="alert" style="position: relative; padding-left: 53px;"><i class="fa '+html.status_icon+'" style="font-size: 37px; position:absolute; top: 5px; left: 10px;"></i><div><span> '+ html.message+'  </span></div><p></p><button type="button" class="close" data-dismiss="alert" aria-label="Close" style=" position: absolute;right: 7px;top: 13px;"><span aria-hidden="true" style="font-size: 22px;padding: 1px 8px;">&times;</span></button></div></div>';
                // $(".notify-message").html(messageData);
            },
            error: function(xhr, status, error){
                alert("Error!" + xhr.status);
                return false;
            },
            complete: function(xhr, status) {
                row.fadeOut();
                // $(btnSub).text("Publish");
                // $(btnSub).removeAttr("disabled");  
                // $('#myModal').modal('hide');
            }
        });
        // return false;
        e.preventDefault();

    });
});


function getVehicle(sup_id, tran_id){
    $.ajax({
        method: "GET",
        url: baseUrl+"transport/vihecle",      
        data: "sup_id="+ sup_id+"&tran_id="+ tran_id,
        dataType: 'json',
        success: function(html){ 
            dataHtml = "";
            if (html.query_data) {
                $.each(html.query_data, function(index){
                    dataHtml += "<tr><td>"+this.name+"</td><td class='text-right'>"+this.price+"</td><td class='text-right'>"+this.kprice+"</td><td class='text-right'><a href='javascript:void(0)' class='btnEdit'  data-id='"+this.tran_id+"' data-name='"+(this.name)+"'   data-price='"+this.price+"' data-kprice='"+this.kprice+"' title='Remove this?'><i style='padding:1px 2px;' class='btn btn-info btn-xs fa fa-pencil-square-o'></i></a>&nbsp;<a href='javascript:void(0)' class='RemoveHotelRate' data-id='"+this.tran_id+"' title='Remove this?' data-type='vehicle'><i style='padding:1px 2px;' class='btn btn-danger btn-xs fa  fa-minus-circle'></i></a></td></tr>";
                }); 
            }else{
                dataHtml = "<tr><td colspan='4' class=text-center>Result Not Found ...!</td></tr>";
            }
            $("tbody#TransportDataList").html(dataHtml);
        },
        error: function(){
            alert("Something Wrong.");
            return false;
        }
    });
    return false;
}

function dataFilter(dataUrl, filterName, type, location, FilterType){
    $.ajax({
        method: "GET",
        url: dataUrl, 
        data: "dataName=" + filterName+"&type="+ type + "&filterType="+ FilterType, 
        dataType: 'html',
        beforeSend: function() {
           $("#data_filter").html('<i class="fa fa-spinner fa-spin loading"></i>');
        },
        success: function(html){
            $("#data_filter").next().remove();
            $(location).html(html);
        },
        error: function(xhr, status, error){
            alert("Error!" + xhr.status);
            return false;
        },
        complete: function() {
           $("#data_filter").next().remove();
        }
    });
    return false;
}

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

function loadService(dataid, datatype, location, selectedId, bus_type =0){
    $.ajax({
        method: "GET",
        url: baseUrl+"option/findlocaiton",      
        data: "id=" + dataid+"&datatype="+ datatype+ "&selectedid="+ selectedId + '&bus_type='+bus_type,    
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
            $("html, body").animate({ scrollTop: 0 }, "slow");
            messageData = '<div class="col-md-12"> <div class="alert alert-dismissible fade show '+html.messagetype+'" role="alert" style="position: relative; padding-left: 53px;"><i class="fa '+html.status_icon+'" style="font-size: 37px; position:absolute; top: 5px; left: 10px;"></i><div><span> '+ html.message+'  </span></div><p></p><button type="button" class="close" data-dismiss="alert" aria-label="Close" style=" position: absolute;right: 7px;top: 13px;"><span aria-hidden="true" style="font-size: 22px;padding: 1px 8px;">&times;</span></button></div></div>';
            $(".notify-message").html(messageData);
        },
        error: function(xhr, status, error){
            alert("Error!" + xhr.status);
            return false;
        },
        complete: function(xhr, status) {
            $(btnSub).text("Publish");
            $(btnSub).removeAttr("disabled");  
            $('#myModal').modal('hide');
        }
    });
    return false;
}