@extends('layout.backend')
@section('title', 'Booked Project List')
<?php 
  $active = 'booked/project'; 
  $subactive ='booked/project';
  use App\component\Content;
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
          <form method="POST" action="{{route('searchProject', ['project'=> 'project'])}}">
            {{csrf_field()}}
            <section class="col-lg-12 connectedSortable">
              <h3 class="border">Project List <span class="fa fa-angle-double-right"></span> <a href="{{route('proForm')}}" class="btn btn-default btn-sm">Create Project</a></h3>
              <div class="col-sm-8 pull-right">
                <div class="col-md-3">
                  <input type="hidden" name="" value="{{{$projectNum or ''}}}" id="projectNum">
                  <input class="form-control input-sm" type="text" id="from_date" name="start_date" placeholder="From Date" value="{{{$startDate or ''}}}" readonly> 
                </div>
                <div class="col-md-3">
                  <input class="form-control input-sm" type="text" id="to_date" name="end_date" placeholder="To Date" value="{{{$endDate or ''}}}" readonly> 
                </div>
                 <div class="col-md-2" style="padding: 0px;">
                  <button class="btn btn-default btn-sm" type="submit">Search</button>
                </div>
              </div>
              <table class="datatable table table-hover table-striped">
                <thead>
                  <tr>                       
                    <th width="65">Project No.</th>
                    <th>Date</th>    
                    <th>Country</th>    
                    <th>Province</th>                   
                    <th>Type</th>
                    <th>Service</th>
                    <th>Pax</th>
                    <th>Day</th>
                    <th>Price {{Content::currency()}}</th>
                    <th>Amount {{Content::currency()}}</th>
                    <th>Price {{Content::currency(1)}}</th>
                    <th>Amount {{Content::currency(1)}}</th>
                    <th class="text-center" >Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($projectBooked as $project)
                  <tr>
                    <td width="75">{{$project->book_project}}</td>
                    <td>{{Content::dateformat($project->book_checkin)}}</td>
                    <td>{{{$project->country->country_name or ''}}}</td>
                    <td>{{{$project->province->province_name or ''}}}</td>
                      <?php        
                        if($project->tour_id > 0){
                          $bsn = "tour";
                          $service = App\Tour::find($project->tour_id);
                          $serviceName = $service->tour_name;
                        }elseif ($project->hotel_id > 0) {
                          $bsn = "hotel";
                          $service = App\Supplier::find($project->hotel_id);
                          if($service){
                            $serviceName = $service->supplier_name;
                          }
                        }elseif ($project->flight_id > 0) {
                          $bsn = "flight";
                          $service = App\Supplier::find($project->flight_id);
                          if($service){
                            $serviceName = $service->supplier_name;
                          }else{
                            $serviceName = "";
                          }
                        }elseif ($project->cruise_id > 0) {
                          $bsn = "cruise";
                          $service = App\Supplier::find($project->cruise_id);
                          $serviceName = $service->supplier_name;
                        }elseif ($project->golf_id > 0) {
                          $bsn = "golf";
                          $service = App\Supplier::find($project->golf_id);
                          $serviceName = $service->supplier_name;
                        }else{
                          $bsn = "";
                          $serviceName = "";
                        }
                      ?>  
                    <td><span style="text-transform: capitalize;">{{ $bsn }}</span></td>
                    <td style="width: 15%;">{{ $serviceName}}</td>
                    <td>{{$project->book_pax}}</td>
                    <td>{{$project->book_day}}</td>
                    <td class="text-right">{{$project->book_price}}</td>
                    <td class="text-right">{{$project->book_amount}}</td>
                    <td class="text-right">{{$project->book_kprice}}</td>
                    <td class="text-right">{{$project->book_kamount}}</td>
                    <td class="text-center">
                      <a target="_blank" href="{{route('previewProject', ['project'=>$project->book_project, 'type'=>'details'])}}" title="Program Details">
                        <label class="icon-list ic_ops_program"></label>
                      </a>
                      @if($bsn != '')
                      <a target="_blank" href="{{route('bookingEdit', ['url'=> $bsn, 'id'=>$project->id])}}" title="Edit Cruise">
                      @else
                      <a href="javascript:void(0)" title="Invalid Link">
                      @endif
                        <label class="icon-list ic_edit"></label>
                      </a>&nbsp;          
                      <a href="javascript:void(0)" class="RemoveHotelRate" data-type="booked_project" data-id="{{$project->id}}" title="Delete this booking">
                        <label class="icon-list ic_remove"></label>
                      </a>                    
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </section>
          </form>
        </div>
    </section>
</div>
<script type="text/javascript">
  $(document).ready(function(){
     $(".datatable").DataTable();
  });
</script>
@include('admin.include.datepicker')
@endsection
