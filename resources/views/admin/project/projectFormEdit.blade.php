@extends('layout.backend')
@section('title', $project->project_number.' Update')
<?php $active = 'booked/project'; 
$subactive ='booking/project';
  use App\component\Content;
  $service = '';
  foreach ($project->service as $key => $sv) {
      $service .= $sv->pivot->service_id.',';
  }
  $tag_user = '';
  foreach ($project->usertag as $key => $tag) {
      $tag_user .= $tag->pivot->user_id.',';
  }
?>
@section('content')
<style type="text/css">
  @media (min-width: 992px){
    .modal-lg {
      width: 1300px;
    }
  }
</style>
  <div class="wrapper">
    @include('admin.include.header')
    @include('admin.include.menuleft')
    <div class="content-wrapper">
      <section class="content"> 
        <div class="row">
          @include('admin.include.message')
          <div class="col-lg-12"><h4 class="border">Travelling Booking Form</h4></div>
          <form method="POST" action="{{route('updateProject')}}">
              {{csrf_field()}}
              <section class="connectedSortable">
                <div class="col-md-9">
                  <div class="row">
                    <div class="col-md-2 col-xs-6">
                      <div class="form-group">
                        <input type="hidden" name="olduser" value="{{$project->user_id}}">
                        <label>Project <span style="color:#b12f1f;">*</span></label>
                        <input type="text" placeholder="Project Number" class="form-control" name="project_number" value="{{ $project->project_number}}" required readonly>
                      </div> 
                    </div> 
                    <div class="col-md-1 col-xs-6">
                      <div class="form-group">
                        <label>PaxNo. <span style="color:#b12f1f;">*</span></label> 
                        <input type="text" placeholder="Pax" class="form-control text-center" name="pax_num" required value="{{{ $project->project_pax or old('pax_num') }}}" />
                      </div> 
                    </div>      
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Revise date</label> 
                        <input type="text" placeholder="2018-04-26" class="form-control book_date" name="revise_date" readonly value="{{{ $project->project_revise or date('Y-m-d') }}}" required/>
                      </div> 
                    </div> 
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Client Email</label> 
                        <input type="email" placeholder="Example@google.com" class="form-control" name="client_email" value="{{{ $project->project_email or ''}}}"/>
                      </div> 
                    </div> 
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Client Name <span style="color:#b12f1f;">*</span> &nbsp; <a href="#" data-toggle="modal" data-target="#AddClient"> <i class="fa  fa-users"></i> Add More Client</a></label> 
                        <input type="text" class="form-control" name="client_name" placeholder="Jonh Smit" required value="{{{ $project->project_client or old('client_name')}}}"/>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>File Number</label> 
                        <input type="text" class="form-control" name="fileno" placeholder="File Number" value="{{{ $project->project_fileno or old('fileno')}}}" />
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Exchange Rate</label> 
                        <input type="text" class="form-control" name="ex_rate" placeholder="Exchange Rate" value="{{{ $project->project_ex_rate or old('ex_rate')}}}" />
                      </div> 
                    </div>

                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Margin</label> 
                        <input type="text" class="form-control" name="margin_rate" placeholder="Margin Rate" value="{{{ $project->margin_rate or old('margin_rate')}}}" />
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Selling Rate</label> 
                        <input type="text" class="form-control" name="sell_rate" placeholder="Selling Rate" value="{{{ $project->project_selling_rate or old('sell_rate')}}}" />
                      </div> 
                    </div>
                     <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>VAT %</label> 
                        <input type="text" class="form-control" name="vat" placeholder="VAT %" value="{{{ $project->vat or old('vat')}}}" />
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Agent Cost of Sale</label> 
                        <input type="text" class="form-control" name="cost_of_sale" placeholder="Cost of Sale" value="{{{ $project->cost_of_sale or old('cost_of_sale')}}}" />
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Additional Invoice</label> 
                        <input type="text" class="form-control" name="add_invoice" placeholder="Additional Invoice" value="{{{$project->project_add_invoice or old('add_invoice')}}}" />
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Credit Note</label> 
                        <input type="text" class="form-control" name="cnote_invoice" placeholder="Credits Note" value="{{{$project->project_cnote_invoice or old('cnote_invoice')}}}" />
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Invoice Number</label> 
                        <input type="text" class="form-control" name="invoice_num" placeholder="Invoice Number" value="{{{ $project->project_invoice_number or old('invoice_num')}}}" />
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Start Date<span style="color:#b12f1f;">*</span></label> 
                        <input type="text" id="from_date" class="form-control" name="start_date" placeholder="2018-04-24" required  value="{{{ $project->project_start or old('start_date')}}}" />
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>End Date<span style="color:#b12f1f;">*</span></label>
                        <input type="text" id="to_date" class="form-control" name="end_date" placeholder="2019-04-24" required value="{{{ $project->project_end or old('end_date')}}}" >
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
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Agent<span style="color:#b12f1f;">*</span></label> 
                        <select class="form-control" name="agent" required="" >
                          <option value="">Agent</option>
                          @foreach(App\Supplier::where(['business_id'=>9, 'supplier_status'=>1])->orderBy('supplier_name')->get() as $agent)
                            <option value="{{$agent->id}}" {{$agent->id == $project->supplier_id ? 'selected':''}}>{{$agent->supplier_name}}</option>
                          @endforeach
                        </select>
                      </div> 
                    </div>                    
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Location</label>
                        <select class="form-control" name="location">
                          @foreach(App\Country::where('country_status',1)->get() as $con)
                            <option value="{{$con->id}}" {{$project->country_id == $con->id ? 'selected':''}}>{{$con->country_name}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Reference</label> 
                        <input class="form-control" type="text" placeholder="Reference" name="reference" value="{{{$project->project_book_ref or old('reference')}}}" >
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Travel Consultant</label> 
                        <input class="form-control" type="text" placeholder="Travel Consultant" name="consultant" value="{{{$project->project_book_consultant or old('consultant')}}}">
                      </div> 
                    </div> 
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <br><br>
                        <label><a href="#" data-toggle="modal" data-target="#uploadPDF"><i class="fa fa-folder" style="font-size: 15px;"></i> Upload PDF Files</a></label> 
                        
                      </div> 
                    </div>    
                    <div class="col-md-12 col-xs-12">
                      <div class="form-group">
                        <script src="{{asset('adminlte/editor/tinymce.min.js')}}"></script>
                        <label>Description for Invoice</label>
                        <textarea class="form-control my-editor " name="pro_desc" rows="6" placeholder="Enter Here ...">{{{$project->project_desc or old('pro_desc')}}}</textarea>   
                      </div>
                    </div>
                    <div class="col-md-6 col-xs-12">
                      <div class="form-group">
                        <label>Hightlights</label>                        
                        <textarea class="form-control my-editor" name="pro_hightlight" rows="6" placeholder="Enter Here..."> {{{$project->project_hight or old('pro_hightlight')}}}</textarea>            
                      </div>
                    </div>
                    <div class="col-md-6 col-xs-12">
                      <div class="form-group">
                        <label>Additional Descriptions</label>
                        <textarea class="form-control  my-editor" name="pro_add_desc" rows="6" placeholder="Enter Here...">{{{$project->project_add_desc or old('pro_add_desc')}}}</textarea>            
                      </div>
                    </div>
                    <div class="col-md-6 col-xs-12">
                      <div class="form-group">
                        <label>Credit Note Descriptions</label>
                        <textarea class="form-control" name="pro_note_desc" rows="6" placeholder="Enter Here..." >{{{$project->project_note_desc or old('pro_note_desc')}}}</textarea>
                      </div>
                    </div>
                    <div class="col-md-6 col-xs-12">
                      <div class="form-group">
                        <label>Remarks for Operation</label>
                        <textarea class="form-control" name="pro_note" rows="6" placeholder="Included/Excluded" >{{{$project->project_note or old('pro_note')}}}</textarea>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-3"><br>
                  <div class="panel panel-default">
                    <div class="panel-body">
                      <div class="col-md-7 col-xs-6">
                        <div class="form-group">
                            <div><label>Option Choice</label>&nbsp;</div>
                            <label style="font-weight:400;"> 
                              <input type="radio" name="option" value="0" {{$project->project_option==0?'checked':'' }} >
                              <span style="position: relative;top:-2px;">Booking</span>
                            </label>&nbsp;&nbsp;
                            @if(isset($_GET['action']))
                            <label style="font-weight: 400;">
                                <input type="radio" name="option" value="1" {{$project->project_option==1?'checked':'' }}>
                                <span style="position: relative;top:-2px;">Quotation</span>
                            </label>
                            @endif
                        </div>
                      </div> 
                      <div class="col-md-5 col-xs-6"> 
                        @if(Auth::user()->role_id == 3)
                        <div class="form-group">
                          <div><label>Project Type</label>&nbsp;</div>
                          <div class="row">
                            <label style="font-weight:400;"> 
                              <input type="radio" name="active" value="1" {{$project->active== 1 ? 'checked':'' }}>
                              <span style="position: relative;top:-2px;">Active</span>
                            </label>&nbsp;&nbsp;
                            <label style="font-weight: 400;">
                              <input type="radio" name="active" value="0" {{$project->active == 0 ? 'checked':'' }}>
                              <span style="position: relative;top:-2px;">InActive</span>
                            </label>
                          </div>
                        </div>
                        @endif
                      </div>
                    </div>
                  </div>
                  <div class="panel panel-default">
                    <div class="panel-body">
                      <div class="form-group">
                        <div><label>Project Status</label>&nbsp;</div>
                        <label style="font-weight:400;"> 
                          <input type="radio" name="project_check" value="1" {{$project->project_check==1?'checked':'' }}>
                          <span style="position: relative;top:-2px;">Already Check</span>
                        </label>&nbsp;&nbsp;
                        <label style="font-weight: 400;">
                            <input type="radio" name="project_check" value="0" {{$project->project_check==0?'checked':'' }}>
                            <span style="position: relative;top:-2px;">Not yet check</span>
                        </label>  <br>
                        <label style="font-weight:400;margin-right:10px"> 
                            <input type="radio" name="project_status" value="1" {{$project->project_status == '1' ?'checked':'' }}>
                            <span style="position: relative;top:-2px;">Enable</span>
                        </label>&nbsp;&nbsp;
                        <label style="font-weight: 400;">
                            <input type="radio" name="project_status" value="0" {{$project->project_status == '0'?'checked':'' }}>
                            <span style="position: relative;top:-2px;">Disable</span>
                        </label> 
                      </div>                      
                    </div>
                  </div>
                  <div class="panel panel-default">
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-6 col-xs-6">
                          <div class="form-group">
                            <div><label>Project Prefix</label>&nbsp;</div>
                            <label style="font-weight:400;"> 
                              <input type="radio" name="project_prefix" value="AE" {{$project->project_prefix == 'AE'?'checked':'' }}>
                              <span style="position: relative;top:-2px;">AE</span>
                            </label>&nbsp;&nbsp;
                            <label style="font-weight: 400;">
                                <input type="radio" name="project_prefix" value="AM" {{$project->project_prefix=='AM'?'checked':'' }}>
                                <span style="position: relative;top:-2px;">AM</span>
                            </label> 
                          </div>
                        </div>
                        <div class="col-md-6 col-xs-6">
                          <div class="form-group">
                            <div><label>Prefix</label>&nbsp;</div>
                            <label style="font-weight:400;"> 
                              <input type="radio" name="prefix" value="Main" {{$project->project_main_status=='Main'?'checked':'' }}>
                              <span style="position: relative;top:-2px;">Main</span>
                            </label>&nbsp;&nbsp;
                            <label style="font-weight: 400;">
                                <input type="radio" name="prefix" value="Sub" {{$project->project_main_status=='Sub'?'checked':'' }}>
                                <span style="position: relative;top:-2px;">Sub</span>
                            </label>
                          </div>
                        </div>                       
                      </div>
                    </div>
                  </div>
                    <!-- Change status -->
                   
                  <!-- change status -->
                  <div class="panel panel-default">
                    <div class="panel-body">
                      <div class="btn-group" style="display: block;">
                        <button type="button" class="form-control" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" data-backdrop="static" data-keyboard="false" role="dialog" data-backdrop="static" data-keyboard="false" >
                         <span class="pull-left"><i class="fa fa-user-plus" style="color: #5aabf1;"></i> User Tags</span><span class="pull-right"><i class="caret"></i></span>
                        </button>  
                         <?php 
                          $getUserTage = App\User::where('banned',0)->whereNotIn('id', [Auth::user()->id])->whereHas('role')->orderBy('role_id')->get(); ?>
                        <div class="obs-wrapper-search">
                          <ul class="dropdown-data" style="width: 100%;" id="Show_date">
                            @foreach($getUserTage as $key=>$user)
                            <li>
                                <label class="container-CheckBox" style="margin-bottom: 0px;">{{$user->fullname}}
                                  <input id="ch{{$key}}" style="width: 14px; height: 14px;" type="checkbox" name="usertag[]" value="{{$user->id}}" {{in_array($user->id, explode(',', $tag_user)) ? 'checked':''}}  {{Auth::user()->id == $user->id ? "checked":"" }}> 
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
                            <input type="radio" name="bank" value="{{$bk->id}}" {{$bk->id == $project->project_bank ?'checked':'' }}> 
                            <span style="position: relative;top:-2px">{{$bk->name}}</span>
                          </label>
                        </div>
                      @endforeach
                    </div>
                  </div>
                
                  <div class="col-md-12 text-center">
                    <div class="form-group">
                      @if($project->project_end < date('Y-m-d') && Auth::user()->role_id !== 2)
                        <button type="button" class="btn btn-success btn-flat btn-sm" title="Project is out of Date" disabled>Confirm Update</button>&nbsp;
                      @else
                        <button type="submit" class="btn btn-success btn-flat btn-sm">Confirm Update</button>&nbsp;
                      @endif
                      <a href="{{route('projectList', ['url'=>'project'])}}" class="btn btn-danger btn-sm ">Back</a>
                    </div>
                  </div>               
                </div>
                <div style="margin-bottom: 30px;"></div>
              </section>
          </form>
        </div>
      </section>
    </div>  
  </div>
  @include('admin.include.datepicker')
  @include('admin.include.editor')
<div class="modal" id="AddClient" role="dialog"  data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-lg">    
    <form id="AddMultiClientForm" method="POST" action="{{route('addClientForProject')}}">
      <div class="modal-content">        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Add Client Name For File No. {{$project->project_prefix}}-{{$project->project_fileno ? $project->project_fileno : $project->project_number}}</strong></h4>
        </div>
        <div class="modal-body">
          {{csrf_field()}}    
          <input type="hidden" name="client_project_number" value="{{$project->project_number}}">
          <input type="hidden" name="project_id" value="{{$project->id}}">       
            <table class="table table_row">
              <tr>
                <th style="border:none; width: 200px;">Client Name</th>
                <th style="border:none;">Nationality</th>
                <th style="border:none;">Passport Number</th>
                <th style="border:none;">Date of Expiry</th>
                <th style="border:none;">Date Of Birth</th>
                <th style="border:none;">Phone</th>
                <th style="border:none;">Dietary</th>
                <th style="border:none;">Allergies</th>
                <th style="border:none;">Share With</th>
                <th style="border:none;">Arrival Flight</th>
                <th style="border:none;">Departure Flight</th>
              </tr>
              <tbody style="border-top: none;">
                <?php $getClient = \App\Admin\ProjectClientName::where('project_number', $project->project_number)->get(); ?>

                @if($getClient->count() > 0)
                  @foreach($getClient as $key => $cl)
                    <tr class="clonrow">
                      <td style="padding-left: 0px;">
                        <div class="row">
                          <div class="col-md-6">
                            <input type="hidden" name="eid[]" value="{{$cl->id}}">
                            <input type="text" name="first_name[]" class="form-control" value="{{$cl->first_name}}">
                          </div>
                          <div class="col-md-6" style="padding-left: 0px;">
                            <input type="text" name="last_name[]" class="form-control" value="{{$cl->last_name}}">
                          </div>
                        </div>
                        <div class="clearfix"></div>
                      </td>
                      <td>
                        <select class="form-control" name="country_id[]" required>
                          @foreach(App\Country::where(['country_status'=>1])->whereNotNull('nationality')->orderBY('nationality')->get() as $con)
                            <option value="{{$con->id}}" {{$con->id == $cl->country_id ? 'selected' : 
                             ''}}>{{$con->nationality}}</option>
                          @endforeach
                        </select>
                      </td>
                      <td><input type="text" name="passport[]" class="form-control" value="{{$cl->passport}}"></td>
                      <td><input type="text" name="expire_date[]" class="form-control book_date" value="{{isset($cl->expired_date) ? date('Y-m-d', strtotime($cl->expired_date)) : ''}}">
                      </td>
                      <td><input  type="text" name="date_of_birth[]" class="form-control book_date" value="{{ isset($cl->date_of_birth) ? date('Y-m-d', strtotime($cl->date_of_birth)) : ''}}"></td>
                      <td><input type="text" name="phone[]" class="form-control " placeholder="Phone No" value="{{$cl->phone}}"></td>
                      <td><input type="text" name="dietary[]" class="form-control " placeholder="Dietary" value="{{$cl->dietary}}"></td>
                      <td><input type="text" name="allergies[]" class="form-control " placeholder="Allergies" value="{{$cl->allergies}}"></td>
                      <td style="padding-right: 0px;"><input type="text" name="share_with[]" class="form-control" placeholder="Share With...." value="{{$cl->share_with}}"></td>
                      <td>
                        <input type="text" name="flight_arr[]" class="form-control" placeholder="Arrival Flight" value="{{$cl->flight_arr}}">
                      </td>
                      <td style="padding-right: 0px;">
                        <input type="text" name="flight_dep[]" class="form-control" placeholder="Departure Flight" value="{{$cl->flight_dep}}">
                      </td>
                      <td><a href="#" data-id="{{$cl->id}}" data-title="{{$cl->client_name}}" class="RemoveClientRow"><i style="color:#913412;font-size:14px;" class="fa fa-minus-circle"></i></a></td>
                    </tr>
                  @endforeach
                @endif
                <tr class="clonrow">
                  <td style="padding-left: 0px;">
                    <div class="row">
                      <div class="col-md-6">
                        <input type="text" name="first_name[]" class="form-control" placeholder="First Name">
                      </div>
                      <div class="col-md-6" style="padding-left: 0px;">
                        <input type="text" name="last_name[]" class="form-control" placeholder="Last Name">
                      </div>
                    </div>
                    <div class="clearfix"></div>
                  </td>
                  <td>
                    <select class="form-control" name="country_id[]" required>
                      @foreach(App\Country::where(['country_status'=> 1])->whereNotNull('nationality')->orderBY('nationality')->get() as $con)
                        <option value="{{$con->id}}">{{$con->nationality}}</option>
                      @endforeach
                    </select>
                  </td>
                  <td><input type="text" name="passport[]" class="form-control " placeholder="Passport Number"></td>
                  <td><input type="text" name="expire_date[]" class="form-control book_date" placeholder="Expire Date" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
                  </td>
                  <td><input type="text" name="date_of_birth[]" class="form-control book_date" placeholder="Date Of Birth:"></td>
                  <td><input type="text" name="phone[]" class="form-control " placeholder="Phone No"></td>
                  <td><input type="text" name="dietary[]" class="form-control " placeholder="Dietary"></td>
                  <td><input type="text" name="allergies[]" class="form-control " placeholder="Allergies"></td>
                  <td style="padding-right: 0px;"><input type="text" name="share_with[]" class="form-control" placeholder="Share With...." ></td>
                  <td>
                    <input type="text" name="flight_arr[]" class="form-control" placeholder="Departure Flight">
                  </td>
                  <td style="padding-right: 0px;">
                    <input type="text" name="flight_dep[]" class="form-control" placeholder="Departure Flight" >
                  </td>
                  <td><a href="#" class="addClientRow"><i class="fa fa-plus"></i></a></td>
                </tr>
              </tbody>
            </table>
        </div>
        <div class="modal-footer" style="text-align: center;">
          <button type="submit" class="btn btn-success btn-sm" >Save</button>
          <a href="#" class="btn btn-default btn-flat btn-sm btn-acc" data-dismiss="modal">Close</a>
        </div>
      </div>      
    </form>
  </div>
</div>

<div class="modal" id="uploadPDF" role="dialog"  data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-md">    
    <form method="POST" action="{{route('addProjectPdF')}}" enctype="multipart/form-data">
      <div class="modal-content">        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Upload Project Data File No. {{$project->project_prefix}}-{{$project->project_fileno ? $project->project_fileno : $project->project_number}}</strong></h4>
        </div>
        <div class="modal-body">
          {{csrf_field()}}    
            <input type="hidden" name="project_number_pdf" value="{{$project->project_number}}">
            <input type="hidden" name="project_id_pdf" value="{{$project->id}}">   
            <input type="hidden" name="pdf_type" value="project_pdf">       
            <div class="form-group">
              <input type="file" name="project_pdf[]" multiple>
            </div>
            <?php $getClient = \App\Admin\Photo::where('project_number', $project->project_number)->get(); ?>
            @if($getClient->count() > 0)
            <table class="table ">
                <tr>
                  <th style="border:none;">Title</th>
                  <th style="border:none;">By User</th>
                  <th style="border:none;" width="8px">Action</th>
                </tr>
                <tbody style="border-top: none;">
                  @foreach($getClient as $key => $cl)
                    <tr>
                      <td> <a target="_blank" href="{{asset('storage/contract/projects')}}/{{$cl->name}}">{{$cl->original_name}} </td>
                      <td>{{{ $cl->user->fullname or ''}}}</td>
                      <td>
                        <span style="cursor:pointer;" class="RemoveHotelRate" data-type="project_pdf" data-id="{{$cl->id}}" title="Delete this ?"><i style="color:#913412;font-size:14px;" class="fa fa-minus-circle"></i></span></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @endif
        </div>
        <div class="modal-footer" style="text-align: center;">
          <button type="submit" class="btn btn-success btn-sm">Save</button>
          <span class="btn btn-default btn-flat btn-sm btn-acc" data-dismiss="modal">Close</span>
        </div>
      </div>      
    </form>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $(".addClientRow").click(function(){
      var this_row = $(this).closest("tr");
      var cloneRow = '<tr><td style="padding-left: 0px;"><div class="row"><div class="col-md-6"><input type="text" name="first_name[]" class="form-control" placeholder="First Name"></div><div class="col-md-6" style="padding-left: 0px;"><input type="text" name="last_name[]" class="form-control" placeholder="Last Name"></div></div><div class="clearfix"></div></td><td><select class="form-control" name="country_id[]" required>@foreach(App\Country::where(["country_status"=> 1])->whereNotNull("nationality")->orderBy("nationality")->get() as $con)<option value="{{$con->id}}">{{$con->nationality}}</option>@endforeach</select></td><td><input type="text" name="passport[]" class="form-control " placeholder="Passport Number"></td><td><input type="text" name="expire_date[]" class="form-control book_date" placeholder="Expire Date"></td><td><input type="text" name="date_of_birth[]" class="form-control book_date" placeholder="Date Of Birth:"></td><td><input type="text" name="phone[]" class="form-control" placeholder="Phone No"></td><td><input type="text" name="dietary[]" class="form-control " placeholder="Dietary"></td><td><input type="text" name="allergies[]" class="form-control " placeholder="Allergies"></td><td style="padding-right: 0px;"><input type="text" name="share_with[]" class="form-control" placeholder="Share With...." ></td><td><input type="text" name="flight_arr[]" class="form-control" placeholder="Arrival Flight"></td><td style="padding-right: 0px;"><input type="text" name="flight_dep[]" class="form-control" placeholder="Departure Flight"></td><td><a data-type="new_row" href="#" class="RemoveClientRow"><i class="fa fa-minus-circle" style="color:#913412;font-size:14px;"></i></a></td></tr>';
      $(".table_row tbody tr:last").before(cloneRow);
      
    });

    $(document).on("click", ".RemoveClientRow", function(){
      var this_row = $(this).closest("tr");
      if(confirm("Are you sure you want to delete this?")){ 
        var id = $(this).data('id');
        if ($(this).data('type') == "new_row") {
          this_row.css({'background-color':'#9E9E9E'});
          this_row.fadeOut(500, function(){
            $(this_row).remove();
          });
        }else{
          $.ajax({
              method: "GET",
              url: baseUrl+"option/remove",      
              data: "dataId=" + id+"&type="+ "projectForClient"+ "&title="+$(this).data("title"),   
              dataType: 'html',
              success: function(data){                
                this_row.css({'background-color':'#9E9E9E'});
                this_row.fadeOut(500, function(){
                  $(this_row).remove();
                });
              },
              error: function(){
                  alert("Something Wrong.");
                  return false;
              },
          });
        }
      }
    });
  });
</script>
@endsection

