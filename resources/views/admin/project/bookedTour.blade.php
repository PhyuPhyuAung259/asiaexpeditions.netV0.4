@extends('layout.backend')
@section('title', 'Booked Tour List')
<?php $active = 'booked/project'; 
  $subactive ='booked/tour';
  use App\component\Content;
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
          <form method="POST" action="{{route('searchProject', ['project'=> 'tour'])}}">
            {{csrf_field()}}
            <section class="col-lg-12 connectedSortable">
                <h3 class="border">Booked Tours List</h3>
                <div class="col-sm-8 col-xs-12 pull-right">
                  <div class="col-md-3">
                    <input type="hidden" svalue="{{{$projectNum or ''}}}" id="projectNum">
                    <input class="form-control input-sm" type="text" id="from_date" name="start_date" placeholder="From Date" value="{{{$startDate or ''}}}"> 
                  </div>
                  <div class="col-md-3">
                    <input class="form-control input-sm" type="text" id="to_date" name="end_date" placeholder="To Date" value="{{{ $endDate or ''}}}"> 
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
                      <th>Location</th>
                      <th>Tour</th>
                      <th>User</th>
                      <th>Pax</th>
                      <th>Price</th>
                      <th>Amount</th>
                      <th class="text-center">Booking Type</th>
                      <th class="text-center">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($projects as $tour)
                      <?php 
                        $tourb= \App\Tour::find($tour->tour_id);
                        $conb = \App\Country::find($tour->country_id);
                        $prob = \App\Province::find($tour->province_id);
                        $user = \App\User::find($tour->book_userId);

                        $proJournal = App\AccountJournal::where(['supplier_id'=>$tour->supplier_id, 'business_id'=>9,'project_number'=>$tour->project_number, "book_id"=>$tour->project_id, 'status'=>1])->first();
                      ?>
                    <tr>
                      <td>{{$tour->book_project}}</td>
                      <td>{{Content::dateformat($tour->book_checkin) }}</td>
                      <td >  {{{ $prob->province_name or ''}}}</td>
                      <td><a target="_blank" href="{{route('getTourReport', ['tour_id'=> $tourb->id, 'type'=> 'selling'])}}">{{{ $tourb->tour_name or ''}}}</a></td>
                      <td>{{ $user['fullname']}}</td>
                      <td>{{ $tour->book_pax }}</td>
                      <td>{{ Content::money($tour->book_price)}}</td>
                      <td>{{ Content::money($tour->book_amount)}}</td>
                      <td class="text-center">
                        @if($tour->book_option == 1)
                        <span class="text-danger">Quotation</span>
                        @else
                          <span class="text-success">Booking</span>
                        @endif
                      </td>
                      <td class="text-right">
                        <a target="_blank" href="{{route('previewProject', ['project'=>$tour->book_project, 'type'=>'details'])}}" title="Program Details">
                          <label class="icon-list ic_ops_program"></label>
                        </a>
                        @if($proJournal == Null)
                          <a target="_blank" href="{{route('bookingEdit', ['type'=>'tour', 'bookid'=>$tour->book_id])}}" title="Edit booked tour">
                            <label class="icon-list ic_edit"></label>
                          </a> 
                         {!! Content::DelUserRole("Delete this Tour Booked ?", "book_tour", $tour->book_id, $tour->user_id ) !!}        
                        @else
                          <span title="Project have been posted. can't edit" style="border-radius: 50px;border: solid 1px #795548; padding: 0px 6px;  position: relative;top: -4px;">Posted</span>      
                        @endif
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
