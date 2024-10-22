  @extends('layout.backend')
  @section('title', 'Journal Entry By Project'. $project->project_number)
  <?php 
    $active = 'finance/accountPayable/project-booked'; 
    $subactive = 'finance/journal';
    use App\component\Content;
    $countryId = Auth::user()->country_id ? Auth::user()->country_id : 0;
  ?>
  @section('content')
    <div class="wrapper">
      @include('admin.include.header')
      @include('admin.include.menuleft')
      <div class="content-wrapper">
        <section class="content"> 
          @include('admin.include.message')
            <h3 class="border text-center" style="color:#777;">Journal Entry By Project No.: {{{ $project->project_number or ''}}}, & File No.: <b>{{$project['project_prefix']}}-{{$project['project_fileno']}}</b></h3>
           <div class="tab-pane">
              @if( $journalList->get()->count() > 0)
                <form target="_blank" action="{{route('PreviewPosted')}}"> 
                  <div>
                    <!-- <label><input id="check_all" type="checkbox" name="checkbox" style="width: 14px; height: 14px; top: 3px; position: relative;"> Check All</label> -->
                      &nbsp;&nbsp;&nbsp;
                      <input type="submit" name="priviwe_print" value="Preview & Print" class="btn btn-default btn-acc btn-xs">
                  </div>
                  <table class="table table-striped table-borderd table-hover datatable">
                    <thead>
                      <tr>
                        <th width="80px">
                          <label class="container-CheckBox" style="margin-bottom: 0px;"> All
                            <input type="checkbox" id="check_all" name="checkbox" >
                            <span class="checkmark"></span>
                          </label>
                        </th>
                        <th><a href="javascript:void(1)">Date</a></th>
                        <th><a href="#">Supplier</a></th>
                        <th title="Service Date">SVR-Date</th>
                        <th><a href="#">Account Type - Account Name</a></th>
                        <th class="text-right"><a href="javascript:void(0)" style="color: #f39c12; font-style: italic;">EST-Receive</a></th>
                        <th class="text-right"><a href="javascript:void(0)" style="color: #f39c12; font-style: italic;">EST-Cost</a></th>
                        @if($journalList->sum('book_amount') > 0)
                        <th class="text-right"><a href="javascript:void(0)">To Receive</a></th>
                        <th class="text-right"><a href="javascript:void(0)">To Pay</a></th>
                        @endif
                        @if($journalList->sum('book_kamount') > 0)
                        <th class="text-right"><a href="javascript:void(0)">To Receive {{Content::currency(1)}}</a></th>
                        <th class="text-right"><a href="javascript:void(0)">To Pay {{Content::currency(1)}}</a></th>
                        @endif
                        <th class="text-right"><a href="javascript:void(0)">Deposit</a></th>
                        <th class="text-right">Paid/Received</th>
                      </tr>
                    </thead>
                    <tbody>
                     <?php 
                        $toRBalance = 0;
                        $toPBalance = 0;
                        $toRBalancek = 0;
                        $toPBalancek = 0; 
                      ?>
                      
                      @foreach($journalList->get() as $acc) 
                        <?php 
                          $AccTrantotalAP = App\AccountTransaction::where(["journal_id"=>$acc->id, "status"=>1, 'type'=>$acc->type]);
                          $depositAmount  = $AccTrantotalAP->sum('credit') > 0 ? $AccTrantotalAP->sum('credit') : $AccTrantotalAP->sum('debit');
                          $depositAmountk= $AccTrantotalAP->sum('kcredit') > 0 ? $AccTrantotalAP->sum('kcredit') : $AccTrantotalAP->sum('kdebit');

                          $hbook  = App\HotelBooked::find($acc->book_id);
                          $book   = App\Booking::find($acc->book_id);
                          $crBook = App\CruiseBooked::find($acc->book_id);
                          $rBook  = App\BookRestaurant::find($acc->book_id);
                          $enBook = App\BookEntrance::find($acc->book_id);
                          $miBook = App\BookMisc::find($acc->book_id);

                          $toRP   = $AccTrantotalAP->sum('debit') > 0 ? ($acc->credit- $AccTrantotalAP->sum('debit')) : 0;
                          $toPA   = $AccTrantotalAP->sum('credit') > 0 ? ($acc->debit - $AccTrantotalAP->sum('credit')) : 0; 
                          $toRPk  = $AccTrantotalAP->sum('kdebit') > 0 ? ($acc->kcredit- $AccTrantotalAP->sum('kdebit')) : 0;
                          $toPAk  = $AccTrantotalAP->sum('kcredit')> 0 ? ($acc->kdebit - $AccTrantotalAP->sum('kcredit')) : 0;
                          $toRBalance  = $toRBalance + ($toRP > 0 ? $toRP : 0);
                          $toPBalance  = $toPBalance + ($toPA > 0 ? $toPA : 0); 
                          $toRBalancek = $toRBalancek + ($toRPk > 0 ? $toRPk : 0);
                          $toPBalancek = $toPBalancek + ($toPAk > 0 ? $toPAk : 0);
                        ?>
                        <tr>
                          <td>

                            <ul class="list-unstyled" style="display: flex;">
                              <li><label class="container-CheckBox" style="margin-bottom: 0px;">
                                    <input type="checkbox" class="checkall" name="value_checked[]" value="{{$acc->id}}">
                                    <span class="checkmark" ></span>
                                  </label>
                              </li>
                              <li style="padding-left: 6px"><a href="javascript:void(0)" class="btnRemoveOption" data-type="journal-entry" data-id="{{$acc->id}}" title="Remove this ?">
                                    <i class="fa fa-minus-circle text-danger" style="font-size: 16px;top: -2px;position: relative;"></i>
                                  </a>
                                </li>
                                <li style="padding-left: 6px">
                                    <a href="#" data-acc_name_title="{{{ $acc->account_name->account_code or ''}}} - {{{ $acc->account_name->account_name or ''}}}" data-payment_desc="{{$acc->remark}}" data-acc_type="{{{$acc->account_type->id or ''}}}" data-acc_name="{{{$acc->account_name->id or ''}}}" data-id="{{$acc->id}}" data-type="account_name" data-credit="{{$acc->credit}}" data-debit="{{$acc->debit}}" data-kcredit="{{$acc->kcredit}}" data-kdebit="{{$acc->kdebit}}" data-pay_date="{{ date('Y-m-d', strtotime($acc->entry_date)) }}"  class="btnEditJournal" data-toggle="modal" data-target="#myModal">
                                    <i style="font-size: 14px;top: -2px;position: relative;" class="fa fa-edit text-primary"></i></a>
                                </li>
                            </ul>
                          </td>
                         
                          <td style="width: 92px;">{{Content::dateformat($acc->entry_date)}}</td>
                          <td>{{{ $acc->supplier->supplier_name or ''}}}</td>
                          <td>                          
                            @if($acc->business_id == 1)
                              {{Content::dateformat($hbook['checkin'])}} -> {{Content::dateformat($hbook['checkout'])}}
                            @elseif ($acc->business_id == 2)
                              {{isset($rBook) ? Content::dateformat($rBook->start_date) : ''}}
                            @elseif ($acc->business_id == 4)
                              {{Content::dateformat($book['book_checkin'])}}  <!-- Flight  checkin date-->
                            @elseif ($acc->business_id == 3)
                              {{ $crBook['checkin'] ? Content::dateformat($crBook['checkin']) : ''}} 
                              {{ $crBook['checkin'] &&  $crBook['checkout'] ? "->" : ""}}
                              {{ $crBook['checkout'] ? Content::dateformat($crBook['checkout']) : ''}}
                            @elseif ($acc->business_id == 6 || $acc->business_id == 7) 
                              {{ isset($book) ? Content::dateformat($book->book_checkin) : '' }}
                            @elseif ($acc->business_id == 54)
                              {{ isset($miBook->booking) ? Content::dateformat($miBook->booking->book_checkin) : ''}}
                            @elseif ($acc->business_id == 55)
                              {{ isset($enBook) ? Content::dateformat($enBook->start_date) : '' }}
                            @elseif ($acc->business_id == 29)
                              {{ $book['book_checkin'] ? Content::dateformat($book['book_checkin']) : ''}}
                            @elseif ($acc->business_id == 9)
                              @if( isset($acc->project) ||  isset($acc->project) )
                                {{ Content::dateformat($acc->project->project_start) }} -> 
                                {{ Content::dateformat($acc->project->project_end) }}
                              @endif
                            @endif
                          </td>
                          <td><a href="#">{{{ $acc->account_type->account_name or ''}}} - {{{ $acc->account_name->account_code or ''}}} - {{{ $acc->account_name->account_name or ''}}}</a></td>
                          <td class="text-right" style="color: #f39c12; font-style: italic;">
                             {{ $acc->credit ? Content::money($acc->credit) : Content::money($acc->kdebit) }}  
                          </td>
                          <td class="text-right" style="color: #f39c12; font-style: italic;">
                            {{ $acc->debit ? Content::money($acc->debit) : Content::money($acc->kdebit) }}
                          </td>                              
                          @if($journalList->sum('book_amount') > 0)
                            <td class="text-right">{{Content::money($toRP)}}</td>
                            <td class="text-right">{{Content::money($toPA)}}</td>
                          @endif
                          @if($journalList->sum('book_kamount') > 0)
                            <td class="text-right">{{Content::money($toRPk)}}</td>
                            <td class="text-right">{{Content::money($toPAk)}}</td>
                          @endif
                          <td class="text-right" style="color: #10e719;"><b>{{$depositAmount ? Content::money($depositAmount) : Content::money($depositAmountk)}}</b></td>
                          <td class="text-right">                          
                            @if($depositAmount < $acc->credit || $depositAmountk < $acc->kcredit)
                              <a target="_blank" href="{{route('getPayable', ['journal_id'=> $acc->id])}}" class="btn btn-default btn-acc btn-xs pull-right hidden-print"> <b>Receive Now</b></a>
                            @elseif($depositAmount < $acc->debit || $depositAmountk < $acc->kdebit)
                              <a target="_blank" href="{{route('getPayable', ['journal_id'=> $acc->id])}}" class="btn btn-default btn-acc btn-xs pull-right hidden-print"><b>Pay Now</b></a>
                            @else
                              <span class="badge badge-light">{{$acc->type == 2 ? "Full Received" : "Full Paid" }}</span>
                            @endif
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                    <tr style="background:#c1ccdb;color: #795548;">
                      <th colspan="7" class="text-right">Sub Total: </th>
                      @if($journalList->sum('book_amount') > 0)
                        <th class="text-right">{!! $toRBalance > 0 ? number_format($toRBalance,2)." ".Content::currency() : ''!!}</th>
                        <th class="text-right">{!! $toPBalance > 0 ? number_format($toPBalance,2)." ".Content::currency() : ''!!}</th>
                      @endif
                      @if($journalList->sum('book_kamount') > 0)
                        <th class="text-right">{!! $toRBalancek > 0 ? number_format($toRBalancek,2)." ".Content::currency(1) : ''!!}</th>
                        <th class="text-right">{!! $toPBalancek > 0 ? number_format($toPBalancek,2)." ".Content::currency(1) : ''!!}</th>
                      @endif
                      <th></th><th></th>
                    </tr>
                  </table>
                </form>
              @endif
          </div>
        </section>
      </div>
    </div>

  @include('admin.include.datepicker')
  <div class="modal" id="myModal" role="dialog"  data-backdrop="static" data-keyboard="true">
    <div class="modal-dialog modal-lg">    
      <form method="POST" action="{{route('editJournal')}}">
        <div class="modal-content">        
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><strong>Edit Journal Entry</strong></h4>
          </div>
          <div class="modal-body">
            {{csrf_field()}}   
            <input type="hidden" name="journal_id" id="journal_id"> 
            <div class="row">
              <div class="col-md-12">
                @if(\Auth::user()->role_id == 2)
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="col-sm-3 text-right" style="padding-top: 7px;">Country</label>
                      <div class="col-sm-9">
                        <select class="form-control location" name="country" data-type="country">
                          <option>--choose--</option>
                          @foreach(App\Country::getCountry(5) as $key => $con)
                          <option value="{{$con->id}}">{{$con->country_name}}</option>
                          @endforeach()
                        </select>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                  </div>
                @endif
                <div class="col-md-5 col-xs-12">
                  <div class="form-group">
                      <label class="col-sm-4 text-right" style="padding-top: 7px;">Record Date</label>
                      <div class="col-sm-5">
                        <input type="text" name="pay_date"  id="pay_date"  class="form-control book_date" readonly="" value="{{date('Y-m-d')}}">
                      </div>
                      <div class="clearfix"></div>
                  </div>
                </div>
                <table class="table ">
                  <tr class="table-head-row">
                    <th width="250px">Account Type</th>
                    <th width="250px">Account Name</th> 
                    <th width="120px">Debit</th>
                    <th width="120px">Credit</th>
                    <th width="120px">{{Content::currency(1)}} Debit</th>
                    <th width="120px">{{Content::currency(1)}} Credit</th>
                  </tr>
                  <tbody id="data_payment_option">
                    <tr>                      
                      <td>
                        <select class="form-control account_type input-sm" name="account_type" data-type="account_name" required="">
                          <option value="0">Choose Account Types</option>
                          @foreach(App\AccountType::where('status', 1)->orderBy('account_name', 'ASC')->get() as $key=> $acc)
                            <option value="{{$acc->id}}">{{$acc->account_name}}</option>
                          @endforeach
                        </select>
                      </td>
                      <td style="position: relative; width: 40%;">
                        <div class="btn-group" style='display: block;'>
                          <button type="button" class="form-control input-sm arrow-down" data-toggle="dropdown" aria-haspopup="false" aria-expanded='false' data-backdrop="static" data-keyboard="false" role="dialog" data-backdrop="static" data-keyboard="false">
                            <span class="pull-left"></span>
                            <span class="pull-right"></span>
                          </button> 
                          <div class="obs-wrapper-search" style="max-height:250px; overflow: auto; ">
                            <div>
                              <input type="text" data-url="{{route('getFilter')}}" id="search_Account" onkeyup="filterAccountName()" class="form-control input-sm">
                            </div>
                            <ul id="myAccountName" class="list-unstyled dropdown_account_name">
                            </ul>
                          </div>
                        </div>
                      </td>
                      <td>
                        <input type="text" class="debit form-control input-sm text-right balance number_only" data-type="debit" name="debit" id="debit" placeholder="00.0">
                      </td>
                      <td>
                        <input type="text" class="credit form-control input-sm text-right balance number_only" data-type="credit" name="credit" id="credit" placeholder="00.0">
                      </td>
                      <td>
                        <input type="text" class="kyat-debit form-control input-sm text-right balance number_only" data-type="kyat-debit" name="kyatdebit" id="kyat-debit" placeholder="00.0">
                      </td>
                      <td>
                        <input type="text" class="kyat-credit form-control input-sm text-right balance number_only" data-type="kyat-credit" name="kyatcredit" id="kyat-credit" placeholder="00.0">
                      </td>
                    </tr>
                    <tr>
                      <td colspan="7" style="border-top: none;">
                        <label>Descriptions</label>
                        <textarea class="form-control" rows="4" name="payment_desc" id="payment_desc" placeholder="Type Descriptions here...!"></textarea>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="modal-footer ">
            <div class="text-center">
              <button class="btn btn-info btn-sm" id="btnUpdateAccount">Save</button>
              <a href="#" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</a>
            </div>
          </div>
        </div>      
      </form>
    </div>
  </div>
  <script type="text/javascript">
    $(document).ready(function(){
      $(document).on("click", ".btnEditJournal", function(){
        dataId = $(this).data("id");
        $("#journal_id").val(dataId);
      });   

      $("#check_all").click(function () {
        if($("#check_all").is(':checked')){
          $(".checkall").prop('checked', true);
            $(".checkall").closest("tr").css({'background': '#c3daf8'});
          // }
        } else {
          $(".checkall").prop('checked', false);
          $(".checkall").closest("tr").css({'background': 'none'});
        }
      });
      
      $(".checkall").on("click", function(){
        if ($(this).is(':checked')) {
          $(this).closest("tr").css({'background': '#c3daf8'});
        }else{
          $(this).closest("tr").css({'background': 'none'});
        }
      });
    });
  </script>
  @include("admin.account.accountant")

<script type="text/javascript">
    function filterAccountName(){
      input = document.getElementById("search_Account");
      filter = input.value.toUpperCase();
      ul = document.getElementById("myAccountName");
      li = ul.getElementsByClassName ("list");
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

@endsection

