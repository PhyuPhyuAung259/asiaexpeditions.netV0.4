@extends('layout.backend')
@section('title', 'Update User')
<?php $active = 'users'; 
$subactive ='user/register';
  use App\component\Content;
  $countryId = Auth::user()->country_id ? Auth::user()->country_id : 0;
 
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
      @include('admin.include.message')
      <div class="row">          
        <div class="alert alert-warning"> You don't have permission to access in this page</div>
      </div>
    </section>
  </div>  
</div>  
@endsection
