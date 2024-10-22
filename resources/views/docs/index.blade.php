@extends('layout.frontend')
@section('title')
<?php 
	$depart = '';
	$sub_depart = '';
?>
@section('content')
@include("include.menu_navbar")
  	<div class="container" style="margin-bottom: 25px; ">
		<a href="{{route('adminhome')}}" class="pull-right"> <i class="fa fa-arrow-circle-left"></i> Go Dashboard</a>
		<br><br>  		
  		<div class="col-md-2" style="border-right: solid 1px #ddd;">
  			<div class="row">
		  		@include('include.menu_left_docs')
		  	</div>
	  	</div>
	  	<div class="clearfix"></div>
	  	<br><br>
		<a href="{{route('adminhome')}}" class="pull-right"> <i class="fa fa-arrow-circle-left"></i> Go Dashboard</a>
  	</div>
@endsection
