@extends('layout.backend')
@section('title', 'Receivable Form')
@section('active', 'active')
<?php 
  $active = 'general_ledger'; 
  $subactive = 'account';
  use App\component\Content;
  $countryId = Auth::user()->country_id ? Auth::user()->country_id : 0;
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
      <h3 class="border text-center" style="color:black;text-transform:uppercase;font-weight:bold;">General Ledger</h3>
      <table class=" table table-hover table-striped">
        <tr>
          <th>No</th>
          <th>Item No</th>
          <th>Description</th>
          <th>Qty Ordered</th>
          <th>Qty Received</th>
          <th>Unit Cost</th>
          <th>Account</th>
          <th>Amount</th>
          <th class="text-center">Options</th>
        </tr>           
        <tbody>
          @foreach($ledgers as $lg)
          <tr>
            <td>{{$lg->project_number}}</td>
            <td></td>                      
            <td></td>
            <td></td> 
            <td></td>                      
            <td></td>
            <td></td>
            <td></td>
            <td class="text-right">            
              <a href="#" title="Edit County">
                <label class="icon-list ic_book_project"></label>
              </a>
              <a href="javascript:void(0)">
                <label class="icon-list ic-trash"></label>
              </a>
            </td>                     
          </tr>
          @endforeach
        </tbody>
      </table>
    </section>
  </div>
</div>
@include('admin.include.datepicker')

@endsection
