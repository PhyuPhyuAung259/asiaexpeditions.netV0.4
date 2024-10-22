@extends('layout.backend')
@section('title', 'River Cruise Program List')
<?php $active = 'supplier/cruises'; 
  $subactive = 'cruise/cabin';
  use App\component\Content;
?>
@section('content')
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
          <section class="col-lg-12 connectedSortable">
            <h3 class="border">Cabin Type & River Cruise Program </h3>
            <table class="datatable table table-hover table-striped">
              <thead>
                <tr>
                  <th>Cabin</th>      
                  <th width="100" class="text-center">Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($cabin as $cpro)
                <tr>                  
                  <td>{{ $cpro->name }}</td>        
                  <td class="text-right">
                    <a href="">
                      <label class="icon-list ic_edit"></label>
                    </a>
                    <a href="javascript:void(0)" class="RemoveHotelRate" data-type="cruise-program" title="Remove River Cruise Program">
                      <label class="icon-list ic_active"></label>
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
  <script type="text/javascript">
  $(document).ready(function(){
     $(".datatable").DataTable();
  });
</script>
@endsection
