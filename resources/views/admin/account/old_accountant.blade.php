
<div class="modal" id="createAccountName" role="dialog" data-backdrop="static" data-keyboard="true">
    <div class="modal-dialog modal-lg">    
        <form method="POST" action="{{route('createAccountName')}}" id="createAccountName">
          <div class="modal-content">        
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title"><strong>Add New Account Name</strong></h4>
              </div>
              <div class="notify-message"></div>
              <div class="modal-body">
              {{csrf_field()}}    
              <input type="hidden" name="eid" id="eid">
                <div class="row">
                  <div class="col-md-3 col-xs-6">
                          <div class="form-group">
                              <label>Account Type <span style="color:#b12f1f;">*</span></label> 
                              <select class="form-control input-sm" name="account_type">
                                @foreach(App\AccountType::where("status", 1)->get() as $actype)
                                  <option value="{{$actype->id}}">{{$actype->account_name}}</option>
                                @endforeach
                              </select>
                          </div> 
                      </div>
                      <div class="col-md-6 col-xs-12">
                          <div class="form-group account_name">
                              <label>Account Name<span style="color:#b12f1f;">*</span></label> 
                              <input autofocus="" type="text" placeholder="Account Name" class="form-control input-sm" name="account_name" required>
                          </div> 
                      </div>        
                      <div class="col-md-12 col-xs-6">
                          <div class="form-group account_code">
                              <label>Account Code <span style="color:#b12f1f;">*</span> <span style="font-weight: 300;">A unique code/number for this account (limited to 4 characters)</span></label> 
                              <input type="text" placeholder="Account Code" class="form-control input-sm" name="account_code" required>
                          </div> 
                      </div>
                      <div class="col-md-12 col-xs-12">
                          <div class="form-group">
                              <label>Description <span style="color:#b12f1f;">*</span></label> 
                              <textarea class="form-control" rows="6" name="desc" placeholder="Description here...!"></textarea>
                          </div> 
                      </div>                   
                  </div>
              </div>
              <div class="modal-footer">
                  <div class="text-center">
                    <button type="submit" class="btn btn-info btn-sm" id="btnAccountNameSave">Save</button>
                    <a href="#" class="btn btn-default btn-sm btn-acc" data-dismiss="modal">Close</a>
                  </div>
              </div>    
          </div>  
        </form>
    </div>
  </div>

<?php 
  use App\component\Content;
?>

<script type="text/javascript">
    $(document).ready(function(){
        var baseUrl = location.protocol+'//'+location.host+"/finance/";
        function dataFilter(dataUrl, filterName, type, location, FilterType, countryId = 0){
            $.ajax({
                method: "GET",
                url: dataUrl, 
                data: "dataName="+filterName +"&type="+type +"&filter_type="+FilterType +"&countryId="+countryId, 
                dataType: 'html',
                beforeSend: function() {
                   $(".data_filter", this).html('<i class="fa fa-spinner fa-spin loading"></i>');
                },
                success: function(html){
                    $(".data_filter", this).next().remove();
                    $(location).html(html);
                },
                error: function(xhr, status, error){
                    alert("Error!" + xhr.status);
                    return false;
                },
                complete: function() {
                   $(".data_filter", this).next().remove();
                }
            });
            return false;
        }

        function loadService(dataid, datatype, location, selectedId, project){
            $.ajax({
                method: "GET",
                url: baseUrl + "findOption",      
                data: "id=" + dataid+"&datatype="+ datatype+ "&selectedid="+ selectedId + "&project=" + project,     
                dataType: 'html',
                beforeSend: function() {
                   $(location).after('<i class="fa fa-spinner fa-spin loading" style="position: absolute;top: 45%;left: 10%;font-size: 16px;"></i>');
                },
                success: function(html){
                    $(location).html(html);
                    $(location).next().remove();
                    // $(location).next().remove();
                },
                error: function(xhr, status, error){
                    alert("Error!" + xhr.status);
                    return false;
                },
                complete: function() {
                   $(location).next().remove();
                }
            });
        }


        function insertData(baseUrl, dataForm, btnSub, btnText = "Public"){
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
                    location.reload();

                },
                error: function(xhr, status, error){
                    alert("Error!" + xhr.status);
                    return false;
                },
                complete: function() {
                    $(btnSub).text(btnText);
                    $(btnSub).removeAttr("disabled");                
                }
            });
            return false;
        }

        function validateField(feildName, title = "Required Field"){
            var formFeidl = $(feildName).closest(".form-group");
            if (feildName.length > 0) {
                $(formFeidl).addClass("has-error");
            }else{
                $(formFeidl).removeClass("has-error");
            }
            return false;
        }

        function formatCurrency(number = "00.00") {
            var value = parseFloat(number);
            return value.toFixed(2);
        }       
        formatCurrency();
        formatMoney();
        function formatMoney(total) {
            var neg = false;
            if(total < 0) {
                neg = true;
                total = Math.abs(total);
            }
            return  parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
        }

        function calculate_sub_total(emp_load){
            if (emp_load > 0) {
                var credit_total = 0;
                $('.credit').each(function(){
                    if ($(this).val() > 0) {
                        credit_total += parseInt($(this).val());
                    } 
                });
                var debit_total = 0;
                $('.debit').each(function(){
                    if ($(this).val() > 0) {
                        debit_total +=  parseInt($(this).val());    
                    }                
                });
                kyat_credit_total=0;
                $('.kyat-credit').each(function(){
                    if ($(this).val() > 0) {
                       kyat_credit_total +=  parseInt($(this).val());    
                    }                
                });
                kyat_debit_total  = 0;
                $('.kyat-debit').each(function(){
                    if ($(this).val() > 0) {
                       kyat_debit_total +=  parseInt($(this).val());    
                    }                
                });
               
                $("#credit_amount").val(parseInt(credit_total).toFixed(2));
                $(".sub_total_credit").text(formatMoney(parseInt(credit_total)));
                $("#debit_amount").val(parseInt(debit_total).toFixed(2));
                $(".sub_total_debit").text(formatMoney(parseInt(debit_total)));

                $("#kyat_credit_amount").val(parseInt(kyat_credit_total).toFixed(2));
                $(".kyat_sub_total_credit").text(formatMoney(parseInt(kyat_credit_total)));
                $("#kyat_debit_amount").val(parseInt(kyat_debit_total).toFixed(2));
                $(".kyat_sub_total_debit").text(formatMoney(parseInt(kyat_debit_total)));
            }
        }
        
        function space(el, after) {
            after = after || 3;
            var v = el.value.replace(/[^\dA-Z]/g, ''),
                reg = new RegExp(".{" + after + "}","g")
            el.value = v.replace(reg, function (a, b, c) {
                return a + ' ';
            });
        }
        
    $(document).ready(function(){
        $(document).on("click", "ul li.list", function(){
            $(this).find("label input").prop("checked", true);
            var sup_Id = $(this).find('label input').val();
            var label = $.trim($(this).find("label").text());
            $(this).closest('.obs-wrapper-search').find('div #search_Account').val(label)
            $(this).closest('.obs-wrapper-search').find('div input[type=hidden]').val(sup_Id);
            $(this).closest(".btn-group").find("button").find("span.pull-left").text(label);
            // $("#search").val(label);
            $(this).closest('.obs-wrapper-search').find('div #search').val(label)
        });

        $(document).ready(function(){
            $('#createAccountName, #LoadSupplier').on('show.bs.modal', function (event) {
                $("#loadProjectConfirm").modal('hide');
                $("#myModal").modal('hide');
            });

            $('#createAccountName , #LoadSupplier').on('hide.bs.modal', function (event) {
                $("#loadProjectConfirm").modal('show');
                $("#myModal").modal('show');
                setTimeout(function(){ $(".notify-message").html(""); }, 1000);
            });
        });

        $(document).on("submit", "#Add_New_supplier", function(e){
            e.preventDefault();
            dataForm = $(this).serialize();
            urlAccName = baseUrl+"add-new-supplier";
            $.ajax({
                method: "POST",
                url: urlAccName,
                data: dataForm,
                dataType: "json",               
                beforeSend: function() {
                    $("#btnAddSupplier").text("Loading");
                    $("#btnAddSupplier").attr("disabled", "disabled");
                },
                success: function(html){
                    messageData = '<div class="alert alert-dismissible fade show '+html.messagetype+'" role="alert" style="position: relative;"><div><span> '+ html.message+'  </span></div><p></p><button type="button" class="close" data-dismiss="alert" aria-label="Close" style=" position: absolute;right: 7px;top: 13px;"><span aria-hidden="true" style="font-size: 22px;padding: 1px 8px;">&times;</span></button></div>';
                    $("#Add_New_supplier").find('.modal-content').find(".notify-message").html(messageData);
                    if (html.messagetype == "success") {
                        $("input[name='title']").val("");
                        $("input[name='supplier_email']").val("");
                        $("input[name='supplier_email']").val("");
                        $("textarea[name='desc']").val("");
                    }
                },
                error: function(xhr, status, error){
                    alert("Error!" + xhr.status);
                    return false;
                },
                complete: function() {
                    $("#btnAddSupplier").text("Save");
                    $("#btnAddSupplier").removeAttr("disabled"); 
                    
                }
            });
            return false;
        });


        $(document).on("submit", "#createAccountName", function(e){
            e.preventDefault();
            dataForm = $(this).serialize();
            urlAccName = baseUrl+"add-new-account";
            $.ajax({
                method: "POST",
                url: urlAccName,
                data: dataForm,
                dataType: "json",               
                beforeSend: function() {
                    $("#btnAccountNameSave").text("Loading");
                    $("#btnAccountNameSave").attr("disabled", "disabled");
                },
                success: function(html){
                    messageData = '<div class="alert alert-dismissible fade show '+html.messagetype+'" role="alert" style="position: relative;"><div><span> '+ html.message+'  </span></div><p></p><button type="button" class="close" data-dismiss="alert" aria-label="Close" style=" position: absolute;right: 7px;top: 13px;"><span aria-hidden="true" style="font-size: 22px;padding: 1px 8px;">&times;</span></button></div>';
                    $("#createAccountName").find('.modal-content').find(".notify-message").html(messageData);
                    if (html.messagetype == "success") {
                        $(".account_code").removeClass("has-warning");
                        $("input[name='account_name']").val("");
                        $("input[name='account_code']").val("");
                        $("textarea[name='desc']").val("");
                    }

                    if (html.messagetype == "warning") {
                        $(".account_code").addClass("has-warning");
                    }
                },
                error: function(xhr, status, error){
                    alert("Error!" + xhr.status);
                    return false;
                },
                complete: function() {
                    $("#btnAccountNameSave").text("Save");
                    $("#btnAccountNameSave").removeAttr("disabled"); 
                    
                }
            });
            return false;
        });

        $(document).on("input keyup paste keypress", "#deposit_amount", function(){
            amount = $("#pay_amount").val() - $(this).val() ;
            pay_amount = amount ? amount.toFixed(2) :"";
            $("#input_amount_to_pay").val(pay_amount);
            $("#amount_to_pay").text(pay_amount);
        });

        $(document).on("input keyup paste keypress", ".transfer_amount", function(){
            formatMoney($(this).val());
        });
        $(document).on("click", ".btnEditJournal", function(){
            dataID = $(this).data("id");
            datatype = $(this).data("type");
            acc_name = $(this).data("acc_name");
            acc_type = $(this).data("acc_type");
            $("input#debit").val($(this).data('debit'));
            $("input#credit").val($(this).data('credit'));
            $("input#kyat-debit").val($(this).data('kdebit'));
            $("input#kyat-credit").val($(this).data('kcredit'));
            $("input#pay_date").val($(this).data('pay_date'));
            $("textarea#payment_desc").val($(this).data('payment_desc'));

            $(".sub_total_debit").text($(this).data('debit') ? formatMoney($(this).data('debit')) : '0.00');
            $(".sub_total_credit").text($(this).data('credit') ? formatMoney($(this).data('credit')) : '0.00');
            $(".kyat_sub_total_debit").text($(this).data('kdebit') ? formatMoney($(this).data('kdebit')) : '0.00');
            $(".kyat_sub_total_credit").text($(this).data('kcredit') ? formatMoney($(this).data('kcredit')) : '0.00');
            OptionLocal = $("div.obs-wrapper-search").find("ul.dropdown_account_name");
            loadService(acc_type, datatype, OptionLocal, acc_name, $(".country_book_supplier").val());
            OptionLocal = $("div.btn-group").find("button.arrow-down  span.pull-left").text($(this).data("acc_name_title"));
            
            $(".account_type  option").each( function(i){
                if($(this).val() == acc_type){
                    $(this).attr("selected", true);
                }else{
                    $(this).removeAttr("selected", true);
                }
            });
        });

        $(document).on("change", ".projectPayable", function(){
            amount = $("option:selected", this).data("balance");
            kamount = $("option:selected", this).data("kbalance");
            if (parseInt(amount)) {
                balanceTotal = amount;
                $("#deposit_amount").attr('name', 'deposit_amount');
                $("#pay_amount").attr('name', 'pay_amount');
            }else{
                balanceTotal = kamount;
                $("#deposit_amount").attr('name', 'deposit_kamount');
                $("#pay_amount").attr('name', 'pay_kamount');
            }
            
            if(parseInt(amount) <= 0){
                $("#deposit_amount").attr("disabled", true);
            }else{
                $("#deposit_amount").attr("disabled", false);    
            }                
            $("#deposit_amount").val(balanceTotal);
            $("#pay_amount").val(balanceTotal);
        });

        $(document).on("click", ".entry_code_view", function(){
            search = $(this).data("entry_code");
            $.ajax({
                method: "GET",
                url: baseUrl + "getBankTransferred",      
                data: "search=" + search,     
                dataType: 'json',
                beforeSend: function() {
                   $("#body_message_bank_transfer").html('<tr><i class="fa fa-spinner fa-spin loading" style="top: 45%;left: 10%;font-size: 16px;"></i></tr>');
                },
                success: function(html){
                    Data = "";
                    $.each(html.LoadData, function(index, bk){
                        Data += "<div><b><a href='#'>"+bk.transfer_amount+"</a> USD</b> <span> was transferred from <b>"+ bk.bank_from+"</b> to <b>"+bk.bank_to+"</b> On <span style='color:#1a6bb3; font-weight:700;'>"+bk.transfer_date+ "  at "+bk.transfer_time+"</a></span></div> <strong>Details</strong><div style='border: solid 1px #f4f4f5; padding: 7px;'><p>"+bk.bank_memo+"</p></div>";
                    });
                    $("#body_message_bank_transfer").html(Data);
                },
                error: function(xhr, status, error){
                    alert("Error!" + xhr.status);
                    return false;
                },
                complete: function() {
                   $(location).next().remove();
                }
            });
        });
        // check Payment option
        $(document).on("click",".payoption", function(){
            pay_amount = $("#pay_amount").val();
            if($(this).val() == 1){
                $("#deposit_amount").val(pay_amount);
            }else{
                $("#deposit_amount").val("");
            }
        });

        $(document).on("click", ".btnEdit", function(){
            $("#edesc").val($(this).data("details"));
            $("input[name='edebit']").val($(this).data("debit"));
            $("input[name='ecredit']").val($(this).data("credit"));
            $("#project_fileno").text($(this).data("project_prefix"));
            $("#edit_id").val($(this).data("id"));
            acc_typeid = $(this).data("acc_type_id");
            acc_nameid = $(this).data("acc_name_id");
            $(".edit_account_type option").each( function(i){
                if($(this).val() == acc_typeid){
                    $(this).attr("selected", true);
                }else{
                    $(this).removeAttr("selected", true);
                }
            });

            $(".edit_account_name option").each( function(i){
                if($(this).val() == acc_nameid){
                    $(this).attr("selected", true);
                }else{
                    $(this).removeAttr("selected", true);
                }
            });
        });

        $(document).on("change", ".receivable-type", function(){
            dataReceivable = '<div class="col-md-3 col-xs-6"><div class="form-group"><label for="for_project">For Project<span style="color:#b12f1f;">*</span></label> <select class="form-control projectPayable" name="projectNo" id="dropdown-project"><option value="0">Project</option>@foreach(App\Project::ProjectByAccount() as $pro)<option value="{{$pro->project_number}}" {{isset($journal->project_number)? ($journal->project_number == $pro->project_number ? "selected":"") :""}}>{{$pro->project_number}}</option>@endforeach</select> </div></div>';
            if ($(this).val() == "1") {
                $("#proJectNoOPtion").html("");
            }else{
                $("#proJectNoOPtion").html(dataReceivable);
            }
        });

        $(document).on("change", ".location", function(){
            datatype = $(this).data("type");
            dataId = 51;
            loadService(dataId, datatype, $("select#dropdown_Province"), $(this).val(), 'Office Supply');
        });

        $(document).on('change', ".country_account", function() {
            var dataId = $(this).val(),
                datatype = $(this).data('type'),
                bus_type = $(".country_book_supplier").children("option:selected").val();
                loadService(bus_type, datatype, $("ul#myAccountName"), "booking ", dataId);
                // loadService(dataId, datatype, $("select#dropdown_Province"), $(this).val(), 'Office Supply');
        });

        $(document).on("change", ".country_book_supplier", function(){
            if ($(this).data("type") == "supplier_by_account_transaction") {
                loadService($(this).val(), $(this).data("type"), $("ul#myAccountName"), "book ", $(".country_account").val());
            }else{
                loadService($(this).val(), $(this).data("type"), $("select#supplier_book"), $(this).data('selected'), "supplier book");
            }
        });

        $(document).on("change", ".business_type", function(){
            $("#total_payable").val("");
            dataType = $(this).data('type');
            this_val = $(this).val();
            projectSelected = $("option:selected", $("#dropdown_project")).val();
            bus_type = $("option:selected", this).val();
            servicetype = $("option:selected", this).data('type');
            if (this_val == 54) { 
                $("#supplier_Name label").text("Service Name");
                loadService($("option:selected", $("#dropdown_project")).val(), "others-payment", $("select#dropdown_supplier"), servicetype, $("option:selected", $(".location")).val());
            }else{
                $("#supplier_Name").html('<div class="form-group"><label>Supplier Name <span style="color:#b12f1f;">*</span></label><select class="form-control  suppliers" name="supplier" id="dropdown_supplier"></select></div>');
                loadService($(this).val(), dataType, $("select#dropdown_supplier"), bus_type, projectSelected );
            }                
        });

        $(document).on("change", ".business", function(){
            con_id = $("option:selected", $(".location")).val();
            datatype  = $(this).data("type");
            var row_location = $(this).closest('tr').find("select.suppliers");
            loadService($(this).val(), datatype, row_location , con_id, 'suppliers');
        }); 

          data_row = '<tr>  <td><select class="form-control input-sm business" name="business[]" data-type="sup_by_bus" required=""><option value="">--choose--</option>@foreach(App\Business::where(["category_id"=>0, "status"=>1])->get() as $key => $bn)<option value="{{$bn->id}}">{{$bn->name}}</option>@endforeach()</select></td><td style="position: relative; widows: 200px"><div id="supplier_Name"><select class="form-control suppliers input-sm" name="supplier[]" id="dropdown_supplier" required=""></select></div></div></td><td> <select class="form-control account_type input-sm" name="account_type[]" data-type="account_name" data-multitype="account_name_journal" required=""> <option value="0">Choose Account Types</option> @foreach(App\AccountType::where("status", 1)->orderBy("account_name", "ASC")->get() as $key=> $acc)   <option value="{{$acc->id}}">{{$acc->account_name}}</option>  @endforeach </select></td> <td style="position: relative;"> <div class="btn-group" style="display: block;"><button type="button" class="form-control input-sm arrow-down" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" data-backdrop="static" data-keyboard="false" role="dialog" data-backdrop="static" data-keyboard="false"><span class="pull-left"></span><span class="pull-right"></span></button> <div class="obs-wrapper-search" style="max-height:250px; overflow: auto; "><div><input type="text" name="acc" data-url="{{route('getFilter')}}" id="search_Account" onkeyup="filterAccountName()" class="form-control input-sm"  required=""><input type="hidden" name="account_name[]" ></div><ul id="myAccountName" class="list-unstyled dropdown_account_name"></ul></div></div></td><td><input type="text" class="debit form-control input-sm text-right balance number_only" data-type="debit" name="debit[]" id="debit" placeholder="00.0">  </td> <td><input type="text" class="credit form-control input-sm text-right balance number_only" data-type="credit" name="credit[]" id="credit" placeholder="00.0"></td><td><input type="text" class="kyat-debit form-control input-sm text-right balance number_only" data-type="kyat-debit" name="kyatdebit[]" id="kyat-debit" placeholder="00.0"> </td><td><input type="text" class="kyat-credit form-control input-sm text-right balance number$(function(){_only" data-type="kyat-credit" name="kyatcredit[]" id="kyat-credit" placeholder="00.0"> </td> <td class="text-center"><span class="btnRemoveEntry"><i class="fa fa-times-circle btn-block" style="font-size: 19px; background: #ddd;padding: 6px;"></i></span></td></tr>';
        $(document).on("click", ".btnAddLine", function(){
            pro_id = $("option:selected", $(".province")).val();
            con_id = $("option:selected", $(".location")).val();             
            $("tbody#data_payment_option tr:last-child").after(data_row);
            datalocation = $("tbody#data_payment_option tr:last-child select#dropdown_supplier");
            loadService(pro_id, "province", datalocation , con_id, 'suppliers');
        });

        $(document).on("change", ".suppliers", function(){
            Total_Amount = $("option:selected", this).data('amount');
            Total_kAmount = $("option:selected", this).data('kamount');
            $("#total_payable").val(Total_Amount);
            $("#book_kamount").val(Total_kAmount);
        });
        $(document).on("change", ".projectNo", function(){
            $("#total_payable").val("");
            if ($(this).data('type') == "service-by-project") {
                bus_typeid = $("option:selected", $(".service-pay")).val();
                loadService(bus_typeid, "service-by-project", $("select#dropdown_supplier"), bus_typeid, $(this).val());
            }else{
                var bus_typeid = $("option:selected", $(".business_type")).val();   
                var bus_type = $("option:selected", $(".business_type")).data('bus_type');
                loadService(bus_typeid, "sup_by_project", $("select#dropdown_supplier"), bus_type, $(this).val());
            }
        });

        $(document).on("change", ".service-pay", function(){
            loadService($(this).val(), "service-by-project", $("select#dropdown_supplier"), $(this).val(), $("option:selected", $("#dropdown_project")).val());
        });

        $("#credit").val($("option:selected", $(".projectNo")).data("amount"));
        $(document).on("keyup paste keypress", ".data_filter", function(){
            dataFilter($(this).data("url"), $(this).val(), $(this).data('type'), $("ul#dropdown-account_name"), $("option:selected", $(".account_type")).val() );
        });

        $(document).on("keypress paste keyup", ".filter_project", function(){
            dataFilter($(this).data("url"), $(this).val(), $(this).data('type'),$("ul#dropdown-project"), "Filter Project" );
        });

        $(document).on("click", ".account_name, .project_list", function(){
            var this_label = $(this).data('label');
            $("#lable_"+this_label).text($(this).text());
            $("#id_"+this_label).val($(this).data('id'));
            $("#code_"+this_label).val($(this).data('code'));
        });

        // account receivable form
        var bus_id = $("option:selected", $(".business_type_receive")).val();
        var countryId = $("option:selected",$(".sup_by_country")).val();
        $(document).on("change", ".business_type_receivable", function(){
            var countryId = $("option:selected",$(".sup_by_country")).val();
            var bus_id = $("option:selected", $(".business_type_receive")).val();
            loadService(bus_id, $(this).data('type'), $("#dropdown_receive_supplier"), countryId, "Supplier");
        });

        $(document).on("change", ".sup_by_country", function(){
            var bus_id = $("option:selected", $(".business_type_receive")).val();
            loadService(bus_id, 'project_by_supplier', $("#dropdown_receive_supplier"), $(this).val(), "Supplier");
        });

        $(document).on("change", ".supplier-receivable", function(){
            datatype = $(this).data("type");
            acc_type = $(this).data("acc_type");
            loadService($(this).val(), datatype, $("#dropdown-project"), acc_type );
            $("#journal_id").val($("option:selected", this).data("journal"));
            $("#account_typeID").val($("option:selected", this).data("acc_type"));
            $("#account_nameID").val($("option:selected", this).data("acc_name"));
        });

        // insert data all form
        $(document).on("submit", "#bank_transfer_form", function(e){
            e.preventDefault();
            dataForm = $(this).serialize();
            addBankTransfer = baseUrl+"addBankTransfer";
            if ($("#total_amount").val() > 0) {
                $("#notification").modal("show");
                $("#message").html("Open balance" )
            }
        }); 

        // form for edit journal 
        $(document).on("submit", "#edit_account_journ_form", function(e) {
            e.preventDefault();
            dataForm = $(this).serialize();
            urlReceivable = baseUrl+"editjournal-entry";
            insertData(urlReceivable,dataForm , $("#btnEditJournal"), "Save");    
        });

        $(document).on("submit", "#account_CreatePayment_form", function(e){
            dataForm = $(this).serialize();
            var supplierFrom    = $("select[name='supplier_from'] option:selected").text();
            var supplierTo      = $("select[name='supplier_to'] option:selected").text();
            var amount = parseFloat($("#deposit_amount").val());
            var currency = $("#currency").val();
            if ($("#input_amount_to_pay").val() < 0 || $("#deposit_amount").val() <= 0) {
                $(".amount_to_pay").css({"color":"#b12f1f"});
                $(".amount_to_pay").addClass("has-error");
                $(".total_balance_to_pay").addClass("has-error");
                $("#deposit_amount").focus();
                return false;
            }else{
                $('#myAlert').modal("show");
                $("#modal-body").html("<p style='font-size:13px;'> Amount: <strong style='color:#607D8B;'> "+ amount.toFixed(2) + "&nbsp;" + currency +"</strong> Pay From <strong>"+supplierFrom+"</strong> To Supplier <strong>"+supplierTo+"</strong></p>");
                $(".total_balance_to_pay").removeClass("has-error");
                $(".amount_to_pay").removeClass("has-error");
                $(".amount_to_pay").css({"color":"#333"});
                e.preventDefault();

            }      
        });
         $(document).on("click", ".btnOkay", function(){
            urlMakePayment = baseUrl+"createPayable";
            dataForm = $("#account_CreatePayment_form").serialize();
            if ($(this).val() == 1) {
                $("#account_CreatePayment_form").submit();
                insertData(urlMakePayment,dataForm , $("#btnSaveCreatedPayment"), "Confirm Pay");
                $('#myAlert').modal('hide');
            }
        });

        $(document).on("submit", "#account_journal_entry_form", function(e){
            e.preventDefault();
            dataForm = $(this).serialize();
            urlReceivable = baseUrl+"createJournal";
            insertData(urlReceivable,dataForm , $("#btnSaveReceivable"), "Save");
        });

        $(document).on("submit", "#add_new_account_form", function(e){
            e.preventDefault();
            dataForm = $(this).serialize();
            urlAccName = baseUrl+"addAccountName";
            insertData(urlAccName, dataForm, $("#btnAddNewAccount"), "Save");
        });

        $(document).on( "change", ".AccountNameByCountry", function() { 
            var OptionLocal = $("div.obs-wrapper-search").find("ul.dropdown_account_name");
            var type = $(this).data('type');
            var acc_type = $(".account_type").children("option:selected").val();
            loadService(acc_type, type, OptionLocal, $(this).val(), $(this).val());
            var supplierLocation = $(".obs-wrapper-search ul.dropdown-data")
            loadService($(this).val(), "supplierBycountry", supplierLocation, [6,7], $(this).val());
        });

        // end form
        $(document).on("click", ".AddToACcount", function(){
            var pay_type = $(this).data("process_type") == "pay" ? 10 : 8;
            $('.obs-wrapper-search').find('div #search_Account').val("")
            $('.obs-wrapper-search').find('div input[type=hidden]').val("");
            $(".btn-group").find("button").find("span.pull-left").text("");
            $(".account_type option.value").each( function(i){
                if($(this).val() == pay_type){
                    $(this).attr("selected", true);
                    $(this).css({"display": "block"});
                }else{
                    $(this).removeAttr("selected", true);
                    $(this).css({"display": "none"});
                }
            });

            OptionLocal = $("div.obs-wrapper-search").find("ul.dropdown_account_name");
            loadService(pay_type, "account_name", OptionLocal, $(this).data("country"), $(this).data("country"));
        });

        $(document).on("change", ".account_type", function(){
            if ($(this).data('method-type') == "one-account_name") {   
                OptionLocal = $("div.btn-group").find("div.obs-wrapper-search").find("ul.dropdown_account_name");               
            }else{
                OptionLocal = $(this).closest("tr").find("td div.btn-group").find("div.obs-wrapper-search").find("ul.dropdown_account_name");
            }       
            loadService($(this).val(), $(this).data('type'), OptionLocal, $(this).data("multitype"), $(".location").val());
        });
        
        $(document).on("input", ".balance", function(){
            val_credit = $(this).closest("tr").find("td input#credit");
            val_debit = $(this).closest("tr").find("td input#debit");
            val_kyat_debit = $(this).closest("tr").find("td input#kyat-debit");
            val_kyat_credit = $(this).closest("tr").find("td input#kyat-credit");
            if ($(this).data("type") == "debit"){
                if ($(this).val() > 0) {
                    $(val_credit).val("");
                    $(val_kyat_credit).val("");
                    $(val_kyat_debit).val("");
                }
            }else if($(this).data("type") == "credit"){
                if ($(this).val() > 0) {
                    $(val_debit).val("");
                    $(val_kyat_credit).val("");
                    $(val_kyat_debit).val("");
                }
            }else if($(this).data("type") == "kyat-debit"){
                if ($(this).val() > 0) {
                    $(val_kyat_credit).val("");
                    $(val_debit).val("");
                    $(val_credit).val("");
                }
            }else if($(this).data("type") == "kyat-credit"){
                if ($(this).val() > 0) {
                    $(val_kyat_debit).val("");
                    $(val_debit).val("");
                    $(val_credit).val("");                       
                }
            }
            calculate_sub_total(1);
        });

        $(document).on("mouseout", ".balance", function(){
            if ($(this).val() > 0) {
                $(this).val(formatCurrency($(this).val()));
            }                 
        });
        $(document).on("keyup keypress", "#receive_voucher", function(){
            space(this, 3);       
        });
    });
    $(document).on("click",".btnEdit", function(){
        $('#myEditForm').modal("show");
    });
    $("#data_payment_option tr td input#debit").val();
    $("#data_payment_option tr td input#credit").val();
    var Credit = $("input#credit").val();
    var Debit  = $("input#debit").val();
 
    $(document).on("click", ".btnRemoveEntry", function(){
        Remove_row = $(this).closest("tr");
        all_row = $("#data_payment_option tr");
        if (all_row.length <= 1) {
            $('#myAlert').modal("show");
            $(".modal-body strong#message").text("You must have at least 1 line items.");
        }else{
            $(Remove_row).remove();
        }
        calculate_sub_total(1);
    });

    $(document).on("click",".btnRemoveOption", function(e){
        datatype = $(this).data("type");
        dataid  = $(this).data("id");
        Remove_row = $(this).closest("tr");
        e.preventDefault();
        if (confirm("Are you sure?")) {
            $.ajax({
                method: "GET",
                url: baseUrl + "removeOption",      
                data: "id=" + dataid+"&datatype="+ datatype,
                dataType: 'json',
                success: function(html){
                    $(Remove_row).css({"backgroundColor":"#999","color":"#f4f4f5"});
                    Remove_row.fadeOut(500, function(){
                        $(Remove_row).remove();
                    });
                    return false;                    
                },
                error: function(xhr, status, error){
                    alert("Error!" + xhr.status);
                    return false;
                },
            });
        }
        return false;
    });

    $(document).on("change", "#bank_from", function(){
        dataId = $(this).val();
        $("#bank_to option").each( function( index){
            if (dataId == $(this).val()) {
                $(this).css({"display":"none"});
            }else{
                $(this).css({"display":"block"});
            }
        });
    });
});


</script>



