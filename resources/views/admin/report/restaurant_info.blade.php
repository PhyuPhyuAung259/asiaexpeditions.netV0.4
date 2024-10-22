<?php  use App\component\Content;?>
@extends('layout.backend')
@section('content')
<div class="container">
    @include('admin.report.headerReport')   
  <a href="javascript:void(0)" class="pull-right" onclick="window.print();"><span class="btn btn-primary btn-xs"><i class="fa fa-print"></i></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
  <table class="table table-bordered" id="roomrate">
    @foreach($suppliers as $key => $supplier)
      <hr>
      <div class="pull-left">
        <h4 style="text-transform: capitalize;"><strong>{{{ $supplier->country->country_name or '' }}} <i class="fa fa fa-angle-double-right"></i> {{{$supplier->province->province_name or ''}}} <i class="fa fa fa-angle-double-right"></i>  {{$supplier->supplier_name}} </strong></h4>
      </div>
      <table class="table" id="roomrate">
        <thead style="background-color: rgb(245, 245, 245); font-weight: 600;">
          <tr>
             <td style="padding: 8px;" width="30">No.</td> 
            <td style="padding: 8px;">Menu</td>
            <td style="padding: 8px;">Price US</td> 
            <td style="padding: 8px;">Price Kyat</td> 
          </tr>
        </thead>
        <tbody>
          <?php $data = App\RestaurantMenu::where('supplier_id', $supplier->id)->get(); ?>
          <?php $key = 1; ?>
          @foreach($data as $key => $rest)
            <?php $key++; ?>
            <tr style="border: 1px solid #eee;">
              <td class="text-center">{{$key}}</td>
              <td style="padding: 8px;">{{$rest->title}}</td>
              <td style="padding: 8px;">{{$rest->price}} <span class="pcolor">{{Content::currency()}}</span></td>
              <td style="padding: 8px;">{{$rest->kprice}} <span class="pcolor">{{Content::currency(1)}}</span></td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <table class="table" id="roomrate">
        <thead style="background-color: rgb(245, 245, 245); font-weight: 600;">
          <tr>
            <td style="padding: 8px;">Info</td>
            <td style="padding: 8px;">Remark</td> 
            <td style="padding: 8px;">Hightlight</td> 
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="padding: 8px; width: 25%;">  
              <address>
                <b>P/H :</b> {{ $supplier->supplier_phone}}/{{$supplier->supplier_phone2}}<br>
                <b>Email :</b> {{$supplier->supplier_email}}<br>
                <b>Address :</b> {{$supplier->supplier_address}}<br>
                <b>Website :</b> {{$supplier->supplier_website}}</p>
              </address>
            </td>
            <td style="padding: 8px; width: 30%;">
              {{$supplier->supplier_remark}}
            </td>
            <td style="padding: 8px; width: 45%;">
              {!!$supplier->supplier_intro!!}
            </td>
          </tr>
        </tbody>  
      </table>
    @endforeach
  </table>
</div>
@endsection