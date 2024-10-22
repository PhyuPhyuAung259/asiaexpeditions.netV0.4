@extends('layout.backend')
@section('title', 'Posting Account Management')
<?php 
  $active = 'finance/journal'; 
  $subactive = 'finance/posting';
  use App\component\Content;
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <form method="POST" action="{{route('findPostingAccount')}}">
          {{csrf_field()}}
            <h3 class="border">Select Project to Post Into Ledger</h3>
            <div class="col-sm-8 col-xs-12 pull-right" style="position: relative; z-index: 2;">
              <div class="col-md-3 col-xs-5">
                <input type="hidden" value="{{{$projectNum or ''}}}" id="projectNum">
                <input class="form-control input-sm" type="text" id="from_date" name="start_date" placeholder="Date From" value="{{{$startDate or ''}}}" readonly>
              </div>
              <div class="col-md-3 col-xs-5">
                <input class="form-control input-sm" type="text" id="to_date" name="end_date" placeholder="Date To" value="{{{$endDate or ''}}}" readonly> 
              </div>
              <div class="col-md-2" style="padding: 0px;">
                <input type="submit" class="btn btn-primary btn-sm" value="Search">
              </div>           
            </div>
            <table class="datatable table table-hover table-striped">
              <thead>
                <tr>                       
                  <th width="75">Project No.</th>
                  <th>ClientName</th>
                  <th>Date From -> To</th>
                  <th>Agent</th>
                  <th>User</th>
                  <th>Country</th>
                  <th class="text-center" style="width: 181px;">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($projects as $project)
                  <?php 
                    $sup = App\Supplier::find($project->supplier_id);
                    $user = App\User::find($project->UserID);
                    $con = App\Country::find($project->country_id);
                  ?>
                  <tr>
                    <td width="75">{{$project->project_number}}</td>
                    <td>{{$project->project_client}}</td>
                    <td>{{Content::dateformat($project->project_start)}} -> {{Content::dateformat($project->project_end)}}</td>
                    <td>{{{ $sup->supplier_name or ''}}}</td>
                    <td>{{{ $user->fullname or ''}}}</td>
                    <td>{{{ $con->country_name or ''}}}</td>
                    <td class="text-center">
                      <a target="_blank" href="{{route('previewPosting', ['project'=>$project->project_number, 'type'=>'Posting Account'])}}" title="View to Post">
                        <label style="cursor:pointer;" class="icon-list ic_ops_program"></label>
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
        </form>
    </section>
  </div>
</div>



@include('admin.include.datepicker')
<script type="text/javascript">
  $(document).ready(function(){
    $(".datatable").DataTable({
      language: {
        searchPlaceholder: "Project/File No. ClientName"
      }
    });
  });

</script>
@endsection
