@extends('layout.backend')
@section('title', 'Driver Service')
<?php
  $active = 'restaurant/menu'; 
  $subactive ='transport/driver/add';
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
        <section class="col-lg-12 connectedSortable">
          <h3 class="border">Transports List <span class="fa fa-angle-double-right"></span> <a href="#" data-toggle="modal" data-target="#myModal" class="btn btn-default btn-sm" id="btnAddDriver">Add New Driver</a></h3>
            <form action="" method="">
              <div class="col-sm-2 col-xs-6 pull-right" style="text-align: right; position: relative; z-index: 2;">
                <label class="location">
                  <select class="form-control input-sm locationchange" name="locat">
                    @foreach(\App\Country::where(['country_status'=>1])->whereHas('entrance')->orderBy('country_name')->get() as $loc)
                      <option value="{{$loc->id}}" {{$locat == $loc->id ? 'selected':''}}>{{$loc->country_name}}</option>
                    @endforeach
                  </select>
                </label>
              </div>
            </form>
            <table class="datatable table table-hover table-striped">
              <thead>
                <tr>
                  <th>Driver Name</th>
                  <th>Supplier Name</th>
                  <th>Phone</th>
                  <th>Email</th>
                  <th class="text-center">Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($drivers as $dr)
                  <tr>
                    <td>{{$dr->driver_name}}</td>
                    <td>{{{$dr->supplier->supplier_name or ''}}}</td>
                    <td>{{$dr->phone}}</td>
                    <td>{{$dr->email}}</td>
                    <td class="text-right"> 
                      <a style="position: relative; top: -5px;" href="#" class="tranEditDriver" data-id="{{$dr->id}}" data-toggle="modal" data-target="#myModal">
                        <i style="padding:1px 2px;" class="btn btn-info btn-xs fa fa-pencil-square-o"></i>
                      </a>&nbsp;
                      <a href="javascript:void(0)" class="RemoveHotelRate" data-type="driver" data-id="{{$dr->id}}" title="Remove this menu ?">
                        <label src="#" class="icon-list ic-trash"></label>
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
<div class="modal in" id="myModal" role="dialog"  data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-lg">    
    <form method="POST" action="{{route('addDriver')}}">
      <div class="modal-content">        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Add New Driver</strong></h4>
        </div>
        <div class="modal-body">
          {{csrf_field()}}    
          <input type="hidden" name="eid" id="eid">
          <div class="row">
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>Country <span style="color:#b12f1f;">*</span></label> 
                <select class="form-control country" id="country" name="country" data-type="driver" required>
                  <option value="">--Choose--</option>
                  @foreach(App\Supplier::whereNotIn("country_id", ["Null", 0])->groupBy('country_id')->get() as $con)
                    <option value="{{{$con->country_id or ''}}}">{{{ $con->country->country_name or ''}}}</option>
                  @endforeach
                </select>
              </div> 
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>City Name <span style="color:#b12f1f;">*</span></label> 
                <select class="form-control province" name="city" data-type="transport_service" id="dropdown-driver" data-title="transportation" required>
                  <option value="">--Choose--</option>
                  @foreach(App\TransportService::where("country_id", Auth::user()->country_id)->groupBy("province_id")->get() as $pro)
                    <option value="{{{$pro->province_id or ''}}}">{{{$pro->province->province_name or ''}}}</option>
                  @endforeach
                </select>
              </div> 
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>Transportation</label>
                <select class="form-control supplier" name="transport" id="dropdown-transport_service" required>
                  <option value="">--Choose--</option>
                  @foreach(App\Supplier::where(['supplier_status'=> 1, 'business_id'=>7])->orderBy('supplier_name')->get() as $sup)
                    <option value="{{$sup->id}}">{{$sup->supplier_name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>Driver Name</label>
                <input type="text" name="driver_name" id="driver_name" class="form-control" required="">
              </div>
            </div>
            <div class="col-md-3 col-xs-6">
              <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" id="phone" class="form-control" required="">
              </div>
            </div>
            <div class="col-md-3 col-xs-6">
              <div class="form-group">
                <label>Phone 2</label>
                <input type="text" name="phone2" id="phone2" class="form-control" >
              </div>
            </div>
            <div class="col-md-3 col-xs-6">
              <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" id="email" class="form-control">
              </div>
            </div>
            <div class="col-md-3 col-xs-6">
              <div class="form-group">
                <label>Email 2</label>
                <input type="email" name="email2" id="email2" class="form-control" >
              </div>
            </div>
            <div class="col-md-6 col-xs-12">
              <div class="form-group">
                <div><label>Address</label></div>
                <textarea rows="3" name="address" id="address" class="form-control" placeholder="Address here.."></textarea>
              </div>
            </div>
            <div class="col-md-6 col-xs-12">
              <div class="form-group">
                <div><label>Introduction</label></div>
                <textarea rows="3" name="intro" id="intro" class="form-control" placeholder="Introduction here.."></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="text-align: center;">
          <button type="submit" class="btn btn-success btn-flat btn-sm">Save</button>
          <a href="#" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal">Cancel</a>
        </div>
      </div>      
    </form>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $(".datatable").DataTable();
    $("#btnAddDriver").on("click", function(){
      $("#eid").val("");
    });
  });

</script>
@endsection
