@extends('layout.backend')
@section('title', 'Booked Hotel List')
<?php $active = 'booked/project'; 
$subactive ='booked/hotel';
  use App\component\Content;
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
          <form method="POST" action="{{route('searchProject', ['project'=> 'hotel'])}}">
             {{csrf_field()}}
            <section class="col-lg-12 connectedSortable">
                <h3 class="border">Booked Hotel List</h3>
                @include('admin.project.Search')
                <table class="datatable table table-hover table-striped">
                  <thead>
                    <tr>                       
                      <th width="75">Project No.</th>
                      <th width="65">File No.</th>
                      <th width="175px">CheckIn<i class="fa fa-long-arrow-right" style="color: #72afd2"></i>CheckOut</th>
                      <th>Location</th>
                      <th>Hotel</th>
                      <th>User</th>
                      <th width="120px" class="text-center">Type</th>
                      <th class="text-center" style="width: 17%;">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($projects as $hotel)
                        <?php 
                          $supb = \App\Supplier::find($hotel->hotel_id);
                          $conb = \App\Country::find($hotel->country_id);
                          $prob = \App\Province::find($hotel->province_id);
                          $user = \App\User::find($hotel->book_userId);
                          $hJnl = App\AccountJournal::where(['supplier_id'=>$hotel->hotel_id, 'business_id'=>1,'project_number'=>$hotel->book_project, 'status'=>1])->first(); 
                          $project = App\Project::where('project_number', $hotel->book_project)->first();
                        ?>
                        <tr>
                          <td>{{$project['project_number']}}</td>
                          <td>{{$project['project_fileno'] ? $project['project_prefix']."-".$project['project_fileno'] : '' }}</td>
                          <td>{{Content::dateformat($hotel->book_checkin) }} <i class="fa fa-long-arrow-right" style="color: #72afd2"></i> {{Content::dateformat($hotel->book_checkout) }}</td>
                          <td>{{{ $prob->province_name or ''}}}</td>
                          <td><a target="_blank" href="{{route('supplierReport' ,['reportId' => $supb->id,'type'=> 'hotels'])}}?type=contract">{{{ $supb->supplier_name or ''}}}</a></td>
                          <td>{{ $user['fullname'] }}</td>
                          <td class="text-center">
                            @if($hotel->book_option == 1)
                            <span class="text-danger">Quotation</span>
                            @else
                              <span class="text-success">Booking</span>
                            @endif
                          </td>
                          <td class="text-center">
                            <a target="_blank" href="{{route('previewProject', ['project'=>$hotel->book_project, 'type'=>'details'])}}" title="Program Details">
                              <label class="icon-list ic_ops_program"></label>
                            </a>&nbsp;
                            @if($hJnl == Null)
                              <a target="_blank" href="{{route('bookingEdit', ['url'=>'hotel', 'id'=>$hotel->book_id])}}" title="Edit hotel">
                                <label class="icon-list ic_edit"></label>
                              </a>&nbsp;
                            @endif
                            <a target="_blank" href="{{route('bapplyRoom', ['pro'=> $hotel->book_project,'hotelid'=>$hotel->hotel_id,'bookid'=> $hotel->book_id])}}" title="Apply room for this hotel" >
                              <i class="fa fa-hotel (alias)" style="font-size: 19px;color: #c38015;"></i>
                            </a>&nbsp;

                            <a target="_blank" href="{{route('searchProject', ['project'=> 'hotelrate', 'textSearch'=>$hotel->book_project])}}" title="View Hotel Booking Rate">
                              <label class="icon-list ic_inclusion"></label>
                            </a>&nbsp;                            
                            @if($hJnl == Null)
                                <a onclick="return confirm('Are your sure you want delete this?');" href="{{route('RhPrice', ['hotel'=> isset($hotel->hotel_id)? $hotel->hotel_id:0 , 'book'=> $hotel->book_id, 'type'=>'hotel'])}}" title="Remove hotel price" >
                                  <i class="fa fa-trash" style="font-size: 19px;color: #8e877b;"></i>
                                </a>                   
                               {!! Content::DelUserRole("Delete this Hotel Booked ?", "book_hotel", $hotel->book_id, $hotel->user_id ) !!}   
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
