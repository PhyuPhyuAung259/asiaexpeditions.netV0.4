@extends('layout.backend')
@section('title', 'Project Quotation')
<?php 
  $active = 'booked/project'; 
  $subactive ='booked/project?type=quotation';
  use App\component\Content;
  $status = isset($_GET['type']) ? $_GET['type'] : '';
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
          <div class="col-md-12">
            <h3 class="border">Project List <span class="fa fa-angle-double-right"></span>​​​ <a href="{{route('proForm', ['type'=> 'quotation'])}}" class="btn btn-primary btn-sm">Add Project Quotation</a>
            </h3>
          </div>
          <form method="POST" action="{{route('searchProject', ['project'=> 'project', 'type'=> $status])}}">            
            {{csrf_field()}}
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
              </div>
              <table class="datatable table table-hover table-striped">
                <thead>
                  <tr>                       
                    <th width="65">Project No.</th>
                    <th>ClientName</th>
                    <th style="width: 62.8125px;">Start Date</th>
                    <th style="width: 58.8125px;">End Date</th>
                    <th>Agent</th>
                    <th style="width: 79.8125px;">User</th>
                    <th>Country</th>
                    <th class="text-center" style="width: 230px;">Action</th>
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
                      <td width="75">{{$project->project_number}}</td>
                      <td>{{$project->project_client}}</td>
                      <td>{{Content::dateformat($project->project_start) }}</td>
                      <td>{{Content::dateformat($project->project_end)}}</td>
                      <td>{{{ $sup->supplier_name or ''}}}</td>
                      <td>{{{ $user->fullname or ''}}}</td>
                      <td>{{{ $con->country_name or ''}}}</td>
                      <td class="text-center">
                        <a target="_blank" href="{{route('previewProject', ['project'=>$project->project_number, 'type'=>'details'])}}" title="Program Details">
                          <label style="cursor:pointer;" class="icon-list ic_del_drop"></label>
                        </a>                      
                        <a target="_blank" href="{{route('previewProject', ['project'=>$project->project_number, 'type'=>'sales'])}}" title="Prview Details">
                          <label style="cursor:pointer;" class="icon-list ic_delpro_drop"></lable>
                        </a> 
                      
                        <a target="_blank" href="{{route('proForm', ['ref'=> $project->project_number, 'type' => 'quotation'])}}" title="Additional Booking">
                          <label style="cursor:pointer;" class="icon-list ic_book_add"></lable>
                        </a>     
                        <a target="_blank" href="{{route('proFormEdit', ['project'=> $project->project_number, 'action'=>'copy'])}}" title="Edit Project">
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
                          <label style="cursor:pointer; background-position: 0 -1429px !important;" class="icon-list ic_delpro_drop"></lable>
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

@include('admin.include.datepicker')
<script type="text/javascript">
  $(document).ready(function(){
    $(".datatable").DataTable({
      language: {
        searchPlaceholder: "File/Project No., ClientName"
      }
    });
  });
</script>
@endsection
