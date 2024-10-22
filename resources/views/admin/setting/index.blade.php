@extends('layout.backend')
@section('title', 'Banks')
<?php $active = 'setting-options';
$subactive ='bank'; 
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
                <h3 class="border">Setting <span style=" font-size: 22px;" class="fa fa-angle-double-right"></span> <a href="{{route('setting')}}">Bank</a></h3>
                <table class="datatable table table-hover table-striped">

                  <thead>
                    <tr>
                      <th>Title</th>                     
                      <th>Created at</th>
                      <th width="120px" class="text-center">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($settings as $ab)
                    <tr>                     
                      <td>{{$ab->title}}</td>
                      <td>{{Content::dateformat($ab->updated_at)}}</td>
                      <td class="text-right">
                        <a href="{{route('settingForm', ['setId'=>$ab->id])}}" title="Edit Form">
                          <label style="cursor: pointer;" class="icon-list ic_edit"></label>
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

