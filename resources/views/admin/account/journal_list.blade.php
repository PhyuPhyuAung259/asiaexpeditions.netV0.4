@extends('layout.backend')
@section('title', 'Journal Entry')
<?php 
  $active = 'finance/accountPayable/project-booked'; 
  $subactive = 'finance/journal';
  use App\component\Content;
  $countryId = Auth::user()->country_id ? Auth::user()->country_id : 0;
?>
@section('content')
  <style type="text/css">
    .table-head-row{
        background: -webkit-linear-gradient(top, #ffffff, #cccccc54);
      }
    #data_payment_option td{
      padding: 0px;
    }
    #data_payment_option tr td:last-child i:hover, #account_journal_data tr td:last-child i.btnRemoveOption:hover{
      color:#b81c1c;
      cursor: pointer;
    }
    #account_journal_data tr td:last-child i.fa-edit:hover{
      color: #3c8dbc;
    }
    #account_journal_data tr td:last-child i.fa-edit{
      color: #3c8dbc85;
      cursor: pointer;
    }
    .dataTables_wrapper div.row:first-child div.dataTables_filtesr {
      display: none;
    }
    #account_journal_data tr:hover {
        cursor: pointer;
    }
  </style>
  <div class="wrapper">
    @include('admin.include.header')
    @include('admin.include.menuleft')
    <div class="content-wrapper">
      <section class="content"> 
        @include('admin.include.message')
          <h3 class="border text-center" style="color:black;text-transform: uppercase;font-weight:bold;">Journal Entry</h3>
          <div class="container ">
            <div class="col-md-12">
              <div class="pull-left"> 
                <a style="color: #3c8dbc;" href="{{route('getJournalJson')}}" class="btn btn-default btn-acc"><i class="fa fa-plus-circle"></i> <b>New Journal</b></a> &nbsp;
                <a style="color: #3c8dbc;" href="{{route('getPayable')}}" class="btn btn-default btn-acc"><i class="fa fa-plus-circle"></i> <b>Make Payment</b></a> &nbsp;
                <a style="color: #3c8dbc;" href="{{route('getAccountReceivable')}}" class="btn btn-default btn-acc"><i class="fa fa-plus-circle"></i> <b>Receive Payment</b></a>
              </div>           
              <div class="clearfix"></div>
              
              <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="all">
                  <div class="searching">
                    <div id="search_form" style="padding: 6px;border: solid #d2d6de 1px; position: relative; background: #e8f1ff;margin: 12px 0px;border-radius: 3px;" class="alert alert-dismissible fade in" role="alert"> 
                      <button style="position: absolute;font-size:19px; right:0px; top:0px; background: none;border: none;" type="button" id="close"><span aria-hidden="true"><i class="fa fa-times-circle"></i> </span></button> 
                      <form method="GET" action="">
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Start Date</label>
                            <input type="text" name="from_date" class="form-control input-sm" id="from_date" value="{{{$_GET['from_date'] or ''}}}">
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <label>End Date</label>
                            <input type="text" name="to_date" class="form-control input-sm" id="to_date" value="{{{$_GET['to_date'] or ''}}}">
                          </div>
                        </div>
                        <div class="col-md-5">
                          <div class="form-group">
                            <label><br></label><div class="clearfix"></div>
                            <div class="pull-left">
                              <input type="text" name="search" class="form-control input-sm" placeholder="Type Project File / No." value="{{{ $_GET['search'] or ''}}}">
                            </div>
                            <div class="pull-left">
                              <button type="submit" class="btn btn-primary btn-sm btn-flat" id="btnSearchJournal">Search</button>
                            </div>
                            <span class="btn btn-link btn-sm" >Clear All</span>
                          </div>
                        </div> 
                      </form>
                      <div class="clearfix"></div>
                    </div>                    
                    <div style="border-top: solid 1px #ddd; margin-top: 11px; height:36px;padding: 6px 10px 0;background: -webkit-linear-gradient(top, #ffffff, #eee);">
                     <!--  <div class="pull-left queckly-tools">
                        <button class="btn btn-default btn-acc btn-xs disabled">Archived</button>
                        <button class="btn btn-default btn-acc btn-xs disabled">Receivable</button>
                        <button class="btn btn-default btn-acc btn-xs disabled">Payment</button>
                      </div> -->
                     <!--  <div class="pull-right">
                        <button class="btn btn-xs search_option" style="color:#3c8dbc; background: -webkit-linear-gradient(top , #f4f4f4, #eee);"><b>Search</b></button>
                      </div> -->
                    </div>
                    <div class="clearfix"></div>
                  </div>
                  <table class="table table-striped table-borderd table-hover datatable">
                    <thead>
                      <tr>
                        <th width="6px">
                          <div class="icon-checkbox">
                            <label class="label"><input type="checkbox" name=""></label>
                          </div>
                        </th>
                        <th width="10px">
                          <a href="#">Operation</a>
                        </th>
                        <th width="116px"><a href="#">P&L For Project</a></th>
                        <th class="text-left"><a href="#">Client Name</a></th>
                        <th class="text-right" width="120px"><a href="#">Debit</a></th>
                        <th class="text-right" width="120px"><a href="#">Credit</a></th>
                        <th class="text-right" width="120px"><a href="#">{{Content::currency(1)}} Debit</a></th>
                        <th class="text-right" width="120px"><a href="#">{{Content::currency(1)}} Credit</a></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($journalList as $acc)
                        <?php $acc_journal = \App\AccountJournal::where(['project_number'=> $acc->project_number, 'status'=>1]);
                          $acc_debit  = $acc_journal->sum("debit");
                          $acc_credit = $acc_journal->sum("credit");
                          $acc_kdebit  = $acc_journal->sum("kdebit");
                          $acc_kcredit = $acc_journal->sum("kcredit");
                          $pro = \App\Project::where('project_number', $acc->project_number)->first();
                          $supName = isset($acc->supplier->supplier_name)? $acc->supplier->supplier_name:'';
                          $proFix  = isset($pro->project_prefix)? $pro->project_prefix:'';
                          $proNo  = isset($acc->project_fileNo) > 0? $acc->project_fileNo: $pro->project_fileno;
                          
                          $pro_client = isset($pro->project_client)? $pro->project_client: '';
                        ?>
                        <tr>
                          <td><div class="icon-checkbox">
                            <label class="label"><input type="checkbox" name=""></label></div>
                          </td> 
                          <td>
                            <a target="_blank" href="{{route('getJournalReport', ['journal_entry' => $acc->project_number])}}"><label style="cursor:pointer; height: 16px; width: 16px;" class="icon-list ic_inclusion" title="Preview & Edit Journal Entry"> </label></a>
                            <a target="_blank" href="{{route('getJournalReport', ['outstanding' => $acc->project_number])}}"><label style="cursor:pointer; height: 16px; width: 16px;" class="icon-list ic_invoice_drop" title="Preview Outstanding "> </label></a>
                          </td>
                          <td>
                            <a target="_blank" href="{{route('getJournalReport', ['pandl_id'=> $acc->project_number])}}">
                            {{ $proFix .'-'.$proNo}}</a>
                          </td>
                          <td>{{{ $pro->project_client or ''}}}</td>
                          <td class="text-right"><a target="_blank" href="{{route('getJournalReport', ['journal_entry' => $acc->project_number])}}">{{Content::money($acc_debit)}}</a></td>
                          <td class="text-right"><a target="_blank" href="{{route('getJournalReport', ['journal_entry' => $acc->project_number])}}">{{Content::money($acc_credit)}}</a></td>
                          <td class="text-right"><a target="_blank" href="{{route('getJournalReport', ['journal_entry' => $acc->project_number])}}">{{Content::money($acc_kdebit)}}</a></td>
                          <td class="text-right"><a target="_blank" href="{{route('getJournalReport', ['journal_entry' => $acc->project_number])}}">{{Content::money($acc_kcredit)}}</a></td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="clearfix"></div><br><br>
      </section>
    </div>
  </div>
@include('admin.include.datepicker')
<script type="text/javascript">
  $(document).ready(function(){
    // $(".search_option").on("click", function(){
    //     $("#search_form").show();
    // });
    // $("#close").on("click", function(){
    //   $("#search_form").hide();
    // });


    var dataReplace = '<form method="GET" action=""><div class="col-md-2"><div class="form-group"><label>Start Date</label><input type="text" name="from_date" class="form-control input-sm" id="from_date" value="{{{$_GET["from_date"] or ''}}}"></div></div><div class="col-md-2"><div class="form-group"><label>End Date</label><input type="text" name="to_date" class="form-control input-sm" id="to_date" value="{{{$_GET["to_date"] or ''}}}"></div></div><div class="col-md-5"><div class="form-group"><label><br></label><div class="clearfix"></div><div class="pull-left"><input type="text" name="search" class="form-control input-sm" placeholder="Type Project File / No." value="{{{ $_GET["search"] or ''}}}"></div><div class="pull-left"><button type="submit" class="btn btn-primary btn-sm btn-flat" id="btnSearchJournal">Search</button></div><span class="btn btn-link btn-sm" >Clear All</span></div></div> </form>';
    // alert(dataReplace);
    
    // $(".dataTables_wrapper div.row:first-child").remove();
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
     $(".datatable").DataTable();
  });
</script>
@include("admin.account.accountant")
@endsection

