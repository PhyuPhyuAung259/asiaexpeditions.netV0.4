@extends('layout.backend')
@section('title', $supplier->supplier_name)
<?php
  $active = 'hotel';
  $subactive ='supplier/add/new';
  use App\component\Content;
  $countryId = $supplier->country_id != "" ? $supplier->country_id : Auth::user()->country_id;
?>
@section('content')
  <div class="wrapper">
    @include('admin.include.header')
    @include('admin.include.menuleft')
    <div class="content-wrapper">
      <section class="content"> 
        <div class="row">
          @include('admin.include.message')
          <div class="col-lg-12"><h3 class="border">Update Hotel Management</h3></div>
          <form method="POST" action="{{route('updateHotelInfo')}}" enctype="multipart/form-data"> 
            {{csrf_field()}}
            <input type="hidden" name="eid" value="{{$supplier->id}}">
            <section class="col-lg-9 connectedSortable">
              <div class="card">                                
                <div class="row">
                  <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                      <label>Supplier name<span style="color:#b12f1f;">*</span></label> 
                      <input autofocus type="text" placeholder="Tour Name" class="form-control" name="title"  value="{{$supplier->supplier_name}}" readonly="">
                    </div> 
                  </div>   
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Contact Name<span style="color:#b12f1f;">*</span></label>
                      <input type="text" value="{{$supplier->supplier_contact_name}}" name="contact_name" class="form-control" placeholder="Contact Person" readonly="">
                    </div>
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Phone 1<span style="color:#b12f1f;">*</span></label> 
                      <input type="text" value="{{$supplier->supplier_phone}}" name="phone_one" class="form-control" placeholder="Ex:+855 123 456 789" readonly="" >
                    </div>
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Phone 2</label>
                      <input type="text" value="{{$supplier->supplier_phone2}}" name="phone_two" class="form-control" placeholder="Ex:+855 123 456 789" readonly="">
                    </div>
                  </div>                  
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Email Address 1 <span style="color:#b12f1f;">*</span></label>
                      <input type="eamil" value="{{$supplier->supplier_email}}" name="email_one" class="form-control" placeholder="example@gmail.com" readonly="">
                    </div>
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Email Address 2</label>
                      <input type="email" value="{{$supplier->supplier_email2}}" name="email_two" class="form-control" placeholder="example@gmail.com" readonly="">
                    </div>
                  </div>
                  <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                      <label>Website</label>
                      <input type="text" value="{{$supplier->supplier_website}}" name="website" class="form-control" placeholder="{{config('app.url_add')}}" readonly="">
                    </div>
                  </div>
                </div>  
                <div class="form-group">
                  <label>Terms & Conditions</label>
                  <script src="{{asset('adminlte/editor/tinymce.min.js')}}"></script>
                  <textarea class="form-control my-editor" name="term_condition" rows="6" placeholder="Enter ...">{!! old('term_condition', $supplier->supplier_term_condition) !!}</textarea>
                </div>
                <div class="form-group">
                  <label>Group Policy</label>
                  
                  <textarea class="form-control my-editor" name="pgroup" rows="6" placeholder="Enter ...">{!! old('pgroup', $supplier->supplier_pgroup) !!}</textarea>
                </div>
                <div class="form-group">
                  <label>Child Policy</label>
                  <textarea class="form-control my-editor" name="pchild" rows="6" placeholder="Enter ...">{!! old('pchild', $supplier->supplier_pchild) !!}</textarea>
                </div>
                <div class="form-group">
                  <label>Cancellation Policy</label>
                  <textarea class="form-control my-editor" name="pcancelation" rows="6" placeholder="Enter ...">{!! old('pcontract', $supplier->supplier_pcancelation) !!}</textarea>
                </div>
                <div class="form-group">
                  <label>Payment Policy</label>
                  <textarea class="form-control my-editor" name="ppayment" rows="6" placeholder="Enter ...">{!! old('ppayment', $supplier->supplier_ppayment) !!}</textarea>
                </div>

                <div class="form-group">
                  <label>Bank Information</label>
                  <textarea class="form-control my-editor" name="supplier_bank_info" rows="6" placeholder="Enter ...">{!! old('supplier_bank_info', $supplier->supplier_bank_info) !!}</textarea>
                </div>
              </div>
            </section>
            <section class="col-lg-3 connectedSortable"><br>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <button type="submit" class="btn btn-success btn-flat btn-sm">Update</button>&nbsp;&nbsp;
                </div>
              </div>

              <div class="panel panel-default">
                <div class="panel-heading"><strong>Hotel Contract</strong></div>
                <div class="panel-body">
                  <div class="form-group">
                    <label><a href="#" data-toggle="modal" data-target="#uploadPDF"><i class="fa fa-folder" style="font-size: 15px;"></i> Upload PDF Files</a></label> 
                  </div>
                </div>
              </div>
              <?php 
                $hotelFacilities = App\HotelFacitily::where('status', 1)->orderBy('name', 'ASC')->get();
              ?>
              @if($hotelFacilities->count() > 0 )
                <div class="panel panel-default">
                  <div class="panel-heading"><strong>Hotel Facility</strong></div>
                  <div class="panel-body scrolTourFeasility" style="padding: 8px;">
                    <ul class="list-unstyled">
                      @foreach($hotelFacilities as $sv)
                        <li>
                          <div class="checkMebox">
                            <label>
                              <span style="position: relative;top: 4px;"> 
                                <i class="fa {{in_array($sv->id, explode(',', $hotelFacility)) ? ' fa-check-square-o':'fa-square-o'}}"></i>
                                <input type="checkbox" name="hotel_facility[]" value="{{$sv->id}}" {{in_array($sv->id, explode(',', $hotelFacility)) ? 'checked':''}}>&nbsp;
                              </span>
                              <span>{{$sv->name}}</span>
                            </label>
                          </div>
                        </li>
                      @endforeach
                    </ul>
                  </div>    
                  <div class="panel-footer"></div>          
                </div>  
              @endif          
             <?php 
                $hotelinfo = App\HotelCategory::where('status', 1)->orderBy('name', 'ASC')->get(); 
             ?>
              @if($hotelinfo->count() > 0)
                <div class="panel panel-default">
                  <div class="panel-heading"><strong>Hotel Info</strong></div>
                  <div class="panel-body scrolTourFeasility" style="padding: 8px;">
                    <ul class="list-unstyled">
                      @foreach($hotelinfo as $sv)
                        <li>
                          <div class="checkMebox">
                            <label>
                              <span style="position: relative;top: 4px;"> 
                                <i class="fa {{in_array($sv->id, explode(',', $hotelCategory)) ? ' fa-check-square-o':'fa-square-o'}}"></i>
                                <input type="checkbox" name="hotel_category[]" value="{{$sv->id}}" {{in_array($sv->id, explode(',', $hotelCategory)) ? 'checked':''}}>&nbsp;
                              </span>
                              <span>{{$sv->name}}</span>
                            </label>
                          </div>
                        </li>
                      @endforeach
                    </ul>
                  </div>
                  <div class="panel-footer"></div>
                </div>
              @endif

            </section>
          </form>
        </div>
      </section>
    </div>  
  </div>
  <div class="modal" id="uploadPDF" role="dialog"  data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-md">    
    <form id="AddProjectPDF" method="POST" action="{{route('addProjectPdF')}}" enctype="multipart/form-data">
      <div class="modal-content">        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Upload Program For {{$supplier->supplier_name}}</strong></h4>
        </div>
        <div class="modal-body">
          {{csrf_field()}}    
            <input type="hidden" name="supplier_id_pdf" value="{{$supplier->id}}">       
            <input type="hidden" name="pdf_type" value="hotel_contract_pdf">       
            <div class="form-group">
              <input type="file" name="project_pdf[]" multiple>
            </div>
            <?php $getClient = \App\Admin\Photo::where(['supplier_id'=> $supplier->id])->get(); ?>
            @if($getClient->count() > 0)
              <table class="table table_row">
                <tr>
                  <th style="border:none;">Title</th>
                  <th style="border:none;">By User</th>
                  <th style="border:none;" width="8px">Action</th>
                </tr>
                <tbody style="border-top: none;">
                    @foreach($getClient as $key => $cl)
                      <tr class="clonrow">
                        <td> <a target="_blank" href="{{asset('storage/contract/projects')}}/{{$cl->name}}">{{$cl->original_name}} </td>
                        <td>{{{ $cl->user->fullname or ''}}}</td>
                        <td>
                          <span style="cursor:pointer;" class="RemoveHotelRate" data-type="project_pdf" data-id="{{$cl->id}}" title="Delete this ?"><i style="color:#913412;font-size:14px;" class="fa fa-minus-circle"></i></span></td>
                      </tr>
                    @endforeach
                </tbody>
              </table>
            @endif
        </div>
        <div class="modal-footer" style="text-align: center;">
          <button type="submit" class="btn btn-success btn-sm">Save</button>
          <span class="btn btn-default btn-flat btn-sm btn-acc" data-dismiss="modal">Close</span>
        </div>
      </div>      
    </form>
  </div>
</div>
  @include('admin.include.editor')
@endsection
