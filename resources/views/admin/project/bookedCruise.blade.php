@extends('layout.backend')
@section('title', 'Booked Cruise List')
<?php $active = 'booked/project'; 
$subactive ='booked/cruise';
  use App\component\Content;
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
          @include('admin.include.message')
          <form method="POST" action="{{route('searchProject', ['project'=> 'cruise'])}}">
           {{csrf_field()}}
            <section class="col-lg-12 connectedSortable">
                <h3 class="border">Booked Cruise List</h3>
                <div class="col-sm-8 col-xs-12 pull-right">
                  <div class="col-md-3">
                    <input type="hidden" value="{{{$projectNum or ''}}}" id="projectNum">
                    <input class="form-control input-sm" type="text" id="from_date" name="start_date" placeholder="From Date" value="{{{$startDate or ''}}}"> 
                  </div>
                  <div class="col-md-3">
                    <input class="form-control input-sm" type="text" id="to_date" name="end_date" placeholder="To Date" value="{{{$endDate or ''}}}"> 
                  </div>
                  <div class="col-md-2" style="padding: 0px;">
                    <button class="btn btn-default btn-sm" type="submit">Search</button>
                  </div>
                </div>
                <table class="datatable table table-hover table-striped">
                  <thead>
                    <tr>                       
                      <th width="75">Project No.</th>
                      <th>Date</th>
                      <!-- <th>City</th> -->
                      <th>River Cruise</th>
                      <th>Program</th>
                      <th>User</th>
                      <th>Booking Type</th>
                      <th class="text-center" style="width: 17%;">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($projects as $rc)
                      <?php 
                        $supb = \App\Supplier::find($rc->cruise_id);
                        $conb = \App\Country::find($rc->country_id);
                        $prob = \App\Province::find($rc->province_id);
                        $cruisb = \App\CrProgram::find($rc->program_id);
                        $user   = \App\User::find($rc->book_userId);
                        $RCJournal = App\AccountJournal::where(['business_id'=>3,'project_number'=>$rc->book_project, 'status'=>1, 'type'=>1, 'supplier_id'=>$rc->cruise_id,])->first(); 
                      ?>
                      <tr>
                        <td width="75">{{$rc->book_project}}</td>
                        <td>{{Content::dateformat($rc->book_checkin) }}</td>
                        <td>{{{ $supb->supplier_name or ''}}} <span class="badge">{{{ $prob->province_name or ''}}}</span></td>
                        <td>{{{ $cruisb->program_name or ''}}}</td>
                        <td>{{{ $user->fullname or ''}}}</td>
                        <td class="text-center">
                          @if($rc->book_option == 1)
                            <span class="text-danger">Quotation</span>
                            @else
                              <span class="text-success">Booking</span>
                            @endif
                        </td>
                        <td class="text-center">
                          <a target="_blank" href="{{route('previewProject', ['project'=>$rc->book_project, 'type'=>'details'])}}" title="Program Details">
                            <label class="icon-list ic_ops_program"></label>
                          </a>&nbsp;
                          @if($RCJournal == Null)
                            <a target="_blank" href="{{route('bookingEdit', ['url'=>'cruise', 'id'=>$rc->book_id])}}" title="Edit Cruise">
                              <label class="icon-list ic_edit"></label>
                            </a>&nbsp;
                          @endif
                          <a target="_blank" href="{{route('bookedCruise', ['pro'=> $rc->book_project,'cruise'=>$rc->cruise_id,'book'=>$rc->book_id])}}" title="Book room for this Cruse" style="position: relative;top: -1px;">
                            <i class="fa fa-hotel (alias)" style="font-size: 19px;color: #c38015;"></i>
                          </a>&nbsp;
                          <a target="_blank" href="{{route('searchProject', ['project'=> 'cruiserate', 'textSearch'=>$rc->book_project])}}" title="View Cruise Booking Rate">
                            <label class="icon-list ic_inclusion"></label>
                          </a>&nbsp;
                          @if($RCJournal == Null)
                            <a onclick="return confirm('Are you sure to Remove Cruise Rate ?');" href="{{route('RhPrice', ['rc'=> isset($rc->cruise_id)? $rc->cruise_id:0 , 'book'=> $rc->book_id, 'type'=> 'cruise'])}}" title="Remove cruise rate all in this project" style="position: relative;top: -2px;">
                              <i class="fa fa-trash" style="font-size: 19px;color: #8e877b;"></i>
                            </a>
                            {!! Content::DelUserRole("Delete this Flight Booked ?", "book_cruise", $rc->book_id, $rc->user_id ) !!}            
                          @else
                              <span title="Project have been posted. can't edit" style="border-radius: 50px;border: solid 1px #795548; padding: 0px 6px;  position: relative;top: -4px;">Posted</span>
                          @endif
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
  <script type="text/javascript">
  $(document).ready(function(){
     $(".datatable").DataTable();
  });
</script>
@include('admin.include.datepicker')
@endsection
