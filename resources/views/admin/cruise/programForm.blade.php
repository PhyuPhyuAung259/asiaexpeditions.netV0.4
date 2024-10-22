@extends('layout.backend')
@section('title', 'Cruise Program')
<?php $active = 'supplier/cruises'; 
  $subactive ='program';
  use App\component\Content;
  $countryId = Auth::user()->country_id ? Auth::user()->country_id : 0;
?>
@section('content')
  <div class="wrapper">
    @include('admin.include.header')
    @include('admin.include.menuleft')
    <div class="content-wrapper">
      <section class="content"> 
        <div class="row">
          @include('admin.include.message')
          <div class="col-lg-12"><h3 class="border">River Cruise Program Management</h3></div>
          <form method="POST" action="{{route('getCrProgram')}}">
              {{csrf_field()}}
              <section class="col-lg-9 connectedSortable">
                <div class="card">                                
                  <div class="row">
                    <div class="col-md-6 col-xs-12">
                      <div class="form-group">
                        <label>Cruise name<span style="color:#b12f1f;">*</span></label> 
                        <select class="form-control" name="cruis_name" required>
                          @foreach(App\Supplier::where('business_id', 3)->get() as $cruis)
                          <option value="{{$cruis->id}}" {{$cruis->id == $supplier->id? 'selected':''}}>{{$cruis->supplier_name}}</option>
                          @endforeach
                        </select>
                      </div> 
                    </div>
                    <div class="col-md-6 col-xs-12">
                      <div class="form-group">
                        <label>Program Name <span style="color:#b12f1f;">*</span></label> 
                        <input type="text" name="program_name" class="form-control" placeholder="Program Name" required>
                      </div> 
                    </div>
                  </div>  
                  <div class="form-group">
                    <label>Hightlights</label>
                    <script src="{{asset('adminlte/editor/tinymce.min.js')}}"></script>
                    <textarea class="form-control" name="program_remark" rows="6" placeholder="Enter ...">{!! old('tour_remark') !!}</textarea>             
                  </div>
                  <div class="form-group">
                    <label>Remarks</label>
                    <textarea class="form-control" name="program_hight" rows="6" placeholder="Enter ...">{!! old('tour_hight') !!}</textarea>             
                  </div>
                  <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control my-editor" name="program_desc" rows="6" placeholder="Enter ...">{!! old('tour_desc') !!}</textarea>             
                  </div>
                  <div class="form-group">
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Status</label>&nbsp;
                        <label style="font-weight:400;"> <input type="radio" name="status" value="1" checked="">Publish</label>&nbsp;&nbsp;
                        <label style="font-weight: 400;"> <input type="radio" name="status" value="0">UnPublish</label>
                      </div> 
                    </div>
                  </div>             
                </div>
              </section>
              <section class="col-lg-3 connectedSortable">
                <div class="panel panel-default">
                  <div class="panel-heading">Applied Type of Cabin</div>
                  <div class="panel-body addscrolling">
                    @foreach(App\CrCabin::where('status', 1)->orderBy('name', 'ASC')->get() as $key=>$cabin)
                      <div>
                        <span style="position:relative;top:3px;padding-right:3px;">
                          <i data-id="{{$key}}" class="fa fa-square-o nocheck"></i>
                          <input style="opacity: 0;" class="choose-option" type="checkbox" name="crcabin[]" value="{{$cabin->id}}" id="{{$key}}">
                        </span>
                        <label style="font-weight: 400;" for="{{$key}}">{{$cabin->name}}</label>
                      </div>
                    @endforeach
                  </div>                  
                </div>
                <div class="panel panel-default">
                  <div class="panel-heading">Destination City Name</div>
                  <div class="panel-body">               
                    <select class="form-control " name="program_dest">
                      @foreach(App\Province::where(['country_id'=>$supplier->country_id, 'province_status'=> 1])->orderBy('province_name', 'ASC')->get() as $key => $pro)
                        <option value="{{$pro->id}}">{{$pro->province_name}}</option>
                      @endforeach
                    </select>
                  </div>                  
                </div>
                <div class="form-group"> 
                  <button type="submit" class="btn btn-success btn-flat btn-sm">Submit</button>&nbsp;&nbsp;
                  <a href="{{route('tourList')}}" class="btn btn-danger btn-flat btn-sm">Cancel</a>               
                </div>
              </section>
          </form>
        </div>
      </section>
    </div>  
  </div>
  @include('admin.include.windowUpload')
  @include('admin.include.editor')
@endsection
