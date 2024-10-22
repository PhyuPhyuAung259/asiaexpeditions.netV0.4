@extends('layout.backend')
@section('title', 'Statement Report')
<?php
  $active = 'reports'; 
  $subactive = 'statement';
  use App\component\Content;
  $agent_id = isset($agentid) ?  $agentid:0;
  $locat = isset($location) ? $location:0;
  $main = isset($sort_main) ? $sort_main:0;
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
      <div class="row">
        <section class="col-lg-12 connectedSortable">
          <h3 class="border">Statement Report</h3>
          <form method="POST" action="{{route('searchStatement')}}">
            {{csrf_field()}}
            
            <div class="col-sm-8 pull-right">
              <div class="col-md-2">
                
                <input class="form-control input-sm" type="text" id="from_date" name="start_date" placeholder="From Date" value="{{{$startDate or ''}}}"> 
              </div>
              <div class="col-md-2" style="padding-right: 0px;">
                <input class="form-control input-sm" type="text" id="to_date" name="end_date" placeholder="To Date" value="{{{$endDate or ''}}}"> 
              </div>
              <div class="col-md-2" style="padding-right: 0px;">
              <select class="form-control input-sm" name="agent">
                  <option value="0">All Agent</option>
                  @foreach(App\Supplier::getSupplier(9)->whereNotIn('pro.project_fileno', ["","Null",0])->get() as $agent)
                  <option value="{{$agent->id}}" {{$agent->id == $agent_id ? 'selected':''}}>{{$agent->supplier_name}}</option>
                  @endforeach
                </select>
              </div>             
              <div class="col-md-2">
                <select class="form-control input-sm" name="sort_location">
                  
                  <option value="AM" {{$locat == 'AM' ? 'selected':''}}>AM</option>
                  <option value="AE" {{$locat == 'AE' ? 'selected':''}}>AE</option>
                </select>
              </div>
              <div class="col-md-2" style="padding: 0px;">
                <button class="btn btn-primary btn-sm" type="submit">Search</button>
              </div>       
              <div class="col-md-2 pull-right">
                  <button style="border-radius: 50px;" class="btn btn-sm btn-default" onclick="exportReportToExcel(this)" >Download Excel<i class="fa fa-download"></i></button>
                  <!-- <button id="btnExport" onclick="exportReportToExcel(this)">EXPORT REPORT</button> -->

                </div>
   
            </div>
            
            <table class="datatable table table-hover ">
              <thead>
                <tr>
                  <th width="48px" rowspan="2">File No.</th>
                  
                  <th class="text-center" rowspan="2">Reference No.</th>
                  <th rowspan="2">Client Name</th>
                  <th rowspan="2">No of Pax</th>
                  <th rowspan="2">Travel Consultant</th>
                  <th width="162px" rowspan="2">Start Date-End Date</th>
                  <th rowspan="2">Selling Rate</th>
                  <th class="text-center" rowspan="2">Cost_of_Sale</th>
                 
                 
                </tr>

              </thead>
              <tbody>
                <?php $toTalPax = 0; ?>
                @foreach($projects as $pro)
                  <?php 
                    $guideSupplier = App\BookGuide::where(['project_number'=>$pro->project_number])->groupBy('supplier_id')->orderBy("created_at")->get(); 
                    $toTalPax = $toTalPax + $pro->project_pax;
                    
                  ?>
                  <tr>
                    <td>{{$pro->project_prefix}}-{{$pro->project_fileno}}</td>
                    <td class="text-center">{{{$pro->project_book_ref or ''}}}</td> 
                    <td>{{$pro->project_client}} 
                      @if($pro->project_pax)
                        <span style="font-weight: 700; color: #3F51B5;">x {{$pro->project_pax}}</span>
                      @endif
                    </td> 
                    <td>{{{$pro->project_pax or ''}}}</td>
                    <td>{{{$pro->project_book_consultant or ''}}}</td>                
                    <td>
                      {{Content::dateformat($pro->project_start)}} - {{Content::dateformat($pro->project_end)}}
                    </td>
                    <td>{{{$pro->project_selling_rate or ''}}}</td>
                    <td class="text-right">{{{$pro->cost_of_sale or ''}}}</td>
                  </tr>
                @endforeach
              </tbody>
              <tfoot>
                  <tr><th colspan="8" class="text-right"><h3>Total Number of Pax: {{$toTalPax}}</h3></th></tr>
              </tfoot>
            </table>
          </form>
        </section>
      </div>
    </section>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $(".datatable").DataTable({
      language: {
        searchPlaceholder: "Number No., File No.",
      }
    });
   
  });
 
  function exportReportToExcel() {
  let table = document.getElementsByTagName("table"); // you can use document.getElementById('tableId') as well by providing id to the table tag
  TableToExcel.convert(table[0], { // html code may contain multiple tables so here we are refering to 1st table tag
    name: `export.xlsx`, // fileName you could use any name
    sheet: {
      name: 'Sheet 1' // sheetName
    }
  });
}
</script>


@include('admin.include.datepicker')
@endsection
 