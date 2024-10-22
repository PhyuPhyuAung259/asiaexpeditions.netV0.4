@extends('layout.backend')
@section('title', 'Client Arrival Report')
<?php
  $active = 'reports'; 
  $subactive = 'arrival_report';
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
          <h3 class="border">Quotation</h3>
            <table class="datatable table table-hover table-striped">
              <thead>
                <tr>
                  <th width="65px">File No.</th>
                  <th>Client Name</th>
                  <th>Agent</th>
                  <th class="text-center">PaxNo</th>
                  <th>Flight Arrival</th>
                  <th>Flight Departure</th>
                  <th>Start Date-End Date</th>
                  <th>User</th>
                  <th class="text-center">Preview</th>
                  <!-- <th class="text-center">Status</th> -->
                </tr>
              </thead>
              <tbody>
                @foreach($projects as $pro)
                <tr>
                  <td>{{$pro->project_prefix}}-{{$pro->project_fileno}}</td>
                  <td>{{$pro->project_client}}</td>         
                  <td>{{{$pro->supplier->supplier_name or ''}}}</td>     
                  <td class="text-center">{{$pro->project_pax}}</td> 
                  <td>
                    @if(isset($pro->flightArr->flightno))
                      {{{ $pro->flightArr->flightno or ''}}}-D:{{{$pro->flightArr->dep_time or ''}}}->A:{{{$pro->flightArr->arr_time or ''}}}
                    @endif
                  </td>   
                  <td>
                    @if(isset($pro->flightDep->flightno))
                      {{{ $pro->flightDep->flightno or ''}}}-D:{{{$pro->flightDep->dep_time or ''}}}->A:{{{$pro->flightDep->arr_time or ''}}}
                    @endif
                  </td>          
                  <td>
                    {{Content::dateformat($pro->project_start)}} - {{Content::dateformat($pro->project_end)}}
                  </td>
                  <td><span style="text-transform: capitalize;">{{{ $pro->user->fullname or ''}}}</span></td>

                  <td class="text-right">                      
                   <a target="_blank" href="{{route('previewProject', ['project'=>$pro->project_number, 'type'=>'operation'])}}" title="Operation Program">
                      <label class="icon-list ic_ops_program"></label>
                    </a>
                    <a target="_blank" href="{{route('previewProject', ['project'=>$pro->project_number, 'type'=>'sales'])}}" title="Prview Details">
                        <label class="icon-list ic_del_drop"></label>
                    </a>     
                    <a target="_blank" href="{{route('getInvoice', ['prject'=>$pro->project_number, 'type'=> 'invoice'])}}" title="View Invoice">
                        <label class="icon-list ic_invoice_drop"></label>
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

@endsection
