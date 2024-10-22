@extends('layout.backend')
@section('title', 'Add New Slide')

<?php use App\component\Content;
  $active = 'setting-options'; 
  $subactive ='slide/add';
  ?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
       <div class="row">    		
	    	<form method="POST" action="{{route('slideStore')}}" enctype="multipart/form-data">
		    	{{ csrf_field() }}
				<section class="col-md-8 connectedSortable">
					<div class="panel"> 
					    <h3>Title</h3>			  
						<div class="row">						    
						    <div class="box-body">
						      	<div class="col-md-12 col-md-12">
			                    	<div class="form-group">
			                            <input type="text" name="title" class="form-control input-md" placeholder="Title" required>
			                        </div>		            
			                        <div class="form-group row">
			                        	<div class="col-md-6 col-xs-6">
					                      <div class="form-group">
					                        <label>Country <span style="color:#b12f1f;">*</span></label> 
					                        <select class="form-control country" name="country" data-type="country" data-method="tour_accommodation" required="">
					                          @foreach(App\Country::where('country_status', 1)->orderBy('country_name')->get() as $con)
					                            <option value="{{$con->id}}" >{{$con->country_name}}</option>
					                          @endforeach
					                        </select>
					                      </div> 
					                    </div> 
					                    <div class="col-md-6 col-xs-6">
					                      <div class="form-group">
					                        <label>City <span style="color:#b12f1f;">*</span></label> 
					                        <select class="form-control" name="city" id="dropdown-data" >
					                          @foreach(App\Province::where('province_status',1)->orderBy('province_name')->get() as $pro)
					                            <option value="{{$pro->id}}">{{$pro->province_name}}</option>
					                          @endforeach
					                        </select>
					                      </div> 
					                    </div>                    	
			                        </div>
									
			                      	<div class="form-group row">
		                            	<div class="col-md-12">
		                            		<label for="desc">Descriptions</label>
		                            		<script src="{{asset('adminlte/editor/tinymce.min.js')}}"></script>
											<textarea name="desc" class="form-control my-editor" placeholder="Say Something"></textarea>
							            </div>
			                        </div>	
			                        <div class="form-group">
					                    <div class="col-md-2 col-xs-6">
					                      <div class="form-group">
					                        <label>Website</label>&nbsp;
					                        <label style="font-weight: 400;"> <input type="radio" name="web" value="1" checked="">Yes</label>&nbsp;&nbsp;
					                        <label style="font-weight: 400;"> <input type="radio" name="web" value="0">No</label>
					                      </div> 
					                    </div>
					                    <div class="col-md-3 col-xs-6">
					                      <div class="form-group">
					                        <label>Status</label>&nbsp;
					                        <label style="font-weight:400;"> <input type="radio" name="status" value="1" checked="">Publish</label>&nbsp;&nbsp;
					                        <label style="font-weight: 400;"> <input type="radio" name="status" value="0">UnPublish</label>
					                      </div> 
					                    </div>
					                  </div>   		                    
			                        <hr class="colorgraph">
				                </div>        
						  	</div>
					  	</div>				  
				  	</div>
				</section>
			
				<section class="col-lg-3 connectedSortable">
	                <div class="panel panel-default">
	                  <div class="panel-body">
	                    <div id="wrap-feature-image" style="position:relative;">

	 
	                  
	                      	<img id="feature-img" src="#" style="width:100%;display:none;margin-bottom:12px;" class="btnUploadFiles" data-type="single-img" data-toggle="modal" data-target="#myUpload">

	                     
	                      <i id="removeImage" class="fa fa-remove (alias)" title="Remove picture" style="display: none;"></i>
	                    </div>
	                    <a href="#uploadfile" class="btnUploadFiles" data-type="single-img" data-toggle="modal" data-target="#myUpload">Set Feature Image</a>
	                  </div>
	                  <div class="panel-footer">Supplier Logo</div>
	                </div>
	                

	                <div class="form-group"> 
	                  <button type="submit" class="btn btn-success btn-flat btn-sm">Submit</button>&nbsp;&nbsp;
	                </div>
                
              	</section>
				<div class="clear"></div>  
			</form>
		</div>
    </section>
   </div>
</div>
@include('admin.include.editor')

@include('admin.include.windowUpload')
@endsection

