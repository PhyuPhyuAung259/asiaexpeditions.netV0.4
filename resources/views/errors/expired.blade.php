
@extends('layout.backend')
@section('title', 'Account Expired')
@section('content')
<header class="main-header" >
    <nav class="navbar navbar-static-top" style="margin-left: 0px; text-align: center;">
    	<img src="{{url('img/jngicon.png')}}" width="150">
      	<h3 style="text-transform: capitalize;">{{{ isset(Auth::user()->company) : Auth::user()->company->title : '' }}} Expired</h3>
      	<h4>Please contact to techniqcal for supporting.<br>Email: virak@jngtravelpro.com</h4>
    </nav>
</header>
@endsection
