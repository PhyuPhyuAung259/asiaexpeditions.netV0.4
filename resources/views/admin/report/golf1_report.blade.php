@extends('layout.backend')
@section('title', 'Golf Report')
<?php
  $active = 'reports'; 
  $subactive = 'tour_report';
  use App\component\Content;
  $total_round=0;
  $grand_total=0;
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
      <div class="row">
        <section class="col-lg-12 connetedSortable">
          <h3 class="border">Golf Report</h3>
          <form method="POST" action="{{route('searchReport')}}">
            {{csrf_field()}}
            <div class="col-sm-8 pull-right">
              <div class="col-md-2">
                
                <input class="form-control input-sm" type="text" id="from_date" name="start_date" placeholder="From Date" value="{{{$startDate or ''}}}"> 
              </div>
              <div class="col-md-2" style="padding-right: 0px;">
                <input class="form-control input-sm" type="text" id="to_date" name="end_date" placeholder="To Date" value="{{{$endDate or ''}}}"> 
              </div>
                        
              <div class="col-md-2" style="padding-right: 0px;">
                <select class="form-control input-sm" name="country" id="country">
                  <option value="0">Location</option>
                  @foreach(App\Country::where('country_status',1)->get() as $country)
                  <option value="{{$country->id}}">{{$country->country_name}}</option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-2" id="hotels" style="padding-right: 0px; display:none;">
              <select class="col-md-2 form-control input-sm  " name="hotel" id="hotel"  >
                  <option value="0">Hotel</option>
                </select>
              </div>
                 
             
              <div class="col-md-2" style="padding-right: 0px;" >
                <select class="form-control input-sm" name="type" id="type"  onchange="enableHotel(this)">
                    <option value="0">Type</option>
                    <option value="1">Tour</option>
                    <option value="2">Golf</option>
                    <option value="3">Hotel</option>
                </select>
              </div>

              <div class="col-md-1 ml-5" style="padding: 0px;margin-left:10px;">
                <button class="btn btn-primary btn-sm" type="submit">Search</button>
              </div>   
                
            </div>  
            <div class="col-sm-1 pull-right checkingAction" style="display: none;">
                <button class="btn btn-sm btn-primary btn_acc" name="viewType" value="view3"> View Agent Tariff</button>
              </div>

            <table class="datatable table table-hover table-striped">
            @if(!empty($golf))
                @foreach($golf as $golf_data)
          
              <thead>
                <tr>
                 
                <th colspan="9" align="left"><font>{{$golf_data->supplier_name}}</font> From {{Content::dateformat($startDate)}} -> {{Content::dateformat($endDate)}}</th>
                        </tr> 
                <tr style="background-color: #ddd">
                  <td width="86">File No.</td>
                  <td>Client Name</td>
                  <td>Start Date</td>
                  <td>Golf Service</td>
                  <td class="text-center">Pax</td>
                  <td class="text-right">Price</td>
                  <td class="text-right">Amount</td>
                  <td class="text-right">Price {{Content::currency(1)}}</td>
                  <td class="text-right">Amount {{Content::currency(1)}}</td>
                </tr>
              </thead>
              <tbody>
              <?php  $bookeds = App\Booking::where(['book_status'=>1, 'golf_id'=>$golf_data->id])
                ->whereHas('project', function($query) {
                    $query->whereNotIn('project_fileno', ['', 0, 'Null']);
                })                
                ->whereBetween('book_checkin', [$startDate, $endDate])->orderBy('book_checkin')->get(); 
                ?>
                    @foreach($bookeds as $key => $sub )
                      <?php $project = App\Project::where('project_number',$sub->book_project)->first(); 
                      $gsv = App\GolfMenu::find($sub->program_id);?>
                      <tr>
                          <td>{{$project['project_prefix']}}-{{$project['project_fileno']}}</td>
                          <td>{{$project['project_client']}} <small><b>x</b></small> <i>[ {{$project['project_pax']}} ]</i></td>
                          <td>{{Content::dateformat($sub->book_checkin)}}</td>                    
                          <td>{{$gsv['name']}}</td>
                          <td class="text-center">{{$sub->book_pax}}</td>
                          <td class="text-right">{{Content::money($sub->book_nprice)}}</td>
                          <td class="text-right">{{Content::money($sub->book_namount)}}</td>
                          <td class="text-right">{{Content::money($sub->book_kprice)}}</td>
                          <td class="text-right">{{Content::money($sub->book_kamount)}}</td>
                          <?php $total_round=$total_round+ $sub->book_pax;
                            $grand_total=$grand_total+$bookeds->sum('book_namount');
                          ?>
                      </tr>
                      </tbody>
                      
                    @endforeach  
              
                @endforeach
                <tfoot>
            <tr style="border: solid 1px #ddd;">
                <td colspan="5" align="right">
                    <font color="#1991d6">
                    Total Round :  {{$total_round}}
                    </font>
                 
                </td>
                <td colspan="2" align="right">
                    <font color="#1991d6">
                    Grand Total :  {{$grand_total}}
                    </font>
                </td>
               
            </tr>
        </tfoot>  
              @endif
               
            </table>
          </form>
        </section>
      </div>
    </section>
  </div>
</div>
<script type="text/javascript">
    function enableHotel(selectElement){
   console.log(selectElement.value);
   var hotelElement=document.getElementById('hotels');
   if(selectElement.value === '3'){
    hotelElement.style.display='block';
   }else{
    hotelElement.style.display='none';
   }
  };
  $(document).ready(function(){
    $(".datatable").DataTable({
      language: {
        searchPlaceholder: "Number No., File No.",
      },
       order: [[3, 'desc']]
    });
    $('#country').change(function() {
      var countryId = $(this).val();
        $.ajax({
          url: '/hotels/' + countryId,
          type: 'GET',
          dataType: 'json',
          success: function(response) {
              var hotelSelect = $('#hotel');
              hotelSelect.empty();
              if (response.length === 0) {
                  hotelSelect.append('<option value="0">No hotels available</option>');
              } else {
                hotelSelect.append('<option value="0">Choose Hotel</option>');
                   $.each(response, function(key, value) {
                      hotelSelect.append('<option value="' + value.id + '">' +
                          value.supplier_name + '</option>');
                  });
              }
          }
       });               
    });
    
  });     

  $(document).ready(function(){
    
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
@include('admin.include.datepicker')
@endsection
