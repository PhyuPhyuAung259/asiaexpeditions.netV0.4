@extends('layout.backend')
@section('title', 'Hotel Category')
<?php $active = 'supplier/hotels'; 
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
                <h3 class="border">Hotel Category <span class="fa fa-angle-double-right"></span> <a href="#" class="btn btn-default btn-sm">New Category</a></h3>                 
                <table class="datatable table table-hover table-striped">
                  <thead>
                    <tr>                     
                      <th>Name</th>
                      <th width="100" class="text-center">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($roomCat as $romcat)
                    <tr>
                      <td>{{$romcat->name}}</td>        					                  
          						<td class="text-center"> 
	                        <a href="#Edit Tour" title="Edit Price">
	                          <img src="#" class="icon-list ic_edit">
	                        </a>	                        
	                        <a href="#Edit Tour" title="View Report Net Price">
	                          <img src="#" class="icon-list {{$romcat->status == 1 ? 'ic_active': 'ic_inactive'}}">
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
