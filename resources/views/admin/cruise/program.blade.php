@extends('layout.backend')
@section('title', ' Cruises Program')
<?php $active = 'supplier/cruises'; 
  $subactive = 'cruise/program';
  use App\component\Content;
?>
@section('content')
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
          <section class="col-lg-12 connectedSortable">
            <h3 class="border">River Cruise Program List</h3>
            <table class="datatable table table-hover table-striped">
              <thead>
                <tr>
                  <th>Program Name</th>
                  <th>River Cruise</th>
                  <th>City</th>                
                  <th width="100" class="text-center">Options</th>
                </tr>
              </thead>
              <tbody>
                @foreach($programs as $cpro)
                <tr>
                  <td>{{$cpro->program_name}}</td>
                  <td>{{{$cpro->supplier->supplier_name or ''}}}</td>
                  <td>{{{$cpro->province->province_name or ''}}}</td>        
                  <td class="text-right"> 
                    <a href="{{route('getProgramEdit', ['cr_url'=> $cpro->supplier->id, 'crId'=> $cpro->id])}}" title="Edit River Cruise Program">
                      <label class="icon-list ic_edit"></label>
                    </a>
                    <a href="javascript:void(0)" class="RemoveHotelRate" data-type="cruise-program" data-id="{{$cpro->id}}" title="Remove River Cruise Program">
                      <label class="icon-list ic_remove"></label>
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
