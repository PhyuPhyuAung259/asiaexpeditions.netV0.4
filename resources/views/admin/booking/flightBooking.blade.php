@extends('layout.backend')
@section('title', 'Flight Booking')
<?php $active = 'booked/project';
$subactive ='booked/flight';
  use App\component\Content;
  $countryId = $book->country_id != null ? $book->country_id : Auth::user()->country_id;
  $amount = number_format(($book->book_price * $book->book_pax), 2);
?>
@section('content')
  <div class="wrapper">
    @include('admin.include.header')
    @include('admin.include.menuleft')
    <div class="content-wrapper">
      <section class="content"> 
        <div class="row">
          @include('admin.include.message')
          <div class="col-lg-12"><h3 class="border">Flight Booking</h3></div>
          <form method="POST" action="{{route('updateBooked', 'flight')}}">
              {{csrf_field()}}
              <input type="hidden" name="bookId" value="{{$book->id}}">
              <section class="col-lg-9">                              
                  <div class="row">
                    <div class="col-md-3 col-xs-12">
                      <div class="form-group">
                        <label>Flight Date <span style="color:#b12f1f;">*</span></label> 
                        <input type="text" class="form-control book_date" name="book_start" value="{{$book->book_checkin}}"  placeholder="Tour Date" required >
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Country <span style="color:#b12f1f;">*</span></label> 
                        <select class="form-control country" name="country" data-type="country_flight" required>
                          @foreach(App\Country::where('country_status', 1)->orderBy('country_name')->get() as $con)
                            <option value="{{$con->id}}" {{$book->country_id == $con->id ? 'selected':''}}>{{$con->country_name}}</option>
                          @endforeach
                        </select>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>City <span style="color:#b12f1f;">*</span></label> 
                        <select class="form-control province" name="city" id="dropdown-data" data-type="pro_Book_flight">
                          <?php 
                            $cityFlight = \App\FlightSchedule::where(['flight_status'=>1, 'country_id'=> $countryId])->groupBy('province_id')->orderBy('flight_from', 'DESC')->get();
                          ?>
                          @if($cityFlight->count() > 0){
                            <option value="0">Choose City</option>
                            @foreach ($cityFlight as $key => $pro)
                              <?php 
                                $fl = \App\Province::find($pro->province_id);
                              ?>
                              @if($fl)
                                <option value="{{$fl->id}}" {{$fl->id == $book->province_id ? 'selected':''}}>{{$fl->province_name}}</option>
                              @endif
                            @endforeach
                          @endif
                        </select>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Destination <span style="color:#b12f1f;">*</span></label> 
                       <select class="form-control city_destination"​​​ id="city_destination" name="city_destination" data-type="single_city_destination">
                          <option value="0">Destination</option>
                        @if($book->city_destination)
                          <option value="{{$book->city_destination}}" selected>{{$book->city_destination}}</option>
                        @endif
                       </select>
                      </div> 
                    </div>        
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Flight Number<span style="color:#b12f1f;">*</span></label> 
                        <select class="form-control Book_FlightNo" data-type="flightno" name="flight_name" id="dropdown-FlightNo" style="font-size: 12px;">
                          @foreach(App\FlightSchedule::where(['flight_status'=> 1, 'supplier_id'=>$book->supplier_id])->orderBy('flightno')->get() as $fno)
                            <option value="{{$fno->id}}" {{$fno->id == $book->flight_id ? 'selected':''}}>{{$fno->flightno}} - D:{{$fno->dep_time}} -> A:{{$fno->arr_time}}</option>
                          @endforeach
                        </select>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Ticketing Agent <span style="color:#b12f1f;">*</span></label> 
                        <?php 
                        $gatFAgent = App\FlightSchedule::find($book->flight_id); 
                        ?>
                        <select class="form-control book_FlightAgent" name="ticketing" id="dropdown-TicketingAgent"  style="font-size: 12px;">
                          @if( isset($gatFAgent->flightagent) )
                              <option value="0" class="no_data">Select Flight Agent</option>
                            @foreach($gatFAgent->flightagent as $sch)
                              <option value="{{$sch->id}}" data-oneway="{{$sch->pivot->oneway_price}}" 
                                      data-return="{{$sch->pivot->return_price}}" 
                                      data-noneway="{{$sch->pivot->oneway_nprice}}" 
                                      data-nreturn="{{$sch->pivot->return_nprice}}" 
                                      data-koneway="{{$sch->pivot->oneway_kprice}}" 
                                      data-kreturn="{{$sch->pivot->return_kprice}}" 
                                      {{$sch->id == $book->book_agent?'selected':''}}>{{$sch->supplier_name}}</option>
                            @endforeach
                          @else 
                            <option value="">No Agent</option>
                          @endif
                        </select>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Pax No.</label> 
                        <select class="form-control book_FlightPax" name="book_pax">
                          @for($n=1; $n<=29; $n++)
                            <option value="{{$n}}" {{$n==$book->book_pax?'selected':''}}>{{$n}}</option>
                          @endfor
                        </select>
                      </div>
                    </div>                    
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <div><label>Book Way</label></div>
                        <label style="font-weight:400;"> 
                          <input type="radio" class="bookway" name="book_way" value="Oneway" {{$book->book_way == 'Oneway'? 'checked':''}} /><span style="position: relative;top:-2px;">Oneway</span></label>&nbsp;&nbsp;
                        <label style="font-weight: 400;"> 
                          <input type="radio" class="bookway" name="book_way" value="Return" {{$book->book_way == 'Return'? 'checked':''}} /><span style="position: relative;top:-2px;">Return</span></label>
                      </div>
                    </div><div class="clearfix"></div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Price {{Content::currency()}}</label> 
                        <input type="text" name="book_price" id="book_price" class="form-control" value="{{$book->book_price}}" placeholder="00.0" readonly>
                        <input type="hidden" name="book_nprice" id="book_nprice" value="{{$book->book_nprice}}">
                        <input type="hidden" name="book_kprice" id="book_kprice" value="{{$book->book_kprice}}">
                      </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Amount {{Content::currency()}}</label> 
                        <input type="text" name="book_amount" id="book_amount" class="form-control" value="{{$book->book_amount}}" placeholder="00.0" readonly>
                      </div>
                    </div>
                    
                </div>
              </section>
              <section class="col-lg-3 ">
                <div class="panel panel-default">
                  <div class="panel-body">
                    <div class="form-group">
                      <div><label>Status</label></div>
                      <label style="font-weight:400;"> <input type="radio" name="status" value="1" {{$book->book_status == 1? 'checked':''}}><span style="position: relative;top:-2px;">Publish</span></label>&nbsp;&nbsp;
                      <label style="font-weight: 400;"> <input type="radio" name="status" value="0" {{$book->book_status == 0? 'checked':''}}><span style="position: relative;top:-2px;">UnPublish</span></label>
                    </div> 
                    <div class="form-group">
                            <div><label>Option Choice</label>&nbsp;</div>
                            <label style="font-weight:400;"> 
                              <input type="radio" name="option" value="0" {{$book->book_option==0?'checked':'' }} >
                              <span style="position: relative;top:-2px;">Booking</span>
                            </label>&nbsp;&nbsp;
                           
                            <label style="font-weight: 400;">
                                <input type="radio" name="option" value="1" {{$book->book_option==1?'checked':'' }}>
                                <span style="position: relative;top:-2px;">Quotation</span>
                            </label>
                            
                        </div>
                  </div>
                  <div class="panel-footer text-center">
                    <button type="submit" class="btn btn-success btn-flat btn-sm">Submit</button> &nbsp;
                    <a href="{{route('projectList', ['url'=> 'tour'])}}" class="btn btn-default btn-flat btn-sm">Go Bak</a>
                  </div>
                </div>
              </section>
          </form>
        </div>
      </section>
    </div>  
  </div>
  @include('admin.include.datepicker')
@endsection
