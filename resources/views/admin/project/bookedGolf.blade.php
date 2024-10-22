@extends('layout.backend')
@section('title', 'Booked Golf List')
<?php $active = 'booked/project'; 
$subactive ='booked/golf';
  use App\component\Content;
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
          <form method="POST" action="{{route('searchProject', ['project'=> 'golf'])}}">
             {{csrf_field()}}
            <section class="col-lg-12 connectedSortable">
                <h3 class="border">Booked Golf List</h3>
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
                      <th width="75">ProjectNo.</th>
                      <th width="63">Date</th>
                      <th>Golf</th>
                      <th>Service</th>
                      <th>User</th>
                      <th class="text-center">Pax</th>
                      <th class="text-center">Price</th>
                      <th class="text-center">Amount</th>
                      <th class="text-center">Booking</th>
                      <th class="text-center" style="width:8%;">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($projects as $golf)
                      <?php 
                        $golfb  = \App\Supplier::find($golf->golf_id);
                        $gmenub = \App\GolfMenu::find($golf->program_id);
                        $conb = \App\Country::find($golf->country_id);
                        $prob = \App\Province::find($golf->province_id);
                        $GJournal = App\AccountJournal::where(['business_id'=>29, 'supplier_id'=>$golf->golf_id, 'project_number'=>$golf->book_project, "book_id"=>$golf->book_id, 'type'=>1, 'status'=>1])->first();
                      ?>
                      <tr>
                        <td width="75">{{$golf->book_project}}</td>
                        <td>{{ Content::dateformat($golf->book_checkin) }}</td>                        
                        <td>{{{ $golfb->supplier_name or '' }}} <span class="badge">{{{ $prob->province_name or '' }}}</span></td>
                        <td>{{{ $gmenub->name or '' }}}</td>
                        <td>{{ $golf->fullname }}</td>
                        <td class="text-center">{{$golf->book_pax}}</td>
                        <td class="text-right">{{$golf->book_price}}</td>
                        <td class="text-right">{{$golf->book_amount}}</td>
                        <td class="text-center">
                          @if($golf->book_option == 1)
                            <span class="text-danger">Quotation</span>
                          @else
                            <span class="text-success">Booking</span>
                          @endif
                        </td>
                        <td class="text-center">
                          <a target="_blank" href="{{route('previewProject', ['project'=>$golf->book_project, 'type'=>'details'])}}" title="Program Details">
                            <label class="icon-list ic_ops_program"></label>
                          </a>&nbsp;
                          @if($GJournal == Null)
                            <a target="_blank" href="{{route('bookingEdit', ['url'=>'golf', 'id'=> $golf->book_id])}}" title="Edit hotel">
                              <label class="icon-list ic_edit"></label>
                            </a>&nbsp;    
                            {!! Content::DelUserRole("Delete this Hotel Booked ?", "book_golf", $golf->book_id, $golf->user_id ) !!}               
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
