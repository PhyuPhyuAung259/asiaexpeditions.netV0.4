@extends('layout.backend')
@section('title', 'Flight Schedule')
<?php $active = 'supplier/flights'; 
  $subactive = 'supplier/flights';
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
              <h3 class="border"> <i class="fa fa-plane"></i> {{$agent->supplier_name}} Ticketing Agent <span class="fa fa-angle-double-right"></span> <a href="{{route('createFlightSchedule')}}" class="btn btn-default btn-sm">New Schedule </a></h3>
              <form method="GET" action="{{route('upscheduleprice')}}">
              {{csrf_field()}}
              <table class="table table-hover table-striped datatable" id="hotel-rate">
                <thead>
                  <tr>
                    <th>Number</th>
                    <th>Dep & Arr Time</th>
                    <th class="text-center">From <i class="fa fa-long-arrow-right"></i> To</th>
                    <th>Airline</th>
                    <th class="text-center" title="Oneway {{Content::currency()}}">Oneway</th>
                    <th class="text-center" title="Return {{Content::currency()}}">Return </th>
                    <th class="text-center" title="Oneway Net {{Content::currency()}}">OnewayNet</th>
                    <th class="text-center" title="Return Net {{Content::currency()}}">ReturnNet</th>
                    <th class="text-center" title="Oneway {{Content::currency(1)}}">Oneway{{Content::currency(1)}}</th>
                    <th class="text-center" title="Oneway {{Content::currency(1)}}">Return{{Content::currency(1)}}</th>
                    <th class="text-center">Action</th> 
                  </tr>
                </thead>
                <tbody>
                  @foreach($schedulesNo as $schedule)
                  <input type="hidden" name="eid" name="eid" value="{{$schedule->id}}">
                  <tr>                      
                    <td>{{$schedule->flightno}}</td>
                    <td class="text-center"><span style="color: #dbb20c;">{{$schedule->dep_time}}</span> <i class="fa fa-fighter-jet"></i> <span style="color: #dbb20c;">{{$schedule->arr_time}}</span></td>
                    <td class="text-center">{{$schedule->flight_from}} <i class="fa fa-long-arrow-right"></i> {{$schedule->flight_to }}</td>   
                    <?php 
                      $airline = App\Supplier::find($schedule->supplier_id);
                    ?>
                    <td>{{{$airline->supplier_name or ''}}}</td>                  
                    <td><input type="text" name="oneway_price" value="{{$schedule->oneway_price}}" class="oneway_price number_only form-control input-sm text-center" style="width: 85px;" title="Double click to enter price "></td>
                    <td><input type="text" name="return_price" value="{{$schedule->return_price}}" class="return_price number_only form-control input-sm text-center" style="width: 85px;" title="Double click to enter price "></td>
                    <td><input type="text" name="oneway_nprice" value="{{$schedule->oneway_nprice}}" class="oneway_nprice number_only form-control input-sm text-center" style="width: 85px;" title="Double click to enter price "></td>
                    <td><input type="text" name="return_nprice" value="{{$schedule->return_nprice}}" class="return_nprice number_only form-control input-sm text-center" style="width: 85px;" title="Double click to enter price "></td>
                    <td><input type="text" name="oneway_kprice" value="{{$schedule->oneway_kprice}}" class="oneway_kprice number_only form-control input-sm text-center" style="width: 85px;" title="Double click to enter price "></td>
                    <td><input type="text" name="return_kprice" value="{{$schedule->return_kprice}}" class="return_kprice number_only form-control input-sm text-center" style="width: 85px;" title="Double click to enter price "></td>
                    <td class="text-center">                    
                      <button type="submit" class="btnSaveFlight btn btn-flat btn-success btn-sm" data-id="{{$schedule->id}}" data-url="{{route('upscheduleprice')}}">Save</button>
                    </td>                     
                  </tr>
                  @endforeach
                </tbody>
              </table> 
            </form>   
              @if($schedulesNo->count() > 0)
                <div class="form-group text-center">
                  <a href="{{route('getFlightSchedule')}}" class="btn btn-primary btn btn-flat btn-md">Back</a>                 
                </div>    
              @endif                       
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
