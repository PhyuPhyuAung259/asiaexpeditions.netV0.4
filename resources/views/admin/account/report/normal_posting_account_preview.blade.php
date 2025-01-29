@extends('layout.backend')
@section('title',$project['project_number']." - Preview Post Account")
    <?php 
        use App\component\Content;
        $us = App\User::find($project['check_by']);
    ?>
    @section('content')
    <style type="text/css">
		.badge{
			line-height: 1.5 !important;
		}
		.modal {
	    background: rgba(0, 0, 0, 0);
		}
		.modal-open .modal {
		    overflow-x: hidden;
		    overflow-y: none !important;
		}
	</style>
    <div class="container-fluid">
        <div class="col-lg-12">
            @include('admin.report.project_header')
            <div class="row">
                @include("admin.include.message")
            </div>
            <div class="pull-left">
                <p>
                    <b>CRD:</b> {{isset($project->project_date) ? Content::dateformat($project->project_date) :''}}, 
					@if( isset($project->project_check) )
					Project No. <b>{{$project->project_number}}</b> is Already checked by <b>{{ $us['fullname']}}</b> at {{Content::dateformat($project->project_check_date)}},
					@endif
					<b>Revised Date</b> {{{ $project->project_revise or ''}}}
				</p> 
            </div>
            <div class="clearfix"></div>
            <!-- Start -->
            @if(isset($project->project_number))
                <form method="GET" target="_blank" action="{{route('requestReport', ['url'=> $project->project_number])}}">
                    <table class="table table-bordered">
                        <?php 
                            $totalCruise=0;
                            $totalHotel=0;
                            $hotelBook  = App\HotelBooked::where('project_number',$project->project_number)->get();
                            $cruiseBook = App\CruiseBooked::where('project_number', $project->project_number)->get();
                            $tourBook   = App\Booking::tourBook($project->project_number)->get();
						    $flightBook = App\Booking::flightBook($project->project_number)->get();
						    $golfBook   = App\Booking::golfBook($project->project_number)->get();
                            $grandtotal = $cruiseBook->sum('sell_amount') + $golfBook->sum('book_amount') + $flightBook->sum('book_amount') + $hotelBook->sum('sell_amount') + $tourBook->sum('book_amount');
                            if (empty((int)$project->project_selling_rate)) {
                                $Project_total = $grandtotal;
                            }else{
                                $Project_total = $project->project_selling_rate;
                            }
                            $totalRevenue = ($Project_total + $project->project_add_invoice) - $project->project_cnote_invoice; 
                        ?>
                        @if(!empty($totalRevenue))
                            <tr style="background-color:#f4f4f4;">
                                <th style="border-top: none;" width="170px">Travelling Date</th>
                                <th style="border-top: none;" colspan="9">Descriptions</th>
                                <th style="border-top: none; width: 17%;" colspan="2" class="text-left">INV-Amount <span class="pull-right">Received</span></th>
                            </tr>
                            <tr>
                                <td>{{ Content::dateformat($project->project_start) }} -> {{ Content::dateformat($project->project_end) }}</td>
                                <td colspan="9">{!! $project->project_desc !!}</td>
                                <td class="" colspan="2">
                                        <?php
                                            $proJournal = App\AccountJournal::where(['supplier_id'=>$project->supplier_id, 'business_id'=>9,'project_number'=>$project->project_number, "book_id"=>$project->id, 'status'=>1])->first();
                                            if(isset($proJournal)){
                                                $accTransaction = App\AccountTransaction::where(['journal_id'=> $proJournal->id, 'account_type_id'=>8, 'status'=>1]);
                                                $color = $accTransaction->sum('debit') == $totalRevenue ? "style=font-weight:700;color:#3c8dbc" : '';
                                            }else{
                                                $color=0;
                                                $accTransaction='';
                                            }
                                            ?>
                                        @if(!empty($accTransaction))
                                            <span class="pull-left" {{$color}}> {{ $totalRevenue == $accTransaction->sum('debit') ? Content::money($totalRevenue) : Content::money($totalRevenue - $accTransaction->sum('debit')) }}</span>
                                        @else
                                            {{Content::money($totalRevenue)}}
                                        @endif

                                    @if(isset($proJournal['book_amount']) && $proJournal['book_amount'] > 0)							
                                        <span class="badge badge-light pull-right">{{ Content::money($accTransaction->sum('debit')) }}</span>
                                        @if ($proJournal['book_amount'] > $accTransaction->sum('debit'))
                                            <a target="_blank" href="{{route('getPayable', ['journal_id'=> $proJournal->id])}}" class="btn btn-info btn-xs hidden-print pull-right"><b>Receive </b></a>                                            <span class="btn btn-link btn-xs hidden-print pull-right">
                                                    <a target="_blank" href="{{route('getJournalReport', ['journal_entry' => $project->project_number])}}">View/Edit</a>
                                                </span>   
                                        @elseif( $totalRevenue >= $proJournal['book_amount'])
                                            <span class="btn btn-link btn-xs hidden-print pull-right">
                                                <a target="_blank" href="{{route('getJournalReport', ['journal_entry' => $project->project_number])}}">View/Edit</a>
                                            </span>
                                        @endif
                                    @else
                                        @if($totalRevenue > 0)
                                            <span data-toggle="modal" data-target="#loadProjectConfirm" class="btn btn-success btn-xs AddToACcount hidden-print pull-right"
                                                data-type="9" data-kamount="" 
                                                data-amount="{{$totalRevenue}}" 
                                                data-process_type="receive" 
                                                data-bus_type="agent"
                                                data-title="{{{ $project->supplier->supplier_name or ''}}}"
                                                data-book_id="{{$project->id}}"
                                                data-supplier="{{ $project->supplier_id }}"  
                                                data-country="{{ $project->country_id}}">Post</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endif
                        @if(!empty($project->project_net_price))
                            <tr style="background-color:#f4f4f4;">
                                <th style="border-top: none;" width="170px"></th>
                                <th style="border-top: none;" colspan="9"></th>
                                <th style="border-top: none;" colspan="2"><span class="pull-right">Net Cost</span></th>
                            </tr>	
                            <tr>
                                <td>{{ Content::dateformat($project->project_start)}} -> {{ Content::dateformat($project->project_end) }}</td>
                                <td colspan="9">{{{ $project->project_desc or '' }}}</td>
                                <td class="text-center" colspan="2">
                                    <?php
                                        $proJournal = App\AccountJournal::where(['supplier_id'=>$project->supplier_agent, 'business_id'=>9,'project_number'=>$project->project_number, "book_id"=>$project->id, 'status'=>1])->first();
                                        $accTransaction = App\AccountTransaction::where(['journal_id'=> $proJournal['id'], 'account_type_id'=>8, 'status'=>1]);
                                        $color = $accTransaction->sum('debit') == $project->project_net_price ? "style=font-weight:700;color:#3c8dbc" : '';
                                    ?>
                                    <span class="pull-left" {{$color}}> 
                                    {{ $project->project_net_price == $accTransaction->sum('credit') ? Content::money($project->project_net_price) : Content::money($project->project_net_price - $accTransaction->sum('credit')) }}
                                </span>
                                    @if( $proJournal['book_amount'] > 0 ) 
                                        
                                        <span class="badge badge-light pull-right">{{{ Content::money($accTransaction->sum('credit')) or '' }}}</span>

                                        @if ($proJournal['book_amount'] > $accTransaction->sum('credit'))
                                            <a target="_blank" href="{{route('getPayable', ['journal_id'=> $proJournal->id])}}" class="btn btn-info btn-xs hidden-print pull-right"><b>Receive</b></a>

                                            <span class="btn btn-link btn-xs hidden-print pull-right">
                                                <a target="_blank" href="{{route('getJournalReport', ['journal_entry' => $project->project_number])}}">View/Edit</a>
                                            </span>
                                            
                                        @elseif( $project->project_net_price >= $proJournal['book_amount'])
                                            <span class="btn btn-link btn-xs hidden-print pull-right">
                                                <a target="_blank" href="{{route('getJournalReport', ['journal_entry' => $project->project_number])}}">View/Edit</a>
                                            </span>
                                        @endif
                                    @else
                                        @if($project->project_net_price > 0)
                                        <span data-toggle="modal" data-target="#loadProjectConfirm" class="btn btn-success btn-xs AddToACcount hidden-print pull-right"
                                            data-type="9" data-kamount="" 
                                            data-amount="{{$project->project_net_price}}" 
                                            data-process_type="pay" 
                                            data-bus_type="agent"
                                            data-title="{{{ $project->supplier_agent->supplier_name or ''}}}"
                                            data-book_id="{{$project->id}}"
                                            data-supplier="{{ $project->supplier_agent }}" 
                                            data-country="{{ $project->country_id}}">Post</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endif
                        <!-- Hotel Start -->
                        <?php 
                            $hotelBook = App\HotelBooked::where(['project_number'=>$project->project_number, 'status'=>1])->orderBy("checkin")->get();
                        ?>
                        @if($hotelBook->count()>0)
                            <tr class="hotel">
                                <th style="border-top:none;" colspan="11"><strong>Hotel OverView</strong></th>
                            </tr>    
                            <tr style="background-color:#f4f4f4;">
                                <th width="200px">Checkin -> Checkout</th>
                                <th colspan="5" width="200px">Hotel</th>
                                <th colspan="2" width="120px">Room</th>
                                <th width="110px">No. Room</th>
                                <th class="text-center">Nights</th>
                                <th class="text-left" width="290px">Amount <span class="pull-right">Paid</span></th>
						    </tr>
                            @foreach($hotelBook as $hotel)
                            <tr class="container_hotel">
                                <td><span>{{ Content::dateformat($hotel->checkin)}} -> {{ Content::dateformat($hotel->checkout) }}</span></td>
                                <td colspan="5">{{{ $hotel->hotel->supplier_name or ''}}} <small style="color: #9E9E9E;">{!! $hotel->remark > 0 ? "($hotel->remark)" :'' !!}</small></td>
                                <td colspan="2" >{{{ $hotel->room->name or ''}}}</td>
                                <td class="text-center">{{{ $hotel->no_of_room or '' }}}</td>
                                <td class="text-center" >{{{ $hotel->book_day or '' }}}</td>
                                <td class="text-right" width="120px">
                                    <?php 
                                        $hotelAmount    =   $hotel->net_amount;
                                        $totalHotel     =   $totalHotel + $hotelAmount;
                                        $HproJournal    =   App\AccountJournal::where(['supplier_id'=>$hotel->hotel->id, 'business_id'=>1,'project_number'=>$project->project_number, "book_id"=>$hotel->id, 'status'=>1])->first();
                                        if(isset($HproJournal)){
                                            $hTransaction = App\AccountTransaction::where(['journal_id'=>$HproJournal['id'], 'status'=>1]); 
                                            $color = $hTransaction->sum('credit') == $totalHotel ? "style=font-weight:700;color:#3c8dbc" : '';
                                        }                                        
                                    ?>
                                    @if(isset($hTransaction))
                                       <span class="pull-left" {{$color}}>{{$hotelAmount == $hTransaction->sum('credit') ? Content::money($hotelAmount) : Content::money($hotelAmount - $hTransaction->sum('credit'))}}</span>	
                                    @else
                                        <span class="pull-left">{{ Content::money($hotelAmount) }}</span>
                                    @endif

                                    @if(isset($HproJournal) && !empty($HproJournal['book_amount']))
                                        <span class="badge badge-light pull-right">{{{ Content::money($hTransaction->sum('credit') ) or '' }}}</span>
		          						@if ( $HproJournal['book_amount'] > $hTransaction->sum('credit'))
			      							<a target="_blank" href="{{route('getPayable', ['journal_id'=> $HproJournal->id])}}" class="btn btn-info btn-xs pull-right hidden-print"><b>Pay</b></a>
			      							<span class="btn btn-link btn-xs hidden-print pull-right">
			          							<a target="_blank" href="{{route('getJournalReport', ['journal_entry'=>$project->project_number])}}">View/Edit</a>
			          						</span>			          						
		          						@elseif ($totalHotel > $HproJournal['book_amount'] || $totalHotel == $hTransaction->sum('credit'))
		      								<span class=" btn btn-link btn-xs hidden-print">
			          							<a target="_blank" href="{{route('getJournalReport', ['journal_entry'=>$project->project_number])}}">View/Edit</a>
			          						</span>
		      							@endif
                                    @else
                                        @if( $hotelAmount > 0)
                                            <span data-toggle="modal" data-target="#loadProjectConfirm" class="btn btn-success btn-xs AddToACcount text-right hidden-print pull-right"
                                                data-type="1" data-kamount="" 
                                                data-amount="{{$hotelAmount}}" 
                                                data-process_type="pay" 
                                                data-bus_type="hotel"
                                                data-title="{{{ $hotel->hotel->supplier_name or ''}}}" 
                                                data-book_id="{{$hotel->id}}"
                                                data-supplier="{{{ $hotel->hotel->id or ''}}}" 
                                                data-country="{{{ $hotel->hotel->country_id or ''}}}">Post
                                            </span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="11" class="text-right"><h5><strong>Total: {{ Content::money($totalHotel)}} {{Content::currency()}} </strong></h5></td>
                            </tr>
                        @endif
                         <!-- Hotel end -->
                        <!-- Flight Start -->
                        <?php 
                            $flightBook = App\Booking::flightBook($project->project_number)->get(); 
                        ?>   
                        @if($flightBook->count() > 0)    
                            <tr class="flight">
                                <td style="border-top: none;" colspan="11"><strong>Flight Expenses</strong></td>
                            </tr>
                            <tr style="background-color:#f4f4f4;">
                                <th width="120px" style="width: 18%;">Date</th>
                                <th colspan="3" width="119px">Supplier Name</th>
                                <th colspan="2" class="text-center">From -> To</th>
                                <th class="text-center">Seats</th>					
                                <th class="text-right">Price {{Content::currency()}}</th>
                                <th class="text-right">Amount</th>
                                <th class="text-right">Price {{Content::currency(1)}}</th>
                                <th class="text-left" style="width: 17%;">Amount <span class="pull-right">Paid</span></th>
                            </tr>
                            @foreach($flightBook as $fl)
                                    <?php $flprice = App\Supplier::find($fl->book_agent);    dd($flprice);?>		
                                    <tr class="container_flight">
                                        <td>{{ Content::dateformat($fl->book_checkin) }}</td>
                                        <?php
                                            $combideFlihgt = $fl->book_namount ? $fl->book_namount : $fl->book_nkamount; 
                                            $flightAmount = $fl->book_namount + $fl->book_nkamount;
                                          
                                            $FJournal = App\AccountJournal::where(['supplier_id'=>$flprice['id'], 'business_id'=>4,'project_number'=>$project->project_number, "book_id"=>$fl->id, 'type'=>1, 'status'=>1])->first();
                                            
                                                if(isset($FJournal)){
                                                    $fSaction = App\AccountTransaction::where(['journal_id'=>$FJournal['id'], 'status'=>1]);
                                                    $color 	= $fSaction->sum('credit') == $fl->book_namount ? "style=font-weight:700;color:#3c8dbc" : '';
                                                    $colork = $fSaction->sum('kcredit') == $fl->book_nkamount ? "style=font-weight:700;color:#3c8dbc" : '';
                                                }								
                                        ?>
                                        <td colspan="3">{{{ $flprice['supplier_name'] or '' }}}</td>
                                        <td colspan="2" class="text-center">{{{ $fl->flight_from or '' }}}<i class="fa fa-arrow-right"></i>{{{ $fl->flight_to or '' }}}</td>
                                        <td class="text-center">{{{ $fl->book_pax or '' }}}</td>
                                        <td class="text-right">{{{ Content::money($fl->book_nprice) or '' }}}</td>
                                        <td class="text-right">
                                            @if(!empty($fSaction))
                                                <span class="pull-left" {{$color}}>{{ $fl->book_namount == $fSaction->sum('credit') ? Content::money($fl->book_namount) :   Content::money($fl->book_namount - $fSaction->sum('credit') ) }} </span>
                                            @else
                                                <span class="pull-left" {{$color}}>{{Content::money($fl->book_namount)}}</span>
                                            @endif    
                                        </td>
                                        <td class="text-right">{{{ Content::money($fl->book_nkprice) or '' }}}</td>
                                        <td class="text-right">
                                            @if(!empty($fSaction))
                                                <span class="pull-left" {{$colork}}>{{ $fl->book_nkamount == $fSaction->sum('kcredit') ? Content::money($fl->book_nkamount) : Content::money($fl->book_nkamount - $fSaction->sum('kcredit')) }}</span>
                                            @else
                                                {{Content::money($fl->book_nkamount)}}
                                            @endif
                                            @if($FJournal['book_amount'] > 0 || $FJournal['book_kamount'] > 0 )
                                                <span class="badge badge-light pull-right">{{ $fSaction->sum('credit') ? Content::money($fSaction->sum('credit')) : Content::money($fSaction->sum('kcredit')) }}</span>
                                                @if($FJournal['book_amount'] > $fSaction->sum('credit') || $FJournal['book_kamount'] > $fSaction->sum('kcredit'))
                                                    <a target="_blank" href="{{route('getPayable', ['journal_id'=>$FJournal['id']]) }}" class="btn btn-info btn-xs pull-right hidden-print"><b>Pay</b></a>
                                                        <span class=" btn btn-link btn-xs hidden-print pull-right">
                                                    <a target="_blank" href="{{route('getJournalReport', ['journal_entry' => $project['project_number']])}}">View/Edit</a></span>
                                                @elseif( $fl->book_namount >= $FJournal['book_amount'] || $fl->book_nkamount >= $FJournal['book_kamount'] )
                                                    <span class="btn btn-link btn-xs hidden-print pull-right">
                                                        <a target="_blank" href="{{route('getJournalReport', ['journal_entry'=> $project['project_number']])}}">View/Edit</a></span>
                                                @endif
                                            @else
                                                @if($flightAmount > 0)
                                                    <span data-toggle="modal" data-target="#loadProjectConfirm" class="btn btn-success btn-xs AddToACcount text-right hidden-print pull-right" 
                                                        data-type="4" data-kamount="{{$fl->book_nkamount}}" 
                                                        data-amount="{{$fl->book_namount}}" 
                                                        data-process_type="pay" 
                                                        data-bus_type="flight"
                                                        data-title="{{{ $flprice->supplier_name or ''}}}" 
                                                        data-book_id="{{$fl->id}}"
                                                        data-supplier="{{{ $flprice->id or ''}}}" 
                                                        data-country="{{{ $flprice->country_id or ''}}}">Post</span>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                            @endforeach	
                            <tr>
                                <td colspan="11" class="text-right">
                                    <h5><strong>
                                            @if($flightBook->sum('book_namount') > 0)
                                                Total {{Content::currency()}}: {{ Content::money($flightBook->sum('book_namount')) }}
                                            @endif
                                                {{$flightBook->sum('book_namount') > 0 && $flightBook->sum('book_nkamount') > 0 ? ',' : ''}}
                                            @if($flightBook->sum('book_nkamount') > 0)
                                                Total {{Content::currency(1)}}: {{ Content::money($flightBook->sum('book_nkamount')) }}
                                            @endif
                                        </strong>
                                    </h5>
                                </td>
                            </tr>
                        @endif
                        <!-- Flight End -->
                        <!-- Golf Start -->
                        <?php 
                            $golfBook = App\Booking::golfBook($project->project_number)->get();
                            $TotalGolf = 0;
						    $TotalKGolf = 0;
                        ?>
                        @if($golfBook->count() > 0)
                            <tr class="golf">
                                <th style="border-top:none;" colspan="11">
                                    <strong style="text-transform:capitalize;">Golf Courses Overview</strong>
                                </th>
                            </tr>
                            <tr style="background-color:#f4f4f4;">
                                <th width="100px">Date</th>
                                <th>Golf</th>
                                <th>Tee Time</th>
                                <th colspan="3">Golf Service</th>
                                <th class="text-center">Pax</th>
                                <th class="text-right">Price {{Content::currency()}}</th>
                                <th class="text-right">Amount </th>
                                <th class="text-right">Price {{Content::currency(1)}}</th>
                                <th class="text-left">Amount <div class="pull-right">Paid</div></th>
                            </tr>
                                @foreach($golfBook as $gf)			
                                    <?php 
                                        $gsv = App\GolfMenu::find($gf->program_id);
                                        $TotalGolf = $TotalGolf + $gf->book_namount;
                                        $TotalKGolf = $TotalKGolf + $gf->book_nkamount;
                                        $GJournal = App\AccountJournal::where(['business_id'=>29, 'supplier_id'=>$gf->golf_id, 'project_number'=>$project['project_number'], "book_id"=>$gf->id, 'type'=>1, 'status'=>1])->first();
                                        if(isset($GJournal)){
                                            $Gsaction = App\AccountTransaction::where(['journal_id'=>$GJournal['id'], 'status'=>1]);
                                            $color 	= $Gsaction->sum('credit') == $gf->book_namount ? "style=font-weight:700;color:#3c8dbc" : '';
                                            $colork = $Gsaction->sum('kdebit') == $gf->book_nkamount ? "style=font-weight:700;color:#3c8dbc" : '';
                                        }                                          
                                        $gTotalPayment = $gf->book_namount > 0 ? $gf->book_namount : $gf->book_nkamount;
                                    ?>
                                    <tr class="container_golf">
                                        <td>{{ Content::dateformat($gf->book_checkin) }}</td>
                                        <td>{{{ $gf->supplier_name or '' }}}</td>
                                        <td>{{{ $gf->book_golf_time or '' }}}</td>
                                        <td colspan="3">{{{ $gsv->name or ''}}}</td>
                                        <td class="text-center">{{{ $gf->book_pax or '' }}}</td>			
                                        <td class="text-right">{{{ Content::money($gf->book_nprice) or ''}}}</td>
                                        <td class="text-right" {{$color}}>
                                            @if(!empty($Gsaction))
                                                {{ $gf->book_namount == $Gsaction->sum('credit') ? Content::money($gf->book_namount) : Content::money($gf->book_namount - $Gsaction->sum('credit')) }}
                                            @else
                                                {{Content::money($gf->book_namount) }}
                                            @endif
                                        </td>
                                        <td class="text-right">{{{ Content::money($gf->book_nkprice) or '' }}}</td>
                                        <td class="text-center" style="width: 17%;">
                                            @if(!empty($Gsaction))
                                                <span class="pull-left" {{$colork}}>
                                                    {{ $gf->book_nkamount == $Gsaction->sum('kcredit') ? Content::money($gf->book_nkamount) : Content::money($gf->book_nkamount - $Gsaction->sum('kcredit')) }}
                                                </span>
                                            @else
                                                {{Content::money($gf->book_nkamount) }}
                                            @endif
                                                
                                        @if(isset($GJournal['book_amount']) && $GJournal['book_amount'] > 0 || isset($GJournal['book_kamount']) && $GJournal['book_kamount'] > 0)
                                                <span class="badge badge-light pull-right">{{ Content::money($Gsaction->sum('credit')) }}</span>

                                                @if($GJournal['book_amount'] > $Gsaction->sum('credit') || $GJournal['book_kamount'] > $Gsaction->sum('kcredit'))
                                                    <a target="_blank" href="{{route('getPayable', ['journal_id'=>$GJournal['id'] ])}}" class="btn btn-info btn-xs pull-right hidden-print"><b>Pay</b></a>

                                                    <span class=" btn btn-link btn-xs hidden-print pull-right">
                                                    <a target="_blank" href="{{route('getJournalReport', ['journal_entry'=>$project->project_number])}}">View/Edit</a></span>
                                                    
                                                @elseif( $gf->book_namount > $GJournal['total_namount'] || $gf->book_nkamount > $GJournal['total_knamount'] )

                                                    <div><span class=" btn btn-link btn-xs hidden-print pull-right">
                                                    <a target="_blank" href="{{route('getJournalReport', ['journal_entry'=>$project->project_number])}}">View/Edit</a>
                                                    </span></div>	      								
                                                @endif
                                            @else
                                                @if($gTotalPayment > 0)
                                                <span data-toggle="modal" data-target="#loadProjectConfirm" class="btn btn-success btn-xs AddToACcount text-right hidden-print pull-right"
                                                    data-type="29" data-kamount="{{$gf->book_nkamount}}" 
                                                    data-amount="{{$gf->book_namount}}" 
                                                    data-process_type="pay" 
                                                    data-bus_type="golf"
                                                    data-title="{{ $gf->supplier_name }}" 
                                                    data-book_id="{{ $gf->id }}"
                                                    data-supplier="{{{ $gf->golf_id or ''}}}" 
                                                    data-country="{{{ $gf->country_id or ''}}}">Post</span>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            <tr>
                                <td colspan="11" class="text-right">
                                    <h5><strong>
                                        @if($TotalGolf > 0)
                                            Total : {{ Content::money($TotalGolf) }} {{Content::currency()}}
                                        @endif
                                            
                                        @if($TotalKGolf > 0) 
                                            Total {{Content::currency(1)}}: {{ Content::money($TotalKGolf) }}
                                        @endif
                                        </strong>
                                    </h5> 
                                </td>
                            </tr>
                        @endif
                        <!-- Golf End -->
                        <!-- Cruise Start -->
                        <?php 
                            $cruiseBook=App\CruiseBooked::where(['project_number'=>$project->project_number, 'status'=>1])->orderBy("checkin")->get();
                        ?>
                        @if($cruiseBook->count() > 0)
                            <tr class="cruise">
                                <th style="border-top: none;" colspan="11">
                                    <strong style="text-transform:capitalize;">River Cruise OverView</strong>
                                </th>
                            </tr>
                            <tr style="background-color:#f4f4f4;">
                                <th width="170px;">Date</th>
                                <th colspan="3">River Cruise</th>
                                <th colspan="4">Program</th>
                                <th>Room</th>
                                <th>Night/Cabin</th>
                                <th class="text-left" style="width: 17%;">Amount <span class="pull-right">Paid</span></th>
                            </tr>
                            @foreach($cruiseBook as $crp)			
                                <?php 
                                    $pcr = App\CrProgram::find($crp->program_id);
                                    $rcr = App\RoomCategory::find($crp->room_id);
                                    $totalCruise = $totalCruise + $crp->net_amount;
                                    $RJournal = App\AccountJournal::where(['book_id'=>$crp->id, 'supplier_id'=>$crp->cruise_id, 'business_id'=>3,'project_number'=>$project->project_number, 'status'=>1, 'type'=>1 ])->first();
                                    if(isset($RJournal)){
                                        $rsaction = App\AccountTransaction::where(['journal_id'=>$RJournal['id'], 'status'=>1]); 
                                        $color 	= $rsaction->sum('credit') == $crp->net_amount ? "style=font-weight:700;color:#3c8dbc" : '';
                                    }                                 
                                ?>	
                                <tr class="container_river-cruise">
                                    <td>{{ Content::dateformat($crp->checkin)}} -> {{ Content::dateformat($crp->checkout) }} </td>
                                    <td colspan="3">{{{ $crp->cruise->supplier_name or ''}}}</td>
                                    <td colspan="4">{{{ $pcr->program_name or ''}}}</td>
                                    <td>{{{ $crp->room->name or ''}}}</td>					
                                    <td class="text-center">{{{ $crp->book_day or '' }}} / {{{ $crp->cabin_pax or '' }}}</td>
                                    <td class="text-right">	
                                        @if(!empty($rsaction))						
                                            <span class="pull-left" {{$color}}> {{ $crp->net_amount == $rsaction->sum('credit') ? Content::money($crp->net_amount) : Content::money($crp->net_amount - $rsaction->sum('credit')) }}</span>
                                        @else
                                            <span class="pull-left">{{Content::money($crp->net_amount)}} </span>
                                        @endif

                                        @if(isset($RJournal['book_amount']) || $RJournal['book_kamount'] > 0 )
                                            <span class="badge badge-light pull-right">{{{ Content::money($rsaction->sum('credit')) or '' }}}</span>
                                            @if($RJournal['book_amount'] > $rsaction->sum('credit') || $RJournal['book_kamount'] > $rsaction->sum('kcredit'))
                                                <a target="_blank" href="{{route('getPayable', ['journal_id'=>$RJournal['id']])}}" class="btn btn-info  btn-xs pull-right hidden-print"><b>Pay</b></a>
                                                <span class=" btn btn-link btn-xs hidden-print pull-right">
                                                    <a  target="_blank" href="{{route('getJournalReport', ['journal_entry' => $project->project_number])}}">View/Edit</a>
                                                </span>
                                                
                                            @elseif($crp->net_amount >= $rsaction->sum('credit'))
                                                <span class=" btn btn-link btn-xs hidden-print pull-right">
                                                    <a  target="_blank" href="{{route('getJournalReport', ['journal_entry' => $project->project_number])}}">View/Edit</a>
                                                </span>
                                            @endif
                                        @else
                                            @if($crp->net_amount > 0)
                                            <span data-toggle="modal" data-target="#loadProjectConfirm" class="btn btn-success btn-xs AddToACcount text-right hidden-print pull-right" 
                                                data-type="3" data-kamount="" 
                                                data-amount="{{$crp->net_amount}}" 
                                                data-process_type="pay" 
                                                data-bus_type="cruise"
                                                data-title="{{{ $crp->cruise->supplier_name or ''}}}" 
                                                data-book_id="{{$crp->id}}"
                                                data-supplier="{{$crp->cruise_id}}" 
                                                data-country="{{$crp->cruise->country_id}}">Post</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="11" class="text-right"><h5><strong>
                                    Total {{Content::currency()}} : {{ Content::money($totalCruise) }}</strong></h5>
                                </td>
                            </tr>
                        @endif
                        <!-- Cruise End -->
                        <!-- Transport Start -->
                        <?php 
                            $tranBook = App\Booking::tourBook($project->project_number)->get(); 
                            $transportTotal = 0;
                            $transportkTotal = 0;
                           
                        ?>
                        @if( $tranBook->count() != 0)
                            <tr class="transport">
                                <th colspan="11" style="border-top:none;">
                                    <strong>Transport Expenses</strong></div>
                                </th>
                            </tr>
                            <tr style="background-color:#f4f4f4;">
                                <th width="110px">Date</th>
                                <th colspan="4">Tour</th>
                                <th>SupplierName</th>
                                <th colspan="2">Service</th>
                                <th >Vehicle</th>
                                <th class="text-right" width="160px">Price {{Content::currency()}}</th>
                                <th class="text-left" width="160px">Price {{Content::currency(1)}} <span class="pull-right">Paid</span></th>
                            </tr>			
                            @foreach($tranBook as $tran)
                            <?php 
                                $pro   = App\Province::find($tran->province_id); 
                                $btran = App\BookTransport::where(['project_number'=>$tran->book_project, 'book_id'=>$tran->id])->first();
                                $price = isset($btran->price)? $btran->price:0; 
								$kprice = isset($btran->kprice)? $btran->kprice:0;
								$transportTotal = $transportTotal + $price;
								$transportkTotal = $transportkTotal + $kprice;
                                $TranAmount = $transportTotal + $transportkTotal;
                                if ($btran !== null && is_object($btran)) {
                                    $TranJournal = App\AccountJournal::where(['supplier_id'=>$btran['transport_id'], 'business_id'=>7,'project_number'=>$project->project_number, 'book_id'=>$tran->id, 'type'=>1, 'status'=>1])->first();
                                    if(isset($TranJournal)){
                                        $TransacTran = App\AccountTransaction::where(['journal_id'=>$TranJournal['id'], 'status'=>1]);
                                        $color 	= $TransacTran->sum('credit') == $btran['price'] ? "style=font-weight:700;color:#3c8dbc" : '';
                                        $colork	= $TransacTran->sum('kcredit') == $btran['kprice'] ? "style=font-weight:700;color:#3c8dbc" : '';
                                    }  
                                }                                            
                            ?>
                            <tr class="container_transport">
                                <td>{{ Content::dateformat($tran->book_checkin) }}</td>
                                <td colspan="4">{{{ $tran->tour_name or '' }}} <br><i>[ {{{ $pro->province_name or ''}}} ]</i></td>
                                  @if(null !== $btran)    
                                    <td>{{{ $btran->transport->supplier_name or ''}}}</td>
                                    <td colspan="2">{{{ $btran->service->title or ''}}}</td>
                                    <td>{{{ $btran->vehicle->name or ''}}}</td>
                                    <td class="text-right" >
                                        @if(!empty($TransacTran))
                                            <span class="pull-left" {{$color}}> {{ $btran['price'] == $TransacTran->sum('credit') ? Content::money($btran['price']) : Content::money($btran['price'] - $TransacTran->sum('credit')) }} </span>
                                        @else
                                            <span class="pull-left">{{ Content::money($btran['price'])}} </span>
                                        @endif
                                    </td>
                                     <td class="text-right" style="width: 17%;">
                                       
                                        @if(!empty($TransacTran))
                                            <span class="pull-left" {{$colork}}>{{ Content::money($btran['kprice'] - $TransacTran->sum('kcredit')) }}</span>
                                        @else
                                            <span class="pull-left">{{  Content::money($btran['kprice'])}}</span>
                                        @endif
                                      
                                        @if(isset($TranJournal['book_amount']) && isset($TranJournal['book_kamount']))
                                       
                                            @if($TranJournal['book_amount'] > 0 || $TranJournal['book_kamount'] > 0)
                                                <span class="badge badge-light pull-right">{{ Content::money($TransacTran->sum('credit')) }}</span>
                                                @if($TranJournal['book_amount'] > $TransacTran->sum('credit') || $TranJournal['book_kamount'] > $TransacTran->sum('kcredit'))
                                                    <a target="_blank" href="{{route('getPayable', ['journal_id'=>$TranJournal['id']])}}" class="btn btn-info btn-xs pull-right hidden-print"><b>Pay</b></a>

                                                    <span class="btn btn-link btn-xs hidden-print pull-right">
                                                        <a target="_blank" href="{{route('getJournalReport', ['journal_entry'=>$project->project_number])}}">View/Edit</a>
                                                    </span>
                                                        
                                                @elseif($btran['price'] >= $TransacTran->sum('credit') || $btran['kprice'] >= $TransacTran->sum('kcredit'))
                                                    <span class="btn btn-link btn-xs hidden-print pull-right">
                                                        <a target="_blank" href="{{route('getJournalReport', ['journal_entry'=>$project->project_number])}}">View/Edit</a>
                                                    </span>
                                                @endif
                                        @endif
                                            @else     
                                                @if(!empty($TranAmount) && $TranAmount > 0)
                                               
                                                    <span data-toggle="modal" data-target="#loadProjectConfirm" class="btn btn-success btn-xs AddToACcount text-right hidden-print pull-right" 
                                                        data-type="7" data-kamount="{{{ $btran['kprice'] or ''}}}" 
                                                        data-amount="{{$btran['price']}}" 
                                                        data-process_type="pay" 
                                                        data-bus_type="transport"
                                                        data-title="{{{ $btran->transport->supplier_name or ''}}}" 
                                                        data-book_id="{{$tran->id}}"
                                                        data-supplier="{{$btran['transport_id']}}" 
                                                        data-country="{{$btran['country_id']}}">Post</span>
                                                @endif
                                            @endif
                                        @endif
                                    </td>
                            </tr>				
                            @endforeach
                            <tr>
                                <td colspan="11" class="text-right">
                                    <h5>
                                        <strong>
                                            @if($transportTotal > 0)
                                                Total {{Content::currency()}}: {{ Content::money($transportTotal) }}
                                            @endif
                                            {{$transportTotal > 0 && $transportkTotal > 0 ? ',' : '' }}
                                            @if($transportkTotal > 0)
                                                Total {{Content::currency(1)}} : {{ Content::money($transportkTotal) }}
                                            @endif
                                        </strong>
                                    </h5>
                                </td>
                            </tr>
                        @endif
                        <!-- Transport End -->
                         <!-- Guide Start -->
                        <?php 
                            $guideBook = App\Booking::tourBook($project->project_number)->get(); 
                            $guidTotal = 0;
                            $guidkTotal = 0;
					    ?>
                        @if($guideBook->count() > 0)
					        <tr class="guide">
                                <th colspan="12" style="border-top:none;">
                                    <strong style="text-transform:capitalize;">guide expenses</strong>
                                </th>
                            </tr>
                            <tr style="background-color:#f4f4f4;">
                                <th width="100px">StartDate</th>
                                <th colspan="4">Tour</th>
                                <th colspan="2">Service</th>
                                <th width="150px">SupplierName</th>
                                <th>Language</th>
                                <th class="text-right">Price {{Content::currency()}}</th>
                                <th class="text-left" style="width: 17%;">Price {{Content::currency(1)}}  <span class="pull-right">Paid</span></th>
                            </tr>			
                            @foreach($guideBook as $tran)
                                <?php 
                                    $pro = App\Province::find($tran->province_id);
                                    $bg  = App\BookGuide::where(['project_number'=>$tran->book_project, 'book_id'=>$tran->id])->first(); 
                                    $price = isset($bg->price)? $bg->price:0; 
								    $kprice = isset($bg->kprice)? $bg->kprice:0;
                                    $guidTotal = $guidTotal + $price;
                                    $guidkTotal = $guidkTotal + $kprice;	
                                    $gAmount = $guidTotal + $guidkTotal;
                                    if ($bg !== null && is_object($bg)) {
                                        $GJournal = App\AccountJournal::where(['supplier_id'=>$bg['supplier_id'], 'business_id'=>6,'project_number'=>$project->project_number, 'book_id'=>$tran->id, 'type'=>1 ,'status'=>1])->first();
                                        if(isset($GJournal)){
                                            $GAccTran = App\AccountTransaction::where(['journal_id'=>$GJournal['id'], 'status'=>1]);
                                            $color 	= $GAccTran->sum('credit') == $bg['price'] ? "style=font-weight:700;color:#3c8dbc" : '';
                                            $colork	= $GAccTran->sum('kcredit') == $bg['kprice'] ? "style=font-weight:700;color:#3c8dbc" : '';
                                        }
                                    }
                                ?>
                                <tr class="container_guide">
                                    <td><span class="hidden-print" style="position: relative;top:2px;"></span>{{ Content::dateformat($tran->book_checkin) }}</td>
                                    <td colspan="4">{{{ $tran->tour_name or '' }}} &nbsp;<i>[ {{{ $pro->province_name or ''}}} ]</i></td>     
                                    @if(null !== $bg)
                                        <td colspan="2">{{{ $bg->service->title or '' }}}</td>
                                        <td>{{{ $bg->supplier->supplier_name or '' }}} </td>
                                        <td>{{{ $bg->language->name or '' }}}</td>
                                        <td class="text-right"> 
                                            @if(!empty($GAccTran))
                                                <span class="pull-left" {{$color}}>{{ $bg['price'] == $GAccTran->sum('credit') ? Content::money($bg['price']) : Content::money($bg['price'] - $GAccTran->sum('credit')) }} </span>
                                            @else	
                                                <span class="pull-left">{{ Content::money($bg['price'])  }} </span>
                                            @endif
                                        </td>
                                        <td class="text-right" style="vertical-align: middle;">
                                            @if(!empty($GAccTran))	
                                                <span class="pull-left" {{$colork}}>{{ $bg['kprice'] == $GAccTran->sum('kcredit') ? Content::money($bg['kprice']) : Content::money($bg['kprice'] - $GAccTran->sum('kcredit'))}}</span>									
                                            @else	
                                                <span class="pull-left">{{Content::money($bg['kprice']) or ''}} </span>
                                            @endif
                                            
                                            @if(isset($GJournal['book_amount']) && isset($GJournal['book_kamount']))
                                                @if($GJournal['book_amount'] > 0 || $GJournal['book_kamount'] > 0)
                                                    <span class="badge badge-light pull-right">{{ $GAccTran->sum('kcredit') ? Content::money( $GAccTran->sum('kcredit')) : Content::money($GAccTran->sum('credit')) }}</span>

                                                    @if($GJournal['book_amount'] > $GAccTran->sum('credit') || $GJournal['book_kamount'] > $GAccTran->sum('kcredit'))
                                                        <a target="_blank" href="{{route('getPayable', ['journal_id'=> $GJournal['id']])}}" class="btn btn-info btn-xs pull-right hidden-print"><b>Pay</b></a>
                                                        <span class="btn btn-link btn-xs hidden-print pull-right">
                                                            <a target="_blank" href="{{route('getJournalReport', ['journal_entry'=>$project->project_number])}}">View/Edit</a>
                                                        </span>
                                                        
                                                    @elseif($bg['price'] >= $GJournal['book_amount'] || $bg['kprice'] >= $GJournal['book_kamount'] )
                                                        <span class="btn btn-link btn-xs hidden-print pull-right">
                                                            <a target="_blank" href="{{route('getJournalReport', ['journal_entry'=> $project->project_number])}}">View/Edit</a>
                                                        </span>
                                                    @endif
                                                @endif
                                            @else
                                                @if($gAmount > 0)
                                                    <span data-toggle="modal" data-target="#loadProjectConfirm" class="btn btn-success btn-xs AddToACcount text-right hidden-print pull-right" 
                                                    data-type="6" data-kamount="{{$bg['kprice']}}" 
                                                    data-amount="{{$bg['price']}}" 
                                                    data-process_type="pay" 
                                                    data-bus_type="guide"
                                                    data-title="{{{ $bg->supplier->supplier_name or ''}}}" 
                                                    data-book_id="{{$tran->id}}"
                                                    data-supplier="{{$bg->supplier_id}}" 
                                                    data-country="{{$bg->country_id}}">Post</span>
                                                @endif
                                            @endif
                                        </td>
                                    @endif
                                </tr>				
                            @endforeach
                            <tr>
                                <td colspan="11" class="text-right">
                                    <h5>
                                        <strong>
                                            @if($guidTotal > 0)
                                                Total {{Content::currency()}}: {{ Content::money($guidTotal) }}
                                            @endif
                                            {{$guidTotal > 0 && $guidkTotal > 0 ? ',' : ''}}
                                            @if($guidkTotal > 0)
                                                Total {{Content::currency(1)}} : {{ Content::money($guidkTotal) }}
                                            @endif
                                        </strong>
                                    </h5>
                                </td>
                            </tr>
                        @endif
                        <!-- Guide End -->
                        <!-- Restaurant Start -->
                        <?php
					        $restBook = App\BookRestaurant::where('project_number', $project->project_number)->orderBy('start_date')->get();
                        ?>
                        @if($restBook->count() > 0)
							<tr class="restaurant">
								<th style="border-top: none;">
									<strong style="text-transform:capitalize;">restaurant expenses</strong>
								</th>
							</tr>
							<tr style="background-color:#f4f4f4;">
				                <th width="100px">Start Date</th>
				                <th colspan="2">SupplierName</th>
				                <th colspan="3">Menu</th>
				                <th class="text-center">Pax</th>
				                <th class="text-right">Price {{Content::currency()}}</th>
				                <th class="text-right">Amount</th>
				                <th class="text-right">Price {{Content::currency(1)}}</th>
				                <th class="text-left" style="widows: 17%;">Amount <span class="pull-right">Paid</span></th>
			                </tr>		
							@foreach($restBook as $rest)
							<?php 
								$RJournal = App\AccountJournal::where(['supplier_id'=>$rest['supplier_id'], 'business_id'=>2,'project_number'=>$project->project_number, 'book_id'=>$rest->id, 'type'=>1, 'status'=>1])->first();
								if(isset($RJournal)){
									$RestAccTran = App\AccountTransaction::where(['journal_id'=>$RJournal['id'], 'status'=>1]);
									$color 	= $RestAccTran->sum('credit') == $rest['amount'] ? "style=font-weight:700;color:#3c8dbc" : '';
									$colork	= $RestAccTran->sum('kcredit') == $rest['kamount'] ? "style=font-weight:700;color:#3c8dbc" : '';
								}							 
								$ResAmount = $rest['amount'] + $rest['kamount'];
									
	  						?>
							<tr class="container_restaurant">
								<td>{{ Content::dateformat($rest->start_date) }}</td>
				                <td colspan="2">{{{$rest->supplier->supplier_name or ''}}}</td>         
				                <td colspan="3">{{{$rest->rest_menu->title or ''}}}</td>
				                <td class="text-center">{{{ $rest->book_pax or '' }}}</td>
				                <td class="text-right">{{ Content::money($rest->price)}}</td>
				                <td class="text-right">
									@if(!empty($RestAccTran))
										<span class="pull-left" {{$color}}>{{ $rest->amount == $RestAccTran->sum('credit') ? Content::money($rest->amount) : Content::money($rest->amount - $RestAccTran->sum('credit') )}} </span>
									@else 
										<span class="pull-left">{{Content::money($rest->amount)}}</span>
									@endif
								</td>
				                <td class="text-right">{{Content::money($rest->kprice)}}</td>
			                  	<td class="text-left">
								    @if(!empty($RestAccTran))
								        <span class="pull-left" {{$colork}}>{{Content::money($rest->kamount - $RestAccTran->sum('kcredit') )}}</span>
								    @else
										<span class="pull-left">{{Content::money($rest->kamount)}}</span>
									@endif
			                  		
			                  		@if($RJournal['book_amount'] > 0 || $RJournal['book_kamount'] > 0)
			                  			<span class="badge badge-light pull-right">{{ $RestAccTran->sum('kcredit') ? Content::money($RestAccTran->sum('kcredit')) : Content::money($RestAccTran->sum('credit'))}}</span>

			                  			@if($RJournal['book_amount'] > $RestAccTran->sum('credit') || $RJournal['book_kamount'] > $RestAccTran->sum('kcredit') )
			  								<a target="_blank" href="{{route('getPayable', ['journal_id'=> $RJournal['id']])}}" class="btn btn-info btn-xs pull-right hidden-print"><b>Pay</b></a>
			  								<span class="btn btn-link btn-xs hidden-print pull-right">
				      							<a target="_blank" href="{{route('getJournalReport', ['journal_entry'=>$project->project_number])}}">View/Edit</a>
				      						</span>
			  								
			  							@elseif($rest->amount >= $RJournal['book_amount'] || $rest->kamount >= $RJournal['book_kamount'] )
			  								<span class="btn btn-link btn-xs hidden-print pull-right">
				      							<a target="_blank" href="{{route('getJournalReport', ['journal_entry'=> $project->project_number])}}">View/Edit</a>
				      						</span>
			  							@endif
	                  				@else
	                  					@if($ResAmount > 0)
		                  				<span data-toggle="modal" data-target="#loadProjectConfirm" class="btn btn-success btn-xs AddToACcount text-right hidden-print pull-right"
	              							data-type="2" data-kamount="{{$rest->kamount}}" 
	              							data-amount="{{$rest->amount}}" 
	              							data-process_type="pay" 
	              							data-bus_type="restaurant"
	              							data-title="{{{ $rest->supplier->supplier_name or ''}}}" 
	              							data-book_id="{{$rest->id}}"
	              							data-supplier="{{$rest->supplier_id}}" 
	              							data-country="{{$rest->country_id}}">Post</span>
	                  					@endif
			                  		@endif
			                  	</td>
							</tr>				
							@endforeach
							<tr>
								<td colspan="11" class="text-right">
									<h5>
										<strong>
											@if($restBook->sum('amount') > 0)
												Total {{Content::currency()}}: {{ Content::money($restBook->sum('amount')) }}
											@endif
											{{$restBook->sum('amount') > 0 && $restBook->sum('kamount') > 0 ? ',':''}}
											@if($restBook->sum('kamount') > 0)
												Total {{Content::currency(1)}}: {{ Content::money($restBook->sum('kamount')) }} 
											@endif
										</strong>
									</h5>
								</td>
							</tr>
						@endif
                        <!-- Restaurant End -->
                        <!-- Entrance Fees Start -->
                        <?php 
                            $EntranceBook = App\BookEntrance::where('project_number', $project->project_number)->orderBy('start_date', 'ASC')->get();
                            $entranAmount = 0; 
                            $entranKAmount = 0;
                        ?>
						@if($EntranceBook->count() > 0)
							<tr class="entran">
								<th colspan="11" style="border-top: none;">
									<strong style="text-transform: capitalize;">entrance fees expenses</strong>
								</th>
							</tr>
							<tr style="background-color:#f4f4f4;">
								<th width="100px">Start Date</th>
			                  	<th colspan="5">Entrance Fees</th>
			                  	<th class="text-center">Pax</th>
			                  	<th class="text-right">Price </th>
			                  	<th class="text-right">{{Content::currency()}} Amount </th>
			                  	<th class="text-right">Price</th>
			                  	<th class="text-left"> {{Content::currency(1)}} Amount <span class="pull-right">Paid</span></th>
							</tr>
							@foreach($EntranceBook as $ent)
								<?php 
									$pro = App\Province::find($ent->province_id); 
									$entKamount = $ent->kamount > 0 ? $ent->kamount : $ent->kprice * $ent->book_pax;
									$entAmount = $ent->amount > 0 ? $ent->amount : $ent->price * $ent->book_pax;
									$entranKAmount = $entranKAmount + $entKamount;
									$entranAmount = $entranAmount + $entAmount;
	  								$EntJournal = App\AccountJournal::where(['business_id'=>55,'project_number'=>$project->project_number, 'book_id'=>$ent->id, 'type'=>1, 'status'=>1])->first();
	  								if(isset($EntJournal)){
										$EntAccTran = App\AccountTransaction::where(['journal_id'=>$EntJournal['id'], 'status'=>1]);
	  									$color = ($entAmount - $EntAccTran->sum('credit')) == 0 ? "style=font-weight:700;color:#3c8dbc" : '';
	              						$colork = ($entKamount - $EntAccTran->sum('kcredit')) == 0 ? "style=font-weight:700;color:#3c8dbc" : '';
									}
								?>
								<tr>
									<td>{{ Content::dateformat($ent->start_date) }}</td>
					                <td  colspan="5">{{{$ent->entrance->name or ''}}}</td>
					                <td class="text-center">{{{ $ent->book_pax or '' }}}</td>
					                <td class="text-right">{{ Content::money($ent->price) }}</td>
					                <td class="text-right">
										@if(!empty($RestAccTran))
											<span class="pull-left" {{$color}}>{{$entAmount == $EntAccTran->sum('credit') ? Content::money($entAmount) : Content::money($entAmount - $EntAccTran->sum('credit'))}} </span>
										@else
                                            <span class="pull-left"> {{Content::money($entAmount)}}</span>
										@endif	
									</td>
					                <td class="text-right">{{ Content::money($ent->kprice) }}</td>
				                  	<td class="text-right">
				                  		
										  	@if(!empty($RestAccTran))
                                                <span class="pull-left" {{$colork}}>{{$entKAmount == $EntAccTran->sum('kcredit') ? Content::money($entKAmount) : Content::money($entKAmount - $EntAccTran->sum('kcredit'))}}</span>
											@else
                                                <span class="pull-left"> {{Content::money($entAmount)}} </span>
											@endif
                                            @if(isset($EntJournal['book_amount']) && isset($EntJournal['book_kamount']))
                                                @if($EntJournal['book_amount'] > 0 || $EntJournal['book_kamount'] > 0)
                                                    <span class="badge badge-light pull-right">{{ $EntAccTran->sum('kcredit') ? Content::money($EntAccTran->sum('kcredit')) : Content::money($EntAccTran->sum('credit')) }}</span>
                                                    
                                                    @if($EntJournal['book_amount'] > $EntAccTran->sum('credit') || $EntJournal['book_kamount'] > $EntAccTran->sum('kcredit'))
                                                        <a target="_blank" href="{{route('getPayable', ['journal_id'=>$EntJournal['id'] ])}}" class="btn btn-info btn-xs pull-right hidden-print"><b>Pay</b></a>
                                                        <span class="btn btn-link btn-xs hidden-print pull-right">
                                                        
                                                        <a target="_blank" href="{{route('getJournalReport', ['journal_entry'=>$project->project_number])}}">View/Edit</a> </span>
                                                        
                                                    @elseif($entKamount >= $EntJournal['book_amount'] || $entKamount >= $EntJournal['book_kamount'] )
                                                        <span class="btn btn-link btn-xs hidden-print pull-right">
                                                        <a target="_blank" href="{{route('getJournalReport', ['journal_entry'=>$project->project_number])}}">View/Edit</a> </span>
                                                    @endif
                                                @endif
	                  			    	    @else
                                            @if($entAmount>0) 
    		                  				<span data-toggle="modal" data-target="#loadProjectConfirm" class="btn btn-success btn-xs AddToACcount text-right hidden-print pull-right"
    	              							data-type="55" data-kamount="{{$entKamount}}" 
    	              							data-amount="{{$entAmount}}" 
    	              							data-process_type="pay" 
    	              							data-title="{{ $ent->entrance->name }}" 
    	              							data-book_id="{{$ent->id}}"
    	              							data-supplier="" 
    	              							data-country="{{$ent->country_id}}">Post
    	              						</span>
                                            @endif
	              				    	@endif
            				                  		
				                  	</td>
								</tr>				
							@endforeach
							<tr>
								<td colspan="11" class="text-right">
									<h5>
										<strong>
											@if($entranAmount > 0)
												Total {{Content::currency()}}: {{Content::money($entranAmount)}}
											@endif
											{{$entranAmount > 0 && $entranKAmount > 0 ? ',' : ''}}
											@if($entranKAmount > 0)
												Total {{Content::currency(1)}}: {{Content::money($entranKAmount)}}
											@endif
										</strong>
									</h5>
								</td>
							</tr>
						@endif
                        <!-- Entrance Fees End -->
                        <!-- MISC Start -->
                            <?php 
                                $MiscBook = App\Booking::tourBook($project->project_number)->get(); 
                                $miscTotal = 0;
                                $misckTotal = 0;
                            ?>
                            @if($MiscBook->count() > 0)
                                <tr class="misc">
                                    <th colspan="11" style="border-top: none;">
                                        <strong style="text-transform: capitalize;">MISC expenses</strong>
                                    </th>
                                </tr>
                                <tr style="background-color:#f4f4f4;">
                                    <th width="100px">Date</th>
                                    <th colspan="10">Title</th>						
                                </tr>			
                                @foreach($MiscBook as $tour)
                                    <?php 
                                    $pro = App\Province::find($tour->province_id); 
                                    $miscService = App\BookMisc::where(['project_number'=>$tour->book_project,'book_id'=>$tour->id])->orderBy("created_at", "DESC")->get();?>
                                    <tr class="container_misc">
                                        <td>{{ Content::dateformat($tour->book_checkin) }}</td>
                                    
                                        <td colspan="10" style="padding-right: 0px;">
                                            <div><strong>{{{ $tour->tour_name or '' }}}</strong> &nbsp; <i>[ {{{$pro->province_name or ''}}} ]</i></div>
                                            @if($miscService->count() > 0) 
                                                <hr style="border-top:none; border-bottom: 1px solid #ddd;padding: 5px 0px; margin-top:0px; margin-bottom: 0px;">
                                                <div class="row "style="font-style: italic;">
                                                    <label class="col-md-5">
                                                        <strong class="pcolor">Service Name</strong>
                                                    </label>
                                                    <label class="col-md-1">
                                                        <label class="row">
                                                            <strong class="pcolor">PaxNo.</strong> 
                                                        </label>
                                                    </label>
                                                    <label class="col-md-1">
                                                        <label class="row">
                                                            <strong class="pcolor">Price{{Content::currency()}}</strong>
                                                        </label>
                                                    </label>
                                                    <label class="col-md-1">
                                                        <label class="row">
                                                            <strong class="pcolor">Amount</strong>
                                                        </label>
                                                    </label>
                                                    <label class="col-md-1">
                                                        <label class="row">
                                                            <strong class="pcolor">Price{{Content::currency(1)}}</strong>
                                                        </label>
                                                    </label>
                                                    <label class="col-md-3 pcolor text-center" style="padding-left: 0px;">
                                                        <strong class="pcolor">Amount <span class="pull-right">Paid &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></strong>
                                                    </label>
                                                </div>		                
                                                @foreach($miscService as $misc)
                                                    <?php 
                                                        $miscAmount = $misc->amount > 0 ? $misc->amount : $misc->book_pax * $misc->price;
                                                        $misckAmount = $misc->kamount > 0 ? $misc->kamount : $misc->book_pax * $misc->kprice;
                                                        $miscTotal = $miscTotal + $miscAmount;
                                                        $misckTotal = $misckTotal + $misckAmount;
                                                        $MiscJournal = App\AccountJournal::where(['business_id'=>54,'project_number'=>$project->project_number, 'book_id'=>$misc->id, 'type'=>1, 'status'=>1])->first();
                                                        if(isset($MiscJournal)){
                                                            $MiscAccTran = App\AccountTransaction::where(['journal_id'=>$MiscJournal['id'], 'status'=>1]);
                                                            $color = ($miscAmount - $MiscAccTran->sum('credit')) == 0 ? "style=font-weight:700;color:#3c8dbc" : '';
                                                            $colork = ($misckAmount - $MiscAccTran->sum('kcredit')) == 0 ? "style=font-weight:700;color:#3c8dbc" : '';
                                                        }
                                                        $MISCTotalAmount = $miscAmount + $misckAmount;
                                                        
                                                    ?>
                                                    <div class="row col-md-12" style="padding-right: 0px;">
                                                        <label class="col-md-5" style="font-weight: 400;">
                                                            <p>{{{ $misc->servicetype->name or '' }}}</p>
                                                        </label>
                                                        <label class="col-md-1" style="font-weight: 400;">
                                                            <p>{{{ $misc->book_pax or '' }}}</p>
                                                        </label>
                                                        <label class="col-md-1" style="font-weight: 400;">
                                                            <p>{{ Content::money($misc->price) }}</p>
                                                        </label>
                                                        <label class="col-md-1" style="font-weight: 400;">
                                                            @if(!empty($miscAccTran))						                  		
                                                                <span class="pull-left" {{$color}}>{{ $miscAmount - $MiscAccTran->sum('credit') ? Content::money($miscAmount) : Content::money($miscAmount - $MiscAccTran->sum('credit')) }} </span>
                                                            @else
                                                                <span class="pull-left">{{Content::money($miscAmount)}}</span>
                                                            @endif
                                                        </label>
                                                        <label class="col-md-1" style="font-weight: 400;">
                                                            <p>{{ Content::money($misc->kprice) }}</p>
                                                        </label>
                                                        <label class="col-md-3 text-center" style="font-weight: 400; padding-right: 0px;">
                                                            @if(!empty($miscAccTran))						                  		
                                                                <span class="pull-left" {{$colork}}> {{ $misckAmount - $MiscAccTran->sum('kcredit') ? Content::money($misckAmount) : Content::money($misckAmount - $MiscAccTran->sum('kcredit')) }}</span>
                                                            @else
                                                                <span class="pull-left" >{{Content::money($misckAmount)}} </span>
                                                            @endif
                                                            @if(isset($EntJournal['book_amount']) && isset($EntJournal['book_kamount']))
                                                                @if($MiscJournal['book_amount'] > 0 || $MiscJournal['book_kamount'] > 0)
                                                                    <span class="badge badge-light pull-right">{{ $MiscAccTran->sum('credit') ? Content::money($MiscAccTran->sum('credit')) : Content::money($MiscAccTran->sum('kcredit')) }}</span>

                                                                    @if($MiscJournal['book_amount'] > $MiscAccTran->sum('credit') || $MiscJournal['book_kamount'] > $MiscAccTran->sum('kcredit'))
                                                                        <a target="_blank" href="{{route('getPayable', ['journal_id'=> $MiscJournal['id']])}}" class="btn btn-info btn-xs pull-right hidden-print  pull-right"><b>Pay</b></a>

                                                                        <span class="btn btn-link btn-xs hidden-print  pull-right">
                                                                            <a  target="_blank" href="{{route('getJournalReport', ['journal_entry' => $project->project_number])}}">View/Edit</a>
                                                                        </span>		
                                                                    @elseif($miscAmount >= $MiscJournal['book_amount'] || $misckAmount >= $MiscJournal['book_kamount'] )
                                                                        <span class="btn btn-link btn-xs hidden-print pull-right">
                                                                            <a  target="_blank" href="{{route('getJournalReport', ['journal_entry' => $project->project_number])}}">View/Edit</a>
                                                                        </span>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                @if($MISCTotalAmount > 0)
                                                                    <span data-toggle="modal" data-target="#loadProjectConfirm" class="btn btn-success btn-xs AddToACcount text-right hidden-print pull-right" 
                                                                        data-type="54" data-kamount="{{$misckAmount}}" 
                                                                        data-amount="{{$miscAmount}}" 
                                                                        data-process_type="pay" 
                                                                        data-title="{{{ $misc->servicetype->name or ''}}}" 
                                                                        data-book_id="{{$misc->id}}"
                                                                        data-supplier="" 
                                                                        data-country="{{ $tour->country_id }}">Post
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        </label>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </td>	                                     
                                    </tr>			
                                @endforeach
                                <tr>
                                    <td colspan="11" class="text-right">
                                        <h5>
                                        <strong>
                                            @if(!empty($miscTotal))
                                                Total: {{Content::money($miscTotal)}} {{Content::currency()}}
                                            @endif
                                            {{$misckTotal > 0 && $miscTotal > 0 ? ',&nbsp;':''}}
                                            @if(!empty($misckTotal))
                                                ,&nbsp;	Total: {{Content::money($misckTotal)}}  {{Content::currency(1)}}
                                            @endif
                                        </strong>
                                        </h5>
                                    </td>
                                </tr>
                            @endif
                        <!-- MISC End -->
                        <?php 
							$grandtotal = $totalHotel + $flightBook->sum('book_namount') + $golfBook->sum('book_namount') + $cruiseBook->sum('net_amount') + $restBook->sum('amount') + $entranAmount + $transportTotal + $guidTotal + $miscTotal;
							$grandktotal = $flightBook->sum('book_kamount') + $golfBook->sum('book_kamount') + $restBook->sum('kamount') + $entranKAmount + $transportkTotal + $guidkTotal + $misckTotal;
						?>
                        <!--  The End-->
                    </table>
                </form>
            @endif         
           
        </div>
    </div>
    <br><br>
	@if(isset($project->project_number))
		<?php 
			$getGuideBooked = \App\Supplier::where(["supplier_status"=>1, 'country_id' => \Auth::user()->country_id])->whereIn("business_id", [6, 7])->orderBy('created_at', 'desc')->get();
		?>
		<div class="modal" id="loadProjectConfirm" role="dialog"  data-backdrop="static" data-keyboard="true">
			<div class="modal-dialog modal-lg" >    
			    <form method="POST" action="{{route('makeToJournal')}}">
			    	{{csrf_field()}} 
		        	<div class="hidden-group">
			          	<input type="hidden" name="project_number" id="project_number" value="{{{$project->project_number or ''}}}">
			          	<input type="hidden" name="project_id" id="project_id" value="{{{$project->id or ''}}}">
			          	<input type="hidden" name="project_fileno" id="project_fileno" value="{{{$project->project_fileno or ''}}}">
			          	<input type="hidden" name="business_id" id="business_id"> 
			          	<input type="hidden" name="supplier_id" id="supplier_name"> 
			          	
			          	<input type="hidden" name="bus_type" id="bus_type"> 
			          	<input type="hidden" name="process_type" id="process_type"> 
			          	<input type="hidden" name="book_id" id="book_id"> 
					</div>
			      	<div class="modal-content">        
				        <div class="modal-header" id="loadProjectConfirmheader" style="cursor: move;">
				          	<button type="button" class="close" data-dismiss="modal">&times;</button>
				          	<h4 class="modal-title" id="modal-title"><strong>Make to Cash Transaction For Project No. {{{ $project->project_number or ''}}}</strong></h4>
				        </div>
				        <div class="modal-body">
				        	<div class="row">
				        		@if(Auth::user()->role_id == 2)
				        		<div class="col-md-4 col-xs-12">
						        	<div class="form-group">
					                  	<label class="col-sm-3 text-right" style="padding-top: 7px;">Country</label>
					                  	<div class="col-sm-9">
					                    	<select class="form-control AccountNameByCountry" name="country" data-type="account_name" required>
					                          	@foreach(App\Country::where('country_status',1)->whereHas('accountName')->orderBy('country_name')->get() as $key => $con)
					                          		<option value="{{$con->id}}">{{$con->country_name}}</option>
					                          	@endforeach()
					                        </select>
					                  	</div>
					                  	<div class="clearfix"></div>
						            </div>
						        </div>
						        @endif
						        <div class="col-md-5 col-xs-12">
						        	<div class="form-group">
					                  	<label class="col-sm-4 text-right" style="padding-top: 7px;">Record Date</label>
					                  	<div class="col-sm-5">
					                    	<input type="text" name="pay_date"   class="form-control book_date" readonly="" value="{{date('Y-m-d')}}">
					                  	</div>
					                  	<div class="clearfix"></div>
						            </div>
						        </div>
				              	<table class="table">
					                <tr class="table-head-row">
						                <th width="150px">Account Type <span style="color: red">*</span></th>
						                <th >Account Name <span style="color: red">*</span></th>
						                <th width="120px">Debit</th>
						                <th width="120px">Credit</th>
						                <th width="120px">{{Content::currency(1)}} Debit</th>
						                <th width="120px">{{Content::currency(1)}} Credit</th>
					                </tr>
					                <tbody id="data_payment_option">
						                <tr>
											<?php $acc_type = App\AccountType::where(['status'=>1])->orderBy('account_name')->get(); ?>
						                    <td class="container_account_type">
							                	<select class="form-control account_type input-sm" name="account_type" data-type="account_name" required>
							                		<option>--choose--</option>
							                		@foreach($acc_type as $acc_type)
							                			<option class="value" value="{{$acc_type->id}}">{{$acc_type->account_name}}</option>
							                		@endforeach
						                      	</select>
						                    </td>
						                    <td style="position: relative;">
					                    		<div class="btn-group" style='display: block;'>
					                    			<button type="button" class="form-control input-sm arrow-down" data-toggle="dropdown" aria-haspopup="false" aria-expanded='false' data-backdrop="static" data-keyboard="false" role="dialog" data-backdrop="static" data-keyboard="false">
						                    			<span class="pull-left"></span>
						                    			<span class="pull-right"></span>
						                    		</button> 
							                    	<div class="obs-wrapper-search" style="max-height:250px; overflow: auto; ">
							                    		<div>
							                    			<input type="text" data-url="{{route('getFilter')}}" id="search_Account" required onkeyup="filterAccountName()" class="form-control input-sm" required>
							                    		</div>
							                    		<ul id="myAccountName" class="list-unstyled dropdown_account_name">
							                    		</ul>
							                    	</div>
							                    </div>
						                    </td>
						                    <td>
						                      	<input type="text" class="debit form-control input-sm text-right" data-type="debit" name="debit" id="debit" placeholder="00.0" readonly="">
						                    </td>
						                    <td>
						                      	<input type="text" class="credit form-control input-sm text-right" data-type="credit" name="credit" id="credit" placeholder="00.0" readonly="">
						                    </td>
						                    <td>
						                      	<input type="text" class="kyat-debit form-control input-sm text-right" data-type="kyat-debit" name="kyatdebit" id="kyat-debit" placeholder="00.0" readonly="">
						                    </td>
						                    <td>
						                      	<input type="text" class="kyat-credit form-control input-sm text-right" data-type="kyat-credit" name="kyatcredit" id="kyat-credit" placeholder="00.0" readonly="">
						                    </td>
						                </tr>
						            
						                <tr>
						                	<td colspan="7" style="border-top: none;">
						                		<label>Descriptions</label>
						                		<textarea class="form-control" rows="4" name="payment_desc" placeholder="Type Descriptions here...!"></textarea>
						                	</td>
						                </tr>
					                </tbody>
					            </table>
					        </div>
				        </div>
				        <div class="modal-footer ">
				          	<div class="text-center">
				            	<button class="btn btn-info btn-sm" id="btnUpdateAccount">Save</button>
				            	<a href="#" class="btn btn-default btn-sm btn-acc" data-dismiss="modal">Cancel</a>
				          	</div>
				        </div>
			      	</div>      
			    </form>
			</div>
		</div>

		<div class="modal" id="myAlert" role="dialog" data-backdrop="static" data-keyboard="true">
		  <div class="modal-dialog modal-sm">    
		    <form method="POST" action="" id="add_new_account_form">
		      <div class="modal-content">        
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title"><strong>Supplier Missing</strong></h4>
		        </div>
		        <div class="modal-body">
		          <strong id="message">Please update supplier name </strong>        
		        </div>
		        <div class="modal-footer">
		          <div class="text-center">
		            <a href="#" class="btn btn-info btn-xs" data-dismiss="modal">OK</a>
		          </div>
		        </div>    
		      </div>  
		    </form>
		  </div>
		</div>

		<div class="modal" id="LoadSupplier" role="dialog" data-backdrop="static" data-keyboard="true" >
			<div class="modal-dialog modal-lg">    
			    <form method="POST" action="{{route('createSupplier')}}" id="Add_New_supplier">
			      <div class="modal-content">        
			        <div class="modal-header">
			          	<button type="button" class="close" data-dismiss="modal">&times;</button>
			          	<h4 class="modal-title"><strong>Add New Supplier</strong></h4>
			        </div>
			        <div class="notify-message"></div>
			        <div class="modal-body">
					   	{{csrf_field()}}    
					    <input type="hidden" name="eid" id="eid">
				        <div class="row">
			                <div class="col-md-6 col-xs-12">
			                    <div class="form-group">
			                      	<label>Supplier name<span style="color:#b12f1f;">*</span></label> 
			                      	<input autofocus="" type="text" placeholder="Tour Name" class="form-control" name="title" required>
			                    </div> 
			                </div>        
			                <div class="col-md-3 col-xs-6">
			                    <div class="form-group">
			                      	<label>Country <span style="color:#b12f1f;">*</span></label> 
			                      	<select class="form-control country" name="country" data-type="country" required>
				                    @foreach(App\Country::where('country_status',1)->whereHas('province')->orderBy('country_name')->get() as $con)
				                        <option value="{{$con->id}}" >{{$con->country_name}}</option>
				                    @endforeach
			                      	</select>
			                    </div> 
			                </div>
			                <div class="col-md-3 col-xs-6">
			                    <div class="form-group">
			                      	<label>City <span style="color:#b12f1f;">*</span></label> 
			                      	<select class="form-control" name="city" id="dropdown-country" required>
				                        @foreach(App\Province::where(['province_status'=> 1, 'country_id'=> Auth::user()->country_id ])->orderBy('province_name')->get() as $pro)
				                          <option value="{{$pro->id}}">{{$pro->province_name}}</option>
				                        @endforeach
			                      	</select>
			                    </div> 
			                </div>
			                <div class="col-md-6 col-xs-6">
			                    <div class="form-group">
			                      	<label>Business Type<span style="color:#b12f1f;">*</span></label>
				                    <select class="form-control" name="business_type">
				                        <option value="0">--Select--</option>
				                        @foreach(App\Business::where(['category_id'=>0, 'status'=>1])->orderBy('name', 'ASC')->get() as $key=>$cat)
				                            <option value="{{$cat->id}}" {{$type == $cat->slug ?'selected':''}}>{{$cat->name}}</option>
				                        @endforeach
			                      	</select>
			                    </div>
			                </div> 
			                <div class="col-md-3 col-xs-6">
			                    <div class="form-group">
			                      	<label>Phone<span style="color:#b12f1f;">*</span></label>
				                    <input class="form-control" name="supplier_phone" placeholder="+855987 654 321">
			                    </div>
			                </div> 
			                <div class="col-md-3 col-xs-6">
			                    <div class="form-group">
			                      	<label>Email<span style="color:#b12f1f;">*</span></label>
				                    <input class="form-control" name="supplier_email" placeholder="virak@asia-expeditions.com">
			                    </div>
			                </div> 
		                	<div class="col-md-12 col-xs-12">
			                    <div class="form-group">
			                      	<label>Description <span style="color:#b12f1f;">*</span></label> 
			                      	<textarea class="form-control" rows="6" name="desc" placeholder="Description here...!"></textarea>
			                    </div> 
			                </div>		
			          	</div>
			        </div>
			        <div class="modal-footer">
			          <div class="text-center">
			            <button class="btn btn-info btn-sm" id="btnAddSupplier" type="submit">Save</button>
			            <a href="#" class="btn btn-default btn-sm btn-acc" data-dismiss="modal">Close</a>
			          </div>
			        </div>    
			      </div>  
			    </form>
			</div>
		</div>


	<script type="text/javascript" src="{{asset('js/jquery.table2excel.min.js')}}"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(document).on("click", ".AddToACcount", function(){
				var bustype = $(this).data("type"),
					supplier_id = $(this).data("supplier"),
					bus_type = $(this).data("bus_type"),
					kamount = $(this).data("kamount"),
					amount = $(this).data("amount"), 
					bookkId = $(this).data("book_id"),
					process_type = $(this).data("process_type"),
					coun_id = $(this).data("country");						
					$(".location option").each( function(i){
		                if($(this).val() == coun_id){
		                    $(this).attr("selected", true);
		                }else{
		                    $(this).removeAttr("selected", true);
		                }
		            });
					$("#business_id").val(bustype);
					$("#supplier_name").val(supplier_id);
					$("#country").val(coun_id);
					$("#credit").val("");
					$("#debit").val("");
					$("#kyat-debit").val("");
					$("#kyat-credit").val("");
					if (process_type == "pay") {
						$("#debit").val(amount);	
						$("#kyat-debit").val(kamount);
						$("#modal-title strong").text("Make Cash Transaction for [" + $(this).data('title')+ "]");
					}else{
						$("#modal-title strong").text("Receive Cash Transaction from [" + $(this).data('title')+ "]");
						$("#credit").val(amount);	
						$("#kyat-credit").val(kamount);
					}
					$("#process_type").val(process_type);
					$("#book_id").val(bookkId);
					$("#bus_type").val(bus_type);
				var data_row = "<tr class='supplier_row'><td colspan='12'><div class='form-group'><label>Supplier<span style='color:#b12f1f;'>*</span></label><div class='btn-group' style='display: block;'><button  type='button' class='form-control arrow-down' data-toggle='dropdown' aria-haspopup='false' aria-expanded='false' data-backdrop='static' data-keyboard='false' role='dialog' data-backdrop='static' data-keyboard='false'><span class='pull-left'></span><span class='pull-right'></span></button> <div class='obs-wrapper-search'><div><input type='text' data-url='{{route('getFilter')}}' id='search' onkeyup='myFunction()' class='form-control' required></div><ul class='dropdown-data' id='myUL' style='width: 100%;' ><li  data-toggle='modal' data-target='#LoadSupplier'><a><i class='fa fa-plus'></i> Add Supplier</a> </li>@foreach($getGuideBooked as $key => $gb)@if(!empty($gb->id))<li class='list' style=' padding: 4px 0px !important;'><label style='position: relative;top: 3px; font-weight: 400;'><input type='radio' name='supplier_name' value='{{$gb->id}}'><span style='position:relative; top:-2px;'>  {{{ $gb->supplier_name or '' }}}</span></label></li>  @endif @endforeach<div class='clearfix'></div></ul></div></div></div></td></tr>";
				$("tbody#data_payment_option tr.supplier_row").remove();
				if (bustype == 55 || bustype == 54 ) {
					$("tbody#data_payment_option tr:first-child").after(data_row);
				}
			});	

			$(".myConvert").click(function(){
				if(confirm('Do you to export in excel?')){
					$(".table").table2excel({
						exclude: ".noExl",
						name: "Operation Expenses {{$project->project_number}}",
						filename: "Operation Expenses {{$project->project_number}}",
						fileext: ".xls",
						exclude_img: true,
						exclude_links: true,
						exclude_inputs: true
					});
					return false;
				}else{
					return false;
				}
			});
		});      
	</script>
	@endif	
	<script>
		function myFunction() {
	        input = document.getElementById("search");
	        filter = input.value.toUpperCase();
	        ul = document.getElementById("myUL");
	        li = ul.getElementsByClassName ("list");
	        for (i = 0; i < li.length; i++) {
	            a = li[i];
	            if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
	              li[i].style.display = "";
	            } else {
	              li[i].style.display = "none";
	            }
	        }
	    }

	    function filterAccountName(){
	        input = document.getElementById("search_Account");
	        filter = input.value.toUpperCase();
	        ul = document.getElementById("myAccountName");
	        li = ul.getElementsByClassName ("list");
	        for (i = 0; i < li.length; i++) {
	            a = li[i];
	            if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
	              li[i].style.display = "";
	            } else {
	              li[i].style.display = "none";
	            }
	        }
	    }
	</script>
	<script type="text/javascript" src="{{asset('adminlte/js/dragdrop.js')}}"></script>
	@include("admin.account.accountant")

	@include("admin.include.datepicker")
@endsection
