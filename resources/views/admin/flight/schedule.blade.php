@extends('layout.backend')
@section('title', 'Flight Schedule')
<?php $active = 'supplier/flights'; 
$subactive = 'cruise/applied/cabin';
  use App\component\Content;
?>

@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
          <section class="col-lg-12 connectedSortable">
              <h3 class="border"> <i class="fa fa-plane"></i> Flight Schedule <span class="fa fa-angle-double-right"></span> <a href="{{route('createFlightSchedule')}}" class="btn btn-default btn-sm">New Schedule </a></h3>
              <form action="" method="">
                <div class="col-sm-2 pull-right" style="text-align: right;">
                  <label class="location">
                    <select class="form-control input-sm locationchange" name="location">
                      @foreach(\App\Country::where('web', 1)->whereHas('schedule')->orderBy('country_name')->get() as $loc)
                        <option value="{{$loc->id}}" {{$locationid == $loc->id ? 'selected':''}}>{{$loc->country_name}}</option>
                      @endforeach
                    </select>
                  </label>
                </div>
              </form>
              <table class="datatable table table-hover table-striped">
                <thead>
                  <tr>
                    <th width="20">Number</th>
                    <th width="190">Departure & Arrival Time</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Days</th>
                    <th>Country</th>
                    <th>Airline</th>
                    <th>Agents</th>              
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($schedules as $schedule)
                  <tr>                      
                    <td>{{$schedule->flightno}}</td>
                    <td>{{$schedule->dep_time}}  <i class="fa fa-fighter-jet"></i> {{$schedule->arr_time}}</td>
                    <td>{{$schedule->flight_from}}</td>
                    <td>{{$schedule->flight_to }}</td>
                    <td width="239px">             
                      @foreach($schedule->weekday as $day)
                        <label class="label label-default">{{substr($day->days_name,0,3)}}</label>
                      @endforeach 
                    </td>
                    <td>{{{$schedule->country->country_name or ''}}}</td>
                    <td>{{{$schedule->supplier->supplier_name or ''}}}</td>
                    <td class="text-right">
                      @foreach($schedule->flightagent as $flight)
                        <a title="View Flight Agency & Price" href="{{route('getSchedulePrice', ['url'=> $flight->id])}}" class="label label-default">{{$flight->supplier_name}} <i class="fa fa-gg"></i></a>
                      @endforeach
                    </td>
                    <td width="100" class="text-right">
                      <a href="{{route('getEditSchedule', ['urid' => $schedule->id])}}" title="Edit flight Schedule">
                        <label class="icon-list ic_book_project"></label>
                      </a>
                      <a href="javascript:void(0)" class="RemoveHotelRate" data-type="flightNo" data-id="{{$schedule->id}}" title="Remove this Flight Number ?">
                        <label class="icon-list ic-trash"></label>
                      </a>            
                    </td>                     
                  </tr>
                  @endforeach
                </tbody>
              </table>                
          </section>
        </div>
    </section>
  </div>  
</div>
<script type="text/javascript">
  $(document).ready(function(){
     $(".datatable").DataTable();
  });
</script>
@endsection
