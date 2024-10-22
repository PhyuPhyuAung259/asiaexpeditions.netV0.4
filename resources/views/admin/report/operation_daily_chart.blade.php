@extends('layout.backend')
@section('title', 'Client Arrival Report')
<?php
  $active = 'reports'; 
  $subactive = 'project/operation-daily-chart';
  use App\component\Content;
  $agent_id = isset($agentid) ? $agentid:0;
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
          <h3 class="border">Operation Daily Chart</h3>
          <form method="POST" action="{{route('searchPOSDailyChart')}}">
            {{csrf_field()}}
            <div class="col-sm-8 pull-right">
              <div class="col-md-2">
                <input type="hidden" name="" value="{{{$projectNo or ''}}}" id="projectNum">
                <input readonly class="form-control input-sm" type="text" id="from_date" name="start_date" placeholder="From Date" value="{{{$startDate or ''}}}"> 
              </div>
              <div class="col-md-2" style="padding-right: 0px;">
                <input readonly class="form-control input-sm" type="text" id="to_date" name="end_date" placeholder="To Date" value="{{{$endDate or ''}}}"> 
              </div>
              <div class="col-md-2">
                <select class="form-control input-sm" name="sort_location">
                  @foreach(\App\Country::countryByProject() as $con)
                    <option value="{{$con->id}}" {{isset($country) && $con->id == $country ? 'selected' : ''}}>{{$con->country_name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-2" style="padding: 0px;">
                <button class="btn btn-primary btn-sm" type="submit">Search</button>
              </div>   
              <div class="col-md-4 text-right" >
                 <!-- <button class="btn btn-primary btn-sm" type="submit">Search</button> -->
                <span class="btn btn-primary btn-sm myConvert"> <i class="fa fa-download"></i>Download</span>
              </div>
            </div>
          <table class="datatable table table-hover table-striped">
            <thead>
              <tr>
                <th style="width:50px;">Date</th>
                <th style="width: 18px;">FileNo.</th>
                <th style="width: 250px;">Client Name</th>
                <th >City / Tour Name</th>
                <th width="170px">Tour Start->End Date</th>
                <th>Guide Name / Phone</th>
                <th>Driver Name/ Phone</th>
                <th>City/Hotel</th>
                <th>City/Golf</th>
                <th>Restaurant</th>
                <!-- <th style="width: 60px;">Status</th> -->
              </tr>
            </thead>
            <tbody>
              @foreach($bookingTour as $pro)
                <?php  
                  $project = App\Project::where('project_number', $pro->project_number)->first();
                  $province = App\Province::find($pro->province_id);
                  $guide  = App\BookGuide::where(['project_number'=>$pro->project_number, 'book_id'=> $pro->book_id])->orderBy("created_at")->first();  
                  $transport = App\BookTransport::where(['project_number'=>$pro->project_number, 'book_id'=>$pro->book_id])->first();

                  $hotel= \App\HotelBooked::where(['project_number'=>$pro->project_number])
                          ->whereDate('checkin', $pro->book_checkin)
                          ->orderBy("checkin", "ASC")->groupBy('hotel_id')->first();
                  $golfSupplier = App\Booking::golfBook($pro->project_number)->whereDate('book_checkin', $pro->book_checkin)->first();

                  $restaurant = \App\BookRestaurant::where('project_number', $pro->project_number)
                    ->whereDate('start_date', $pro->book_checkin)
                    ->orderBy("start_date")->first();
                ?>
              <tr>
                <td>{{Content::dateformat($pro->book_checkin)}}</td>
                <td><b>{{{ $project->project_prefix or ''}}}-{{{ $project->project_fileno or ''}}}</b></td>
                <td>{{$project->project_client}} 
                  @if($project)
                  <span style="color: #034ea2;"> x {{$project->project_pax}} Pax</span>
                  @endif
                </td>
                
                <td> {{$pro->tour_name}}, <span style="color: #034ea2;">{{{ $province->province_name or ''}}}</span></td>
                <td>
                  @if($project)
                    {{Content::dateformat($project->project_start)}} -> {{Content::dateformat($project->project_end)}} 
                  @endif
                </td>
                <td>
                  @if($guide)
                    <div>{{{$guide->supplier->supplier_name or ''}}}</div> 
                    @if(isset($guide->supplier->supplier_phone))
                       {{$guide->supplier->supplier_phone}}
                    @endif
                    @if(isset($guide->supplier->supplier_phone))
                       {{$guide->supplier->supplier_phone2}}
                    @endif
                  @endif
                </td>
                <td>
                    <div>{{{ $transport->vehicle->name or ''}}}</div>
                    @if(isset($transport->driver->driver_name))
                    / {{{ $transport->driver->driver_name or ''}}}
                    @endif
                    @if(isset($transport->driver->phone2))
                    / {{{ $transport->driver->phone2 or ''}}}
                    @endif
                 </td>
                <td>
                  @if(isset($hotel))
                    <?php $hotelSupplier = App\Supplier::find($hotel->hotel_id); ?>
                    <div>
                     
                      {{{ $hotel->hotel->supplier_name or ''}}}
                    </div>
                    @if($hotel) 
                      <div><span style="color: #034ea2;">CIN</span>:
                      <strong>{{Content::dateformat($hotel->checkin)}}->{{Content::dateformat($hotel->checkout)}}</strong>
                     @if(isset($hotelSupplier))
                        ,<span style="color: #034ea2;">{{$hotelSupplier->province->province_name }}</span>
                      @endif</div>
                    @endif
                  @endif
                  
                </td>
                <td>
                  @if(isset($golfSupplier))
                    <?php $Golfprovince = App\Province::find($golfSupplier->province_id); ?>
                  {{{$golfSupplier->supplier_name or ''}}}
                    @if($Golfprovince), 
                    ,<span style="color: #034ea2;">Tee Time:{{$golfSupplier->book_golf_time}}</span>
                      ,<span style="color: #034ea2;">{{$Golfprovince->province_name}}</span>
                    @endif
                  @endif
                </td>
                <td>
                  @if(isset($restaurant->supplier))
                     <?php $RestProvince = App\Province::find($restaurant->province_id); ?>
                      {{{$RestProvince->supplier_name or ''}}}
                    {{{$restaurant->supplier->supplier_name or ''}}}
                    @if($RestProvince)
                      ,<span style="color: #034ea2;">{{$RestProvince->province_name}}</span>
                    @endif
                  @endif
                </td>
               <!--  <td>
                    <div class="btn-group">
                      <span style="cursor: pointer;-webkit-box-shadow:none;box-shadow:none;" class=" dropdown-toggle" data-toggle="dropdown"> 
                        <i class="fa fa-circle" style="color: {{$pro->active == 1 ? '#21ef91': '#FF5722'}}; font-size:12px;"></i> {{$pro->active == 1 ? 'Active': 'Inactive'}}
                      <i  class="fa fa-angle-down"></i>
                      </span>
                      <ul class="dropdown-menu" style="min-width: 100%; padding: 0px;">
                        <li><a href="#" style="padding: 10px;"><i class="fa fa-circle" style="color: #21ef91;"></i>Active {!!$pro->active==1?'<i class="fa fa-check" style="color: #009688;font-style: italic;"></i>' : '' !!}</a></li>
                        <li><a href="#" style="padding: 10px;"><i class="fa fa-circle" style="color: #FF5722;"></i>Inactive 
                        {!!$pro->active==0?'<i class="fa fa-check" style="color: #009688;font-style: italic;"></i>' : '' !!} </a> </li>
                      </ul>
                    </div>
                </td> -->
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
<script type="text/javascript" src="{{asset('js/jquery.table2excel.min.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $(".datatable").DataTable({
      // language: {
      //   searchPlaceholder: "Number No., File No.",
      // }
      columnDefs: [
        {  type : "datetime-moment", targets:  "sort-date" },
     ]
    });

    $(".myConvert").click(function(){
        if(confirm('Do you to export in excel?')){
          $(".datatable").table2excel({
            exclude: ".noExl",
            name: "Daily Operation Chart {{{ $startDate or ''}}} - {{{ $endDate or ''}}}",
            filename: "Daily Operation Chart {{{ $startDate or ''}}} - {{{ $endDate or ''}}}",
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
@include('admin.include.datepicker')
@endsection
