@extends('layout.backend')
@section('title', 'Golf Service ') 
<?php
  $active = 'restaurant/menu'; 
  $subactive = 'golf/service';
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
          <h3 class="border">Golf Services <span class="fa fa-angle-double-right"></span> <a href="#" class="btn btn-default btn-sm" id="addMiscService" data-toggle="modal" data-target="#myModal">Add Service</a></h3>
            <table class="datatable table table-hover table-striped">
              <thead>
                <tr> 
                  <th>Golf Service</th>
                  <th>Golf Names</th>
                  <th>Price {{Content::currency()}}</th>
                  <th>Price Net {{Content::currency()}}</th>
                  <th>Price {{Content::currency(1)}}</th>
                  <th>Price Net {{Content::currency(1)}}</th>
                  <th class="text-center">Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($gservice as $rest)
                <tr>
                  <td>{{$rest->name}}</td>
                  <td>{{{$rest->supplier->supplier_name or ''}}}</td>                   
                  <td class="text-right">{{Content::money($rest->price)}}</td>
                  <td class="text-right">{{Content::money($rest->nprice)}}</td>
                  <td class="text-right">{{Content::money($rest->kprice)}}</td>
                  <td class="text-right">{{Content::money($rest->nkprice)}}</td>
                  <td class="text-right">                      
                    <button data-entrance_id="{{$rest->supplier_id}}" data-entrance="{{{$rest->supplier->supplier_name or ''}}}" style="padding:0px; border:none; top: -5px; position: relative;" class="miscEdit" data-province="{{{$rest->supplier->id or ''}}}" data-id="{{$rest->id}}"  data-title="{{$rest->name}}" data-price="{{$rest->price}}" data-nprice="{{$rest->nprice}}" data-kprice="{{$rest->kprice}}" data-nkprice="{{$rest->nkprice}}" data-toggle="modal" data-target="#myModal">
                      <i style="padding:1px 2px;" class="btn btn-info btn-xs fa fa-pencil-square-o"></i>
                    </button>
                    <a href="javascript:void(0)" class="RemoveHotelRate" data-type="golf_service" data-id="{{$rest->id}}" title="Remove this?">
                      <label  class="icon-list ic-trash"></label>
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

<div class="modal fade" id="myModal" role="dialog"  data-backdrop="static" data-keyboard="true">
  <div class="modal-dialog modal-lg">    
    <form method="POST" action="{{route('addGolfService')}}">
      <div class="modal-content">        
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><strong>Add Golf Service</strong></h4>
        </div>
        <div class="modal-body">
          {{csrf_field()}}    
          <input type="hidden" name="eid" id="eid">
          <div class="row">
            <?php $getGolf = App\Supplier::where(['business_id'=>29, 'supplier_status'=>1])->orderBy('supplier_name', 'ASC')->get() ?>
            <div class="col-md-6 col-xs-6">
                <div class="form-group">
                  <label>Golf Name</label> 
                  <div style="position: relative;">
                      <div class="dropdown-toggle form-control wrapper-filter-item" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative;">
                          <span style="position: absolute;right: 11px;padding: 4px;"><i class="fa fa-sort-up"></i></span>
                        <ul id="MainUl" class="list-unstyled" style="display: flex;">
                        
                        </ul>
                      </div>
                      <ul class="dropdown-menu wrapper-ulist-item" style="width: 100%">
                        <div style="padding-top: 0px; padding: 12px;">      
                        <span>
                          <input style="border-radius: 20px;" id="searchGolfName" type="text" class="form-control" onkeyup="golfFilter()" placeholder="Type for search..">
                        </span>    
                        <div style="max-height: 218px; overflow: auto;">                
                          <ul id="golf" class="list-unstyled" style="padding-top: 5px;">
                              @foreach($getGolf as $key => $cl)                            
                                <li class="ulList" data-id="{{$cl->id}}" data-fieldname="golf_name"
                                  data-name="{{$cl->supplier_name}}">
                                  <span>{{$cl->supplier_name}}</span>
                                </li>                   
                              @endforeach     
                          </ul>
                        </div> 
                        <div><a href="{{route('getSupplierForm', ['type'=>'golf'])}}"><i class="fa fa-plus"></i> Add New</a></div>
                      </div>
                      </ul>
                  </div>
              </div>
            </div>
            <div class="col-md-6 col-xs-12">
              <div class="form-group">
                <label>Service Name</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Service Name" required="">
              </div>
            </div>
            <div class="col-md-3 col-xs-6">
              <div class="form-group">
                <label>Price {{Content::currency()}}</label>
                <input type="text" name="price" id="price" class="form-control number_only" placeholder="00.0">
              </div>
            </div>
            <div class="col-md-3 col-xs-6">
              <div class="form-group">
                <label>Price Net {{Content::currency()}} </label>
                <input type="text" name="nprice" id="nprice" class="form-control number_only" placeholder="00.0">
              </div>
            </div>
            <div class="col-md-3 col-xs-6">
              <div class="form-group">
                <label>Price {{Content::currency(1)}}</label>
                <input type="text" name="kprice" id="kprice" class="form-control number_only" placeholder="00.0">
              </div>
            </div>
            <div class="col-md-3 col-xs-6">
              <div class="form-group">
                <label>Price Net {{Content::currency(1)}}</label>
                <input type="text" name="nkprice" id="nkprice" class="form-control number_only" placeholder="00.0">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="text-align: center;">
          <button type="submit" class="btn btn-success btn-flat btn-sm" id="btnEntrance">Confirm</button>
          <a href="#" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal">Cancel</a>
        </div>
      </div>      
    </form>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
     $(".datatable").DataTable();

    $("ul.wrapper-ulist-item ul li").on('click', function(){
      $(this).closest('ul div div ').closest('ul.wrapper-ulist-item').closest('div').find('ul#MainUl').html('<li><input type="hidden" name="'+ $(this).data('fieldname') +'" value="'+$(this).data('id')+'"><span>'+$(this).data('name')+'</span></li>');
    });
  });


  function golfFilter() {
    input = document.getElementById("searchGolfName");
    filter = input.value.toUpperCase();
    ul = document.getElementById("golf");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) {
      a = li[i];
      if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
        li[i].style.display = "";
      } else {
        li[i].style.display = "none";
      }
    }   
  }
</script>


<style type="text/css">
  .selectAuto{
    padding-top: 0px;
      padding: 12px;
      background-color: #fff;
      border: 1px solid #0000001f;
      border-radius: 4px;
      -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
      box-shadow: 0 1px 1px rgba(0,0,0,.05);
  }
  .wrapper-filter-item ul li{
    padding: 0px 10px 3px 10px;
      border: solid 1px #ddd;
      border-radius: 3px;
      background-color: #dddddd2e;
  }
  .wrapper-ulist-item ul li{
    padding: 6px 4px;
    transition: transform 300ms cubic-bezier(0.075, 0.132, 0.165, 1);
  }
  .wrapper-ulist-item ul li:hover{
      color: #fff;
      background-color: #337ab7;
      border-color: #2e6da4;
  }
</style>
@endsection
