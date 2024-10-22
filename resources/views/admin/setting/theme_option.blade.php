@extends('layout.backend')
@section('title', 'Setting')
@section('active', 'active')
<?php 

  $active = 'setting-options'; 
  $subactive = 'account';
  use App\component\Content;
  $countryId = Auth::user()->country_id ? Auth::user()->country_id : 0;
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
      <div class="col-md-9">
        <div class=" box box-success with-border">
          <h5 class="box-header" style="margin-top:0px; margin-bottom:0px; background-color:#F4F4FB; padding: 25px; border: solid 1px #ecf0f5;"><strong>Theme Option</strong> <span class="pull-right"><i class="fa fa-tasks" style="font-size: 25px;"></i> </span></h5>
            <div class="tabbable tabs-left">
              <ul class="nav nav-tabs">
                <li><a href="#general-setting" data-toggle="tab"><i class="fa fa-asterisk"></i>&nbsp;&nbsp;General Setting</a></li>
                <li class="active"><a href="#header-setting" data-toggle="tab"><i class="fa fa-mortar-board (alias)"></i>&nbsp;&nbsp;Header Setting</a></li>
                <li><a href="#footer-setting" data-toggle="tab"><i class="fa fa-rebel"></i>&nbsp;&nbsp;Footer Setting</a></li>
                <li><a href="#style-setting" data-toggle="tab"><i class="fa fa-yen (alias)"></i>&nbsp;&nbsp;Style Setting</a></li>
                <li><a href="#social-setting" data-toggle="tab"><i class="fa fa-share-alt-square"></i>&nbsp;&nbsp;Social Setting</a></li>
                <li><a href="#company-profile" data-toggle="tab"><i class="fa fa-sliders"></i>&nbsp;&nbsp;Conpany Profile</a></li>
              </ul>
              <div class="tab-content" style="padding-top: 12px;">
                <div class="tab-pane fade in " id="general-setting">
                  <form method="post" action="">
                    <b>General setting</b>
                    <p>These lists are meant to identify articles which deserve editor attention because they are the most important for an encyclopedia to have, as determined by the community of participating editors. They may also be of interest to readers as an alternative to lists of overview articles.</p>            
                    <button class="btn btn-info btn-xs" id="general-setting">Save</button>     
                  </form>
                </div>
                <div class="tab-pane fade in active" id="header-setting">
                  <div class="col-md-8">
                      <img src="">
                  </div>
                </div>
                <div class="tab-pane fade in " id="footer-setting"> 
                  <div class="">
                    <strong>Footer Setting</strong>
                    <p>because they are the most important for an encyclopedia to have, as determined by the community of participating editors. They may also be of interest to readers as an alternative to lists of overview articles.</p>                 
                  </div>
                </div>              
                <div class="tab-pane fade in" id="style-setting"> 
                  <div class="">
                    <strong>Style Setting</strong>
                    <p>meant to identify articles which deserve editor attention because they are the most important for an encyclopedia to have, as determined by the community of participating editors. They may also be of interest to readers as an alternative to lists of overview articles.</p>                 
                  </div>
                </div>              
                <div class="tab-pane fade in" id="social-setting"> 
                   <div class="">
                    <strong>Social Setting</strong>
                    <p>deserve editor attention because they are the most important for an encyclopedia to have, as determined by the community of participating editors. They may also be of interest to readers as an alternative to lists of overview articles.</p>
                   </div>
                </div>
                <div class="tab-pane fade in" id="company-profile"> 
                  <form method="post" action="">
                    <div class="col-md-8 col-xs-12">
                      <div class="form-group">

                      </div>
                    </div>
                    <div class="col-md-8 col-xs-12">
                    </div>
                    <div class="col-md-5 col-xs-12">
                      <div class="form-group">
                        <label>Service Name</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Service Name">
                      </div>
                    </div>
                    <div class="col-md-5 col-xs-12">
                      <div class="form-group">
                        <label>Service Name</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Service Name">
                      </div>
                    </div>
                    <div class="col-md-5 col-xs-12">
                      <div class="form-group">
                        <label>Service Name</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Service Name">
                      </div>
                    </div>
                    <div class="col-md-8 col-xs-12">
                        <button class="btn btn-primary btn-sm">Save </button>
                    </div>
                  </form>
                </div>
              </div>
            </div><div class="clearfix"></div>  
        </div>
      </div>
    </section>
  </div>
</div>
@endsection
