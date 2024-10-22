<?php 
  $comadd = App\Company::find(1);
?>
<nav class="navbar navbar" style="background-color: #00a65a; border-radius: 0px;" >
  <div class="container text-center">
    <h3><a href="#" style="text-decoration: none; color: white; font-style: 25px;">{{$comadd->title}}</a></h3>
    
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
    </div>
    <div class="collapse navbar-collapse text-center" id="myNavbar" >
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Documentation</a></li>
        <li><a href="#">Policies</a></li>
        <li><a href="#">Mission</a></li>
      </ul>
    </div>
  </div>
</nav>
