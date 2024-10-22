@extends('layout.backend')
@section('title', 'Tours List')
<?php $active = 'setting-options'; 
  $subactive ='blog';
  use App\component\Content;
?>
@section('content')
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
          <section class="col-lg-12 connectedSortable">
            @include('admin.include.message')
            <h3 class="border">Blog Lists <span class="fa fa-angle-double-right"></span> <a href="{{route('blogcreate')}}" class="btn btn-primary btn-sm">Add New Activity</a></h3>
            <form action="" method="">
              <div class="col-sm-2 col-xs-6 pull-right" style="text-align: right; position: relative; z-index: 2;">
                <label class="location">
                  <select class="form-control input-sm locationchange" name="location">
                    @foreach(\App\Country::whereHas('tour', function($query) {$query->where('post_type', 1);})->where('country_status', 1)->orderBy('country_name', 'DESC')->get() as $loc)
                      <option value="{{$loc->id}}" {{$locationid == $loc->id ? 'selected':''}}>{{$loc->country_name}}</option>
                    @endforeach
                  </select>
                </label>
              </div>
            </form>
            <table class="datatable table table-hover table-striped">
              <thead>
                <tr>
                  <th style="width: 12px;">Photo</th>
                  <th>Tittle</th>
                  <th>Published</th>
                  <th width="110" class="text-center">Options</th>
                </tr>
              </thead>
              <tbody>
                @foreach($tours as $tour)                 
                <tr>
                  <td><img src="{{Content::urlthumbnail($tour->tour_photo, $tour->user_id)}}" style="width: 100%"></td>
                  <td>{{$tour->tour_name}}</td>
                  <td>{{ Content::dateformat($tour->updated_at) }}</td>
                  <td class="text-right">                      
                    <a href="{{route('blogedit', ['url'=>$tour->id])}}" title="Edit Activity">
                      <label src="#" class="icon-list ic_edit_tour"></label>
                    </a>                   
                    {!! Content::DelUserRole("Delete this blog ?", "tour", $tour->id, $tour->user_id ) !!}    
                  </td>                     
                </tr>
                @endforeach
              </tbody>
            </table>                
          </section>
        </div>
    </section>
  </div>  
<script type="text/javascript">
  $(document).ready(function(){
     $(".datatable").DataTable();
  });
</script>
@endsection
