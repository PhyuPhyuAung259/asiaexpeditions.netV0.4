@extends('layout.backend')
@section('title', config('app.title'))
<?php $action = 'student'; ?>

@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
            <section class="col-lg-12 connectedSortable">
               <div ng-view></div>
            </section>          
          <!--   <section class="col-lg-5 connectedSortable">
            </section> -->
        </div>
    </section>
  </div>
</div>
@endsection
