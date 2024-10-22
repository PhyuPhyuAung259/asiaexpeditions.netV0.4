@extends('layout.backend')
@section('title', 'River Cruise Cabin')
<?php $active = 'supplier/cruises'; 
  $subactive = 'cruise/cabin/price';
  use App\component\Content;
?>
@section('content')
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
          <section class="col-lg-12 connectedSortable">
            <h3 class="border">Cabin Type & River Cruise Program <span class="fa fa-angle-double-right"></span> <a href="#" class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModal">Add New Cabin </a></h3>
             <form action="" method="">
                <div class="col-sm-2 pull-right" style="text-align: right;">
                  <label class="location">
                    <span class="fa fa-map-marker"></span>
                    <select class="form-control input-sm locationchange" name="location">
                      @foreach(\App\Country::where('web', 1)->get() as $loc)
                        <option value="{{$loc->id}}" {{$locationid == $loc->id ? 'selected':''}}>{{$loc->country_name}}</option>
                      @endforeach
                    </select>
                  </label>
                </div>
              </form>
              <table class="datatable table table-hover table-striped">
                <thead>
                  <tr>
                    <th>TypeCabin</th>
                    <th>Program</th>
                    <th>StartDate</th>
                    <th>EndDate</th>
                    @foreach(\App\RoomCategory::where('status',1)->orderBy('id', 'ASC')->get() as $cat)
                      <th title="{{$cat->name}}">{{$cat->key_name}}</th>
                    @endforeach              
                    <th width="60" class="text-center">Options</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($cabinPrice as $cpro)
                  <tr>
                    <?php $supplier = App\Supplier::find($cpro->supplier_id); ?>                  
                    <td class="text-left">{{$cpro->name}}</td> 
                    <td class="text-left">{{$cpro->program_name}}</td>
                    <td width="100">{{ Content::dateformat($cpro->start_date) }}</td>
                    <td width="100">{{ Content::dateformat($cpro->end_date) }}</td>
                    <td>{{number_format($cpro->ssingle_price,2)}} </td>
                    <td>{{number_format($cpro->stwn_price,2)}}</td>
                    <td>{{number_format($cpro->sdbl_price,2)}}</td>
                    <td>{{number_format($cpro->sextra_price,2)}}</td>
                    <td>{{number_format($cpro->schextra_price,2)}}</td>
                    <td>{{number_format($cpro->nsingle_price,2)}}</td>
                    <td>{{number_format($cpro->ntwn_price,2)}}</td>
                    <td>{{number_format($cpro->ndbl_price,2)}}</td>
                    <td>{{number_format($cpro->nextra_price,2)}}</td>
                    <td>{{number_format($cpro->nchextra_price,2)}}</td>   
                    <td class="text-right">
                      <a href="{{route('editCabin', ['cproid'=> $cpro->program_id, 'ccabinid'=> $cpro->cabin_id])}}" title="Edit price of type of cabin">
                        <label class="icon-list ic_edit"></label>
                      </a>
                      <a href="javascript:void(0)" class="RemoveHotelRate" data-type="cruise-cabin-price" data-id="{{$cpro->id}}" title="You  want to remove this ?">
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
  <script type="text/javascript">
  $(document).ready(function(){
     $(".datatable").DataTable();
  });
</script>
@endsection
