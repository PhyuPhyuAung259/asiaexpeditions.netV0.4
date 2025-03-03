@extends('layout.backend')
@section('title', 'Hotel Booking')
<?php $active = 'booked/project';
$subactive ='booked/hotel';
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
          <div class="col-lg-12"><h3 class="border">Hotel Booking</h3></div>
          <form method="POST" action="{{route('updateBooked', 'Hotel')}}">
              {{csrf_field()}}
              <input type="hidden" name="bookId" value="{{$book->id}}">
              <section class="col-lg-9">                              
                  <div class="row">
                    <div class="col-md-3 col-xs-12">
                      <div class="form-group">
                        <label>Check-In<span style="color:#b12f1f;">*</span></label> 
                        <input type="text" class="form-control" id="from_date" name="book_start" value="{{$book->book_checkin}}"  placeholder="Tour Date">
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-12">
                      <div class="form-group">
                        <label>Check-Out </label> 
                        <input type="text" class="form-control" id="to_date" name="book_end" value="{{$book->book_checkout}}"  placeholder="Tour Date" required >
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Country <span style="color:#b12f1f;">*</span></label> 
                        <select class="form-control country" name="country" data-type="country" required>
                          @foreach(App\Country::where('country_status', 1)->orderBy('country_name')->get() as $con)
                            <option value="{{$con->id}}" {{$book->country_id == $con->id ? 'selected':''}}>{{$con->country_name}}</option>
                          @endforeach
                        </select>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>City <span style="color:#b12f1f;">*</span></label> 
                        <select class="form-control book_City" name="city" id="dropdown-data" data-type="pro_hotel">
                          @foreach(App\Province::where(['province_status'=> 1, 'country_id'=> $countryId ])->orderBy('province_name')->get() as $pro)
                            <option value="{{$pro->id}}" {{$pro->id == $book->province_id ? 'selected':''}}>{{$pro->province_name}}</option>
                          @endforeach
                        </select>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Hotel Name <span style="color:#b12f1f;">*</span></label>
                        <select class="form-control booking_name" name="hotel_name" id="dropdown-booking"  required>
                          @foreach(App\Supplier::where(['supplier_status'=>1, 'business_id'=> 1, 'province_id'=> $book->province_id])->orderBy('supplier_name')->get() as $sup)
                            <option value="{{$sup->id}}" {{$sup->id==$book->hotel_id?'selected':''}}>{{$sup->supplier_name}}</option>
                          @endforeach
                        </select>
                      </div> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                      <div class="form-group">
                        <label>Pax No.</label> 
                        <select class="form-control book_FlightPax" name="book_pax">
                          @for($n=1; $n<=49; $n++)
                            <option value="{{$n}}" {{$n==$book->book_pax?'selected':''}}>{{$n}}</option>
                          @endfor
                        </select>
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
