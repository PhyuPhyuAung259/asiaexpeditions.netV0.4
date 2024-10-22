@extends('layout.backend')
@section('title', 'Restaurant Menu')
<?php 
  $active = 'restaurant/menu';
  $subactive ='restaurant/menu';
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
          <form method="POST" action="{{route('AddRestMenu')}}">
              <div class="col-lg-12"> 
                <div class="border form-group">
                  <div class="col-md-2">
                    <div class="row">
                      <strong style="font-size: 23px;">Restaurant Name</strong>
                    </div>
                  </div>  
                  <div class="col-md-2 col-xs-6">                  
                    <select class="form-control country" id="country" name="country" data-type="country"  data-bus_type="2" data-locat="data" data-title="2" required>
                      <?php $getRest = App\Country::where(['country_status'=>1])->whereHas('supplier', 
                            function($query) { $query->where(['supplier_status'=>1, 'business_id'=> 2]);})->orderBy('country_name')->get(); ?>
                      <option>--Choose--</option>
                      @foreach($getRest as $con)
                        <option value="{{$con->id}}" {{$con->id == Auth::user()->country_id ? 'selected':''}}>{{$con->country_name}}</option>
                      @endforeach
                    </select>
                  </div>   
                  <div class="col-md-2 col-xs-6">                  
                    <select class="form-control province" id="dropdown-country" name="province" data-type="booking_restaurant" data-title="2" required>
                    </select>
                  </div>                      
                  <div class="col-md-3 col-xs-6">                  
                    <select class="form-control" name="rest_name" id="restaurant" required>  
                    <!--I changed id= dropdown_booking_restaurant to restaurant and add js code-->
                    </select>
                  </div><div class="clearfix"></div>
                </div>
              </div>
              {{csrf_field()}}
              <section class="col-lg-9">                              
                <table class="table table-hover table-striped" id="restaurant">
                  <thead>
                    <tr>                     
                      <th>Menu Name</th>
                      <th class="text-center">Price {{Content::currency()}}</th>
                      <th class="text-center">Price {{Content::currency(1)}}</th>
                      <th class="text-center">action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="width: 60%;">
                        <input type="text" name="menu_name[]" class="form-control input-sm" placeholder="Menu Name" required="">
                      </td> 
                      <td>
                        <input type="text" name="price[]" class="number_only form-control text-center input-sm" placeholder="00.0" required="">
                      </td> 
                      <td>
                        <input type="text" name="kprice[]" class="number_only form-control text-center input-sm" placeholder="00.0">
                      </td> 
                      <td><span class="btn btn-info btn-xs addMenu" ><i class="fa fa-plus-circle"></i> Add new</span></td>
                    </tr>               
                  </tbody>
                </table>  
              </section>
              <section class="col-lg-3 ">
                <div class="panel panel-default">
                  <div class="panel-body">
                    <button type="submit" class="btn btn-success btn-flat btn-sm">Confirm</button>
                  </div>
                </div>
              </section>
          </form>
        </div>
      </section>
    </div>  
  </div>

  <script type="text/javascript">
    $(".addMenu").on("click", function(){
        $("table#restaurant tbody tr:last").after('<tr><td style="width: 60%;"><input type="text" name="menu_name[]" class="form-control input-sm" placeholder="Menu Name" required></td> <td><input type="text" name="price[]" class="number_only form-control text-center input-sm" placeholder="00.0" required> </td>  <td> <input type="text" name="kprice[]" class="number_only form-control text-center input-sm" placeholder="00.0"></td> <td><span class="btn btn-danger btn-xs RemoveRest" data-type="hotelrate"><i class="fa fa-minus-square"></i> Remove</span></td></tr>');
    });
    $(document).on('click','.RemoveRest', function(){
      $(this).closest("tr").remove();
    });
     $('#dropdown-country').change(function() {
       
        var cityId = $(this).val();
           $.ajax({
            url: '/restaurants/' + cityId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var restaurantSelect = $('#restaurant');
                restaurantSelect.empty();
                if (response.length === 0) {
                  restaurantSelect.append('<option value="0">No restaurants available</option>');
                } else {
                  restaurantSelect.append('<option value="0">Choose restaurant</option>');
                    $.each(response, function(key, value) {
                      restaurantSelect.append('<option value="' + value.id + '">' +
                            value.supplier_name + '</option>');
                    });
                } 
            }
          }); 
      });
  </script>

@endsection
