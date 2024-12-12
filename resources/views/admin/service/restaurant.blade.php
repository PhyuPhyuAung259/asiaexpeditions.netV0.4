@extends('layout.backend')
@section('title', 'Restaurant Menu')
<?php
  $active = 'restaurant/menu'; 
  $subactive ='restaurant/menu';
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
          <h3 class="border">Resturants List <span class="fa fa-angle-double-right"></span> <a href="{{route('createrestMenu', ['rest' =>'restaurants'])}}" class="btn btn-default btn-sm">Add New Menu</a></h3>
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
                <th>Restaurant</th>
                <th>Menu</th>
                <th class="text-center">Price {{Content::currency()}}</th>
                <th class="text-center">Price {{Content::currency(1)}}</th>
                <th class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($restaurant as $rest)
              <tr>
                <td>{{{$rest->supplier->supplier_name or ''}}}</td>
                <td>{{$rest->title}}</td>
                <td class="text-right">{{number_format($rest->price,2)}}</td>
                <td class="text-right">{{number_format($rest->kprice,2)}}</td>
                <td class="text-right">                      
                  <button class="restEditMenu" data-id="{{$rest->id}}" style="padding:0px; border:none;" data-country_id="{{$rest->country_id}}" data-type="country_rest" data-menu="{{$rest->title}}" data-price="{{$rest->price}}" data-rest_name="{{{$rest->supplier->id or ''}}}" data-kprice="{{$rest->kprice}}" data-toggle="modal" data-target="#myModal">
                    <i style="padding:1px 2px;" class="btn btn-info btn-xs fa fa-pencil-square-o"></i>
                  </button>
                  <a href="javascript:void(0)" class="RemoveHotelRate" data-type="restMenu" data-id="{{$rest->id}}" title="Remove this menu ?">
                    <label class="icon-list ic-trash"></label>
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

<div class="modal fade" id="myModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">    
    <form id="form_submitRestMenu" method="POST" action="{{route('EditRestMenu')}}">
      <div class="modal-content">        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Update Restaurant Menu</strong></h4>
        </div>
        <div class="modal-body">
          {{csrf_field()}}    
          <input type="hidden" name="eid" id="restid">
          <div class="row">
            <div class="col-md-6 col-xs-6">    
              <div class="form-group">
                <input type="hidden" name="country">
                <label>Country <span style="color:#b12f1f;">*</span></label> 
                <select class="form-control country" id="country" name="country" data-type="country" data-bus_type="2" required>
                  <option value="">--choose--</option>
                    @foreach(App\Country::getCountry(2) as $con)
                      <option value="{{$con->id}}">{{$con->country_name}}</option>
                    @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>City Name <span style="color:#b12f1f;">*</span></label> 
                <select class="form-control province" name="city" data-type="restaurant" id="dropdown-country" required>                
                </select>
              </div> 
            </div>
            <div class="col-md-6 col-xs-6">    
              <div class="form-group">
                <label>Restaurant Name <span style="color:#b12f1f;">*</span></label> 
                  <select class="form-control" name="rest_name" id="dropdown-restaurant" required>
                  </select>
              </div>
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>Menu Name<span style="color:#b12f1f;">*</span></label> 
                <input type="text" placeholder="Title" class="form-control" name="menu_name" id="menu_name" required autofocus>
              </div> 
            </div>       
            <div class="col-md-6">
              <div class="form-group">
                <label>Price {{Content::currency()}} </label>
                <input type="text" class="form-control" name="price" id="price">
              </div>
            </div>     
            <div class="col-md-6">
              <div class="form-group">
                <label>Price {{Content::currency(1)}} </label>
                <input type="text" class="form-control" name="kprice" id="kprice">
              </div>
            </div>         
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success btn-flat btn-sm" >Publish</button>
            <a href="#" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal">Cancel</a>
          </div>
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
