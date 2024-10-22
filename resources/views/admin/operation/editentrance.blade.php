@extends('layout.backend')
@section('title', 'Entrance Assignment')
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
                    <div class="m-5">
                        <div class="modal-dialog modal-lg">
                            <form method="POST" action="{{route('assignEntrance')}}">
                            <div class="modal-content">        
                                <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><strong id="form_title">Entrance Fees Edit Form</strong></h4>
                                </div>
                                <div class="modal-body">
                                {{csrf_field()}}    
                                    <input type="hidden" name="restId" id="tour_id" value="{{$editentrance->id}}">
                                	<input type="hidden" name="project_number" id="project_number" value="{{$editentrance->project_number }}">
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Start Date</label> 
                                                <input type="date" id="start_date" name="start_date" class="form-control book_date" placeholder="Start Date" value="{{$editentrance->start_date}}" >	
                                                <!-- <input type="text" name="star
                                                t_date" class="form-control book_date" placeholder="Start Date" value="{{date('Y-m-d')}}">	 -->
                                            </div> 
                                        </div>	
                                                 
                                        <div class="col-md-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Country <span style="color:#b12f1f;">*</span></label> 
                                            <select class="form-control country" id="country" name="country" data-type="entrance"
                                            data-pro_of_bus_id="entrance_fee_type" data-locat="data" data-title="6" required>
                                                if($editentrance->country_id !== null){
                                                    <option value="{{$editentrance->country_id}}">   <?php  $country = DB::table('country')->where('id', $editentrance->country_id)->first();?> {{$country->country_name}}	</option>
                                                    @foreach(App\Country::getEntranCon() as $con)
                                                        <option value="{{$con->id}}">{{$con->country_name}}</option>
                                                    @endforeach
                                                }
                                                
                                            </select>
                                        </div>  
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                        <div class="form-group">
                                            <label>City Name <span style="color:#b12f1f;">*</span></label>
                                            <select class="form-control city" name="city" data-type="entrance_fee" id="dropdown-entrance" required>
                                            if($editentrance->province_id !== null){
                                                    <option value="{{$editentrance->province_id}}">   <?php  $province = DB::table('province')->where('id', $editentrance->province_id)->first();?> {{$province->province_name}}	</option>
                                                    @foreach(App\Province::getEntranPro($editentrance->country_id) as $pro)
                                                        <option value="{{$pro->id}}">{{$pro->province_name}}</option>
                                                    @endforeach 
                                                }
                                           
                                            
                                            </select>
                                        </div> 
                                        </div>
                                
                                        <div class="col-md-6">
                                        <div class="form-group">
                                                <label>Entrance Fees </label>
                                                <select class="form-control rest_menu" name="rest_menu" id="dropdown-entrance_fee">
                                                    <option value="{{$editentrance->service_id}}">Select Entrance</option>
                                                    @foreach(App\Entrance::where('status', 1)->orderBy('name', 'ASC')->get() as $rm)
                                                    <option value="{{$rm->id}}" data-price="{{$rm->price}}" data-kprice="{{$rm->kprice}}">{{$rm->name}}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>    

                                        <!-- <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Transportation<span style="color:#b12f1f;">*</span></label>
                                                <select class="form-control transportation" id="dropdown-transport_service" name="transportation"></select>
                                            </div>
                                        </div>       -->
                                        <div class="col-md-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Pax No.</label>
                                            <input type="number" name="pax" id="pax" class="form-control text-center pax">
                                        </div>
                                        </div>
                                        <div class="col-md-3 col-xs-6">
                                        <div class="form-group">
                                            <label>Price {{Content::currency()}}</label>
                                            <input type="text" name="price" id="price" class="form-control editprice" placeholder="00.0" readonly>
                                        </div>
                                        </div>
                                        <div class="col-md-3 col-xs-6">
                                        <div class="form-group">
                                            <label>Price {{Content::currency(1)}}</label>
                                            <input type="text" name="kprice" id="kprice" class="form-control editprice" placeholder="00.0" readonly>
                                        </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Remark</label>
                                            <textarea class="form-control remark" id="remark" name="remark" rows="5" placeholder="Remark..."></textarea>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer" style="text-align: center;">
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