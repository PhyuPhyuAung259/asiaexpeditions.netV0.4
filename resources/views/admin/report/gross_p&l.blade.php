@extends('layout.backend')
@section('title', 'Gross Profit & Loss Report')
<?php
  $active = 'reports'; 
  $subactive = 'arrival_report';
  use App\component\Content;
  $agent_id = isset($agentid) ?  $agentid:0;
  $locat = isset($location) ? $location:0;
  $main = isset($sort_main) ? $sort_main:0;
  $total_sell_rate=0;
  $total_net=0;
  $grand=0;
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
      <div class="row">
        <section class="col-lg-12 connectedSortable">
          <h3 class="border">Gross Profit & Loss Report</h3>
          <form method="POST" action="{{route('searchGross')}}">
            {{csrf_field()}}
            <div class="col-sm-8 pull-right">
              <div class="col-md-2">
                
                <input class="form-control input-sm" type="text" id="from_date" name="start_date" placeholder="From Date" value="{{{$startDate or ''}}}"> 
              </div>
              <div class="col-md-2" style="padding-right: 0px;">
                <input class="form-control input-sm" type="text" id="to_date" name="end_date" placeholder="To Date" value="{{{$endDate or ''}}}"> 
              </div>
              <div class="col-md-2" style="padding-right: 0px;">
                <select class="form-control input-sm" name="agent">
                  <option value="0">All Agent</option>
                  @foreach(App\Supplier::getSupplier(9)->whereNotIn('pro.project_fileno', ["","Null",0])->get() as $agent)
                  <option value="{{$agent->id}}" {{$agent->id == $agent_id ? 'selected':''}}>{{$agent->supplier_name}}</option>
                  @endforeach
                </select>
              </div>             
              <div class="col-md-2">
                <select class="form-control input-sm" name="sort_location">
                  
                  <option value="AM" {{$locat == 'AM' ? 'selected':''}}>AM</option>
                  <option value="AE" {{$locat == 'AE' ? 'selected':''}}>AE</option>
                </select>
              </div>
              <div class="col-md-2" style="padding: 0px;">
                <button class="btn btn-primary btn-sm" type="submit">Search</button>
              </div>          
            </div>
        
            <table class="datatable table table-hover table-striped">
              <thead>
                <tr>
                  <th width="48px">File No.</th>
                  <th>Client Name</th>
                  <th>No of Pax</th>
                  <th class="text-center">Status</th>
                  <th>Flight Arrival</th>
                  <th width="123px">Flight Departure</th>
                  <th width="162px">Start Date-End Date</th>
                  <th>selling rate</th>
                  <th>Grand total</th>
                  <th>Gross profit & Loss</th>
                  <th class="text-center">Preview</th>
                </tr>
              </thead>
              <tbody>
                <?php $toTalPax = 0; ?>
                @foreach($projects as $pro)
                  <?php 
                
                    $guideSupplier = App\BookGuide::where(['project_number'=>$pro->project_number])->groupBy('supplier_id')->orderBy("created_at")->get(); 
                    $toTalPax = $toTalPax + $pro->project_pax;
                  ?>
                  <tr>
                    <td>{{$pro->project_prefix}}-{{$pro->project_fileno}}</td>
                    <td>{{$pro->project_client}} 
                      @if($pro->project_pax)
                        <span style="font-weight: 700; color: #3F51B5;">x {{$pro->project_pax}}</span>
                      @endif
                    </td> 
                    <td>{{{$pro->project_pax or ''}}}</td>
                    <td class="text-center">
                      <div class="btn-group">
                        <span style="cursor: pointer;-webkit-box-shadow:none;box-shadow:none;" class=" dropdown-toggle" data-toggle="dropdown"> 
                          <i class="fa fa-circle" style="color: {{$pro->active == 1 ? '#21ef91': '#FF5722'}}; font-size:12px;"></i> &nbsp; {{$pro->active == 1 ? 'Active': 'Inactive'}}
                        &nbsp;&nbsp;<i  class="fa fa-angle-down"></i>
                        </span>
                        <ul class="dropdown-menu" style="min-width: 100%; padding: 0px;">
                          <li><a href="#" style="padding: 10px;"><i class="fa fa-circle" style="color: #21ef91;"></i>Active {!!$pro->active==1?'<i class="fa fa-check" style="color: #009688;font-style: italic;"></i>' : '' !!}</a></li>
                          <li><a href="#" style="padding: 10px;"><i class="fa fa-circle" style="color: #FF5722;"></i>Inactive 
                          {!!$pro->active==0?'<i class="fa fa-check" style="color: #009688;font-style: italic;"></i>' : '' !!} </a> </li>
                        </ul>
                      </div>
                    </td>
                    <td>
                      @if(isset($pro->flightArr->flightno))
                        {{{ $pro->flightArr->flightno or ''}}}-D:{{{$pro->flightArr->dep_time or ''}}}->A:{{{$pro->flightArr->arr_time or ''}}}
                      @endif
                    </td>   
                    <td>
                      @if(isset($pro->flightDep->flightno))
                        {{{ $pro->flightDep->flightno or ''}}}-D:{{{$pro->flightDep->dep_time or ''}}}->A:{{{$pro->flightDep->arr_time or ''}}}
                      @endif
                    </td>          

                    <td>
                      {{Content::dateformat($pro->project_start)}} - {{Content::dateformat($pro->project_end)}}
                    </td>
                    
                    <!-- <td><span style="text-transform: capitalize;"></span></td> -->
                    <?php 
                      $hotelBook= \App\HotelBooked::where(['project_number'=>$pro->project_number])->orderBy("checkin");
                      $flightBook = App\Booking::flightBook($pro->project_number);
                      $golfBook = App\Booking::golfBook($pro->project_number);
                      $restBook = \App\BookRestaurant::where('project_number', $pro->project_number)->orderBy('start_date');
                      $cruiseBook = App\CruiseBooked::where(['project_number'=>$pro->project_number]);
                      $EntranceBook = \App\BookEntrance::where('project_number', $pro->project_number)->orderBy('start_date', 'ASC');
                      $btran = App\BookTransport::where(['project_number'=>$pro->project_number, 'book_id'=>$pro->book_id])->first();
                      $price = isset($btran->price)? $btran->price:0;
                      $transportTotal =0; 
                      $transportkTotal =0; 
                      $kprice = isset($btran->kprice)? $btran->kprice:0;
                      $transportTotal = $transportTotal + $price;
                      $transportkTotal = $transportkTotal + $kprice;
                      $bg  = App\BookGuide::where(['project_number'=>$pro->project_number,'book_id'=>$pro->book_id])->first(); 
                      $guidTotal = 0;
                      $guidkTotal = 0;
                      $price = isset($bg->price)?$bg->price :0;
                      $guidTotal = $guidTotal + $price;
                      $kprice = isset($bg->kprice)? $bg->kprice :0;
                      $guidkTotal = $guidkTotal + $kprice;
                      $miscTotal =0;
                      $misckTotal =0;
                      $miscService = App\BookMisc::where(['project_number'=>$pro->project_number,'book_id'=>$pro->book_id])->orderBy("created_at", "DESC")->get();
                      foreach($miscService as $misc){
							            	
								            	$miscTotal = $miscTotal + $misc->amount;
								            	$misckTotal = $misckTotal + $misc->kamount;
                      } 
                      ?>
                    <td> <?php 
                    if(empty($pro->project_selling_rate))  {
                      $sell_rate = ($hotelBook->sum('sell_amount') + $flightBook->sum('book_amount') + $golfBook->sum('book_amount')
                                    + $cruiseBook->sum('sell_amount') + $restBook->sum('amount') + $EntranceBook->sum('amount')) 
                                    + $transportTotal + $guidTotal + $miscTotal;
                    }else{
                      $sell_rate=$pro->project_selling_rate;
                    }
                        $total_sell_rate= $total_sell_rate + $sell_rate;
                    ?> {{number_format($sell_rate,2)}}</td>
                    <td>
                      <!-- Grand total -->
                    <?php   
                      $grandtotal = ($hotelBook->sum('net_amount') + $flightBook->sum('book_namount') + $golfBook->sum('book_namount')
                                    + $cruiseBook->sum('net_amount') + $restBook->sum('amount') + $EntranceBook->sum('amount')) 
                                    + $transportTotal + $guidTotal + $miscTotal;
                      $total_net=$grandtotal+$total_net;
                      $grand=$total_sell_rate - $total_net;
                    ?>
                     {{ number_format($grandtotal,2)}}
                    </td>  
                    <td><?php $gross_profit= $sell_rate - $grandtotal?> {{$gross_profit}} </td>   
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
              <tfoot>
                <tr>
                    <td colspan="2" class="text-right"><strong>Total Number of Pax : </strong></td>
                    <td colspan="4" >{{$toTalPax}}</td>
                    <td class="text-right"> <strong> Grand Total :</strong> </td>
                    <td>{{$total_sell_rate}}</td>
                    <td>{{$total_net}}</td>
                    <td>{{$grand}}</td>
                </tr>
              </tfoot>
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
  });
</script>
@include('admin.include.datepicker')
@endsection
