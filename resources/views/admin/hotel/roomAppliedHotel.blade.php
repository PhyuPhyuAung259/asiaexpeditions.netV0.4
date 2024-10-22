@extends('layout.backend')
@section('title', 'Hotel & Room Rate')
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
          <h3 class="border"><span style="color: #f39c12; font-size: 22px;" class="fa fa-hotel (alias)"></span> Hotel Room</h3>
            <form action="" method="">
              <div class="col-sm-2 pull-right" style="text-align: right;">
                <label class="location">
                  <select class="form-control input-sm locationchange" name="location">
                    @foreach(\App\Country::where('country_status',1)->whereHas('supplier', function($query) {
                        $query->where(['supplier_status'=>1, 'business_id'=>1]);
                      })->orderBy('country_name')->get() as $loc)
                      <option value="{{$loc->id}}" {{$locationid == $loc->id ? 'selected':''}}>{{$loc->country_name}}</option>
                    @endforeach
                  </select>
                </label>
              </div>
            </form>                  
            <table class="datatable table table-hover table-striped">
              <thead>
                <tr>                     
                  <th>Hotel</th>
                  <th>RoomType</th>
                  <th width="100" class="text-center">Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($roomHotel as $app)
                <tr>
                  <td>{{$app->supplier_name}}</td>      
                  <td>{{$app->name}}</td>        					                  
      						<td class="text-center"> 
                    <a href="{{route('getHotelRate', ['hotelid'=>$app->supplier_id, 'roomId' => $app->room_id])}}" title="Add hotel Rate">
                      <label class="icon-list ic_book_add"></label>
                    </a>
                    <a href="{{route('getEditHotelRate', ['hotelid'=>$app->supplier_id, 'roomId' => $app->room_id])}}" title="Edit hotel rate">
                      <label class="icon-list ic_edit_hprice"></label>
                    </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>                
        </section>
      </div>
    </section>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
     $(".datatable").DataTable();
  });
</script>
@endsection
