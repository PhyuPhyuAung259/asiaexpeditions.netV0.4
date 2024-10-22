@extends('layout.backend')
@section('title', 'Booked Cruise List')
<?php $active = 'booked/project'; 
$subactive ='booked/cruiserate';
  use App\component\Content;
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
          <form method="POST" action="{{route('searchProject', ['project'=> 'cruiserate'])}}">
            {{csrf_field()}}
            <section class="col-lg-12 connectedSortable">
                <h3 class="border">Booked Cruise Rate List</h3>
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
                      <th>Check-In</th>
                      <th>Check-Out</th>
                      <th>Cruise Name</th>
                      <th>Cabin Type</th>
                      @foreach(App\RoomCategory::take(5)->orderBy('id', 'ASC')->get() as $cat)
                        <th>{{$cat->name}}</th>
                      @endforeach
                      <th>Booking Type</th>
                      <th class="text-center">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($projects as $crRate)
                    <tr> 
                      <td width="65">{{$crRate->project_number}}</td>
                      <td>{{Content::dateformat($crRate->checkin) }}</td>
                      <td>{{Content::dateformat($crRate->checkout) }}</td>
                      <td>{{{ $crRate->cruise->supplier_name or ''}}}</td>
                      <td>{{{ $crRate->room->name or ''}}}</td>
                      <td class="text-right">{{Content::money($crRate->ssingle)}}</td>
                      <td class="text-right">{{Content::money($crRate->stwin)}}</td>
                      <td class="text-right">{{Content::money($crRate->sdouble)}}</td>
                      <td class="text-right">{{Content::money($crRate->sextra)}}</td>
                      <td class="text-right">{{Content::money($crRate->schextra)}}</td>
                      <td class="text-center">
                        @if($crRate->option == 1)
                          <span class="text-danger">Quotation</span>
                        @else
                          <span class="text-success">Booking</span>
                        @endif
                      </td>
                      <?php $RCJournal = App\AccountJournal::where(['book_id'=>$crRate->id, 'business_id'=>3,'project_number'=>$crRate->project_number, 'status'=>1, 'type'=>1])->first(); ?>
                      <td class="text-right">
                          <a target="_blank" href="{{route('hVoucher', ['project'=>$crRate->project_number, 'bcruise'=> $crRate->id, 'bookid'=>$crRate->book->id, 'type'=>'cruise-voucher'])}}" title="Cruise Voucher">
                          <label class="icon-list ic_inclusion"></label>
                          </a> &nbsp;
                        @if($RCJournal == Null)
                          {!! Content::DelUserRole("Delete this Cruise Rate ?", "book_cruiserate", $crRate->id, $crRate->user_id ) !!}        
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