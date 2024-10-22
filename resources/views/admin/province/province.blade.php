@extends('layout.backend')
@section('title', 'Provinces List')
<?php
  $active = 'country'; 
  $subactive ='province';
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
                <h3 class="border">Provinces List <span class="fa fa-angle-double-right"></span> <a href="{{route('getProvince')}}" class="btn btn-primary btn-sm">New Province</a></h3>
                <form action="" method="">
                  <div class="col-sm-2 pull-right" style="text-align: right;">
                    <label class="location">
                     
                      <select class="form-control input-sm locationchange" name="location">
                        @foreach(\App\Country::where('country_status', 1)->orderBy('country_name')->get() as $loc)
                          <option value="{{$loc->id}}" {{$locationid == $loc->id ? 'selected':''}}>{{$loc->country_name}}</option>
                        @endforeach
                      </select>
                    </label>
                  </div>
                </form>
                <table class="datatable table table-hover table-striped">
                  <thead>
                    <tr>
                      <th width="12px">Photo</th>
                      <th>Province</th>
                      <th>Country</th>
                      <th>Created Date</th>
                      <th class="text-center">Status</th>
                      <th width="100" class="text-center">Options</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($provinces as $province)
                    <tr>
                      <td><img src="{{Content::urlthumbnail($province->province_picture)}}" style="width: 100%"></td>
                      <td>{{$province->province_name}}</td>
                      <td>{{{$province->country->country_name or ''}}}</td>
                      <td>{{Content::dateformat($province->updated_at)}}</td>          
                      <td class="text-center">{!! $province->province_status == 1 ? "<label class='label label-success'>Active</label>" : "<label class='label label-warning'>Inactive</label>" !!}</label></td>
                      <td class="text-right">                      
                        <a href="{{route('getProvinceEdit',['url'=> $province->id])}}" title="Edit Provice">
                          <label class="icon-list ic_book_project"></label>
                        </a>
                        <a href="javascript:void(0)" class="RemoveHotelRate" data-type="province" data-id="{{$province->id}}" title="Disable this province ?">
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
<script type="text/javascript">
  $(document).ready(function(){
     $(".datatable").DataTable();
  });
</script>
@endsection
