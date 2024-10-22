@extends('layout.backend')
@section('title', 'Booked Hotel List')
<?php $active = 'booked/project'; 
$subactive = 'booked/hotelrate';
  use App\component\Content;
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
          <form method="POST" action="{{route('searchProject', ['project'=> 'hotelrate'])}}">
             {{csrf_field()}}
            <section class="col-lg-12 connectedSortable">
                <h3 class="border">Booked Hotel Rate List</h3>
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
                      <th width="56">File No.</th>
                      <th width="148">CheckIn <i class="fa fa-long-arrow-right" style="color: #72afd2"></i> CheckOut</th>
                      <th>Hotel</th>
                      <th>Room Type</th>
                      <th>No.Room</th>
                      <th>Night</th>
                      @foreach(App\RoomCategory::take(5)->orderBy('id', 'ASC')->get() as $key => $cat)
                        <th class="text-right" title="{{$cat->name}}" style="text-transform: capitalize;">{{substr($cat->key_name, 1)}}</th>
                      @endforeach
                      <th width="70px" class="text-center">Type</th>
                      <th class="text-center" width="98">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($projects as $hotel)
                      <?php $project = App\Project::where('project_number', $hotel->project_number)->first(); ?>
                      <tr> 
                        <td width="65">{{$hotel->project_number}}</td>
                        <td>{{isset($project['project_fileno']) ? $project['project_prefix']."-".$project->project_fileno : ''}}</td>
                        <td>{{Content::dateformat($hotel->checkin)}} <i class="fa fa-long-arrow-right" style="color: #72afd2"></i> {{Content::dateformat($hotel->checkout) }}</td>
                        <td>{{{ $hotel->hotel->supplier_name or ''}}}</td>
                        <td>{{{ $hotel->room->name or ''}}}</td>
                        <td>{{$hotel->no_of_room}}</td>
                        <td>{{$hotel->book_day}}</td>
                        <td class="text-right">{{Content::money($hotel->ssingle)}}</td>
                        <td class="text-right">{{Content::money($hotel->stwin)}}</td>
                        <td class="text-right">{{Content::money($hotel->sdouble)}}</td>
                        <td class="text-right">{{Content::money($hotel->sextra)}}</td>
                        <td class="text-right">{{Content::money($hotel->schextra)}}</td>
                        <td class="text-center">
                          @if($hotel->hotel_option == 1)
                          <span class="text-danger">Quotation</span>
                          @else
                            <span class="text-success">Booking</span>
                          @endif
                        </td>

                        <?php $HproJournal = App\AccountJournal::where(['supplier_id'=>$hotel->hotel_id, 'business_id'=>1,'project_number'=>$hotel->project_number, "book_id"=>$hotel->id, 'status'=>1])->first(); ?>
                        <td class="text-right">
                            <a  target="_blank" href="{{route('hVoucher', ['project'=>$hotel->project_number, 'bhotel'=> $hotel->id, 'bookid'=>$hotel->book->id, 'type'=>'hotel-voucher'])}}" title="Hotel Voucher">
                              <label class="icon-list ic_inclusion"></label>
                            </a>

                          @if($HproJournal == Null)
                              <a href="javascript:void(0)" class="RemoveHotelRate" data-type="book_hotelrate" data-id="{{$hotel->id}}" title="Delete this Hotel Rate">
                                <label class="icon-list ic_remove"></label>
                              </a>  
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
  $(".datatable").DataTable({
    language: {
      searchPlaceholder: "File / Project No."
    }
  });
</script>
@include('admin.include.datepicker')
@endsection