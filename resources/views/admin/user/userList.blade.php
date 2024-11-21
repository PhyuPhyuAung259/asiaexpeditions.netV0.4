@extends('layout.backend')
@section('title', 'User List')
<?php $active = 'users';
  $subactive ='users'; 
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
                <h3 class="border">Users List <span style=" font-size: 22px;" class="fa fa-angle-double-right"></span> <a href="{{route('userForm')}}" class="btn btn-default btn-sm">Add User</a></h3>
                <table class="datatable table table-hover table-striped">
                  <thead>
                    <tr>
                      <th width="30px">Photo</th>
                      <th>UserName</th>
                      <th>Gender</th>
                      <th>Phone</th>
                      <th>Email</th>
                      <th>Password</th>
                      <th>Role</th>
                      <th>Joining on</th>
                      <th>Status</th>
                      <th width="100" class="text-center">action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($users as $user)
                    <tr>
                      <td width="30px"><img src="/storage/avata/thumbnail/{{$user->picture}}" class="img-responsive"></td>
                      <td>{{$user->fullname}}</td>
                      <td>{{$user->gender}}</td>
                      <td>{{$user->phone }}</td>
                      <td>{{$user->email}}</td>
                      <td>{{$user->password_text}}</td>
                      <td>{{{ $user->role->name or ''}}}</td> 
                      <td>{{Content::dateformat($user->created_at)}}</td>   
                      <td>{!!$user->banned==0? "<label class='label label-default'>Deactive<label>": "<label class='label label-success'>Active</label>"!!}</td>      
                      <td class="text-right">                      
                        <a target="_blank" href="{{route('userStore', ['id'=> $user->id])}}" class="btn btn-info btn-xs " title="Change User Info">
                         <i class="fa fa-edit"></i>
                        </a>
                        @if(Auth::user()->role_id == 2)
                          <a target="_blank" href="{{route('editpermission', ['url'=> $user->id])}}" class="btn btn-primary btn-xs" title="Change Password & User Permission">
                            <!-- <label style="cursor: pointer;" class="icon-list ic_user_permission"></label> -->
                            <i class="fa fa-gear (alias)"></i>
                          </a>                       

                          <a href="javascript:void(0)" data-type="user" class="RemoveHotelRate btn btn-danger btn-xs" data-id="{{$user->id}}" title="Remove this user?">
                            <!-- <label style="cursor: pointer;" class="icon-list ic_remove"></label> -->
                            <i class="fa fa-minus-circle"></i>
                          </a>
                         @endif
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
  });
</script>
<!-- <div class="modal fade" id="myModal" role="dialog"  data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-lg">    
    <form id="form_submitUser" method="POST" action="{{route('addUser')}}">
      <div class="modal-content">        
        <div class="modal-header" style="padding: 5px 13px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong id="form_title">Add MISC Service</strong></h4>
        </div>
        <div class="modal-body">
          {{csrf_field()}}
          <div class="row">
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>Full Name <span style="color:#b12f1f;">*</span></label> 
                <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Full Name" required> 
              </div> 
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>UserName <span style="color:#b12f1f;">*</span></label> 
                <input type="text" class="form-control" name="username" id="username" placeholder="User Name" required>
              </div> 
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>Email Address <span style="color:#b12f1f;">*</span></label> 
                <input type="email" class="form-control" name="email" id="email" placeholder="virak@asia-expeditions.com"  required>
              </div> 
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>Phone <span style="color:#b12f1f;">*</span></label>
                <input type="text" class="form-control" name="phone" id="phone" placeholder="(+855) 1234 567 890" required>
              </div>
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>Password<span style="color:#b12f1f;">*</span> <small>(Password at less 6 character)</small></label> 
                <input type="password" class="form-control" name="password" id="password" placeholder="Password " required>
              </div> 
            </div>
            <div class="col-md-6 col-xs-6">
              <div class="form-group">
                <label>Confirm Password <span style="color:#b12f1f;">*</span>  <small>(Must be the same password)</small></label>
                <input type="password" class="form-control" name="con-password" id="con-password" placeholder="Confirm Password" required>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="padding: 5px 13px;">
          <button type="submit" class="btn btn-success btn-flat btn-sm" id="btnSubmit">Register Now</button>
          <a href="#" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal">Cancel</a>
        </div>
      </div>      
    </form>
  </div>
</div> -->
@endsection
