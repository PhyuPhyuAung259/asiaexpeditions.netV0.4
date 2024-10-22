
<?php
  $comadd = \App\Company::find(1);
?>
<!DOCTYPE html>
<html>
<head>
  <title>{{ $message }} Not found - {{$comadd->title}}</title>
  <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
</head>
<body>
<br>
  <div class=" text-center">
      <center>
        <h3><strong style="text-shadow: 0px 1px 3px #f8f9fa;font-weight: 700;">{{$comadd->title}}</strong></h3>
      </center>
      <div style="padding: 12px;background-color: #293A4A;color: #FFFFFF;">  
          <h3 style="margin-top: 9px;"><i class="fa fa-warning text-yellow"></i> 404 Oops! <strong>{{$message }}</strong> was not found.</h3>
      </div>
      <a href="{{url()->previous()}}" class="btn btn-link">Go Back</a>
  </div>

</body>
</html>