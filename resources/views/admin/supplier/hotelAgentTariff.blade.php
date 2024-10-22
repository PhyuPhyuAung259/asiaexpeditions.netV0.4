@extends('layout.backend')
@section('title','Hotel Tariff')
<?php  use App\component\Content;
		$title = 'hotel';

?>
@section('content')
	<div class="col-lg-12">
    
        @include('admin.report.headerReport')		
            <h3 class="text-center"><strong class="btborder" style="text-transform: uppercase;">{{$title}} Tariff As of ({{ \Carbon\Carbon::now()->format('Y-m-d') }})</strong></h3>
          
                <div class="col-md-12 pull-center hidden-print">
                        <form action="{{route('sortHotelTariff')}}" method="POST">
                            {{csrf_field()}}
                            @foreach ($hotelChecked as $hotelId)
                                <input type="hidden" name="supIds[]" value="{{ $hotelId }}">
                            @endforeach
                            <div class="col-md-12" style="padding-right: 0px;margin-bottom:5px;">
                                <div class="col-md-3"></div>
                                <div class="col-md-3">
                                    
                                    <div class="input-group">
                                        <select class="form-control input-sm" name="fmonth">
                                            <option value="">--Date--</option>
                                            <?php 
                                                $months = ["January","February","March", "April","May","June","July","August", "September","October","November", "December"];
                                            ?>
                                            @foreach($months as $key => $m)
                                                <option value="{{$key+1}}" {{(isset($fmonth)?$fmonth:'') == $key+1 ?'selected':''}}>{{$m}}</option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-addon">From & To</span>
                                        <select class="form-control input-sm" name="tmonth">
                                            <option value="">--Date--</option>
                                            
                                            @foreach($months as $key => $m)
                                                <option value="{{$key+1}}" {{(isset($tmonth)?$tmonth:'') == $key+1 ?'selected':''}}>{{$m}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                        <select class="form-control input-sm" name="year">
                                            <?php $plusYear = date('Y', strtotime('+10 years'));  ?>
                                            <option value="">---Years---</option>
                                            @for($y = 2015; $y <= $plusYear; $y++)
                                                <option value="{{$y}}" {{(isset($year)?$year == $y:'') ? 'selected':''}}>{{$y}}</option>
                                            @endfor
                                        </select>               
                                    <div class="clearfix"></div>
                                </div>	
                                <div class="col-md-2" style="padding-left: 0px;">
                                    <label><br></label>
                                    <button class=" btn btn-default btn-sm active">Query</button>
                                </div>	
                                <div class="pull-right hidden-print col-md-2">
                                    <a href="javascript:void(0)" onclick="window.print();"><span class="fa fa-print btn btn-primary"> Print</span></a>&nbsp;&nbsp;&nbsp;&nbsp;
                                </div>					
                            </div>
                        </form>
                
        	</div>
            @foreach($suppliers as $key => $supplier)          
                <table class="table" id="roomrate">
                    <tr><h4 style="text-transform: capitalize;"><strong>{{{ $supplier->country->country_name or '' }}} <i class="fa fa fa-angle-double-right"></i> {{{$supplier->province->province_name or ''}}} <i class="fa fa fa-angle-double-right"></i>  {{$supplier->supplier_name}} </strong></h4></tr>
                    <tr style="background-color: rgb(245, 245, 245);">
                        <th style="padding:2px; width: 20%;"><span>Room Type</span></td>
                        <th style="padding:2px;"><span>From Date</span> <span class="fa fa-long-arrow-right"
                                style="top: 1px; position: relative;"></span> <span>To Date</span></th>
                                @foreach (\App\RoomCategory::where('status', 1)->take(4)->orderBy('id', 'ASC')->get() as $cat)
                                    <th title="{{ $cat->name }}" style="padding: 2px;" class="text-right">
                                        <span>{{ $cat->name }}</span>
                                    </th>
                                @endforeach
                       
                    </tr>
                    @foreach ($supplier->room as $room)
                        @if (isset($fmonth) != '' && isset($tmonth) != '' && isset($year) != '')
                            <?php
                            $getRate = App\RoomRate::where(['supplier_id' => $supplier->id, 'room_id' => $room->id])
                                ->whereMonth('start_date', '>=', $fmonth)
                                ->whereMonth('start_date', '<=', $tmonth)
                                ->whereYear('start_date', $year)
                                ->orderBy('start_date', 'ASC')
                                ->get();
                            // dd($getRate);
                            ?>
                        @else
                            <?php
                            $getRate = App\RoomRate::where(['supplier_id' => $supplier->id, 'room_id' => $room->id])
                                ->orderBy('start_date', 'ASC')
                                ->get();
                            ?>
                        @endif
                        <tr>
                            <td style="vertical-align: middle; width: 20%;" colspan="{{ $getRate->count() == 0 ? '12' : '' }}"
                                rowspan="{{ $getRate->count() + 1 }}"><b>{{ $room->name }}</b></td>
                        </tr>
                        @foreach ($getRate as $rate)
                            <tr>
                                <td style="font-size:12px;" class="date-font">{{ Content::dateformat($rate->start_date) }} <i
                                        class="pcolor">-></i> {{ Content::dateformat($rate->end_date) }}</td>
                                <td class="text-right">{{ Content::money($rate->ssingle) }} <small
                                        class="pcolor">{{ $rate->ssingle > 0 ? Content::currency() : '' }}</small></td>
                                <td class="text-right">{{ Content::money($rate->stwin) }} <small
                                        class="pcolor">{{ $rate->stwin > 0 ? Content::currency() : '' }}</small></td>
                                <td class="text-right">{{ Content::money($rate->sdbl_price) }} <small
                                        class="pcolor">{{ $rate->sdbl_price > 0 ? Content::currency() : '' }}</small></td>
                                <td class="text-right">{{ Content::money($rate->sextra) }} <small
                                        class="pcolor">{{ $rate->sextra > 0 ? Content::currency() : '' }}</small></td>
                               
                            </tr>
                        @endforeach
                    @endforeach
                    <tr>
                        <td>
                            <strong>Remark</strong>
                            {{{$supplier->supplier_remark or ''}}}
                        </td>
                    </tr>
                @endforeach
                
            </table>    
    </div>
  	@include('admin.include.datepicker')
@endsection

