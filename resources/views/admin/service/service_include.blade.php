@extends('layout.backend')
@section('title', 'Services Inlude & Exclude')
<?php $active = 'restaurant/menu'; 
  $subactive ='service/include';
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
        <section class="col-lg-12 ">
          <h3 class="border">All Service <span class="fa fa-angle-double-right"></span> <a href="#" class="btn btn-default btn-sm btnEditRoom" data-toggle="modal" data-target="#myModal">Add New Service </a></h3>
          <table class="datatable table table-hover table-striped">
            <thead>
              <tr>                     
                <th>Service Name</th>
                <th>Service Type</th>
                <th width="100" class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($service as $sv)
              <tr>
                <td>{{$sv->service_name}}</td>     
                <td>{{ $sv->service_cat == 1 ?'Include':'Exclude' }}</td>
    						<td class="text-center"> 
                    <a class="btnEditRoom" href="#" title="Edit Room Type" data-toggle="modal" data-id="{{$sv->id}}" data-name="{{$sv->service_name}}" data-target="#myModal">
                      <label class="icon-list ic_edit"></label>
                    </a>	                        
                    <a href="javascript:void(0)" >
                      <label class="icon-list {{$sv->service_status == 1 ? 'ic_active': 'ic_inactive'}}"></label>
                    </a>
                    &nbsp;&nbsp;
                    <a href="javascript:void(0)" class="RemoveHotelRate" data-type="service_include" data-id="{{$sv->id}}" title="Remove this?">
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
    <form method="POST" action="{{route('addService')}}">
      <div class="modal-content">       
        <input type="hidden" name="seviceid" id="seviceid"> 
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Add Service</strong></h4>
        </div>
        <div class="modal-body">
          {{csrf_field()}} 
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Room Name</label>
                <input type="text" name="service_name" id="service_name" class="form-control" placeholder="Service Name">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>Service Type</label>&nbsp;
                <label style="font-weight:400;"> <input type="radio" name="service_cat" value="1" checked="">&nbsp;&nbsp;Include</label>&nbsp;&nbsp;
                <label style="font-weight: 400;"> <input type="radio" name="service_cat" value="0">&nbsp;&nbsp;Exclude</label>
              </div>
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>Status</label>&nbsp;
                <label style="font-weight:400;"> <input type="radio" name="status" value="1" checked="">&nbsp;&nbsp;Publish</label>&nbsp;&nbsp;
                <label style="font-weight: 400;"> <input type="radio" name="status" value="0">&nbsp;&nbsp;UnPublish</label>
              </div>
            </div>           
          </div>
          <div class="modal-footer" style="text-align: center;">
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
    $("#service_name").val($(this).data('name'));
    $("#seviceid").val($(this).data('id'));
  });
</script>

<script type="text/javascript">
  $(document).ready(function(){
     $(".datatable").DataTable();
  });
</script>
@endsection
