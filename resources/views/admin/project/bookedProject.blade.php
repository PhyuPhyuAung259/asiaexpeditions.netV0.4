@extends('layout.backend')
@section('title', 'Booked Project List')
<?php 
  $active = 'booked/project'; 
  $subactive ='booked/project';
  use App\component\Content;
  $status = isset($_GET['status']) ? $_GET['status'] : '';
?>
@section('content')
<style type="text/css">

    .createNetPrice:hover .net_price{
      display: block;
    }
    .net_price{
      display: none;
      background: white;
      border: solid #ddd;
      box-shadow: 0px 0px 0px 0px #ddd;
      z-index: 2;
      position: absolute;
      padding: 6px;
    }
</style>

  @include('admin.include.header')
  @include('admin.include.menuleft')
  
  @if(\Auth::user()->role_id == 9)
    <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
          <div class="col-md-12">
            <h3 class="border">Project List 
              <div class="pull-right">
                <select class="form-control input-sm" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                  <option value="">--select--</option>
                  <option {{isset($_GET['status']) && $_GET['status'] == 'Disable' ? 'selected' : ''}} value="{{route('projectList', ['project'=> 'project', 'status'=>'Disable'])}}&checkin={{{$startDate or ''}}}&checkout={{{$endDate or ''}}}">Disable</option>
                  <option {{isset($_GET['status']) && $_GET['status'] == 'Active' ? 'selected' : ''}} value="{{route('projectList', ['project'=> 'project', 'status'=>'Active'])}}&checkin={{{$startDate or ''}}}&checkout={{{$endDate or ''}}}">Active</option>
                  <option {{isset($_GET['status']) && $_GET['status'] == 'Inactive' ? 'selected' : ''}} value="{{route('projectList', ['project'=> 'project', 'status'=>'Inactive'])}}&checkin={{{$startDate or ''}}}&checkout={{{$endDate or ''}}}">Inactive</option>
                </select>
              </div>
            </h3>
          </div>
          <form method="POST" action="{{route('searchProject', ['project'=> 'project', 'status'=> $status])}}">            
            {{csrf_field()}}
            <input type="hidden" name="status" value="{{$status}}">
            <section class="col-lg-12 connectedSortable">
              <div class="col-sm-8 col-xs-12 pull-right" style="position: relative; z-index: 2;">
                <div class="col-md-3 col-xs-5">
                  <input type="hidden" value="{{{$projectNum or ''}}}" id="projectNum">
                  <input class="form-control input-sm" type="text" id="from_date" name="start_date" placeholder="Date From" value="{{{$startDate or ''}}}" readonly>  
                </div>
                <div class="col-md-3 col-xs-5">
                  <input class="form-control input-sm" type="text" id="to_date" name="end_date" placeholder="Date To" value="{{{$endDate or ''}}}" readonly>  
                </div>
                <div class="col-md-2" style="padding: 0px;">
                  <button class="btn btn-primary btn-sm" type="submit">Search</button>
                </div>
                @if(\Auth::user()->role_id == 2)
                  <div class="col-md-1" style="padding: 0px;">
                    <a href="{{route('createPaymentLink')}}" class="btn btn-primary btn-sm">Create Payment Link</a>
                  </div>
                @endif
              </div>
              <table class="datatable table table-hover table-striped">
                <thead>
                  <tr>                       
                    <th width="53">Project</th>
                    <th width="51">FileNo.</th>
                    <th>ClientName</th>
                    <th width="177">Date From <i class="fa fa-long-arrow-right" style="color: #72afd2"></i> To</th>
                    <th width="135">Agent</th>
                    <th width="121">User</th>
                    <!-- <th>Country</th> -->
                    <th class="text-center" style="width: 267px;">Sales</th>
                 
                  </tr>
                </thead>
                <tbody>
                  @foreach($projects as $project)
                    <?php 
                      $sup = App\Supplier::find($project->supplier_id);
                      $user= App\User::find($project->UserID);
                      $con = App\Country::find($project->country_id);
                    ?>
                    <tr>
                      <td width="65">
                        <div class="createNetPrice">
                          {{$project->project_number}}
                          <ul class="list-unstyled net_price">
                            <li><a href="#" data-toggle="modal" class="btnAddNetPrice" data-id="{{$project->project_id}}" data-supplier_agent="{{$project->supplier_agent}}" data-project_net_price="{{$project->project_net_price}}" data-target="#myModal">
                              {!! $project->project_net_price > 0 ? 'Update Net Price' : ' add Net Price' !!}
                            </a></li>
                          </ul> 
                        </div>
                      </td>
                      <td>{{isset($project->project_fileno) ? $project->project_prefix."-".$project->project_fileno : ''}}</td>
                      <td>{{$project->project_client}} <span title="Pax Number {{$project->project_pax}}" class="badge">{{$project->project_pax}}</span></td>
                      <td>{{Content::dateformat($project->project_start) }} <i class="fa fa-long-arrow-right" style="color: #72afd2"></i> {{Content::dateformat($project->project_end)}}</td>
                      <td>{{{ $sup->supplier_name or ''}}}</td>
                      <td>{{{ $user->fullname or ''}}}</td>
                      <!-- <td>{{{ $con->country_name or ''}}}</td> -->
                      <td class="text-center">
                                     
                        <a target="_blank" href="{{route('previewProject', ['project'=>$project->project_number, 'type'=>'sales'])}}" title="Prview Details">
                          <label style="cursor:pointer;" class="icon-list ic_delpro_drop"></lable>
                      
                      
                        </a>       
                      </td>
                     
                    </tr>
                  @endforeach
                </tbody>
              </table> 
              <!-- <div class="pull-left">Check All</div> -->
            </section>
          </form>
        </div>
    </section>
</div>
  @else
 
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
          <div class="col-md-12">
            <h3 class="border">Project List <span class="fa fa-angle-double-right"></span>​​​ <a href="{{route('proForm')}}" class="btn btn-primary btn-sm">Create Project</a>
              <div class="pull-right">
                <select class="form-control input-sm" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                  <option value="">--select--</option>
                  <option {{isset($_GET['status']) && $_GET['status'] == 'Disable' ? 'selected' : ''}} value="{{route('projectList', ['project'=> 'project', 'status'=>'Disable'])}}&checkin={{{$startDate or ''}}}&checkout={{{$endDate or ''}}}">Disable</option>
                  <option {{isset($_GET['status']) && $_GET['status'] == 'Active' ? 'selected' : ''}} value="{{route('projectList', ['project'=> 'project', 'status'=>'Active'])}}&checkin={{{$startDate or ''}}}&checkout={{{$endDate or ''}}}">Active</option>
                  <option {{isset($_GET['status']) && $_GET['status'] == 'Inactive' ? 'selected' : ''}} value="{{route('projectList', ['project'=> 'project', 'status'=>'Inactive'])}}&checkin={{{$startDate or ''}}}&checkout={{{$endDate or ''}}}">Inactive</option>
                </select>
              </div>
            </h3>
          </div>
          <form method="POST" action="{{route('searchProject', ['project'=> 'project', 'status'=> $status])}}">            
            {{csrf_field()}}
            <input type="hidden" name="status" value="{{$status}}">
            <section class="col-lg-12 connectedSortable">
              <div class="col-sm-8 col-xs-12 pull-right" style="position: relative; z-index: 2;">
                <div class="col-md-3 col-xs-5">
                  <input type="hidden" value="{{{$projectNum or ''}}}" id="projectNum">
                  <input class="form-control input-sm" type="text" id="from_date" name="start_date" placeholder="Date From" value="{{{$startDate or ''}}}" readonly>  
                </div>
                <div class="col-md-3 col-xs-5">
                  <input class="form-control input-sm" type="text" id="to_date" name="end_date" placeholder="Date To" value="{{{$endDate or ''}}}" readonly>  
                </div>
                <div class="col-md-2" style="padding: 0px;">
                  <button class="btn btn-primary btn-sm" type="submit">Search</button>
                </div>
                @if(\Auth::user()->role_id == 2)
                  <div class="col-md-1" style="padding: 0px;">
                    <a href="{{route('createPaymentLink')}}" class="btn btn-primary btn-sm">Create Payment Link</a>
                  </div>
                @endif
              </div>
              <table class="datatable table table-hover table-striped">
                <thead>
                  <tr>                       
                    <th width="53">Project</th>
                    <th width="51">FileNo.</th>
                    <th>ClientName</th>
                    <th width="177">Date From <i class="fa fa-long-arrow-right" style="color: #72afd2"></i> To</th>
                    <th width="135">Agent</th>
                    <th width="121">User</th>
                    <!-- <th>Country</th> -->
                    <th class="text-center" style="width: 267px;">Sales</th>
                    <th class="text-center" style="width: 181px;">Operation</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($projects as $project)
                    <?php 
                      $sup = App\Supplier::find($project->supplier_id);
                      $user= App\User::find($project->UserID);
                      $con = App\Country::find($project->country_id);
                    ?>
                    <tr>
                      <td width="65">
                        <div class="createNetPrice">
                          {{$project->project_number}}
                          <ul class="list-unstyled net_price">
                            <li><a href="#" data-toggle="modal" class="btnAddNetPrice" data-id="{{$project->project_id}}" data-supplier_agent="{{$project->supplier_agent}}" data-project_net_price="{{$project->project_net_price}}" data-target="#myModal">
                              {!! $project->project_net_price > 0 ? 'Update Net Price' : ' add Net Price' !!}
                            </a></li>
                          </ul> 
                        </div>
                      </td>
                      <td>{{isset($project->project_fileno) ? $project->project_prefix."-".$project->project_fileno : ''}}</td>
                      <td>{{$project->project_client}} <span title="Pax Number {{$project->project_pax}}" class="badge">{{$project->project_pax}}</span></td>
                      <td>{{Content::dateformat($project->project_start) }} <i class="fa fa-long-arrow-right" style="color: #72afd2"></i> {{Content::dateformat($project->project_end)}}</td>
                      <td>{{{ $sup->supplier_name or ''}}}</td>
                      <td>{{{ $user->fullname or ''}}}</td>
                      <!-- <td>{{{ $con->country_name or ''}}}</td> -->
                      <td class="text-center">
                        <a target="_blank" href="{{route('previewProject', ['project'=>$project->project_number, 'type'=>'details'])}}" title="Program Details">
                          <label style="cursor:pointer;" class="icon-list ic_del_drop"></label>
                        </a>                      
                        <a target="_blank" href="{{route('previewProject', ['project'=>$project->project_number, 'type'=>'sales'])}}" title="Prview Details">
                          <label style="cursor:pointer;" class="icon-list ic_delpro_drop"></lable>
                        </a> 
                      
                        <a target="_blank" href="{{route('getInvoice', ['prject'=>$project->project_number, 'type'=> 'invoice'])}}" title="View Invoice">
                          <label style="cursor:pointer;" class="icon-list ic_invoice_drop"></lable>
                        </a> 
                        <a target="_blank" href="{{route('getInvoice', ['prject'=>$project->project_number, 'type'=> 'add_invoice'])}}" title="View Additional Invoice">
                          <label style="cursor:pointer;" class="icon-list ic_invoice_add"></lable>
                        </a> 
                        <a target="_blank" href="{{route('getInvoice', ['prject'=>$project->project_number, 'type'=> 'credit_not_invoice'])}}" title="View Credit Not Invoice">
                          <label style="cursor:pointer;" class="icon-list ic_invoice_credit"></lable>
                        </a>                     
                        <a target="_blank" href="{{route('proForm')}}?ref={{$project->project_number}}" title="Additional Booking">
                          <label style="cursor:pointer;" class="icon-list ic_book_add"></lable>
                        </a>     
                        <a target="_blank" href="{{route('proFormEdit', ['project'=> $project->project_number])}}" title="Edit Project">
                          <label style="cursor:pointer;" class="icon-list ic_book_project"></lable>
                        </a>    
                        <a target="_blank" href="{{route('preProject', ['project'=> $project->project_number])}}" title="View One Project">
                          <label style="cursor:pointer;" class="icon-list ic_inclusion"></lable>
                        </a>   
                        <a target="_blank" href="{{route('previewProject', ['project'=>$project->project_number, 'type'=>'details-net'])}}" title="Program Details Net">
                          <label style="cursor:pointer;" class="icon-list ic_del_net"></lable>
                        </a> 

                        <a target="_blank" href="{{route('previewProject', ['project'=>$project->project_number, 'type'=>'sales-net'])}}" title="Program Sales Net">
                          <label style="cursor:pointer; background-position: 0 -1574px !important;" class="icon-list ic_delpro_drop"></lable>
                        </a> 
                        <a style="font-size: 13px;position: relative;top: -2px;" target="_blank" href="{{route('previewProject', ['project'=>$project->project_number, 'type'=>'group-price'])}}"  title="Project Group Price">
                          <!-- <i class="fa fa-file-excel-o"></i> -->
                           <label style="cursor:pointer; background-position: 0 -1429px !important;" class="icon-list ic_delpro_drop"></lable>
                        </a>       
                      </td>
                      <td class="text-center">
                        <a target="_blank" href="{{route('previewProject', ['project'=>$project->project_number, 'type'=>'operation'])}}" title="Operation Program">
                          <label style="cursor:pointer;" class="icon-list ic_ops_program"></label>
                        </a>
                        <a target="_blank" href="{{route('getops', ['ops'=>'entrance', 'project'=> $project->project_number])}}" title="Entrance Fees">
                          <label style="cursor:pointer;" class="icon-list ic_entrance_fee"></label>
                        </a> 
                        <a target="_blank" href="{{route('getops', ['ops'=>'restaurant', 'project'=> $project->project_number])}}" title="Booking Restaurant">
                          <label style="cursor:pointer;" class="icon-list ic_restuarant"></label>
                        </a> 
                        <a target="_blank" href="{{route('getops', ['ops'=>'transport', 'project'=> $project->project_number])}}" title="Booking Transport">
                          <label style="cursor:pointer;" class="icon-list ic_transport"></label>
                        </a> 
                        <a target="_blank" href="{{route('getops', ['ops'=>'guide', 'project'=> $project->project_number])}}" title="Booking Guide">
                          <label style="cursor:pointer;" class="icon-list ic_guide"></label>
                        </a> 
                        <a target="_blank" href="{{route('getops', ['ops'=>'golf', 'project'=> $project->project_number])}}" title="Bookig Golf Courses">
                          <label style="cursor:pointer;" class="icon-list ic_golf"></label>
                        </a>
                        <a target="_blank" href="{{route('getops', ['ops'=>'misc', 'project'=> $project->project_number])}}" title="Booking MISC"> 
                          <label style="cursor:pointer;" class="icon-list ic_misc"></label>
                        </a>         
                        {!! Content::DelUserRole("Delete this project ?", "book_project", $project->project_id, $project->UserID ) !!} 
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table> 
              <!-- <div class="pull-left">Check All</div> -->
            </section>
          </form>
        </div>
    </section>
</div>

    @endif

<div class="modal in" id="myModal" role="dialog"  data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-lg">    
    <form method="POST" action="{{route('projectAddNetPrice')}}">
      <div class="modal-content">        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Create Net Price</strong></h4>
        </div>
        <div class="modal-body">
          {{csrf_field()}}    
          <input type="hidden" name="project_id" id="project_id">
          <div class="row">
            <div class="col-md-4 col-xs-12">
              <div class="form-group">
                <label>Location</label>
                <select class="form-control country" id="country_supplier_agent" name="supplier_agent" data-type="supplier_by_country" data-title="9">
                  <?php $location = App\Supplier::where(['supplier_status'=>1,'business_id'=>9])->whereHas('country')->groupBy('country_id')->orderBy('country_id')->get(); ?>
                  @foreach($location as $con)
                    <option value="{{$con->country_id}}">{{$con->country->country_name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-4 col-xs-6">
              <div class="form-group">
                <label>Supplier Agent<span style="color:#b12f1f;">*</span></label> 
                <select class="form-control" name="supplier_agent" id="dropdown-supplier_by_country" required>
                  <option>Agent</option>
                  <?php $getAgent = App\Supplier::where(['business_id'=>9, 'supplier_status'=>1])->orderBy('supplier_name')->get(); ?>
                  @if($getAgent->count() > 0)
                    @foreach($getAgent as $agent)
                      <option value="{{$agent->id}}">{{$agent->supplier_name}}</option>
                    @endforeach
                  @endif
                </select>
              </div> 
            </div>
            <div class="col-md-4 col-xs-6">
              <div class="form-group text-center">
                <label>Net Price</label>
                <input type="text" name="project_net_price" class="form-control number_only text-center" required>
              </div>
            </div>
          </div>
        <div class="modal-footer" style="text-align: center;">
          <button type="submit" class="btn btn-success btn-flat btn-sm">Create </button>
          <a href="#" class="btn btn-acc btn-flat btn-sm" data-dismiss="modal">Cancel</a>
        </div>
      </div>      
    </form>
  </div>
</div>

@include('admin.include.datepicker')
<script type="text/javascript">
  $(document).ready(function(){
    $(document).on('click', '.btnAddNetPrice', function() {
      var supplier_agent = $(this).data('supplier_agent');
      $("#dropdown-supplier_by_country option").each( function(i){
          if($(this).val() == supplier_agent){
            $(this).attr("selected", true);
          }else{
            $(this).removeAttr("selected", true);
          }
      });
      $('#project_id').val($(this).data('id'));
      $('input[name=project_net_price]').val($(this).data('project_net_price'));
    });

    $(".datatable").DataTable({
      language: {
        searchPlaceholder: "File/Project No., ClientName"
      },
      order: [[3, 'asc']]
    });
  });
</script>
@endsection
