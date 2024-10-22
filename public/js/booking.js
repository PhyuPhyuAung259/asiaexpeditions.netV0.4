$(function(){
    var baseUrl = location.protocol+'//'+location.host+"/";
    var i = 1;
    //{ =====================================addictional booking ====================================================={
    //add booking row 
    $(".add_row").click(function(){
        var type = $(this).data("type");
        var data_url = $(this).data("url");     
        $.ajax({
            type: "get",
            url: data_url,
            data: "i=" + i +"&type=" + type,
            datatype: 'html',
            beforeSend: function() {
            // setting a timeout
                $(this).attr('disabled','disabled');
                $("#LoadingRow").css({'display':'block'});
                $("#placeholder").addClass('loading');
            },
            success: function(html){
                i++;
                $("#add_book_project tbody tr:last").after(html);
                // return false;
            },
            error: function(){
                alert("Something Wrong.");
                return false;
            },
            complete: function() {
                $("#LoadingRow").css({'display':'none'});
                // $(this).removeAttr('disabled');
            }
        });
        return false;
    });
    $(document).on('click', ".reset_booking",function(){
        if (confirm("Are you sure, you want to reset all booking?")) {
            $("#add_book_project tbody tr").fadeOut(500, function(){
                $("#add_book_project tbody").html("<tr></tr>");
                calculate_sub_total(1);
            });
            return false;
        }else{
            return false;
        }
    });
    $(document).on("click",".RemoveBook", function(){
        var remoeRow = $(this).closest("tr");
        if(confirm("Are you sure you want to delete this?")){         
            remoeRow.css({'background-color':'#9E9E9E'});
            remoeRow.fadeOut(500, function(){
                $(remoeRow).remove();
                calculate_sub_total(1);
            });
        }else{
            return false;
        }
    });
    // choose country for province
    $(document).on('change', '.country', function(){
        $(".no_country", this).remove();
        var countryId = $(this).val();
        var datatype = $(this).data('type');
        var proOfBustype = $(this).data("pro_of_bus");
        var proOfBusid = $(this).data('pro_of_bus_id');
        if(datatype == "country_rest"){
            var location = $("#dropdown-restaurant");
            loadData(countryId, datatype, location, proOfBustype, proOfBusid);
        }else{
            var location = $(this).closest("tr").find("td.province_container").find("select.province");
            loadData(countryId, datatype, location, proOfBustype, proOfBusid);
        }
    });

    // choose province for tour program
    $(document).on('change', '.province', function(){
        var provinceId = $(this).val();
        var datatype = $(this).data('type');
        if (datatype == "pro_tour"){
            var tour = $(this).closest("tr").find("td.tour_container").find("select.tour");
            loadData(provinceId, datatype, tour, 'Tour');
        }else if (datatype == "pro_hotel"){
            var hotel = $(this).closest("tr").find("td.hotel_container").find("select.hotel");
            loadData(provinceId, datatype, hotel, 'Hotel');
        }else if (datatype == "pro_flight"){
            flight = $(this).closest("tr").find("td.province_container").find("select.city_destination");
            $(this).closest("tr").find("td.flight_container").find("select.flightno").html("<option value=''>Flight No</option>");
            $(this).closest("tr").find("td.flight_container").find("select.ticketing").html("<option value=''>Ticketing</option>");

            loadData(provinceId, datatype, flight, 'Flight Number');

        }else if (datatype == "pro_Book_flight"){
            datatype = "pro_flight";
            flight = $("select#city_destination");
            loadData(provinceId, datatype, flight, 'Flight Number');
        }else if (datatype == "river-cruise"){
            $("#no_program", this).remove();
            var cruise = $(this).closest("tr").find("td.cruise_container").find("select.cruise-program");
            loadData(provinceId, datatype, cruise, 'Cruise Program');
        }else if (datatype == "pro_golf"){
            var golf = $(this).closest("tr").find("td.golf_container").find("select.golf");
            loadData(provinceId, datatype, golf, 'Golf');
        }
    });

    $(document).on("change", ".city_destination", function() {
        if ($(this).data("type") == "single_city_destination") {
            flightno_loc = $("select#dropdown-FlightNo");
            flightProvince = $("select.province").val();
            datatype = "city_destination";
        }else{
            datatype = $(this).data('type');
            flightno_loc = $(this).closest("tr").find("td.flight_container").find("select.flightno");
            flightProvince = $(this).closest("tr").find("td.province_container").find("select.province").val();
        }
        loadData($(this).val(), datatype, flightno_loc, flightProvince);
    });

    // choose tour and pax
    $(document).on('change', '.tour', function(){
        var tourId = $(this).val();
        var datatype = $(this).data('type');
        var Pax = $(this).closest("tr").find("td.pax_container").find("select.pax_no");
        loadData(tourId, datatype, Pax, 'Pax');
    });

    $(document).on('change', '.river-cruise', function(){
        $("#no_program", this).remove();
        var tourId = $(this).val();
        var datatype = $(this).data('type');
        var cruiseprogram = $(this).closest("tr").find("td.cruise_container").find("select.cruise-program");
        loadData(tourId, datatype, cruiseprogram, 'Cruise Program');
    });

    $(document).on('change', '.pax_no', function(){
        $(".no_select", this).remove();
        var tour_pax = $(this).val();
        var tour_price = $(this).find("option:selected").data('price');
        var tour_nprice = $(this).find("option:selected").data('nprice');
        var pax_price = $(this).closest("tr").find('td.pax_price span');
        var pax_price_hidden = $(this).closest("tr").find('td.pax_price #pax_price');
        var pax_nprice_hidden = $(this).closest("tr").find('td.pax_price #tour_nprice');
        var pax_total = $(this).closest("tr").find('td.pax_total span');
        var total_hide_amount = $(this).closest("tr").find('td.pax_total #tour_amount');
        pax_price.text(tour_price);
        pax_price_hidden.val(tour_price);
        pax_nprice_hidden.val(tour_nprice);
        total_hide_amount.val(parseFloat(tour_price) * parseFloat(tour_pax));
        pax_total.text((parseFloat(tour_price) * parseFloat(tour_pax)).toFixed(2));
        calculate_sub_total(1);
    });


    $(document).on('change', '.flight', function(){
        var dataId = $(this).val();
        var datatype = $(this).data('type');
        var flightno = $(this).closest("tr").find("td.flight_container").find("select.flightno");
        loadData(dataId, datatype, flightno, 'flightNo');
    });

    $(document).on('change', '.flightno', function(){
        var dataId = $(this).val();
        var datatype = $(this).data('type');
        var ticketing = $(this).closest("tr").find("td.flight_container").find("select.ticketing");
        loadData(dataId, datatype, ticketing, 'Flight No.');
    });

    $(document).on('change', '.ticketing', function(){
        $(".no_agent", this).remove();
        var book_flight_way = $(this).closest("tr").find("td.bookway_container").find("select.bookway");
        var book_flight_pax = $(this).closest("tr").find("td.fagent_container").find("select.flightPax");
        var flight_pax = $("option:selected", book_flight_pax).val();
        var bookway = $("option:selected", book_flight_way).val();
        var oneway_price = $("option:selected", this).data('oneway');
        var return_price = $("option:selected", this).data('return');
        var oneway_nprice = $("option:selected", this).data('noneway');
        var return_nprice = $("option:selected", this).data('nreturn');
        var oneway_kprice = $("option:selected", this).data('koneway');
        var return_kprice = $("option:selected", this).data('kreturn');
        var text_price = $(this).closest("tr").find("td.pax_price span");
        var text_hide_price = $(this).closest("tr").find("td.pax_price #pax_price");
        var text_hide_nprice = $(this).closest("tr").find("td.pax_price #pax_nprice");
        var text_hide_kprice = $(this).closest("tr").find("td.pax_price #pax_kprice");
        var text_amount = $(this).closest("tr").find("td.pax_total span");
        var text_amount_hide = $(this).closest("tr").find("td.pax_total #flight_amount");

        if (bookway == "Oneway") {
            text_price.text(oneway_price);
            text_hide_price.val(oneway_price);   
            text_hide_nprice.val(oneway_nprice);
            text_amount.text((oneway_price * flight_pax).toFixed(2)); 
            text_amount_hide.val(oneway_price * flight_pax);
            text_hide_kprice.val(oneway_kprice * flight_pax);      
        }else{
            text_price.text(return_price);
            text_hide_price.val(return_price);
            text_hide_nprice.val(return_nprice);
            text_amount.text((return_price * flight_pax).toFixed(2));
            text_amount_hide.val(return_price * flight_pax);
            text_hide_kprice.val((return_kprice * flight_pax).toFixed(2));
        }
        calculate_sub_total(1);
    });

    $(document).on('change', '.flightPax', function(){
        var book_price = $(this).closest("tr").find("td.pax_price").find("#pax_price").val();  
        var text_amount = $(this).closest("tr").find("td.pax_total span");
        var text_amount_hide = $(this).closest("tr").find("td.pax_total #flight_amount");
        text_amount_hide.val(book_price * $(this).val());
        text_amount.text((book_price * $(this).val()).toFixed(2));
    });

    $(document).on('change', '.book_FlightPax', function(){
        flighPax = $(this).val();
        var book_price = $("#book_price").val();
        $("#book_amount").val((flighPax * book_price).toFixed(2));
    });
    
    $(document).on('change', '.bookway', function(){
        var book_flight_pax = $(this).closest("tr").find("td.fagent_container").find("select.flightPax");
        var flight_pax = $("option:selected", book_flight_pax).val();
        var book_flight_agent = $(this).closest("tr").find("td.flight_container").find("select.ticketing");
        var oneway_price = $("option:selected", book_flight_agent).data('oneway');
        var return_price = $("option:selected", book_flight_agent).data('return');
        var oneway_nprice = $("option:selected", book_flight_agent).data('noneway');
        var return_nprice = $("option:selected", book_flight_agent).data('nreturn');
        var oneway_kprice = $("option:selected", book_flight_agent).data('koneway');
        var return_kprice = $("option:selected", book_flight_agent).data('kreturn');

        var text_price = $(this).closest("tr").find("td.pax_price span");
        var text_hide_price = $(this).closest("tr").find("td.pax_price #pax_price");
        var text_hide_nprice = $(this).closest("tr").find("td.pax_price #pax_nprice");
        var text_hide_kprice = $(this).closest("tr").find("td.pax_price #pax_kprice");
        var text_amount = $(this).closest("tr").find("td.pax_total span");
        var flight_amount_hide = $(this).closest("tr").find("td.pax_total #flight_amount");
        // alert(return_price);
        if ($(this).val() == "Oneway") {           
            text_price.text(oneway_price);
            text_hide_price.val(oneway_price);   
            text_amount.text((flight_pax * parseFloat(oneway_price)).toFixed(2));    
            flight_amount_hide.val(flight_pax * parseFloat(oneway_price));
            text_hide_nprice.val(oneway_nprice); 
            text_hide_kprice.val(return_kprice);   
        }else{
            text_price.text(return_price);
            text_hide_price.val(return_price);
            text_amount.text((flight_pax * parseFloat(return_price)).toFixed(2));
            flight_amount_hide.val(flight_pax * parseFloat(return_price));
            text_hide_nprice.val(return_nprice); 
            text_hide_kprice.val(return_kprice); 
        }
        calculate_sub_total(1);
    });

    $(document).on('change', '.golf', function(){
        var dataId = $(this).val();
        var datatype = $(this).data('type');
        var golf_service = $(this).closest("tr").find("td.golf_container").find("select.golf_service");
        loadData(dataId, datatype, golf_service, 'Golf Service');
    });    

    $(document).on('change', '.golf_service', function(){
        $("#no_golf_service", this).remove();
        var dataId = $(this).val();
        var datatype = $(this).data('type');
        var price   = $("option:selected", this).data('price');
        var nprice  = $("option:selected", this).data('nprice');
        var kprice  = $("option:selected", this).data('kprice');
        var nkprice  = $("option:selected", this).data('nkprice');
        var golfPax = $(this).closest("tr").find("td.golf_pax_container").find("select.golfPax");
        var golfpaxNo = jQuery("option:selected", golfPax).val();
        var text_price = $(this).closest("tr").find("td.pax_price span").text(price);
        $(this).closest("tr").find("td.pax_price #golfprice").val(price);
        $(this).closest("tr").find("td.pax_price #golfnprice").val(nprice);
        $(this).closest("tr").find("td.pax_price #golfkprice").val(kprice);
        $(this).closest("tr").find("td.pax_price #golfnkprice").val(nkprice);
        
        $(this).closest("tr").find("td.pax_total span").text((parseFloat(price) * parseFloat(golfpaxNo)).toFixed(2));
        $(this).closest("tr").find("td.pax_total #golfamount").val((parseFloat(price) * parseFloat(golfpaxNo)).toFixed(2));
        $(this).closest("tr").find("td.pax_total #golfnamount").val((parseFloat(nprice) * parseFloat(golfpaxNo)).toFixed(2));
        $(this).closest("tr").find("td.pax_total #golfkamount").val((parseFloat(kprice) * parseFloat(golfpaxNo)).toFixed(2));
        $(this).closest("tr").find("td.pax_total #golfnkamount").val((parseFloat(nkprice) * parseFloat(golfpaxNo)).toFixed(2));
        // var GolfTotal = 
        // text_hide_amount.val(GolfTotal);
        // text_amount.text(GolfTotal);
        calculate_sub_total(1);
    });
    
    $(document).on('change', '.golfPax', function(){
        var text_get_price = $(this).closest("tr").find("td.pax_price #golfprice").val();
        var text_get_nprice = $(this).closest("tr").find("td.pax_price #golfnprice").val();
        var text_get_kprice = $(this).closest("tr").find("td.pax_price #golfkprice").val();
        var text_get_nkprice = $(this).closest("tr").find("td.pax_price #golfnkprice").val();
        var GolfTotal = (parseFloat(text_get_price) * parseFloat($(this).val())).toFixed(2);
        var GolfnTotal = (parseFloat(text_get_nprice) * parseFloat($(this).val())).toFixed(2);
        var GolfkTotal = (parseFloat(text_get_kprice) * parseFloat($(this).val())).toFixed(2);
        var GolfnkTotal = (parseFloat(text_get_nkprice) * parseFloat($(this).val())).toFixed(2);
        $(this).closest("tr").find("td.pax_total span").text(GolfTotal);
        $(this).closest("tr").find("td.pax_total #golfamount").val(GolfTotal);
        $(this).closest("tr").find("td.pax_total #golfnamount").val(GolfnTotal);
        $(this).closest("tr").find("td.pax_total #golfkamount").val(GolfkTotal);
        $(this).closest("tr").find("td.pax_total #golfnkamount").val(GolfnkTotal);
    });
    
});


// edit project booking section start
$(function(){
    $(document).on('change', '.book_City', function(){
        var datatype = $(this).data('type');
        var tourId = $(this).val();
        var location = $("select#dropdown-booking");
        loadData(tourId, datatype, location, 'booking tour');
        $("select#dropdown-tourPax").html("");
        $("#book_price").val('00.0');
        $("#book_amount").val('00.0');
    });

    // click option for choose booking supplier / Service name
    $(document).on('change', '.booking_name', function(){
        var datatype = $(this).data('type');
        var tourId = $(this).val();
        if(datatype == "book_tour"){
            var location = $("select#dropdown-tourPax");
            loadData(tourId, datatype, location, 'Booking Tour');
            var paxSelected = $("option:selected", location).data('sprice');
            $("#book_price").val('00.0');
            $("#book_amount").val('00.0');
        }else if(datatype == "airline")
        {
            var location = $("select#dropdown-FlightNo");
            loadData(tourId, datatype, location, 'booking Flight');
            $("select#dropdown-TicketingAgent").html('<option>No Agent</option>');
            $("#book_price").val('00.0');
            $("#book_amount").val('00.0');
         }else if(datatype == "river-cruise")
        {
            var location = $("select#dropdown-Cruise_program");
            loadData(tourId, datatype, location, 'booking Cruise Program');
            $("select#dropdown-TicketingAgent").html('<option>No Agent</option>');
            $("#book_price").val('00.0');
            $("#book_amount").val('00.0');
        }else if (datatype == "book_golf") 
        {
            var location = $("select#dropdown-golfservice");
            loadData(tourId, datatype, location, 'booking Golf');
            $("#book_price").val('00.0');
            $("#book_amount").val('00.0');
        }
    });

    $(document).on('change', '.book_pax', function(){
        var bookPax = $(this).val();
        var PaxType = $(this).data('type');
        if (PaxType == "golf_pax") {
            var book_price = $("#book_price").val();
            $("#book_amount").val((parseFloat(book_price) * bookPax).toFixed(2));
        }
    });

    $(document).on('change', '.bookTourPax', function(){
        $("#no_tourPax", this).remove();
        var tour_price = $("#book_price");
        var tour_nprice = $("#book_nprice");
        var tour_amount = $("#book_amount");
        var sprice = $('option:selected',this).data('price');
        var nprice = $('option:selected',this).data('nprice');
        var tourPax = $(this).val();
        tourAmoun = parseFloat(sprice) * parseFloat(tourPax);
        tour_price.val(sprice);
        tour_nprice.val(nprice);
        tour_amount.val((tourAmoun).toFixed(2));
    });

    // flight edit booking
    $(document).on('change', '.bookGolfService', function(){
        var text_price = $("option:selected", this).data('price');
        var text_nprice = $("option:selected", this).data('nprice');
        var GPax = $(".book_pax").val();
        $("#book_price").val(text_price);
        $("#book_nprice").val(text_nprice);
        $("#book_amount").val( (parseFloat(text_price) * GPax).toFixed(2));
    });

    $(document).on('change', '.Book_FlightNo', function(){
        var datatype = $(this).data('type');
        var tourId = $(this).val();
        var location = $("select#dropdown-TicketingAgent");
        loadData(tourId, datatype, location, 'Flight Agent');
        $("#book_price").val('00.0');
        $("#book_amount").val('00.0');
    });

    $(document).on('change', '.book_FlightAgent', function(){
        $(".no_data", this).remove();
        var pax_price = $(".book_FlightPax");
        var FlightPax = $('option:selected', pax_price).val();
        var oneway_price = $("option:selected", this).data('oneway');
        var return_price = $("option:selected", this).data('return');
        var oneway_nprice = $("option:selected", this).data('noneway');
        var return_nprice = $("option:selected", this).data('nreturn');
        var oneway_kprice = $("option:selected", this).data('koneway');
        var return_kprice = $("option:selected", this).data('kreturn');
        var bookway = $('input[name=book_way]:checked').val();
        if (bookway == "Return") {
            $("#book_price").val(return_price);
            $("#book_nprice").val(return_nprice);
            $("#book_kprice").val(return_kprice);
            $("#book_amount").val((return_price * FlightPax).toFixed(2));
        }else{
            $("#book_price").val(oneway_price);
            $("#book_nprice").val(oneway_nprice);
            $("#book_kprice").val(oneway_kprice);
            $("#book_amount").val((oneway_price * FlightPax).toFixed(2));
        }
    });

    $(".bookway").on("click", function(){
        var price_selected = $(".book_FlightAgent");
        var pax_price = $(".book_FlightPax");
        var FlightPax = $("option:selected", pax_price).val();
        var oneway_price = $("option:selected", price_selected).data('oneway');
        var return_price = $("option:selected", price_selected).data('return');
        var oneway_nprice = $("option:selected", price_selected).data('noneway');
        var return_nprice = $("option:selected", price_selected).data('nreturn');
        var oneway_kprice = $("option:selected", price_selected).data('koneway');
        var return_kprice = $("option:selected", price_selected).data('kreturn');
        if ($(this).val() == "Return") {
            $("#book_price").val(return_price);
            $("#book_nprice").val(return_nprice);
            $("#book_kprice").val(return_kprice);
            $("#book_amount").val((return_price * FlightPax).toFixed(2));
        }else{
            $("#book_price").val(oneway_price);
            $("#book_nprice").val(oneway_nprice);
            $("#book_kprice").val(oneway_kprice);
            $("#book_amount").val((oneway_price * FlightPax).toFixed(2));
        }
    });
});



function loadData(id, datatype, location, title = '', bus_type = 1){
    $.ajax({
        method: "GET",
        url: baseUrl+"option/findlocaiton",      
        data: "id=" + id+"&datatype="+ datatype+ "&title="+ title+ "&bus_type="+ bus_type,   
        dataType: 'html',
        success: function(data){                
            $(location).html(data);
            calculate_sub_total(1);
        },
        error: function(){
            alert("Something Wrong.");
            return false;
        },
    });
    return false;
}
// Calculate total 
function calculate_sub_total(animate_option){
    var sub_total = 0;
    $(".pax_total span").each(function(){
        sub_total += parseFloat($(this).text());
    });
    $("#sub_total_value").text(sub_total.toFixed(2));
    $("#project_amount").val(sub_total.toFixed(2));
    if(animate_option > 0){
        $(".sub_total_container").animate({backgroundColor: "#428bca", color:"white"}, 200, function(){$(this).animate({backgroundColor: "#ecf0f5", color:"black"}, 100)});
    }
}
//} =====================================end addictional booking =====================================================