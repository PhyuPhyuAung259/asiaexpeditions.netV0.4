@extends('layout.backend')
@section('title', 'User List')
<?php $active = 'users';
  $subactive ='user/role'; 
  use App\component\Content;
?>

@section('content')
  @include('admin.include.header') 
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
          @include('admin.include.message')
          <form action="POST">
            <section class="col-lg-12 connectedSortable">
                <h3 class="border">Role Management <span style=" font-size: 22px;" class="fa fa-angle-double-right"></span> <a href="#" data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Role</a></h3>
                <table class="datatable table table-hover table-striped">
                  <thead>
                    <tr>
                      <th width="200px">UserName</th>
                      <th width="350px">Description</th>
                      <th width="30px" class="text-center">Status</th>
                      <th width="150px" class="text-center">action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($roles as $role)
                    <tr>
                      <td>{{$role->name}}</td>
                      <td>{{$role->desc}}</td>
                      <td class="text-center">{!! $role->status == 1 ? "<span class='label label-success'>Active</span>" : "<span class='label label-warning'>Inactive</span>" !!}</td>
                      <td class="text-center" style="font-size: 14px;">
                          <a data-toggle="modal" data-target="#myModal" href="#" class="editRole" data-id="{{$role->id}}" data-title="{{$role->name}}" data-desc="{{$role->desc}}">Edit</a>&nbsp; | &nbsp;
                          <a target="_blank" href="{{route('roleApply', ['action'=> 'menu', 'role_id' =>$role->id])}}" title="Change Password & User Permission">
                            Menu Management
                          </a>        
                      </td>                     
                    </tr>
                    @endforeach
                  </tbody>
                </table>                
            </section>
          </form>
        </div>
    </section>
  </div>  
<script type="text/javascript">
  $(document).ready(function(){
     $(".datatable").DataTable();

    $(document).on("click", ".editRole", function(){
        $("#title").val($(this).data("title"));
        $("#desc").val($(this).data("desc"));
        $("#eid").val($(this).data('id'));
    });
  });
</script>
<div class="modal fade" id="myModal" role="dialog"  data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-md">    
    <form method="POST" action="{{route('createRole')}}">
      <div class="modal-content">        
        <div class="modal-header" >
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong id="form_title"> Role Form</strong></h4>
        </div>
        <div class="modal-body">
          {{csrf_field()}}
          <input type="hidden" name="eid" id="eid">
          <div class="row">
            <div class="col-md-12 col-xs-6">
              <div class="form-group">
                <label>Title<span style="color:#b12f1f;">*</span></label> 
                <input type="text" class="form-control" name="title" id="title" placeholder="Title" required> 
              </div> 
            </div>
            <div class="col-md-12 col-xs-6">
              <div class="form-group">
                <label>Descriptions<span style="color:#b12f1f;">*</span></label> 
                <textarea class="form-control" name="desc" id="desc" rows="6" placeholder="Descriptions here...!"></textarea>
              </div> 
            </div>
          </div>
        </div>
        <div class="modal-footer" style="text-align: center;">
          <button type="submit" class="btn btn-success btn-flat btn-sm" id="btnSaveRole">Save</button>
        </div>
      </div>      
    </form>
  </div>
</div>
@endsection
