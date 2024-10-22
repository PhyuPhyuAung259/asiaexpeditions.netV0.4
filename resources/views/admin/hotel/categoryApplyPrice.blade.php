@extends('layout.backend')
@section('title', 'Hotel & Room Rate')
<?php $active = 'supplier/hotels'; 
  $subactive ='hotel/hotelrate';
  use App\component\Content;
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
      <div class="row">
        <section class="col-lg-12 connectedSortable">
          <h3 class="border">Hotel & Room Rate</h3>
          <form method="POST" action="{{route('serachHotelRate')}}">
            {{csrf_field()}}
            <div class="col-sm-8 pull-right">
                <div class="col-md-3">
                  <input type="hidden" name="" value="{{{$htoelName or ''}}}" id="projectNum">
                  <input class="form-control input-sm" type="text" id="from_date" name="start_date" placeholder="Start Date" value="{{{$startDate or ''}}}"> 
                </div>
                <div class="col-md-3">
                  <input class="form-control input-sm" type="text" id="to_date" name="end_date" placeholder="End Date" value="{{{$endDate or ''}}}"> 
                </div>
                 <div class="col-md-2" style="padding: 0px;">
                  <button class="btn btn-default btn-sm" type="submit">Search</button>
                </div>
            </div>
            <table class="datatable table table-hover table-striped" >
              <thead>
                <tr>                     
                  <th width="168">Hotel</th>
                  <th>RoomType</th>
                  <th>User</th>
                  <th width="137px">Date Start->End</th>
                  @foreach(\App\RoomCategory::where('status',1)->orderBy('id', 'ASC')->get() as $cat)
                    <th title="{{$cat->name}}" style="text-transform: capitalize;">{{$cat->key_name}}</th>
                  @endforeach
                  <th class="text-center">Status</th>
                </tr>
              </thead>
              <tbody>                  
                @foreach($hotelrates as $rate)
                 <?php 
                  $sup = \App\Supplier::find($rate->supplier_id);
                  $room= \App\Room::find($rate->room_id);
                  $user = \App\User::find($rate->user_id);

                ?>
                <tr class="text-right">                     
                  <td class="text-left" style="width:20%;">{{{ $sup->supplier_name or ''}}}</td>
                  <td class="text-left" style="width:13%;">{{{ $room->name or ''}}}</td>
                  <td>{{$user['fullname']}}</td>
                  <td class="text-center" style="width:38%;">{{Content::dateformat($rate->start_date)}} -> {{Content::dateformat($rate->end_date)}}</td>
                  <td>{{Content::money($rate->ssingle)}}</td>
                  <td>{{Content::money($rate->stwin)}}</td>
                  <td>{{Content::money($rate->sdbl_price)}}</td>
                  <td>{{Content::money($rate->sextra)}}</td>
                  <td>{{Content::money($rate->schexbed)}}</td>
                  <td>{{Content::money($rate->nsingle)}}</td>
                  <td>{{Content::money($rate->ntwin)}}</td>
                  <td>{{Content::money($rate->ndbl_price)}}</td>
                  <td>{{Content::money($rate->nextra)}}</td>
                  <td>{{Content::money($rate->nchexbed)}}</td>
      				    <td class="text-center"> 
                    <a href="{{route('getEditHotelRate', ['hotelid'=> $rate->supplier_id, 'roomId'=>$rate->room_id])}}" title="Edit hotel rate">
                      <label class="icon-list ic_edit_hprice"></label>
                    </a>	                        
                    <a href="javascript:void(0)" class="RemoveHotelRate" data-type="roomRate" data-id="{{$rate->id}}" title="Remove this hotel rate">
                      <label class="icon-list ic-trash"></label>
                    </a>
                  </td>
                </tr>                    
                @endforeach
              </tbody>
            </table>  
          </form>              
        </section>
      </div>
    </section>
  </div> 
</div>
<script type="text/javascript">
  $(document).ready(function(){
   $(".datatable").DataTable({
          language: {
            searchPlaceholder: "Hotel Name",
          }
        });
  });
</script>

@include('admin.include.datepicker')
@endsection
