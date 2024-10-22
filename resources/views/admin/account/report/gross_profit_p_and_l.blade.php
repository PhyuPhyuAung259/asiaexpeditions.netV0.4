@extends('layout.backend')
@section('title', "Gross Profit P & L")
<?php
  $active = 'reports'; 
  $subactive = 'arrival_report';
  use App\component\Content;
  $agent_id = isset($agentid) ?  $agentid:0;
  $locat = isset($location) ? $location:0;
  $main = isset($sort_main) ? $sort_main:0;
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
      <div class="row">
        <section class="col-lg-12 connectedSortable">
          <h3 class="border">Gross Profit P & L</h3>
          <form method="POST" action="{{route('searchGrossProfitPL')}}">
            {{csrf_field()}}
            <div class="col-sm-8 pull-right">
              <div class="col-md-2">
                <input type="hidden" name="" value="{{{$projectNo or ''}}}" id="projectNum">
                <input readonly class="form-control input-sm" type="text" id="from_date" name="start_date" placeholder="From Date" value="{{{$currentDate or ''}}}"> 
              </div>
              <div class="col-md-2" style="padding-right: 0px;">
                <input readonly class="form-control input-sm" type="text" id="to_date" name="end_date" placeholder="To Date" value="{{{$endDate or ''}}}"> 
              </div>             
              <div class="col-md-2">
                <button class="btn btn-primary btn-sm" type="submit">Search</button>
              </div> 
              <div class="col-md-2" style="padding: 0px;">
                <button class=" btn btn-primary btn-acc btn-sm" type="submit" name="printBtn" value="print">Print <i class="fa fa-print"></i></button>
              </div>                    
            </div>
            <table class="datatable table table-hover table-striped">
              <thead>
                <tr>
                  <th width="70px"><input style="position: absolute; z-index: 999; width: 14px; height:14px; cursor: pointer;" type="checkbox" id="check_all">  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;File No.</th>
                  <th>Client Name & Pax</th>
                  <th>Revenues</th>
                  <th>Cost Of sales</th>
                  <th>Gross Profit</th>
                  <th class="text-center">(%)</th>
                  <th>Agent</th>
                  <th>User</th>
                  <th width="131px">Start Date-End Date</th>
                  <th class="text-center">Preview</th>
                </tr>
              </thead>
              <tbody>
                @foreach($projects as $pro)
                  <?php   
                    $hotelBook  = App\HotelBooked::where('project_number', $pro->project_number);
                    $cruiseBook = App\CruiseBooked::where('project_number', $pro->project_number);
                    $tourBook   = App\Booking::tourBook($pro->project_number);
                    $flightBook = App\Booking::flightBook($pro->project_number);
                    $golfBook   = App\Booking::golfBook($pro->project_number);
                    // $grandtotal = $cruiseBook->sum('sell_amount') + $golfBook->sum('book_amount') + $flightBook->sum('book_amount') + $hotelBook->sum('sell_amount') + $tourBook->sum('book_amount');
                    // $netAmount = $cruiseBook->sum('net_amount') + $golfBook->sum('book_namount') + $flightBook->sum('book_namount') + $hotelBook->sum('net_amount') + $tourBook->sum('book_namount');
                    $netAmountTran = App\AccountJournal::where(['project_id'=>$pro['id'], 'status'=>1])->whereNotIn('account_type_id', [8])->whereNotNull('project_id');

                    $netAmountTranRP = App\AccountJournal::where(['project_id'=>$pro['id'], 'status'=>1, 'account_type_id'=>8])->whereNotNull('project_id');
                    $netAmount = $netAmountTran->sum("debit");
                    if (empty((int)$pro->project_selling_rate)) {
                      $Project_total = $netAmountTranRP->sum('credit');
                    }else{
                      $Project_total =  $pro->project_selling_rate;
                    }
                    // $totalRevenue = ($Project_total + $pro->project_add_invoice) - $pro->project_cnote_invoice;
                    $totalRevenue = $netAmountTranRP->sum('credit');
                    // $totalRevenue = ($getRevenue->sum('credit') );

                    $grossProfit = $totalRevenue - $netAmount;
                    $getPercentage = $grossProfit > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;
                  ?>
                  <tr>
                    <td><input type="checkbox" name="checkSegment[]" value="{{$pro->id}}" class="checkall" > {{$pro->project_prefix}}-{{$pro->project_fileno}}</td>
                    <td>{{$pro->project_client}} 
                      @if($pro->project_pax)
                        <span style="font-weight: 700; color: #3F51B5;">x {{$pro->project_pax}}</span>
                      @endif
                    </td> 
                    <td>{{Content::money($totalRevenue)}}</td>
                    <td class="text-right"><a target="_blank" href="{{route('previewPosting', ['project_number'=> $pro['project_number'], 'type'=>'Posting Account'])}}">{{Content::money($netAmount)}}</a></td>
                    <td class="text-right"><a target="_blank" href="{{route('previewPosting', ['project_number'=> $pro['project_number'], 'type'=>'Posting Account'])}}"><b>{{Content::money($grossProfit)}}</b></a></td>
                    <td class="text-right"><a href="#">{{$getPercentage > 0 ? Content::money($getPercentage). '%' : ''}} </a></td>
                    <td>{{{$pro->supplier->supplier_name or ''}}}</td>   
                    <td>{{{$pro->user->fullname or ''}}}</td>
                    <td>{{Content::dateformat($pro->project_start)}} - {{Content::dateformat($pro->project_end)}}</td>
                    <td class="text-right">                      
                      <a target="_blank" href="{{route('previewProject', ['project'=>$pro->project_number, 'type'=>'operation'])}}" title="Operation Program">
                        <label style="cursor: pointer;" class="icon-list ic_ops_program"></label>
                      </a>
                      <a target="_blank" href="{{route('previewProject', ['project'=>$pro->project_number, 'type'=>'sales'])}}" title="Prview Details">
                        <label style="cursor: pointer;" class="icon-list ic_del_drop"></label>
                      </a>               
                    </td>                     
                  </tr>
                @endforeach
              </tbody>
            </table>
          </form>
        </section>
      </div>
    </section>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $(".datatable").DataTable({
      language: {
        searchPlaceholder: "Number No., File No.",
      }
    });

    $("#check_all").click(function () {
        if($("#check_all").is(':checked')){
           // Code in the case checkbox is checked.
            $(".checkall").prop('checked', true);
        } else {
             // Code in the case checkbox is NOT checked.
            $(".checkall").prop('checked', false);
        }
    });
  });
</script>
@include('admin.include.datepicker')
@endsection
