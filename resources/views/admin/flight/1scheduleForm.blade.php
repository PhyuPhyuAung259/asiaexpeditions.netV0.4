@extends('layout.backend')
@section('title', 'Add Flight Schedule')
<?php $active = 'supplier/flights'; 
$subactive = 'flight-schedule/add/new';
  use App\component\Content;
  $countryId = Auth::user()->country_id ? Auth::user()->country_id : 0;
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
          <h3 class="border"> <i class="fa fa-plane"></i> Flight Schedule Form</h3>
          <div class="card">
            <form method="POST" action="{{route('createSchedule')}}">
              {{csrf_field()}}    
              <div class="row">
                <div class="col-md-3 col-xs-6">
                  <div class="form-group">
                    <label>Flight Number <span style="color:#b12f1f;">*</span></label> 
                    <input type="text" placeholder="3K 591" class="form-control" name="flightNo" required>
                  </div> 
                </div>        
                <div class="col-md-3 col-xs-6">
                  <div class="form-group">
                    <label>Country <span style="color:#b12f1f;">*</span></label> 
                    <select class="form-control country" name="country" data-type="country" required>
                      @foreach(App\Country::where('country_status', 1)->orderBy('country_name')->get() as $con)
                        <option value="{{$con->id}}" {{Auth::user()->country_id == $con->id ? 'selected':''}}>{{$con->country_name}}</option>
                      @endforeach
                    </select>
                  </div> 
                </div>
                <div class="col-md-3 col-xs-6">
                  <div class="form-group">
                    <label>City <span style="color:#b12f1f;">*</span></label> 
                    <select class="form-control" name="city" id="dropdown-data" required>
                      @foreach(App\Province::where(['province_status'=> 1, 'country_id'=> $countryId ])->orderBy('province_name')->get() as $pro)
                        <option value="{{$pro->id}}">{{$pro->province_name}}</option>
                      @endforeach
                    </select>
                  </div> 
                </div>
                <!-- Flight -------------------------------------- -->
                <div class="col-md-3 col-xs-6">
                    <?php 
                      $title =  'Airlines Name';
                      $sup_id = '';
                      $field_name  = 'airline';
                      $bus_id = 4; 
                      $routeMap = route('getSupplierForm', ['type'=>'flights']); 
                    ?>
                    @include('admin.include.SupplierFilter')                
                </div>
                <!-- Flight --------------------------- -->
              </div>
              <div class="row">
                <div class="col-md-3 col-xs-6">
                  <div class="form-group">
                    <label>Ticketing Agent<span style="color:#b12f1f;">*</span></label> 
                    <div style="position: relative;">
                        <div class="dropdown-toggle form-control wrapper-filter-item" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative;">
                            <span style="position: absolute;right: 11px;padding: 4px;"><i class="fa fa-sort-up"></i></span>
                            <ul id="MainUl" class="list-unstyled" style="display: flex;">
                            
                            </ul>
                        </div>
                        <ul class="dropdown-menu wrapper-FGlist-item" style="width: 100%">
                          <div style="padding-top: 0px; padding: 12px;">      
                          <span>
                            <input style="border-radius: 2px;" id="searchAgent" type="text" class="form-control" onkeyup="AgentFilter()" placeholder="Type for search..">
                          </span>    
                          <div style="max-height: 218px; overflow: auto;">                
                            <ul id="ticketing_agent" class="list-unstyled" style="padding-top: 5px;">                                   
                              @foreach(App\Supplier::where(['business_id'=>37,'supplier_status'=>1])->orderBy('supplier_name')->get() as $key=>$sup)
                                <li style="padding: 0px" data-name="{{$sup->supplier_name}}">                              
                                  <label style="width: 100%; font-weight: 400">
                                  <input style="top: 2px; position: relative;" type="checkbox" name="flightAgent[]" value="{{$sup->id}}"> 
                                    {{$sup->supplier_name}}
                                  </label>                                    
                                </li>                           
                              @endforeach
                            </ul>
                          </div> 
                          <div><a href="{{route('getSupplierForm', ['type'=>'ticketing-agent']) }}"><i class="fa fa-plus"></i> Add New </a></div>
                        </div>
                        </ul>
                    </div>
                  </div>
                </div>        
                <div class="col-md-3 col-xs-6">
                  <div class="form-group">
                    <label>Departure Time <span style="color:#b12f1f;">*</span></label> 
                    <input type="text" name="dep_time" class="form-control" placeholder="Time: 09:00 AM">
                  </div> 
                </div>
                <div class="col-md-3 col-xs-6">
                  <div class="form-group">
                    <label>Arrival Time <span style="color:#b12f1f;">*</span></label> 
                    <input type="text" name="arr_time" class="form-control" placeholder="Time: 12:00 AM">
                  </div> 
                </div>
                <div class="col-md-3 col-xs-6">
                  <div class="form-group">
                    <label>Flight From <span style="color:#b12f1f;">*</span></label> 
                    <input type="text" name="flight_from" class="form-control" placeholder="From: Phnom Penh">
                  </div>
                </div>
                <div class="col-md-3 col-xs-6">
                  <div class="form-group">
                    <label>Flight To <span style="color:#b12f1f;">*</span></label> 
                    <input type="text" name="flight_to" class="form-control" placeholder="To: Siem Reap">
                  </div>
                </div>
                <div class="col-md-6 col-xs-6">
                  <div class="form-group">
                    <div><label>Operation Days</label></div>
                    @foreach(App\WeekDay::orderBy('id', 'ASC')->get() as $key=>$day)
                      <input id="checkday{{$key}}" name="weekday[]" type="checkbox" value="{{$day->id}}" style="position: relative;top:4px; width: 16px; height: 16px;"> 
                      <label class="label label-default" for="checkday{{$key}}">{{$day->days_name}}</label>
                    @endforeach
                  </div>
                </div>
              </div>  
              <div class="form-group">     
                <script src="{{asset('adminlte/editor/tinymce.min.js')}}"></script>   
                <label>Flight Description</label>
                <textarea class="my-editor form-control" rows="6" name="flight_desc" placeholder="Enter ..."></textarea>
              </div>
              <div class="col-md-3 col-xs-6">
                <div class="form-group">
                  <label>Status</label>&nbsp;
                  <label style="font-weight:400;"> <input type="radio" name="status" value="1" checked="">Publish</label>&nbsp;&nbsp;
                  <label style="font-weight: 400;"> <input type="radio" name="status" value="0">UnPublish</label>
                </div> 
              </div>
              <button type="submit" class="btn btn-success btn-flat btn-sm">Submit</button>
            </form>
          </div>
        </section>
      </div>
    </section>
  </div>  
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $(document).on("click","ul.wrapper-FGlist-item ul li", function(){
      $(this).closest('ul div div').closest('ul.wrapper-FGlist-item').closest('div').find('ul#MainUl li:last-child').after('<li><span>'+$(this).data('name')+'</span></li>');
    });
  });

  function AgentFilter() {
    input = document.getElementById("searchAgent");
    filter= input.value.toUpperCase();
    ul = document.getElementById("ticketing_agent");
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
@include('admin.include.editor')
@endsection
