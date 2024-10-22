
@extends('layout.backend')
@section('title', 'Gross Profit P & L ')
<?php 
  use App\component\Content;
  $comadd = \App\Company::find(1);
?>
@section('content')
<div class="container">
    @include('admin.report.headerReport') 
  @if(isset($projects) && $projects->count() > 0)
    <p class="text-right hidden-print">
      <a href="javascript:void(0)" class="myConvert hidden-print"><span class=" btn btn-primary btn-acc btn-xs"><i class="fa fa-download"></i> Download In Excel</span></a>&nbsp;
      <span onclick="window.print()" class=" btn btn-xs btn-primary btn-acc"><i class="fa fa-print"></i> Print</span>
    </p>
    <table class="datatable table table-hover table-striped">
      <thead>
        <tr>
          <th width="75px">File No.</th>
          <th>Client Name & Pax</th>
          <th>Revenues</th>
          <th>Cost Of sales</th>
          <th>Gross Profit</th>
          <th class="text-center">(%)</th>
          <th>Agent</th>
          <th>User</th>
          <th width="170px">Date From - To</th>
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
            $grandtotal = $cruiseBook->sum('sell_amount') + $golfBook->sum('book_amount') + $flightBook->sum('book_amount') + $hotelBook->sum('sell_amount') + $tourBook->sum('book_amount');
            $netAmount = $cruiseBook->sum('net_amount') + $golfBook->sum('book_namount') + $flightBook->sum('book_namount') + $hotelBook->sum('net_amount') + $tourBook->sum('book_namount');
            if (empty((int)$pro->project_selling_rate)) {
              $Project_total = $grandtotal;
            }else{
              $Project_total =  $pro->project_selling_rate;
            }
            $totalRevenue = ($Project_total + $pro->project_add_invoice) - $pro->project_cnote_invoice;
            $grossProfit = $totalRevenue - $netAmount;
            $getPercentage = $grossProfit > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;
          ?>
          <tr>
            <td>{{$pro->project_prefix}}-{{$pro->project_fileno}}</td>
            <td>{{$pro->project_client}} 
              @if($pro->project_pax)
                <span style="font-weight: 700; color: #3F51B5;">x {{$pro->project_pax}}</span>
              @endif
            </td> 
            <td>{{Content::money($totalRevenue)}}</td>
            <td>{{Content::money($netAmount)}}</td>
            <td><a href="#"><b>{{Content::money($grossProfit)}}</b></a></td>
            <td><a href="#">{{Content::money($getPercentage)}}</a></td>
            <td>{{{$pro->supplier->supplier_name or ''}}}</td>   
            <td>{{{$pro->user->fullname or ''}}}</td>
            <td>{{Content::dateformat($pro->project_start)}} - {{Content::dateformat($pro->project_end)}}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>
<div class="clearfix"></div>
<br><br><br>
<script type="text/javascript" src="{{asset('js/jquery.table2excel.min.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $(".myConvert").click(function(){
      if(confirm('Do you to export in excel?')){
        $(".datatable").table2excel({
          exclude: ".noExl",
          name: "Gross Profit P & L",
          filename: "Gross Profit P & L On {{date('d-F-Y')}}",
          fileext: ".xls",
          exclude_img: true,
          exclude_links: true,
          exclude_inputs: true
        });
        return false;
      }else{
        return false;
      }
    });
  });
</script>
@endsection
