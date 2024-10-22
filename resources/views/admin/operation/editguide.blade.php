@extends('layout.backend')
@section('title', 'Guide Assignment')
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
                @if($guide==null)
                <div class="guide" >
                    <div class="modal-dialog modal-lg">
                        <form method="POST" action="{{route('assignGuide')}}">
                            <div class="modal-content">        
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title"><strong id="form_title">Guide Assignment</strong></h4>
                                </div>
                                <div class="modal-body">
                                    {{csrf_field()}}    
                                    <input type="hidden" name="bookid" id="tour_id" value="{{$btour->id}}">
                                    <input type="hidden" name="project_number" id="project_number" value="{{$btour->book_project}}">
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Start Date</label> 
                                                <input type="date" name="start_date" class="form-control book_date" placeholder="Start Date" value="{{$btour->book_checkin}}">	
                                            </div> 
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Country <span style="color:#b12f1f;">*</span></label> 
                                            <select class="form-control country" id="country" name="country" data-type="country" data-locat="guide_data" data-pro_of_bus_id="6" data-title="6" required>
                                                <option value="">--Choose--</option>
                                                @foreach(App\Country::getGuideCon() as $con)
                                                    <option value="{{$con->id}}">{{$con->country_name}}</option>
                                                @endforeach
                                            </select>
                                        </div> 
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                        <div class="form-group">
                                            <label>City Name <span style="color:#b12f1f;">*</span></label> 
                                            <select class="form-control province" name="city" data-type="apply_guide" id="dropdown-country"  required>
                                            <option value="">--choose--</option>			                  
                                            </select>
                                        </div> 
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-xs-6 ">
                                        <div class="form-group">
                                            <label>Service Name</label>
                                            <select class="form-control tran_name" name="tran_name" data-type="apply_language"  id="dropdown-apply_guide">
                                            </select>
                                        </div>
                                        </div>
                                        
                                        <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Language</label>
                                                <select class="form-control language" name="language" id="dropdown-rest_menu" data-type="language-supplier">
                                                    <option>Choose Language</option>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xs-6 ">
                                            <div class="form-group">
                                                <label>Guide Name</label>
                                                <select class="form-control guide_name" name="guide_name" data-type="guide_name" id="dropdown-language-data">
                                                    <option>No Guide</option>			               	
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xs-6">
                                            <div class="form-group">
                                                <label>Telephone</label>
                                                <input type="text" name="phone" id="phone" class="form-control" placeholder="(+855) 123 456 789" >
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
                                    </div>
                                </div>
                                <div class="modal-footer" style="text-align: center !important;">
                                    <button type="submit" class="btn btn-success btn-flat btn-sm" >Publish</button>
                                    <a href="#" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal">Close</a>
                                </div>
                            </div>      
                        </form>
                    </div>
                </div>
                @else
                    <div class="guide">
                        <div class="modal-dialog modal-lg">
                            <form method="POST" action="{{route('assignGuide')}}">
                                <div class="modal-content">        
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title"><strong id="form_title">Guide Assignment</strong></h4>
                                    </div>
                                    <div class="modal-body">
                                        {{csrf_field()}}    
                                        <input type="hidden" name="bookid" id="tour_id" value="{{$guide->book_id}}">
                                        <input type="hidden" name="project_number" id="project_number" value="{{$guide->project_number}}">
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <div class="form-group">
                                                    <label>Start Date <span style="color:#b12f1f;">*</span></label> 
                                                    <input type="date" name="start_date" class="form-control" placeholder="Start Date" value="{{{$guide->start_date or ''}}}">	
                                                </div> 
                                            </div>
                                            <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Country <span style="color:#b12f1f;">*</span></label> 
                                                <select class="form-control country" id="country" name="country" data-locat="guide_data" data-pro_of_bus_id="6" data-title="6" required>
                                                    @if($guide->country_id !== null)
                                                        <option value="{{$guide->country_id}}">   <?php  $country = DB::table('country')->where('id', $guide->country_id)->first();?> {{$country->country_name}}	</option>
                                                            @foreach(App\Country::getGuideCon() as $con)
                                                               <option value="{{$con->id}}">{{$con->country_name}}</option>
                                                        	@endforeach
                                                    @endif
                                                    
                                                </select>
                                            </div> 
                                            </div>
                                            <div class="col-md-6 col-xs-6">
                                            <div class="form-group">
                                                <label>City Name <span style="color:#b12f1f;">*</span></label> 
                                                <select class="form-control province" name="city" data-type="apply_guide" id="city"  required>
                                                @if($guide->province_id !== null)
                                                    <option value="{{$guide->province_id}}">   <?php  $province = DB::table('province')->where('id', $guide->province_id)->first();?> {{$province->province_name}}	</option>
                                                    <?php
                                                    $province=DB::table('province')->where('country_id',$guide->country_id)->get();
                                                    ?>
                                                    @foreach($province as $pro)
                                                        <option value="{{$pro->id}}">{{$pro->province_name}}</option>
                                                    @endforeach
                                                @endif			                  
                                                </select>
                                            </div> 
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-xs-6 ">
                                                <div class="form-group">
                                                    <label>Service Name</label>
                                                    <select class="form-control tran_name" name="tran_name" data-type="apply_language"  id="dropdown-apply_guide">
                                                        @if($guide->service_id !== null)
                                                        <option value="$guide->service_id"> <?php  $service = DB::table('guide_service')->where('id', $guide->service_id)->first();?> {{$service->title or ''}}</option>
                                                        <?php
                                                        $service=DB::table('guide_service')->where('province_id',$guide->province_id)->get();
                                                        ?>
                                                        @foreach($service as $gservice)
                                                            <option value="{{$gservice->id}}">{{$gservice->title}}</option>
                                                        @endforeach
                                                    @endif	
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6 col-xs-6">
                                                <div class="form-group">
                                                    <label>Language</label>
                                                    <select class="form-control language" name="language" id="dropdown-rest_menu" data-type="language-supplier">
                                                    @if($guide->service_id !== null)
                                                        <option value="$guide->language_id"> <?php  $lang = DB::table('guide_language')->where('id', $guide->language_id)->first();?> {{{$lang->name or ''}}}</option>
                                                        <?php
                                                        $lang=DB::table('guide_language')->where('guide_service_id',$guide->service_id)->get();
                                                        ?>
                                                        @foreach($lang as $glang)
                                                            <option value="{{$glang->id}}">{{$glang->name}}</option>
                                                        @endforeach
                                                    @endif	
                                                        
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-xs-12 ">
                                                <strong style="color:red;">To make changes to the Language, you will need to select the Service Name again.</strong>
                                            </div>
                                        
                                            <div class="col-md-3 col-xs-6 ">
                                                <div class="form-group">
                                                    <label>Guide Name <span style="color:#b12f1f;">*</span></label>
                                                    <select class="form-control guide_name" name="guide_name" data-type="guide_name" id="dropdown-language-data" required>
                                                        <option>No Guide</option>			               	
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-xs-6">
                                                <div class="form-group">
                                                    <label>Telephone</label>
                                                    <input type="text" name="phone" id="phone" class="form-control" placeholder="(+855) 123 456 789" >
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
                                        </div>
                                    </div>
                                    <div class="modal-footer" style="text-align: center !important;">
                                        <button type="submit" class="btn btn-success btn-flat btn-sm" >Publish</button>
                                        <a href="#" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal">Close</a>
                                    </div>
                                </div>      
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
            $('#country').change(function() {
                var countryId = $(this).val();

                $.ajax({
                    url: '/cities/' + countryId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        var citySelect = $('#city');
                        citySelect.empty();
                        if (response.length === 0) {
                            citySelect.append('<option value="">No cities available</option>');
                        } else {
                            $.each(response, function(key, value) {
                                citySelect.append('<option value="' + value.id + '">' +
                                    value.province_name + '</option>');
                            });
                        }
                    }
                });
            });
        });
</script>
@include('admin.include.datepicker')
@endsection