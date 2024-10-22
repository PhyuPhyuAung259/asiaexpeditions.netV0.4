  @extends('layout.backend')
  @section('title', 'Payment Link')
  <?php 
    $active = 'finance/journal'; 
    $subactive = 'finance/payment_getway';
    use App\component\Content;
  ?>

  @section('content') 
  <div class="wrapper">
    @include('admin.include.header')
    @include('admin.include.menuleft')
    <div class="content-wrapper">

      <section class="content"> 
        <div class="row">
          @include('admin.include.message')         
          <section class="col-lg-12 connectedSortable">
            
            <h3 class="border"> Create Payment Link</h3>
            <div class="row">   

              <div class="col-md-10 col-md-offset-1" style="margin-bottom: 20px">
                <?php 
                  if (isset($_GET['action']) && !empty($_GET['eid']) ) {
                    $actionForm = route('editPaymentLink');
                  }else{
                    $actionForm = route('addPaymentLink');
                  }
                 ?>
                <form action="{{$actionForm}}" method="post">
                  {{csrf_field()}}
                  <div class="account-saction"><br>     
                    <div class="col-md-1 col-xs-6" >
                        <div class="form-group">
                          <label>Invoice # </label>
                          <input type="text" name="invoice_number" class="form-control" value="{{{$invNumber or ''}}}" readonly>
                      </div>
                    </div>
                    <div class="col-md-5 col-xs-6">
                      <div class="form-group" style="position: relative;">
                        <label>Client Name<span style="color:#b12f1f;">*</span> <span style="color: #3c8dbc; cursor: pointer; z-index: 9;" data-toggle="modal" data-target="#myModal">Search By Date <i class="fa fa-calendar-plus-o"></i></span></label>
                        <div class="form-control werqwer" style="cursor: pointer; z-index: 444; line-height: 20px;">
                          <input type="hidden" name="project_id" id="client_name" value="{{{$payment->project_id or ''}}}">
                          <input type="hidden" name="eid_payment" value="{{{$payment->id or ''}}}">
                          <?php 
                            if (isset($payment->project)) {                        
                              $client_show = $payment->project->project_fileno."-".$payment->project->project_client." ,Date:".Content::dateformat($payment->project->project_start)."->".Content::dateformat($payment->project->project_end);
                            }else{
                              $client_show = "Choose Client";
                            }
                           ?>
                          <span id="show_text">{{ $client_show }}</span> 
                          <span id="arrow">
                            <i class="fa fa-sort-up" style="position: absolute; right: 17px; top:35px;  "></i>
                          </span>
                        </div>
                        <div class="selectAuto" style="display: none; padding-top: 0px; padding: 12px;">      
                          <span>
                            <input style="border-radius: 20px;" id="search" type="text" class="form-control" onkeyup="myFunction()" placeholder="Search for names.." title="Type client name" >
                          </span>    
                          <div style="max-height: 285px; overflow-y: auto;">                
                            <ul id="myUL" class="list-unstyled" style="padding-top: 5px;">
                              @foreach($getClient as $key => $cl)                            
                                  <?php 
                                    $clientPaid = \App\Payment\PaymentLink::where(["project_id"=>$cl->id, 'payment_confirm'=> "unpaid"])->first();
                                    if(isset($cl->flightArr)){
                                      $flightNo = ",  Flight No. A:".$cl->flightArr->flightno."-".$cl->flightArr->arr_time .", D:".$cl->flightArr->flightno."-".$cl->flightArr->dep_time;
                                    }else{
                                      $flightNo = "";
                                    }
                                  ?>                                  
                                  <li class="list" data-inv_number="{{$cl->project_fileno}}" data-value="{{$cl->id}}" data="{{$cl->project_fileno}}-{{ $cl->project_client }} 
                                   , Date:{{Content::dateformat($cl->project_start)}}->{{Content::dateformat($cl->project_end)}}" >{{$cl->project_fileno}}-{{ $cl->project_client }} 
                                   , Date:{{Content::dateformat($cl->project_start)}}->{{Content::dateformat($cl->project_end)}}
                                  </li>
                                @endforeach                          
                            </ul> 
                            <div class="clearfix"></div>
                          </div> 
                        </div>
                      </div>
                    </div>     
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Full Name<span style="color:#b12f1f;">*</span></label>
                        <input type="text" name="fullname" class="form-control" placeholder="Full Name" required="" value="{{{$payment->fullname or ''}}}">
                      </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Email <span style="color:#b12f1f;">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="google@gmail.com" required="" value="{{{$payment->email or ''}}}" {{isset($payment->email) ? "readonly":''}}>
                      </div>
                    </div>  

                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Amount to pay <span style="color:#b12f1f;">*</span></label>
                        <input type="text" name="vpc_Amount" class="form-control number_only" placeholder="Amount to Pay: 0000.00" required="" value="{{{$payment->original_amount or ''}}}">
                      </div>
                    </div>          
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Payment Type<span style="color:#b12f1f;">*</span></label>
                        <select name="vpc_card" id="vpc_card" class="form-control">
                            <option value="">Please Select</option>
                            <option value="MC" {{isset($payment->card_type) && $payment->card_type == 'MC' ? 'selected' :'' }}>Mastercard</option>
                            <option value="VC" {{isset($payment->card_type) && $payment->card_type == 'VC' ? 'selected' :'' }}>Visa</option>
                          </select>
                      </div>
                    </div>     
                    <div class="col-md-12 col-xs-12">
                      <div class="form-group">
                        <label>Customer Description</label>
                        <script src="{{asset('adminlte/editor/tinymce.min.js')}}"></script>
                        <textarea class="form-control my-editor" rows="4" name="desc" placeholder="Message here ...!">{{{$payment->desc or ''}}}</textarea>
                      </div>
                    </div>
                    <div class="col-md-12 text-center">
                      <div class="form-group">
                        <button class="btn btn-info btn-sm">Confirm</button>
                      </div>
                    </div><div class="clearfix"></div>
                    <hr>
                  </div>
                </form>
                <div class="clearfix"></div><br>
                @include("admin.payment.team_and_conditions")
                <div class="clearfix"></div>
              </div>
              <div class="wrapp_page" ></div>

            </div>                                
          </section>
        </div>
      </section>
    </div>
  </div> 

  <div class="modal fade" id="myModal" role="dialog"  data-backdrop="static" data-keyboard="true">
    <div class="modal-dialog modal-sm">    
      <form method="GET" action="{{route('createPaymentLink')}}">
        <div class="modal-content">        
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><strong>Project Date</strong></h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6 col-xs-6">
                <div class="form-group">
                  <label>Start <span style="color:#b12f1f;">*</span></label> 
                  <input type="text" name="start_date" class="form-control" id="from_date" value="{{{ $_GET['start_date'] or ''}}}" >  
                </div> 
              </div>
              <div class="col-md-6 col-xs-6">
                <div class="form-group">
                  <label>End Date <span style="color:#b12f1f;">*</span></label> 
                  <input type="text" name="end_date" class="form-control" id="to_date" value="{{{ $_GET['end_date'] or ''}}}">  
                </div> 
              </div>
            </div>
          </div>
          <div class="modal-footer" style="text-align: center;" >
            <button type="submit" class="btn btn-success btn-flat btn-sm">Search</button>
          </div>
        </div>      
      </form>
    </div>
  </div>
  <style type="text/css">
      .selectAuto{        
          background-color: #fff; 
          border: 1px solid #ccc; 
          border-top: none; 
          position: absolute; 
          top: 55px; 
          width: 100%;
          z-index: 99; padding: 15px;
      }

      ul li.list{
          padding: 6px 4px;
          cursor: pointer;
      }
      ul li.list.active{
         color: #fff;
          background-color: #337ab7;
          border-color: #2e6da4;
      }
      ul li.list:hover{
          color: #fff;
          background-color: #337ab7;
          border-color: #2e6da4;
      }
      .wrapp_page{
          position: absolute;
          width: 100%;
          height: 100%;
          top: 0;
          display: none;
      }
      .has-errors {
        border-color: #a94442;
          -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
      }
  </style>
  <script type="text/javascript">
      $(document).ready(function(){
    
          $(".werqwer").click( function(){
            $("#arrow").html('<i class="fa fa-sort-down" style="position: absolute; right: 17px; "></i>');
            $('.selectAuto').css('display', 'block');
            $('.wrapp_page').css('display', 'block');
            $('.werqwer div').css('display','block');
            $("#search").focus();
          });     

          $(".wrapp_page").click( function () {
              $("#arrow").html('<i class="fa fa-sort-up" style="position: absolute; right: 17px; top:35px;"></i>');
              $('.selectAuto').css('display', 'none');
              $('.wrapp_page').css('display', 'none');
              $('.werqwer div').css('display','none');
          });

          $("ul li").click( function () {
              var data = $(this).attr('data'),
                  value = $(this).attr('data-value'),
                  inv_number = $(this).attr('data-inv_number');
              $("#client_name").val(value);
              $("#inv_number").val(inv_number);
              $('#show_text').text(data);
              $('.selectAuto').css('display', 'none');
              $('.wrapp_page').css('display', 'none');
              $('.werqwer div').css('display','none');
              $("#arrow").html('<i class="fa fa-sort-up" style="position: absolute; right: 17px; top:35px;"></i>');
          });

          $("#btn_submit").click( function (e) {
            var client_name = $("#client_name").val(),
            amount = $("#amount").val(),
            first_name = $("#firstname").val()
            last_name = $("#lastname").val()
            email = $("#email").val();    
            
            if ( client_name.length == "" ) {
              $("#has-errors").addClass('has-errors');
              $("#amount").addClass('has-errors');
              $("#firstname").addClass('has-errors');
              $("#lastname").addClass('has-errors');
              $("#email").addClass('has-errors');
              return false;
              e.preventDefault();
            }else if (amount.length == '') {
              $("#has-errors").removeClass('has-errors');
              $("#amount").addClass('has-errors');
              return false;
              e.preventDefault();
            }else if (first_name.length == "") {
              $("#amount").removeClass('has-errors');
              $("#firstname").addClass('has-errors');
              return false;
              e.preventDefault();
            }else if (last_name.length == "") {
          
            $("#firstname").removeClass('has-errors');
            $("#lastname").addClass('has-errors');    
          return false;
              e.preventDefault();       
            }else if (email.length == "") {

              $("#firstname").removeClass('has-errors');
              $("#email").addClass('has-errors');
              return false;
              e.preventDefault();
            }else{
              $("#has-errors").removeClass('has-errors');
              $("#amount").removeClass('has-errors');
              $("#firstname").removeClass('has-errors');
              $("#lastname").removeClass('has-errors');
              $("#email").removeClass('has-errors');
            }
          });
          $("#amount").keydown(function (event) {
              var num = event.keyCode;
              if ((num > 95 && num < 106) || (num > 36 && num < 41) || num == 9) {
                  return;
              }

              if (event.shiftKey || event.ctrlKey || event.altKey) {
                  event.preventDefault();
              } else if (num != 46 && num != 8) {
                  if (isNaN(parseInt(String.fromCharCode(event.which)))) {
                      alert('accept only number')
                      event.preventDefault();
                  }
              }
          });
      });
  </script>

  <script>
    function myFunction() {
      input = document.getElementById("search");
      filter = input.value.toUpperCase();
      ul = document.getElementById("myUL");
      li = ul.getElementsByTagName("li");
      for (i = 0; i < li.length; i++) {
          a = li[i];
          if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
          } else {
            li[i].style.display = "none";
          }
      }
    }
  </script>
  @include('admin.include.datepicker')
  @include('admin.include.editor')
  @endsection
