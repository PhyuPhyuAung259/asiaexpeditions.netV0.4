@extends('layout.backend')
@section('title', 'MISC Services')
<?php
  $active = 'restaurant/menu'; 
  $subactive ='misc/service';
  use App\component\Content;
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
      <div class="row">
        <section class="col-lg-12 connectedSortable">
          <h3 class="border">MISC Service List <span class="fa fa-angle-double-right"></span> <a href="#" class="btn btn-default btn-sm" id="addMiscService" data-toggle="modal" data-target="#myModal">Add MISC Service</a></h3>
          <form action="" method="">
            <div class="col-sm-2 col-xs-6 pull-right" style="text-align: right; position: relative; z-index: 2;">
              <label class="location">
                <select class="form-control input-sm locationchange" name="locat">
                  @foreach(\App\Country::where(['country_status'=>1])->whereHas('supplier')->orderBy('country_name')->get() as $loc)
                    <option value="{{$loc->id}}" {{$locat == $loc->id ? 'selected':''}}>{{$loc->country_name}}</option>
                  @endforeach
                </select>
              </label>
            </div>
          </form>
          <table class="datatable table table-hover table-striped">
            <thead>
              <tr>
                <th>Service Name</th>
                <th>City</th>
                <th>Price {{Content::currency()}}</th>
                <th>Price {{Content::currency(1)}}</th>
                <th class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($misc_service as $rest)
              <tr>
                <td>{{$rest->name}}</td>    
                <td>{{{$rest->province->province_name or ''}}}</td>                  
                <td class="text-right">{{number_format($rest->price,2)}}</td>
                <td class="text-right">{{number_format($rest->kprice,2)}}</td>
                <td class="text-right">                      
                  <button style="padding:0px; border:none; position: relative;top:-5px;" class="miscEdit" data-id="{{$rest->id}}" data-country="{{$rest->country_id}}" data-province="{{$rest->province_id}}"  data-title="{{$rest->name}}" data-price="{{$rest->price}}" data-kprice="{{$rest->kprice}}" data-toggle="modal" data-target="#myModal">
                    <i style="padding:1px 2px;" class="btn btn-info btn-xs fa fa-pencil-square-o"></i>
                  </button>
                  <a href="javascript:void(0)" class="RemoveHotelRate" data-type="delMISC" data-id="{{$rest->id}}" title="Remove this?">
                    <label  class="icon-list ic-trash"></label>
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

<div class="modal fade" id="myModal" role="dialog"  data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-md">    
    <form id="form_submitMISCService" method="POST" action="{{route('addMisc')}}">
      <div class="modal-content">        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong id="form_title">Add MISC Service</strong></h4>
        </div>
        <div class="modal-body">
          {{csrf_field()}}    
          <input type="hidden" name="eid" id="eid">
          <div class="row">
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>Country <span style="color:#b12f1f;">*</span></label> 
                <select class="form-control country" id="country" name="country" data-type="country_misc" data-title="tour_bus" required>
                    <option value="">Country</option>
                  @foreach(App\Country::where('country_status', 1)->orderBy('country_name')->get() as $con)
                    <option value="{{$con->id}}">{{$con->country_name}}</option>
                  @endforeach
                </select> 
              </div> 
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>City Name <span style="color:#b12f1f;">*</span></label> 
                <select class="form-control province" name="city"  id="dropdown-country_misc" required>
                  <option value="">City</option>
                  @foreach(App\Province::where(['province_status'=> 1])->orderBy('province_name')->get() as $pro)
                    <option value="{{$pro->id}}">{{$pro->province_name}}</option>
                  @endforeach
                </select>
              </div> 
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Service Name</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Service Name">
              </div>
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>Price {{Content::currency()}}</label>
                <input type="text" name="price" id="price" class="form-control number_only" placeholder="00.0">
              </div>
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>Price {{Content::currency(1)}}</label>
                <input type="text" name="kprice" id="kprice" class="form-control number_only" placeholder="00.0">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success btn-flat btn-sm" id="btnMisc">Publish</button>
          <a href="#" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal">Cancel</a>
        </div>
      </div>      
    </form>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
     $(".datatable").DataTable();
  });
</script>
@endsection
