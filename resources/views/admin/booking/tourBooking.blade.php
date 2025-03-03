@extends('layout.backend')
@section('title', 'Tour Booking')
<?php $active = 'booked/project';
$subactive ='booked/tour';
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
          <div class="col-lg-12"><h3 class="border">Tour Booking Form 1</h3></div>
          <form method="POST" action="{{route('updateBooked', 'tour')}}">
              {{csrf_field()}}
              <input type="hidden" name="bookId" value="{{$book->id}}">
              <section>                              
                  <div class="row">
                    <div class="col-md-3 col-xs-12">
                      <div class="form-group">
                        <label>Tour Date <span style="color:#b12f1f;">*</span></label> 
                        <input type="text" class="form-control book_date" name="book_start" value="{{$book->book_checkin}}" placeholder="Tour Date" readonly>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Country <span style="color:#b12f1f;">*</span></label> 
                        <select class="form-control country" name="country" data-type="country" data-locat="data" required>
                          @foreach(App\Country::where('country_status', 1)->orderBy('country_name')->get() as $con)
                            <option value="{{$con->id}}" {{$book->country_id == $con->id ? 'selected':''}}>{{$con->country_name}}</option>
                          @endforeach
                        </select>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>City <span style="color:#b12f1f;">*</span></label> 
                        <select class="form-control book_City province" name="city" id="dropdown-data" data-type="pro_tour">
                          @foreach(App\Province::where(['province_status'=> 1, 'country_id'=> $countryId ])->orderBy('province_name')->get() as $pro)
                            <option value="{{$pro->id}}" {{$pro->id == $book->province_id ? 'selected':''}}>{{$pro->province_name}}</option>
                          @endforeach
                        </select>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Tour Name <span style="color:#b12f1f;">*</span></label> 
                        <select class="form-control booking_name" name="tour_name" id="dropdown-booking" data-type="book_tour" required>
                          @foreach(App\Tour::where(['tour_status'=> 1, 'province_id'=> $book->province_id ])->orderBy('tour_name')->get() as $tour)
                            <option value="{{$tour->id}}" {{$tour->id == $book->tour_id ? 'selected':''}}>{{$tour->tour_name}}</option>
                          @endforeach
                        </select>
                      </div> 
                    </div>        
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Number of Pax<span style="color:#b12f1f;">*</span></label> 
                        <select class="form-control bookTourPax" name="book_pax" id="dropdown-tourPax">
                          @foreach(App\TourPrice::where(['status'=> 1, 'tour_id'=> $book->tour_id ])->orderBy('pax_no')->get() as $tprice)
                            @if($tprice->sprice > 0 || $tprice->nprice > 0)
                            <option value="{{$tprice->pax_no}}" data-price="{{$tprice->sprice}}" data-nprice="{{$tprice->nprice}}" {{$tprice->pax_no == $book->book_pax ? 'selected':''}}>{{$tprice->pax_no}} = {{$tprice->sprice}} {{Content::currency()}}</option>
                            @endif
                          @endforeach
                        </select>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Price {{Content::currency()}}</label> 
                        <input type="text" name="book_price" id="book_price" class="form-control" value="{{$book->book_price}}" readonly="" placeholder="00.0" >
                        <input type="hidden" name="book_nprice" id="book_nprice" value="{{$book->book_nprice}}">
                      </div>
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Amount {{Content::currency()}}</label> 
                        <input type="text" name="book_amount" id="book_amount" class="form-control" value="{{$amount}}" number_only" placeholder="00.00" readonly="">
                      </div>
                    </div>
                  </div>
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
