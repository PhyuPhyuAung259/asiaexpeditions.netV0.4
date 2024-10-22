@extends('layout.backend')
@section('title', 'Slide Show')
<?php
  $active = 'setting-options'; 
  $subactive ='slide-show';
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
                <h3 class="border">Slide Show<span class="fa fa-angle-double-right"></span> <a href="{{route('createSlide')}}" class="btn btn-default btn-sm">Add New Slide</a></h3>
              
                <table class="datatable table table-hover table-striped">
                  <thead>
                    <tr>
                      <th width="12px">Photo</th>
                      <th>Title</th>
                      <th width="100" class="text-center">Options</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($slides as $slide)
                    <tr>
                      <td><img src="{{Content::urlthumbnail($slide->photo)}}" style="width: 100%"></td>
                      <td>{{$slide->title}}</td>
                      <td> 
                        <a href="{{route('getSlide', ['eid'=>$slide->id])}}">
                          <i style="padding:1px 2px;" class="btn btn-info btn-xs fa fa-pencil-square-o"></i>
                        </a>&nbsp;
                        <a href="javascript:void(0)" data-type="slide-show" class="RemoveHotelRate" data-id="{{$slide->id}}" title="Remove this ?">
                          <i class="fa fa-minus-circle btn-xs btn-danger"></i>
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
<script type="text/javascript">
  $(document).ready(function(){
     $(".datatable").DataTable();
  });
</script>
@endsection
