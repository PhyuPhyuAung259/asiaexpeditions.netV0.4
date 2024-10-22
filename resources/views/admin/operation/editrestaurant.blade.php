@extends('layout.backend')
@section('title', 'Restaurant Assignment')
<?php
  $active = 'restaurant/menu'; 
  $subactive = 'entrance/service';
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
                <div class="" >
                    <div class="modal-dialog modal-lg">
                        <form method="POST" action="{{route('assignRestuarant')}}">
                            <div class="modal-content">        
                                <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><strong id="form_title">Restaurant Booking Edit For Project Number: {{$editrestaurant->project_number}}</strong></h4>
                                </div>
                                <div class="modal-body">
                                {{csrf_field()}}    
                                    <input type="hidden" name="restId" id="tour_id" value="{{$editrestaurant->id}}">
                                    <input type="hidden" name="bookid" value="{{{$booking->id or ''}}}">
                                    <input type="hidden" name="project_number" id="project_number" value="{{$editrestaurant->project_number}}">
                                    <div class="row">
                                        <div class="col-md-3 col-xs-6">
                                            <div class="form-group">
                                                <label>Start Date</label> 
                                                <input type="date" name="start_date" class="form-control book_date" placeholder="Start Date" value="{{$editrestaurant->start_date}}" >	
                                            </div> 
                                        </div>
                                        <div class="col-md-3 col-xs-6">
                                            <div class="form-group">
                                                <label>Booking Date</label> 
                                                <input type="date" name="book_date" class="form-control book_date" placeholder="Booking Date" value="{{$editrestaurant->book_date}}" >
                                            </div> 
                                        </div>
                                        <div class="col-md-3 col-xs-6">
                                        <div class="form-group">
                                            <label>Country <span style="color:#b12f1f;">*</span></label> 
                                            <select class="form-control country" id="country" name="country" data-type="country"  data-bus_type="2" data-locat="data" data-title="2" required>
                                                @if($editrestaurant->country_id !== null)
                                                        <option value="{{$editrestaurant->country_id}}">   <?php  $country = DB::table('country')->where('id', $editrestaurant->country_id)->first();?> {{$country->country_name}}	</option>
                                                            @foreach(App\Country::getRestCon() as $con)
                                                            <option value="{{$con->id}}">{{$con->country_name}}</option>
                                                        @endforeach
                                                    @endif
                                            </select>
                                        </div> 
                                        </div>
                                        <div class="col-md-3 col-xs-6">
                                        <div class="form-group">
                                            <label>City Name <span style="color:#b12f1f;">*</span></label> 
                                            <select class="form-control province" name="city" data-type="booking_restaurant" id="dropdown-country" required>
                                                @if($editrestaurant->province_id !== null)
                                                    <option value="{{$editrestaurant->province_id}}">   <?php  $province = DB::table('province')->where('id', $editrestaurant->province_id)->first();?> {{$province->province_name}}	</option>
                                                    @foreach(App\Province::getRestPro($editrestaurant->country_id) as $pro)
                                                        <option value="{{$pro->id}}">{{$pro->province_name}}</option>
                                                    @endforeach
                                                @endif
                                                
                                            </select>
                                        </div> 
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Restaurant Name</label>
                                            <select class="form-control rest_name" name="rest_name" data-type="booking_restaurant_menu" id="dropdown-booking_restaurant">
                                                @if($editrestaurant->supplier_id !==null)
                                                    <option value="{{$editrestaurant->supplier_id}}"> <?php  $supplier = DB::table('suppliers')->where('id', $editrestaurant->supplier_id)->first();?> {{$supplier->supplier_name}}	</option>
                                                    <?php 
                                                    $supplier=DB::table('suppliers')
                                                                ->where('province_id',$editrestaurant->province_id)
                                                                ->where('business_id',2)
                                                                ->where('supplier_status',1)
                                                                ->get();
                                                    ?>
                                                    @foreach($supplier as $restname)
                                                        <option value="{{$restname->id}}">{{$restname->supplier_name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label>Menu </label>
                                               
                                                @if($editrestaurant->supplier_id !==null)
                                                <select class="form-control rest_menu" name="rest_menu" id="dropdown-booking_restaurant_menu">
                                                    <option value="{{$editrestaurant->menu_id}}"> <?php  $menu = DB::table('restaurant_menu')->where('id', $editrestaurant->menu_id)->first();?> {{{ $menu->title or '' }}}	</option>
                                                    <?php 
                                                    $menu=DB::table('restaurant_menu')
                                                                ->where('supplier_id',$editrestaurant->supplier_id)
                                                                ->get();
                                                    ?>
                                                    @foreach($menu as $restmenu)
                                                        <option value="{{$restmenu->id}}">{{$restmenu->title}}</option>
                                                    @endforeach
                                                </select>
                                                @endif
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                        <strong style="color:red;">To make changes to the restaurant_menu, you will need to select the restaurant again.</strong>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Pax No.</label>
                                            <input type="number" name="pax" id="pax" class="form-control text-center" value="{{$editrestaurant->book_pax}}">
                                        </div>
                                        </div>
                                        <div class="col-md-3 col-xs-3">
                                        <div class="form-group">
                                            <label>Price {{Content::currency()}}</label>
                                            <input type="text" name="price" id="price" class="form-control editprice" placeholder="00.0"  readonly>
                                        </div>
                                        </div>
                                        <div class="col-md-3 col-xs-3">
                                        <div class="form-group">
                                            <label>Price {{Content::currency(1)}}</label>
                                            <input type="text" name="kprice" id="kprice" class="form-control editprice" placeholder="00.0" value="{{$editrestaurant->kprice}}" readonly>
                                        </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Remark</label>
                                            <textarea class="form-control" id="remark" name="remark" rows="5" placeholder="Remark..."></textarea>
                                        </div>
                                        </div>
                                         
                                        
                                    </div>
                                </div>
                                <div class="modal-footer" >
                                <button type="submit" class="btn btn-success btn-flat btn-sm" >Publish</button>
                                <a href="#" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal">Cancel</a>
                                </div>
                            </div>      
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@include('admin.include.datepicker')
@endsection