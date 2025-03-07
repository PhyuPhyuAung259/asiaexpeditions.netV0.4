@extends('layout.backend')
@section('title', 'Hotel Facility')
<?php $active = 'supplier/hotels'; 
  $subactive ='hotel/room';
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
        <section class="col-lg-12">
          <h3 class="border">Hotel Facility <span class="fa fa-angle-double-right"></span> <a href="#" class="btn btn-default btn-sm btnEditRoom" data-toggle="modal" data-target="#myModal">Add New </a></h3>
          <table class="datatable table table-hover table-striped">
            <thead>
              <tr>                     
                <th>Name</th>
                <th width="100" class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($hfacility as $rom)
              <tr>
                <td>{{$rom->name}}</td>        					                  
    						<td class="text-center"> 
                    <a class="btnEditRoom" href="#" title="Hotel Facility" data-toggle="modal" data-id="{{$rom->id}}" data-name="{{$rom->name}}" data-target="#myModal">
                      <label class="icon-list ic_edit"></label>
                    </a>	                        
                    &nbsp;&nbsp;
                    <a href="javascript:void(0)" class="RemoveHotelRate" data-type="hotel_facility" data-id="{{$rom->id}}" title="Remove this?">
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

<div class="modal fade" id="myModal" role="dialog" data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-md">
    <form method="POST" action="{{route('eddHotelFacility')}}">
      <div class="modal-content">       
        <input type="hidden" name="eid" id="roomid"> 
        <div class="modal-header" style="padding: 5px 13px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong> Room Type</strong></h4>
        </div>
        <div class="modal-body">
          {{csrf_field()}} 
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Facility Name</label>
                <input type="text" name="name" id="facility_name" class="form-control" placeholder="Facility Name">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-xs-6">
              <div class="form-group">
                <label>Status</label>&nbsp;
                <label style="font-weight:400;"> <input type="radio" name="status" value="1" checked="">Publish</label>&nbsp;&nbsp;
                <label style="font-weight: 400;"> <input type="radio" name="status" value="0">UnPublish</label>
              </div>
            </div>
          </div>
          <div class="modal-footer" style="padding: 5px 13px;">
              <button type="submit" class="btn btn-success btn-flat btn-sm">Publish</button>
              <a href="#" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal">Cancel</a>
          </div>
        </div>     
      </div>   
    </form>
  </div>
</div>
<script type="text/javascript">
  $(document).on("click", ".btnEditRoom", function(){
    $("#facility_name").val($(this).data('name'));
    $("#roomid").val($(this).data('id'));
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
     $(".datatable").DataTable();
  });
</script>
@endsection
