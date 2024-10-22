@extends('layout.backend')
@section('title', 'All Country')
<?php $active = 'country'; 
  $subactive ='country';
use App\component\Content;

?>

@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
          <form action="POST">
            <section class="col-lg-12 connectedSortable">
                <h3 class="border">Countries List <span class="fa fa-angle-double-right"></span> <a href="{{route('getCountry')}}" class="btn btn-default btn-sm">New Country</a></h3>
                <table class="datatable table table-hover table-striped">
                  <thead>
                    <tr>
                      <th width="12px">Flag</th>
                      <th>Country</th>
                      <th>Published On</th>
                      <th width="30">Status</th>
                      <th width="100" class="text-center">Options</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($countries as $country)
                    <tr>
                      <td><img src="{{Content::urlthumbnail($country->country_photo)}}" style="width: 100%"></td>
                      <td>{{$country->country_name}}</td>
                      <td>{{Content::dateformat($country->updated_at)}}</td>                      
                      <td>{!! $country->country_status == 1 ? "<label class='label label-success'>Active</label>" : "<label class='label label-warning'>Inactive</label>" !!}</label></td>
                      <td class="text-right">            
                        <a href="{{route('getCountryEdit', ['con' => $country->id])}}" title="Edit County">
                          <label class="icon-list ic_book_project"></label>
                        </a>
                        <a href="javascript:void(0)" class="RemoveHotelRate" data-type="country" data-id="{{$country->id}}" title="Disable this country ?">
                          <label class="icon-list ic-trash"></label>
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
</div>
<script type="text/javascript">
  $(document).ready(function(){
     $(".datatable").DataTable();
  });
</script>
@endsection
