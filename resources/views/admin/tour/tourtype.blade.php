@extends('layout.backend')
@section('title', 'Tour Type')
<?php $active = 'tours'; 
  $subactive ='tourtype';
  use App\component\Content;
?>
@section('content')
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
      <div class="row">
        @include("admin.include.message")
        <section class="col-lg-12 connectedSortable">
          <h3 class="border">Tour Type <span class="fa fa-angle-double-right"></span> <a href="# Add new Tour" class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModal">Add New</a></h3>
          <table class="datatable table table-hover table-striped">
            <thead>
              <tr>
                <th width="150">Title</th>
                <th>Meta Keyword <small>(Webiste)</small></th>
                <th width="100" class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($tourtype as $tour)
              <tr>
                <td>{{$tour->name}}</td>
                <td>{!! $tour->meta_keyword !!}</td>
                <td class="text-right">
                  <span class="editTourType" data-url="{{route('getTourTypeedit')}}" data-type="tourtype" data-id="{{$tour->id}}" title="Edit Tour type" data-toggle="modal" data-target="#myModal">
                    <label class="icon-list ic_edit"></label>
                  </span>                
                </td>                     
              </tr>
              @endforeach
            </tbody>
          </table>                
        </section>
      </div>
    </section>
  </div>  
<div class="modal fade" id="myModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md">    
    <form method="POST" action="{{route('createTourType')}}">
      <div class="modal-content">        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Add New Tour Type</strong></h4>
        </div>
        <div class="modal-body">
          {{csrf_field()}}    
          <input type="hidden" name="eid" id="eid">
          <div class="row">
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>Title<span style="color:#b12f1f;">*</span></label> 
                <input type="text" placeholder="Title" class="form-control" name="title" id="title" required autofocus>
              </div> 
            </div>       
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>Business IOS</label> 
                <input type="text" placeholder="IOS" class="form-control" name="bus_ios" id="bus_ios">
              </div> 
            </div>        
            
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Keyword www.google.com (SEO)</label>
                <textarea class="form-control" rows="3" placeholder="Keyword (SEO)" name="meta_keyword" id="meta_keyword"></textarea>
              </div>
            </div>
          </div>
           <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Description </label>
                <textarea class="form-control" rows="5" placeholder="Description (SEO)" name="meta_desc" id="meta_desc"></textarea>
              </div>
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <div><label>Website</label></div>
                <label style="font-weight: 400;"><input type="radio" name="web" value="1" checked="">Yes</label>&nbsp;&nbsp;
                <label style="font-weight: 400;"><input type="radio" name="web" value="0">No</label>
              </div> 
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <div><label>Status</label></div>
                <label style="font-weight: 400;"><input type="radio" name="status" value="1" checked="">Publish</label>&nbsp;&nbsp;
                <label style="font-weight: 400;"><input type="radio" name="status" value="0">UnPublish</label>
              </div> 
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success btn-flat btn-sm" id="btnTourTypeSave">Publish</button>
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
