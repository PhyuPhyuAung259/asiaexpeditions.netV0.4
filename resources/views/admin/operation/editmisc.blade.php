@extends('layout.backend')
@section('title', 'Transport Assignment')
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
                <div class="misc">
                    <div class="modal-dialog modal-lg">
                        <form method="POST" action="{{route('assignMisc')}}">
                            <div class="modal-content">        
                                <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><strong> Miscellaneouse Assignment</strong></h4>
                                </div>
                                <div class="modal-body">
                                {{csrf_field()}}    
                                    <input type="hidden" name="bookid" id="tour_id" value="{{$bmisc->id}}">
                                    <input type="hidden" name="project_number" id="project_number" value="{{$bmisc->book_project}}">
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Start Date</label> 
                                                <input type="date" name="start_date" class="form-control book_date" placeholder="Start Date" value="{{$bmisc->book_checkin}}">	
                                            </div> 
                                        </div>	
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Country <span style="color:#b12f1f;">*</span></label> 
                                                <select class="form-control country" id="country" name="country" data-type="country" data-pro_of_bus_id="misc_type" data-locat="data" data-title="6" required>
                                                @if($bmisc->country_id !== null)
													<option value="{{$bmisc->country_id}}">   <?php  $country = DB::table('country')->where('id', $bmisc->country_id)->first();?> {{$country->country_name or ''}}	</option>
														@foreach(App\Country::Where('country_status',1)->wherehas('transportservice')->orderBy('country_name')->get() as $con)
															<option value="{{$con->id}}">{{$con->country_name}}</option>
														@endforeach
												@endif
                                                </select>
                                            </div> 
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>City Name <span style="color:#b12f1f;">*</span></label> 
                                                <select class="form-control province" name="city" data-type="apply_misc" id="dropdown-country"  data-title="Miscellaneouse" required>
                                                <option value="">--Choose--</option>
                                                @foreach(App\Province::where(['province_status'=> 1])->orderBy('province_name')->get() as $pro)
                                                    <option value="{{$pro->id}}">{{$pro->province_name}}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Service Type</label>
                                                <select class="form-control tran_name service_type" name="service_type" id="dropdown-apply_misc">
                                                    <option>Select Service</option>
                                                    @foreach(App\MISCService::where(['status'=> 1])->orderBy('name', 'ASC')->get() as $sv)
                                                    <option value="{{$sv->id}}" data-price="{{$sv->price}}" data-kprice="{{ $sv->kprice }}">{{$sv->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Pax No.</label>
                                                <input type="number" name="book_pax" id="book_pax" class="form-control text-center" value="1">
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12 ">
                                            <strong style="color:red;">To make changes to the Service Type, you will need to select the City Name again.</strong>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Price {{Content::currency()}}</label>
                                                <input type="text" name="price" id="price" class="form-control" placeholder="00.0" >
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Price {{Content::currency(1)}}</label>
                                                <input type="text" name="kprice" id="kprice" class="form-control" placeholder="00.0" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Remark</label>
                                                <textarea class="form-control" name="remark" id="remark" rows="5" placeholder="Remark here..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer" style="text-align: center;">
                                    <button type="submit" class="btn btn-success btn-flat btn-sm">Publish</button>
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