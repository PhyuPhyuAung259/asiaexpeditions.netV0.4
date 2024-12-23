@extends('layout.backend')
@section('title', "Client List")
<?php 
	use App\component\Content;
	$comadd = \App\Company::find(1); 
	$user = App\User::find($project->check_by);
	$Probooked = App\Booking::where(['book_project'=>$project->project_number, 'book_status'=>1, "book_option"=>0])->get();
	
	?>
@section('content')
<div class="container">
	<div class="row">
		<div class="col-lg-12">
			@include('admin.report.project_header')	 
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
             <h3 class="text-center border">Client List</h3>
        </div>
        <div class="col-md-2 pull-right">
                  <button style="border-radius: 50px;" class="btn btn-sm btn-default" onclick="exportReportToExcel(this)" >Download Excel<i class="fa fa-download"></i></button>
                  <!-- <button id="btnExport" onclick="exportReportToExcel(this)">EXPORT REPORT</button> -->

                </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="datatable table table-hover table-striped " id='table-client'>
                
                    <tr>
                        <th>No.</th>
                        <th>Client Name/s</th>
                        <th>Nationality</th>
                        <th>Passport No.</th>
                        <th>Date of Expiry</th>
                        <th>Date Of Birth</th>
                        <th>Arrival Flight</th>
                        <th>Departure Flight</th>
                    </tr>
                    
                    <?php $n=1; ?>
						@foreach($clientlist as $key=> $cl)
							<tr>
                                <td>{{$n++}}</td>
								<td>{{$cl->client_name}}</td>
								<td>{{{ $cl->country->nationality or ''}}}</td>
								<td>{{{ $cl->passport or ''}}}</td>
								<td>{{Content::dateformat($cl->expired_date)}}</td>
								<td>{{Content::dateformat($cl->date_of_birth)}}</td>
								<td>{{$cl->flight_arr}}</td>
								<td>{{$cl->flight_dep}}</td>
								
							</tr>
						@endforeach
                
            </table>
        </div>
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
  const table = document.getElementById("table-client");
  TableToExcel.convert(table, {
    name: 'export.xlsx',
    sheet: {
      name: 'Sheet 1'
    }
  });

}
</script>

@endsection