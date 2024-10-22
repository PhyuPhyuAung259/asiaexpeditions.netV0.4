@extends('layout.backend')
@section('title', 'Create New Booking')
<?php 
$active = 'booked/project'; 
$subactive ='booking/project';
  use App\component\Content;
  if (isset($_GET['ref']) && !empty($_GET['ref'])) {
    $permission = "readonly";
    $projectEdit = '<input type="hidden" name="project_edit" value="'.$_GET['ref'].'">';
    $projectNumber = $_GET['ref'];
    $service = '';
    foreach ($project->service as $key => $sv) {
      $service .= $sv->pivot->service_id.',';
    }
    $tag_user = '';
    foreach ($project->usertag as $key => $tag) {
      $tag_user .= $tag->pivot->user_id.',';
    }
  }else{
    $service ='';
    $tag_user ='';
    $projectNumber = $projectNo;
    $permission = "";
    $projectEdit = "";
  }
?>

@section('content')
  <div class="wrapper">
    @include('admin.include.header')
    @include('admin.include.menuleft')
    <div class="content-wrapper">
      <section class="content"> 
        <div class="row">
          @include('admin.include.message')
          <div class="col-lg-12"><h4 class="border">Travelling Booking Form</h4></div>
          <form method="POST" action="{{route('createProject')}}">
            {!! $projectEdit !!}
            {{csrf_field()}}
            <section class="connectedSortable">
                <div class="col-md-9">
                  <div class="row">
                    <div class="col-md-2 col-xs-12" style="padding-right: 0px;">
                      <div class="form-group">
                        <label>Project<span style="color:#b12f1f;">*</span></label>
                        <input type="text" placeholder="Project Number" class="form-control" name="project_number" value="{{{ $project->project_number or $projectNo}}}" required readonly>
                      </div> 
                    </div>        
                    <div class="col-md-1 col-xs-6">
                      <div class="form-group">
                        <label>PaxNo.</label> 
                        <input type="text" placeholder="Pax" class="form-control text-center" name="pax_num"  value="{{{ $project->project_pax or old('pax_num') }}}" {{$permission}}/>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Client Name <span style="color:#b12f1f;">*</span></label> 
                        <input type="text" class="form-control" name="client_name" placeholder="Jonh Smit" required value="{{{ $project->project_client or old('client_name') }}}" {{$permission}}/>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Client Email</label> 
                        <input type="email" placeholder="Example@google.com" class="form-control" name="client_email" {{$permission}} />
                      </div> 
                    </div> 
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>File Number</label> 
                        <input type="text" class="form-control" name="fileno" placeholder="File Number" value="{{{ $project->project_fileno or old('fileno')}}}"  {{$permission}}/>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Start Date<span style="color:#b12f1f;">*</span></label> 
                        <input type="text" id="from_date" class="form-control" name="start_date" placeholder="2018-04-24" required  value="{{{ $project->project_start or old('start_date')}}}"  {{$permission}}/>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>End Date<span style="color:#b12f1f;">*</span></label>
                        <input type="text" id="to_date" class="form-control" name="end_date" placeholder="2019-04-24" required value="{{{ $project->project_end or old('end_date')}}}"  {{$permission}}>
                      </div> 
                    </div>

                    <!--Flight Schedule Lists ------------------- -->
                    <div class="col-md-6 col-xs-6">
                      <?php 
                        $dep_id   = isset($project) ? $project['project_dep_time'] : '';
                        $dep_name = isset($project->flightDep) ? $project->flightDep['flightno']. '-' . $project->flightDep['dep_time'] : '';
                        $arr_id   = isset($project) ? $project['project_arr_time'] : '';
                        $arr_name = isset($project->flightArr) ? $project->flightArr->flightno. '-' . $project->flightArr->arr_time : '';

                      ?>
                      @include('admin.include.FlightFilter')
                    </div>
                    <!-- -------------------- -->
 
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Agent<span style="color:#b12f1f;">*</span></label> 
                       <select class="form-control" name="agent" required="" {{$permission}}>
                          <option value="">Agent</option>
                          <?php $getAgent = App\Supplier::where(['business_id'=>9, 'supplier_status'=>1])->orderBy('supplier_name', 'ASC')->get(); 
                            $supId = isset($project->supplier_id) ? $project->supplier_id:'';
                          ?>
                          @if($getAgent->count() > 0)
                            @foreach($getAgent as $agent)
                              <option value="{{$agent->id}}" {{$agent->id == $supId ? 'selected':''}}>{{$agent->supplier_name}}</option>
                            @endforeach
                          @endif
                        </select>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Reference</label> 
                        <input class="form-control" type="text" placeholder="Reference" name="reference" value="{{{$project->project_book_ref or old('reference')}}}" {{$permission}}>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Travel Consultant</label> 
                        <input class="form-control" type="text" placeholder="Travel Consultant" name="consultant" value="{{{$project->project_book_consultant or old('consultant')}}}" {{$permission}}>
                      </div> 
                    </div>                                  
                    <div class="col-md-3 col-xs-12">
                      <div class="form-group">
                        <label>Location</label>
                        <select  class="form-control" name="location" {{$permission}}>
                          @foreach(App\Country::where('country_status',1)->orderBy('country_name')->get() as $con)
                            <option value="{{$con->id}}" {{isset($project->country_id) ? ($project->country_id == $con->id? 'selected':''):'' }}>{{$con->country_name}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6 col-xs-6">
                      <div class="form-group">
                        <script src="{{asset('/adminlte/editor/tinymce.min.js')}}"></script>
                        <label>Hightlights</label>
                        @if(isset($_GET['ref']))
                          <div class="form-control" disabled {{strlen($project->project_hight) > 0 ? 'style=height:auto;' : "" }}>
                            {!! $project->project_hight !!}
                          </div>
                        @else
                          <textarea class="form-control my-editor" name="pro_hightlight" rows="6" placeholder="Enter Here..." {{$permission}}> 
                          {!! $project->project_hight or old('pro_hightlight') !!}</textarea> 
                        @endif
                      </div>
                    </div>
                    <div class="col-md-6 col-xs-6">
                      <div class="form-group">
                        <label>Description</label>
                        @if(isset($_GET['ref']))
                          <div class="form-control" disabled {{strlen($project->project_desc) > 0 ? 'style=height:auto;' : "" }}>
                            {!! $project->project_desc !!}
                          </div>
                        @else
                          <textarea class="form-control my-editor" name="pro_desc" rows="6" placeholder="Enter Here ..." {{$permission}}>{{{$project->project_desc or old('pro_desc')}}}</textarea>  
                        @endif
                      </div>
                    </div>
                    <div class="col-md-6 col-xs-6">
                      <div class="form-group">
                        <label>Additional Descriptions</label>
                        @if(isset($_GET['ref']))
                          <div class="form-control" disabled {{strlen($project->project_add_desc) > 0 ? 'style=height:auto;' : "" }}>
                            {!! $project->project_add_desc !!}
                          </div>
                        @else
                          <textarea class="form-control my-editor" name="pro_add_desc" rows="6" placeholder="Enter Here..." {{$permission}}>{{{$project->project_add_desc or old('pro_add_desc')}}}</textarea>    
                        @endif
                                
                      </div>
                    </div>
                    <div class="col-md-6 col-xs-6">
                      <div class="form-group">
                        <label>Credit Note Descriptions</label>
                        @if(isset($_GET['ref']))
                          <div class="form-control" disabled {{strlen($project->project_note_desc) > 0 ? 'style=height:auto;' : "" }}>
                            {!! $project->project_note_desc !!}
                          </div>
                        @else
                          <textarea class="form-control my-editor" name="pro_note_desc" rows="6" placeholder="Enter Here..." {{$permission}}>{{{$project->project_note_desc or old('pro_note_desc')}}}</textarea>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-3"><br>
                  <div class="panel panel-default">
                    <div class="panel-body">
                      <div class="form-group">
                          <div><label>Option Choice</label>&nbsp;</div>
                          <label style="font-weight:400;"> 
                            <input type="radio" name="option" value="0" {{isset($_GET['type']) ? "":"checked"}}>
                            <span style="position: relative;top:-2px;">Booking</span>
                          </label>&nbsp;&nbsp;
                          <label style="font-weight: 400;">
                              <input type="radio" name="option" value="1" {{isset($_GET['type']) ? "checked":""}}>
                              <span style="position: relative;top:-2px;">Quotation</span>
                          </label>
                      </div> 
                    </div>
                  </div>
                  <div class="panel panel-default">
                    <div class="panel-body">
                      <div class="btn-group" style="display: block;">
                        <button type="button" class="form-control" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" data-backdrop="static" data-keyboard="false" role="dialog" data-backdrop="static" data-keyboard="false" {{$permission}}>
                         <span class="pull-left"><i class="fa fa-user-plus" style="color: #5aabf1;"></i> User Tags</span><span class="pull-right"><i class="caret"></i></span>
                        </button>  
                        <?php 
                        $getUserTage = App\User::where('banned',0)->whereNotIn('id', [Auth::user()->id])->whereHas('role')->orderBy('role_id')->get(); ?>
                        <div class="obs-wrapper-search">
                          <ul class="dropdown-data" style="width: 100%;" id="Show_date">
                            @foreach($getUserTage as $key=>$user)
                            <li {{in_array($user->id, [Auth::user()->id])? "style=display:none":""}}>
                                <label class="container-CheckBox" style="margin-bottom: 0px;">{{$user->fullname}}
                                  <input id="ch{{$key}}" type="checkbox" name="usertag[]" value="{{$user->id}}" {{in_array($user->id, explode(',', $tag_user)) ? 'checked':''}} {{in_array($user->id, [Auth::user()->id])? 'checked':''}}> 
                                  <span class="checkmark hidden-print" ></span>
                                </label>
                            </li>                           
                            @endforeach
                            <div class="clearfix"></div>
                          </ul>
                        </div>
                      </div>
                    </div>                  
                  </div>
                  <div class="panel panel-default">
                    <div class="panel-body">
                      <label>Payment Optoin</label>
                      @foreach(App\Bank::orderBy('name', 'ASC')->get() as $key=>$bk)
                        <div>                        
                          <label style="font-weight:400;">
                            <input type="radio" name="bank" value="{{$bk->id}}" {{$key == 0 ?'checked':''}}> 
                            <span style="position: relative;top:-2px">{{$bk->name}}</span>
                          </label>
                        </div>
                      @endforeach
                    </div>
                    <!-- <div class="panel-footer">Payment Optoin</div> -->
                  </div>
                </div>
                <div class="col-md-12">
                  <table class="table" id="add_book_project">
                    <thead class="text-center">
                      <tr>
                        <th class="text-center" style="width: 25%;">Date</th>
                        <th class="text-center">Country</th>
                        <th class="text-center">City</th>
                        <th class="text-center" style="width: 22%;">Description</th>
                        <th class="text-center">Pax</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Total</th>
                        <th class="text-right" style="width: 6%;">
                          <div class="dropdown">
                            <button class="btn btn-xs btn-primary dropdown-toggle" type="button" id="menu1" data-toggle="dropdown"> Add <i class="fa fa-plus-square"></i> 
                            </button>
                            <?php $quotation = isset($_GET['type']) && $_GET['type'] == "quotation" ? "quotation" : 'hotel_quotation';  ?>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                              <li role="presentation"><a role="menuitem" tabindex="-1" href="#" class="add_row" data-type="tour" data-option="" data-url="{{route('add_row')}}">Tour</a></li>
                              <li role="presentation"><a role="menuitem" tabindex="-1" href="#" class="add_row" data-type="hotel" data-option={{$quotation}} data-url="{{route('add_row')}}">Hotel</a></li>
                              <li role="presentation"><a role="menuitem" tabindex="-1" href="#" class="add_row" data-type="flight" data-option="" data-url="{{route('add_row')}}">Flight</a></li>
                              <li role="presentation"><a role="menuitem" tabindex="-1" href="#" class="add_row" data-type="cruise" data-option=""  data-url="{{route('add_row')}}">Cruise</a></li>
                              <li role="presentation"><a role="menuitem" tabindex="-1" href="#" class="add_row" data-type="golf" data-option="" data-url="{{route('add_row')}}">Golf</a></li>
                            </ul>
                          </div>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr></tr>
                    </tbody>
                
                  </table>
                  <div id="LoadingRow" style="display:none; position: absolute; margin:-4% 42% 17% 45%">
                    <center><span style="font-size: 38px;" id="placeholder" class="fa fa fa-spinner fa-spin"></span></center>
                  </div> 
                </div>
                <div class="col-md-12 text-right">
                  <div class="form-group">
                    <button type="submit" class="btn btn-success btn-flat btn-sm">Comfirm Booking</button>&nbsp;
                    <span class="btn btn-danger btn-sm reset_booking">Reset Booking</span>     
                  </div>
                </div>               
                <div class="col-md-12">
                  <table class='table'>
                    <thead>
                      <tr class="text-center">
                        <td style="width: 50%;"><strong>Service Included</strong></td>
                        <td style="width: 50%;"><strong>Service Excluded</strong></td>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                    $Included=App\Service::where(['service_cat'=>1,'status'=>1])->orderBy('service_name')->get();
                    $Excluded=App\Service::where(['service_cat'=>0,'status'=>1])->orderBy('service_name')->get();
                    ?>
                    <tr> 
                      <td style="vertical-align: top;">
                        <table class="table" style="width: 100%">
                          @foreach($Included->chunk(2) as $roomService)
                          <tr>
                            @foreach($roomService as $room)
                              <td style="width: 50%;">
                                 <label class="container-CheckBox" style="margin-bottom: 0px;">{{$room->service_name}}
                                  <input type="checkbox" id="check_all" name="service[]" value="{{$room->id}}" {{in_array($room->id, explode(',', $service))? 'checked':''}}  >
                                  <span class="checkmark hidden-print" ></span>
                                </label>
                              </td>
                            @endforeach
                          </tr>
                          @endforeach
                        </table>
                      </td>

                      <td style="vertical-align: top;">
                        <table class="table" style="width: 100%">
                          @foreach($Excluded->chunk(2) as $svChunk)
                          <tr>
                            @foreach($svChunk as $room)
                            <td style="width: 50%;">
                                <label class="container-CheckBox" style="margin-bottom: 0px;">{{$room->service_name}}
                                  <input type="checkbox" id="check_all" name="service[]" value="{{$room->id}}" {{in_array($room->id, explode(',', $service))? 'checked':''}}  >
                                  <span class="checkmark hidden-print" ></span>
                                </label>
                            </td>
                            @endforeach
                          </tr>
                          @endforeach
                        </table>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
                <div style="margin-bottom: 30px;"></div>
            </section>
          </form>
        </div>
      </section>
    </div>  
  </div>


  @include('admin.include.datepicker')
@endsection
