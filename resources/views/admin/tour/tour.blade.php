@extends('layout.backend')
@section('title', 'Tours List')
<?php $active = 'tours'; 
  $subactive ='tours';
  use App\component\Content;
?>
@section('content')
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
          <section class="col-lg-12 connectedSortable">
            <h3 class="border">Tours List <span class="fa fa-angle-double-right"></span> <a href="{{route('tourForm')}}" class="btn btn-default btn-sm">New Tour</a></h3>
            
            <form action="" method="">
              <div class="col-sm-2 col-xs-6 pull-right" style="text-align: right; position: relative; z-index: 2;">
                <label class="location">
                  <select class="form-control input-sm locationchange" name="location">
                    @foreach(\App\Country::getCountryTour() as $loc)
                      <option value="{{$loc->id}}" {{$locationid == $loc->id ? 'selected':''}}>{{$loc->country_name}}</option>
                    @endforeach
                  </select>
                </label>
              </div>
             
            </form>
            <form target="_blank" action="{{route('getHotelinfo')}}" method="GET">
              <div class="col-sm-1 pull-right checkingAction" style="display: none;">
                <button class="btn btn-sm btn-primary btn_acc" name="viewType" value="view3"> View Agent Tariff</button>
              </div>

              <table class="datatable table table-hover table-striped">
                <thead>
                  <tr>
                    <th style="vertical-align: middle;">
                          <label class="container-CheckBox">
                            <input type="checkbox" id="check_all" >
                            <span class="checkmark hidden-print" ></span>
                          </label>
                    </th>
                    <th style="width: 12px;">Photo</th>
                    <th>TourName</th>
                    <th width="170">TourType</th>
                    <th>Website</th>
                    <th>City</th>
                    <th>Published</th>
                    <th>Pax&Price</th>
                    <th width="120" class="text-center">Options</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($tours as $tour)                 
                  <tr>
                    <td style="vertical-align: middle;">
                            <label class="container-CheckBox" >
                                <input type="checkbox"  id="check_all" class="checkall"  name="tour_checked[]" value="{{$tour->id}}">
                                <span class="checkmark hidden-print"></span>
                            </label>
                    </td>
                    <td><img src="{{Content::urlthumbnail($tour->tour_photo, $tour->user_id)}}" style="width: 100%"></td>
                    <td>{{$tour->tour_name}}</td>
                    <td>
                      @foreach($tour->categories as $busin)
                        <label class="label label-default label-xs">{{$busin->name}}</label>
                      @endforeach
                    </td>
                    <td> <span class="badge text-primary">{{ $tour->web == 1 ? 'Yes' : 'No' }}</span></td>
                    <td>{{{ $tour->province->province_name or '' }}}</td>
                    <td>{{ Content::dateformat($tour->updated_at) }}</td>
                    <td class="text-center">
                      <span class="badge">{{$tour->pricetour->count()}}</span>
                    </td>                  
                    <td class="text-right">                      
                      <a href="{{route('getTourUpdate', ['url'=> $tour->id])}}" title="Edit Tour">
                        <label src="#" class="icon-list ic_edit_tour"></label>
                      </a>
                      <a href="{{route('getTourPrice', ['url'=> $tour->id])}}" title="Add Tour Pax & Price">
                        <label src="#" class="icon-list ic_tour_addprice"></label>
                      </a>
                      <a href="{{route('getTourPriceEdit', ['url' => $tour->id])}}" title="Edit Tour Pax & Price">
                        <label src="#" class="icon-list ic_tour_editprice"></label>
                      </a>
                      <a target="_blank" href="{{route('getTourReport', ['url'=> $tour->id, 'type'=> 'selling'])}}" title="View Tour Report Selling Price">
                        <label src="#" class="icon-list ic_report_selling"></label>
                      </a>
                      <a target="_blank" href="{{route('getTourReport', ['url'=> $tour->id, 'type'=> 'net'])}}" title="View Tour Report Net Price">
                        <label src="#" class="icon-list ic_report_net"></label>
                      </a>
                      {!! Content::DelUserRole("Delete this tour ?", "tour", $tour->id, $tour->user_id ) !!}    
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
<script type="text/javascript">
  $(document).ready(function(){
     $(".datatable").DataTable();
  });

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
