@extends('layout.backend')
@section('title', 'System')
@section('active', 'active')
<?php $active = 'active'; 
  $subactive ='users';?>

@section('content')
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content">        
        @if(Auth::check())   
          <?php 
              $depart_id  = [];
              $roleDepart = \App\Role::find(\Auth::user()->role_id);
              if (!empty($roleDepart->department )) {
                foreach ($roleDepart->department as $key => $dep) {
                    $depart_id[] = $dep->pivot->department_id;
                }
              }
              $getDepart =\App\Department::where(['status'=>1, 'type'=>2])->whereIn("id", $depart_id)->orderBy('order')->get();
            ?>   
          @foreach($getDepart as $key=> $dep )
            <?php
              $getSubDepartment = \App\DepartmentMenu::getDepartment_menu($dep->id);
              ?>
              @if($getSubDepartment->count() > 0)
                <div class="col-sm-2 col-md-2 col-lg-1 col-xs-4 no-padding ">
                    <a href="{{route('adminhome')}}/{{$dep->slug}}">
                      <div class="small-box btn btn-default" style="{{$dep->style}}">
                          <div class="icon">
                            <i class="{{$dep->icon}}"></i>
                          </div>
                          <div>
                          <span class="text-shadow">{{$dep->name}}</span>
                        </div>  
                      </div>
                    </a>
                </div>     
              @endif
          @endforeach
        @endif
    </section>
  </div>
@endsection
