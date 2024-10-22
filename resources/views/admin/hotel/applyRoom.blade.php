@extends('layout.backend')
@section('title', 'Apply Price For'. $subhotel->supplier_name)
<?php $active = 'supplier/hotels'; 
  $subactive ='hotel/hotelroom';
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
                <h3 class="border">Assign Room Type for <b>{{$subhotel->supplier_name}} Hotel</b></h3>
                <form method="POST" action="{{route('getRoomApplyNow')}}">
                  {{csrf_field()}}
                  <table class="table table-hover table-striped" id="applyroom">
                    <thead>
                      <tr>                     
                        <th width="100">Hotel Name</th>
                        <th colspan="4">
                          <select class="form-control filterHotel" name="hotel">
                            @foreach($hotels as $hotel)
                            <option value="{{$hotel->id}}" {{$hotelId == $hotel->id ? 'selected':''}}>{{$hotel->supplier_name}}</option>      
                            @endforeach                  
                          </select>
                        </th>
                        <th width="100" class="text-center">
                          <input type="submit" name="btnApply" value="Apply Now" class="btn btn-success btn-flat btn-sm">
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($roomApply->chunk(4) as $roomchunk)
                        <tr>
                          <td></td>
                          @foreach($roomchunk as $room)
                            <td>
                              <label class="container-CheckBox" style="margin-bottom: 0px;">{{$room->name}}
                                <input type="checkbox" id="check_all" name="roomId[]" value="{{$room->id}}" {{in_array($room->id, explode(',', $roomId)) ? 'checked':''}}  >
                                <span class="checkmark hidden-print" ></span>
                              </label>
                            </td>
                          @endforeach
                          <td></td>
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
@endsection
