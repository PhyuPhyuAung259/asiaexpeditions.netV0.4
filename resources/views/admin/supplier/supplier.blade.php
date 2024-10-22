@extends('layout.backend')
@section('title', isset($business) ? $business->name: 'Suppliers')
<?php 
  $active = isset($business) ? 'supplier/'.$business->slug: 'suppliers'; 
  $supply = isset($business) ? $business->slug: 'supplier'; 
  $subactive = isset($business) ? 'supplier/'.$business->slug: 'suppliers';
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
          <h3 class="border">Suppliers {{{ $business->name or ''}}} List <span class="fa fa-angle-double-right"></span> <a href="{{route('getSupplierForm', ['type'=> $supply] )}}" class="btn btn-default btn-sm">New {{{ $business->name or 'Supplier'}}}</a> 
          
          </h3>     
          @if(Auth::user()->role_id == 2 || Auth::user()->role_id == 4)
          <form action="" method="" style="position: relative; z-index: 2;">
            <div class="col-sm-2 col-xs-6 pull-right" style="text-align: right; margin-right: -15px;">
              <label class="location">
                <select class="form-control input-sm locationchange" name="location">
                  <option value="">--Choose--</option>
                  	<?php 
                    	if (isset($business)) {
                    		$getCountry = \App\Country::countryBySupplier($business['id']);
                    	}else{
                    		$getCountry = \App\Country::where('country_status', 1)->orderBy('country_name', 'ASC')->get();
                    	}
                  	?>
	                @foreach($getCountry as $loc)
	                    <option value="{{$loc->id}}" {{$locationid == $loc->id ? 'selected':''}}>{{$loc->country_name}}</option>
	                @endforeach
                </select>
              </label>
            </div>
          </form>
         
          @endif
          @if(isset($supplierName) && $supplierName == 'hotels')
            <?php  $route = route('getHotelinfo'); ?>
          @elseif(isset($supplierName) && $supplierName == 'restaurants')
            <?php  $route = route('getRestautantinfo'); ?>  
          @else
            <?php  $route = route('getRestautantinfo'); ?>
          @endif
          <form target="_blank" action="{{$route}}" method="GET">
              <div class="col-sm-1 pull-right">
                <button style="border-radius: 50px;" class="btn btn-sm btn-default ok_download" data-type="excel">Download Excel  &nbsp;<i class="fa fa-download"></i></button>
              </div>
              <div class="col-sm-1 pull-right checkingAction" style="display: none;">
                <button class="btn btn-sm btn-primary btn_acc" name="viewType" value="view1"> Preview Info</button>
              </div>
              <div class="col-sm-1 pull-right checkingAction" style="display: none;">
                <button class="btn btn-sm btn-primary btn_acc" name="viewType" value="view2"> View Agent Tariff</button>
              </div>
              <table class="datatable table table-hover table-striped excel-sheed">
                <thead>
                  <tr>
                    @if(isset($supplierName) && $supplierName == 'hotels')
                    <th style="vertical-align: middle;">
                      <label class="container-CheckBox">
                        <input type="checkbox" id="check_all" >
                        <span class="checkmark hidden-print" ></span>
                      </label>
                    </th>
                    @endif
                    @if(isset($supplierName) && $supplierName == 'restaurants')
                    <th style="vertical-align: middle;">
                      <label class="container-CheckBox">
                        <input type="checkbox" id="check_all" >
                        <span class="checkmark hidden-print" ></span>
                      </label>
                    </th>
                    @endif
                    <th class="hidden-xs" width="20px">Logo</th>
                    <th>Name</th>
                    <th class="hidden-xs" width="135px">Location</th>
                    @if(!isset($supplierName))
                    <th>Type</th>
                    @endif
                    <th>Phone</th>
                    <th>Email</th>
                    <th class="hidden-xs" width="75">Added By</th>
                    @if(isset($supplierName))
                      @if($supplierName == 'restaurants')
                        <th>Restaurant</th>
                      @endif
                    @endif

                    @if(isset($supplierName))
                      @if($supplierName == 'hotels')
                      <th class="text-center">Room</th>
                      @endif
                    @endif
                    <th class="text-center hidden-xs">Status</th>
                    @if(isset($supplierName))
                      @if($supplierName == 'cruises')
                      <th class="text-center">Program</th>
                      @endif
                    @endif
                    <th width="150" class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($suppliers as $sup)
                    <tr>
                    	@if(isset($supplierName) && $supplierName == 'hotels')
                        <td style="vertical-align: middle;">
                          <label class="container-CheckBox">
                              <input type="checkbox"  id="check_all" class="checkall" name="hotel_checked[]" value="{{$sup->id}}">
                              <span class="checkmark hidden-print"></span>
                          </label>
                        </td>
                      @endif 
                      @if(isset($supplierName) && $supplierName == 'restaurants')
                        <td style="vertical-align: middle;">
                          <label class="container-CheckBox">
                              <input type="checkbox" class="checkall" name="hotel_checked[]" value="{{$sup->id}}">
                              <span class="checkmark hidden-print"></span>
                          </label>
                        </td>
                      @endif                
                      <td class="hidden-xs" width="20px">
                        <img src="/storage/{{$sup->supplier_photo}}" style="width: 100%"></td>
                      <td>{{$sup->supplier_name}}</td>
                      <td class="hidden-xs" style="color: #605ca8;">
                        {{{ $sup->country->country_name or ''}}},  {{{ $sup->province->province_name or ''}}}</td>
                      @if(!isset($supplierName))
                        <td>{{{$sup->business->name or ''}}}</td>
                      @endif
                      <td>{{$sup->supplier_phone}}</td>
                      <td>{{$sup->supplier_email}}</td>

                      <td class="hidden-xs">{{{ $sup->user->fullname or ''}}}</td> 
                      @if(isset($supplierName))
                        @if($supplierName == "hotels")
                        <td class="text-center"><label title="Number of Room" class="badge">{{$sup->room->count()}}</label></td>
                        @endif
                      @endif  
                      @if(isset($supplierName))
                        @if($supplierName == 'restaurants')
                          <td class="text-center"><label class="badge">{{$sup->res_menu()->where('status',1)->count()}}</label> <a target="_blank" href="{{route('restMenu')}}?rest={{$sup->id}}">View Menu</a></td>
                        @endif
                      @endif 
                      <td class="text-center hidden-xs">
                        @if($sup->supplier_status == 1)
                          <label class="icon-list ic_active"></label>
                       
                        @else
                          <label class="icon-list ic_inactive"></label>
                           
                        @endif
                      </td>
                      @if(isset($supplierName))
                        @if($supplierName == "cruises")
                        <td class="text-center"><label class="badge">{{$sup->crprogram->count()}}</label></td>
                        @endif
                      @endif                                   
                      <td class="text-right">
                        @if(isset($supplierName))
                          @if($supplierName == "transport")
                            <a target="_blank" href="{{route('getDriver', ['id'=> $sup->id])}}" title="Preview Driver">
                              <i style="padding:1px 2px; position: relative;top:-5px;" class="btn btn-primary btn-xs a fa fa-list-alt"></i>
                            </a>
                          @endif
                        @endif
                        <a href="{{route('getEditSupplier', ['url'=> $sup->id])}}" title="Edit Supplier">
                          <label  class="icon-list ic_book_project"></label>
                        </a>
                        @if(isset($supplierName))
                          @if($supplierName == "cruises")
                            <a href="{{route('getCruiseProgram',['uri' => $sup->id])}}" title="Create River Cruises Program">
                              <label  class="icon-list ic_book_add"></label>
                            </a>
                          @endif
                        @endif                  

                        @if(isset($supplierName))
                          @if($supplierName == "hotels")
                            <a href="{{route('getRoomApply',['hotelId' => $sup->id])}}" title="Apply Room">
                              <label class="icon-list ic_roomApply"></label>
                            </a>              
                            <a target="_blank" href="{{route('supplierReport' ,['reportId' => $sup->id,'type'=> isset($sup->business->slug)? $sup->business->slug :''])}}?type=selling" title="View report hotel selling price">
                              <label class="icon-list ic_report"></label>
                            </a> 
                            <a href="{{route('getEditHotelInfo', ['url'=> $sup->id])}}" title="Edit hotel Infor">
                              <label  class="icon-list ic_book_project"></label>
                            </a>
                            <a target="_blank" href="{{route('supplierReport' ,['reportId' => $sup->id,'type'=> isset($sup->business->slug)? $sup->business->slug :''])}}?type=contract" title="View report hotel contract">
                              <label  class="icon-list ic_invoice_drop"></label>
                            </a>
                          @endif
                          @if($supplierName== "restaurants")
                          <a target="_blank" href="{{route('supplierReport' ,['reportId' => $sup->id,'type'=> 'RestaurantInfo'])}}" title="View {{{$business->name or 'supplier'}}} information Report">
                          <label class="icon-list ic_report"></label> 
                        </a> 
                          @endif
                        @endif 
                        <a target="_blank" href="{{route('supplierReport' ,['reportId' => $sup->id,'type'=> isset($sup->business->slug)? $sup->business->slug :'','sub_type'=>'with Price'])}}" title="View {{{$business->name or 'supplier'}}} Report">
                          <label class="icon-list ic_report"></label>
                        </a> 
                        <a target="_blank" href="{{route('supplierReport' ,['reportId' => $sup->id,'type'=> isset($sup->business->slug)? $sup->business->slug :'','sub_type'=>'without Price'])}}" title="View Agent {{{$business->name or 'supplier'}}} Tariff">
                          <label class="icon-list ic_report"></label>
                        </a>            
                        <a href="javascript:void(0)" class="RemoveHotelRate" data-type="supplier" data-id="{{$sup->id}}" title="Remove this 0 Number ?">
                          <label class="icon-list ic_remove"></label>
                        </a>                   
                      </td>                     
                    </tr>
                  @endforeach
                </tbody>
              </table> 
          </form>               
        </section>
      </div>
    </section>
  </div>  
</div>
<script type="text/javascript" src="{{asset('js/jquery.table2excel.min.js')}}"></script>
<script type="text/javascript">
  $(".ok_download").click(function(){
    console.log(1)
    var type = $(this).data('type');
    if (type == 'excel') {
      $(".excel-sheed").table2excel({
        exclude: ".noExl",
        name: "Supplier booked report by",
        filename: "Supplier booked report by",
        fileext: ".xls",
        exclude_img: true,
        exclude_links: true,
        exclude_inputs: true            
      });
      return false;
    }
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
      $(".datatable").DataTable();
    // $(".checkingAction").css({"display": "none"});
      $('input[type="checkbox"]').change(function(){
          var checkBotton = false;
          $(".checkall").each(function(i, v){
            if($(v).prop("checked") == true){
              checkBotton = true;
            }
          });

          if(checkBotton){
            $(".checkingAction").fadeIn();
          }else{
            $(".checkingAction").fadeOut();
          }
      });
  });
</script>

@endsection
