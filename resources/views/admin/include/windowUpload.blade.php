<div class="modal" id="myUpload" role="dialog" data-backdrop="static" data-keyboard="false" data-show="true">
	<div class="modal-dialog modal-lg">    
		<div class="modal-content">        
			<div class="modal-header windowbg" style="border-bottom:none;">
			  	<button type="button" class="close" data-dismiss="modal">&times;</button>
			  	<h4 class="modal-title"><strong>Feature Image</strong></h4>
			</div>
			<div class="modal-body" style="padding: 0px 0px;">
			  	<div class="row">
			        <div class="col-md-12">
			        	<div style="width: 73%;">
			    			<div class="pull-right">
					    		<select class="form-control input-sm" id="sort_photoBymonth" style="border-radius:4px 4px 0 0; font-size: 14px; height: 41px;">
							    	<option >No</option>
						    	</select>
				    		</div>
			    		</div>
					  	<ul class="nav nav-tabs windowbg" role="tablist" style="padding-left:9px;">
						    <li role="presentation" class="upload_file"><a href="#upload_file" aria-controls="upload_file" role="tab" data-toggle="tab">Upload Files</a></li>
						    <li role="presentation" class="active media-library"><a href="#media-content" aria-controls="media" role="tab" data-toggle="tab">Media Library</a></li>
						</ul>				
					  	<div class="tab-content" style="min-height:360px;">
						    <div role="tabpanel" class="tab-pane" id="upload_file">
						    	<form id="form_uploadeFile" method="POST" action="{{route('uploadfile')}}" enctype="multipart/form-data">
						    		{{csrf_field()}}
							    	<div class="col-md-12" style="padding:17.95% 43%;">
							    		<div>Upload file type<small>(JPG, JPEG, PNG GIF, PDF)</small></div>
							    		<!-- <input type="text" name="hello"> -->
							    		<input type="file" id="btnUPloadFile" style="display:none;" name="uploadfile[]" multiple>
							    		<span class="btn btn-default" id="btnUPload">Upload file</span>
							    		<br>
							    		<!-- <button type="submit" class="btn btn-success">Upload </button> -->
							    	</div>
							    	<div class="text-center"><span>Maximum upload file size: 64 MB.</span></div>
							    </form>
						    </div>
						    <div role="tabpanel" class="tab-pane active" id="media-content">
						    	<div class="col-md-9" id="loadImage" style="padding: 9px;">
						    		<div id="Loading" style="display:none; position:absolute; margin:27% 30% 41% 46%;">
						                <center><span style="font-size: 25px;" id="placeholder" class="fa fa fa-spinner fa-spin"></span></center>
						            </div> 
						    		<ul class="list-unstyled" style="display:none;"></ul>
							    </div>
							    <div class="col-md-3">
							    	<div class="row">
									  	<div id="preview-item-side">
									  		<div id="img-details" class="text-center" style="display:none;">
										  		<h4 style="margin-top: 0px;"><strong>Image Details</strong></h4>
												<img src="#" style="width:100%; margin: 6px 0px;">	
												<div><label id="image_title">No title...</label></div>
												<hr style="margin-bottom: 8px; margin-top: 0px;">
					 							<span style="margin-bottom: 12px;" class="reMoveImageFileUPloaded btn btn-danger btn-xs" data-url="">Delete File Permanently</span><br>
												<input type="text" name="file" id="url-image" readonly="" class="form-control">
											</div>
									  	</div>
								  	</div>
								</div><div class="clearfix"></div>
						    </div>		
					    </div>
			        </div>		       
			    </div>
			    <div class="clearfix"></div>
			</div>
			<div class="modal-footer windowbg" style="padding: 7px 13px;">
				<div class="seleted-img pull-left" style="margin-bottom: -10px;">
					<span class="pull-left"><span>selected</span><br>
					<a href="#"><span id="clear-gallery" style="color:red;">Clear</span></a> &nbsp;&nbsp;
					</span>
					<ul class="pull-left" id="gallery-image"></ul>
					<div class="clearfix"></div>
				</div>
				<div class="pull-right" style="padding-top:5px;">
			  		<button type="button" id="btnsetfeature" data-type="single-img" disabled="disabled" class="btn btn-success btn-flat btn-sm btnFeatureImageSet" style="display: none;">Set Feature Image</button>
			  		<button type="button" id="btnsetgallery" data-type="multi-img" disabled="disabled" class="btn btn-success btn-flat btn-sm btnFeatureImageSet" style="display: none;">Set Gallery Image</button>          
			  	</div>
			  	<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>