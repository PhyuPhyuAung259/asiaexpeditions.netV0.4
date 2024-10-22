@extends('layout.backend')
@section('title', 'Booked Flight List')
<?php $active = 'booked/project'; 
  $subactive ='booked/flight';
  use App\component\Content;
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
          <form method="POST" action="{{route('searchProject', ['project'=> 'flight'])}}">
             {{csrf_field()}}
            <section class="col-lg-12 connectedSortable">
                <h3 class="border">Booked Flight List</h3>
                <div class="col-sm-8 pull-right">
                  <div class="col-md-3">
                    <input type="hidden" name="" value="{{{$projectNum or ''}}}" id="projectNum">
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
                      <th>Flight Agency</th>
                      <th>Flight No.</th>
                      <th>Destination</th>
                      <th>User</th>
                      <th>Pax</th>
                      <th class="text-right">Unit {{Content::currency()}}</th>
                      <th class="text-right">Amount</th>
                      <th>Booking Type</th>
                      <th class="text-center">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($projects as $fsch)
                      <?php 
                        $supb  = \App\FlightSchedule::find($fsch->flight_id);
                        $agenb = \App\Supplier::find($fsch->book_agent);
                        $user  = \App\User::find($fsch->book_userId);

                        $FJournal = App\AccountJournal::where(['supplier_id'=>$agenb['id'], 'business_id'=>4,'project_number'=>$fsch->book_project, "book_id"=>$fsch->book_id, 'type'=>1, 'status'=>1])->first();
                      ?>
                    <tr>
                      <td width="75">{{$fsch->book_project}}</td>
                      <td>{{Content::dateformat($fsch->book_checkin) }}</td>
                      <td>{{{ $agenb->supplier_name or ''}}}</td>
                      <td>{{{ $supb->flightno or ''}}}</td>
                      <td>{{{ $supb->flight_from or ''}}} - {{{ $supb->flight_to or ''}}}</td>
                      <td>{{{ $user->fullname or ''}}}</td>
                      <td>{{ $fsch->book_pax }}</td>
                      <td class="text-right">{{ Content::money($fsch->book_price)}}</td>
                      <td class="text-right">{{ Content::money($fsch->book_amount)}}</td>
                      <td class="text-center">
                        @if($fsch->book_option == 1)
                          <span class="text-danger">Quotation</span>
                        @else
                          <span class="text-success">Booking</span>
                        @endif
                      </td>
                      <td class="text-center">
                        <a target="_blank" href="{{route('previewProject', ['project'=>$fsch->book_project, 'type'=>'details'])}}" title="Program Details">
                          <label class="icon-list ic_ops_program"></label>
                        </a>
                        @if($FJournal == Null)
                          <a target="_blank" href="{{route('bookingEdit', ['type'=>'flight', 'bookid'=>$fsch->book_id])}}" title="Edit flight booking">
                            <label class="icon-list ic_edit"></label>
                          </a> 
                          {!! Content::DelUserRole("Delete this Flight Booked ?", "book_flight", $fsch->book_id, $fsch->user_id ) !!}            
                        @else
                          <span title="Project have been posted. can't edit" style="border-radius: 50px;border: solid 1px #795548; padding: 0px 6px;    position: relative;top: -4px;">Posted</span>
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
