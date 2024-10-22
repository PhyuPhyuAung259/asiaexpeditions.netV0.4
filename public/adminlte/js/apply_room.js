$(function(){
$("#confirm-booking").attr("disabled", true);
	$(document).on('change', "#checkRoom", function(){
		if( $(this).is(':checked')){
		}else{
			var RoomChecked = $(this).closest("tr").find('td.container_room_no').find("#no_of_room");
			$(this).closest("tr").find("td.container_category").each( function(i, v){
				if($("#roomCat", v).prop("checked")){
					$("input#roomCat", v).prop('checked', false);
					$("input#roomCat", v).prop("disabled", false);					
					var text_room_price = $(this).closest("tr").find('td.container_selling_price span');
					var hidden_room_price = $(this).closest("tr").find('td.container_selling_price #room_price');
					var text_room_nprice = $(this).closest("tr").find('td.container_net_price span');
					var hidden_room_nprice = $(this).closest("tr").find('td.container_net_price #room_net_price');
					var text_room_amount = $(this).closest("tr").find('td.container_selling_amount');
					var text_room_net_amount = $(this).closest("tr").find('td.container_net_amount');
					$(RoomChecked).attr("required", false);
					text_room_price.text('00.0');
					text_room_nprice.text('00.0');
					hidden_room_price.val('00.0');
					hidden_room_nprice.val('00.0');
					text_room_amount.find('span').text('00.0');
					text_room_net_amount.find('span').text('00.0');
					text_room_amount.find('input').val('00.0');
					text_room_net_amount.find('input').val('00.0');
				}
			});
		}
		var CheckNoRoom = true;
		$("tr td.container_room").each(function(i, v){
			if ($('.checkRoom', v).is(':checked')) {
				CheckNoRoom = false;
			}
		});
		$("#confirm-booking").attr("disabled", CheckNoRoom);
	});
	
	// checkbox action for choose room category
	$(document).on('change', '#roomCat', function(){
		var checkRoom = $(this).closest('tr').find('td.container_category span#checkStatus');
		var room_container  = $(this).closest("tr").parent().closest("tr").find("td.container_room");
		var roomType = $("input#checkRoom", room_container);
		var roomCat = $(this).closest('tr').find('td.container_category');
		var selling_price = $(this).data('selling');
		var net_price = $(this).data('net');
		var no_of_room = $(this).closest("tr").find('td.container_room_no select');
		var RoomChecked = $(this).closest("tr").find('td.container_room_no').find("#no_of_room");
		var text_room_price = $(this).closest("tr").find('td.container_selling_price span');
		var hidden_room_price = $(this).closest("tr").find('td.container_selling_price #room_price');
		var text_room_nprice = $(this).closest("tr").find('td.container_net_price span');
		var hidden_room_nprice = $(this).closest("tr").find('td.container_net_price #room_net_price');
		var text_room_amount = $(this).closest("tr").find('td.container_selling_amount');
		var text_room_net_amount = $(this).closest("tr").find('td.container_net_amount');
		var bookDay = $("#book_day").val();
		if ($(roomType).prop("checked")){
			if ($(this).prop('checked')){
				
				if  (selling_price > 0 || net_price > 0) {
					$('option:eq(1)', no_of_room).attr('selected', 'selected').val();
					$('option:eq(0)', no_of_room).removeAttr('selected', 'selected').val("");
				}
				bookingDay = bookDay <= 0 ? 1 : bookDay;
				text_room_price.text((selling_price ? selling_price:'NaN'));
				text_room_nprice.text((net_price ? net_price:"NaN"));
				hidden_room_price.val(selling_price);
				hidden_room_nprice.val(net_price);
				text_room_amount.find('span').text((parseFloat(selling_price) * parseFloat(no_of_room.val()) * parseFloat(bookingDay)).toFixed(2));
				text_room_amount.find('input').val(((parseFloat(selling_price) * parseFloat(no_of_room.val())) * parseFloat(bookingDay)).toFixed(2));
				text_room_net_amount.find('span').text(((parseFloat(net_price) * parseFloat(no_of_room.val())) * parseFloat(bookingDay)).toFixed(2));
				text_room_net_amount.find('input').val(((parseFloat(net_price) * parseFloat(no_of_room.val())) * parseFloat(bookingDay)).toFixed(2));
				$(RoomChecked).attr("required", true);
			}else{
				$('option:eq(1)', no_of_room).removeAttr('selected', 'selected').val();
				$('option:eq(0)', no_of_room).attr('selected', 'selected').val("");
				text_room_price.text('00.0');
				text_room_nprice.text('00.0');
				hidden_room_price.val('00.0');
				hidden_room_nprice.val('00.0');
				text_room_amount.find('span').text('00.0');
				text_room_net_amount.find('span').text('00.0');
				text_room_amount.find('input').val('00.0');
				text_room_net_amount.find('input').val('00.0');
				$(RoomChecked).attr("required", false);
			}
		}else{
			alert('Please checked room type before choose room category;')
			$(this).prop("checked", false);
			room_container.animate({backgroundColor: "#428bca", color:"white"}, 200, function(){$(this).animate({backgroundColor: "#ecf0f5", color:"black"}, 100)});
			return false;
		}
	});

	$(document).on('change', '#no_of_room', function(){
		var bookDay = $("#book_day").val();
		var roomCat = $(this).closest("tr").find('td.container_category #roomCat');
		var selling_price = $(this).closest("tr").find('td.container_selling_price #room_price').val();
		var net_price = $(this).closest("tr").find('td.container_net_price #room_net_price').val();
		var RoomNo = $(this).val();
		var text_room_amount = $(this).closest("tr").find('td.container_selling_amount span');
		var text_room_net_amount = $(this).closest("tr").find('td.container_net_amount span');
		var hidden_room_amount = $(this).closest("tr").find('td.container_selling_amount input');
		var hidden_room_net_amount = $(this).closest("tr").find('td.container_net_amount input');
		bookingDay = bookDay <= 0 ? 1 : bookDay;
		text_room_amount.text(((parseFloat(selling_price) * parseFloat(RoomNo)) * parseFloat(bookingDay)).toFixed(2));
		text_room_net_amount.text(((parseFloat(net_price) * parseFloat(RoomNo)) * parseFloat(bookingDay)).toFixed(2));
		hidden_room_amount.val(((parseFloat(selling_price) * parseFloat(RoomNo)) * parseFloat(bookingDay)).toFixed(2));
		hidden_room_net_amount.val(((parseFloat(net_price) * parseFloat(RoomNo)) * parseFloat(bookingDay)).toFixed(2));
	});

});
