<?php
use App\component\Content;
$comadd = App\Company::find(1);
$logo = Storage::url('avata/' . $comadd->logo);
?>
<header class="main-header">
    <!-- Logo -->
    <a href="{{ route('adminhome') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="{{ $logo }}" style="width: 100%;"></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><img src="{{ $logo }}" style="width: 15%;"></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <h3 class="pull-left" id="name">{{ $comadd->title }}</h3>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ asset('storage/avata/' . Auth::user()->picture) }}" class="user-image">

                        <span class="hidden-xs"></span>
                        <div class="clearfix"></div>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="{{ asset('storage/avata/thumbnail/' . Auth::user()->picture) }}"
                                class="img-circle">
                            <p>
                                {{ Auth::user()->fullname }} - {{ Auth::user()->position }}
                                <small>Member since {{ date('d-M-Y', strtotime(Auth::user()->created_at)) }}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ route('userStore', ['id' => Auth::user()->id]) }}"
                                    class="btn btn-default btn-flat">Edit Profile</a>
                            </div>
                            <div class="pull-right">
                                <form method="GET" action="{{ route('logOut') }}">
                                    <input type="submit" name="btnSignout" value="Sign out"
                                        class="btn btn-default btn-flat">
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>


<div class="custom-notif alert  alert-dismissible" style="display: none;">
    <span style="padding: 2px 12px;" class="close" data-dismiss="alert" aria-label="close">&times;</span>
    <i class="fa"></i>
    <strong id="message-title">Welcome for nitification</strong>
</div>
<div class="clearfix"></div>
