@extends('layout.backend')
@section('title','Chart of Account')
<?php 
  $active = 'chartofaccount'; 
  $subactive ='chartofaccount';
  use App\component\Content;
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
       @include('admin.include.message') 
      <div class="row">
      <section class="content"> 
        <div class="row">
          <div class="col-md-12">
            <h3 class="border">Chart of Account<span class="fa fa-angle-double-right"></span>​​​ <a href="{{route('accForm')}}" class="btn btn-primary btn-sm">New Account</a>
              
            </h3>
          </div>
          <form method="POST" action="">            
            {{csrf_field()}}
           
            <section class="col-lg-12 connectedSortable">
             
              <table class="datatable table table-hover table-striped">
                <thead>
                  <tr>                       
                    <th >Account Code</th>
                    <th >Account Name</th>
                    <th>Account Type</th>
                    <th >Detail Type </th>
                    
                    <th class="text-center" style="width: 181px;">Operation</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($account as $acc)
                  
                  
                    <tr>
                      <td>{{$acc->account_code}}</td>
                      <td>{{$acc->account_name}}</td>
                      <td> <?php $acc_type = App\AccountType::where('id',$acc->account_type_id)->first();
                      ?>
                      {{$acc_type->account_name}}</td>
                      <td>{{$acc->account_desc}}</td>
                      <td>  <a target="_blank" href="{{route('editAccForm', ['id'=> $acc->id])}}" title="Edit Project">
                          <label style="cursor:pointer;" class="icon-list ic_book_project"></lable>
                        </a> 
                        <a href="{{route('removeAcc', ['id'=> $acc->id])}}" title="Remove Account">
                          <label style="cursor:pointer;" class="icon-list ic_remove"></lable>
                        </a> 
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table> 
              <!-- <div class="pull-left">Check All</div> -->
            </section>
          </form>
        </div>
    </section>
      </div>
    </section>
  </div>  
</div>
@include('admin.include.datepicker')
<script type="text/javascript">
  $(document).ready(function(){
 
    $(".datatable").DataTable({
    language: {
        searchPlaceholder: "Account Name"
    } ,  
    order: [[3, 'asc']]
  });
});
</script>
@endsection
