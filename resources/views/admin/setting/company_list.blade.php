@extends('layout.backend')
@section('title', 'Company')
<?php $active = 'setting-options';
$subactive ='company'; 
use App\component\Content;
?>

@section('content')
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
          <form action="POST">
            <section class="col-lg-12 connectedSortable">
                <h3 class="border">Companies List <span style=" font-size: 22px;" class="fa fa-angle-double-right"></span> <a href="{{route('companyForm')}}" class="btn btn-default btn-sm">Add New Company</a></h3>
                <table class="datatable table table-hover table-striped">
                  <thead>
                    <tr>
                      <th>Logo</th>
                      <th>Title</th>                     
                      <th>Joining on</th>
                      <th class="text-center">Status</th>
                      <th width="100" class="text-center">action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($companies as $cp)
                    <tr>
                      <?php $logo = $cp->logo ? "/storage/avata/".$cp->logo: '/img/nofile.jpg';?>
                      <td width="120px"><img src="{{$logo}}" style="width: 100%;"></td>
                      <td>{{$cp->title}}</td>
                      <td>{{Content::dateformat($cp->updated_at)}}</td>
                      <td class="text-center">{!! $cp->status > 0 ? '<span class="badge">Enable</span>':'<span class="badge label-warning">Disabled</span>' !!}</td>
                      <td class="text-right">
                        <a target="_blank" href="{{route('companyForm', ['cp_id'=> $cp->id])}}" title="Edit Profile">
                          <label style="cursor: pointer;" class="icon-list ic_edit"></label>
                        </a>         
                        <a href="javascript:void(0)" class="RemoveHotelRate" data-type="company" data-id="{{$cp->id}}" title="Remove this user?">
                          <label style="cursor: pointer;" class="icon-list ic_remove"></label>
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
  });
</script>

@endsection
