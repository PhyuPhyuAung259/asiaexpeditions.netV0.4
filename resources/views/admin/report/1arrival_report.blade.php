@extends('layout.backend')
@section('title', 'Client Arrival Report')
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
          <h3 class="border">Client Arrival Report</h3>
          <form method="POST" action="{{route('searchArrival')}}">
            {{csrf_field()}}
            <div class="col-sm-8 pull-right">
              <div class="col-md-2">
                <input type="hidden" name="" value="{{{$projectNo or ''}}}" id="projectNum">
                <input readonly class="form-control input-sm" type="text" id="from_date" name="start_date" placeholder="From Date" value="{{{$startDate or ''}}}"> 
              </div>
              <div class="col-md-2" style="padding-right: 0px;">
                <input readonly class="form-control input-sm" type="text" id="to_date" name="end_date" placeholder="To Date" value="{{{$endDate or ''}}}"> 
              </div>
              <div class="col-md-2" style="padding-right: 0px;">
                <select class="form-control input-sm" name="agent">
                  @foreach(App\Supplier::getSupplier(9)->whereNotIn('pro.project_fileno', ["","Null",0])->get() as $agent)
                  <option value="{{$agent->id}}" {{$agent->id == $agent_id ? 'selected':''}}>{{$agent->supplier_name}}</option>
                  @endforeach
                </select>
              </div>             
              <div class="col-md-2">
                <select class="form-control input-sm" name="sort_location">
                  <option value="AE" {{$locat == 'AE' ? 'selected':''}}>AE</option>
                  <option value="AM" {{$locat == 'AM' ? 'selected':''}}>AM</option>
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
                  <th>Client Name & Pax</th>
                  <th class="text-center">Status</th>
                  <th>Flight Arrival</th>
                  <th width="123px">Flight Departure</th>
                  <th width="162px">Start Date-End Date</th>
                  <th width="280px">Guide Name</th>
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
                    <td>
                      @if($guideSupplier->count() > 0)
                        @foreach($guideSupplier as $key=> $sup)
                          @if($sup->supplier)
                            <span style="margin: 0px 2px; border: solid 1px #9e9e9ea1;padding: 0px 3px;border-radius: 3px;">{{{$sup->supplier->supplier_name or ''}}} <span style="color: #034ea2;">{{{ $sup->province->province_name or ''}}},</span></span>
                          @endif
                        @endforeach
                      @endif
                    </td>
                    <!-- <td><span style="text-transform: capitalize;"></span></td> -->
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
                  <tr><th colspan="8" class="text-right"><h3>Total Number of Pax: {{$toTalPax}}</h3></th></tr>
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
