<!DOCTYPE html>
<?php 
  $comadd = App\Company::find(1);
?>
<html>

  <head>

    <meta charset="utf-8">

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>@yield('title') | {{$comadd->title}}</title>    

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="shortcut icon" type="image/x-icon" href="{{url('storage/avata')}}/{{$comadd->logo}}">
    
    <!-- <link rel="stylesheet" type="text/css" href="{{asset('adminlte/dist/css/AdminLTE_old.css')}}"> -->
    
    <link rel="stylesheet" type="text/css" href="{{asset('adminlte/css/all.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('adminlte/css/style.min.css')}}">

    

    <!-- <link rel="stylesheet" type="text/css" href="{{asset('adminlte/css/style.css')}}"> -->


    <link rel="stylesheet" type="text/css" href="{{asset('adminlte/table/datatables.min.css')}}">

    

    <link rel="stylesheet" type="text/css" href="{{asset('adminlte/table/datepicker.css')}}">

    <script type="text/javascript" src="{{asset('adminlte/js/all.min.js')}}"></script>

    <script type="text/javascript" src="{{asset('adminlte/table/datatables.min.js')}}"></script>

    <!-- <script type="text/javascript" src="/adminlte/bower_components/fastclick/lib/fastclick.js"></script> -->

    <script type="text/javascript" src="{{asset('adminlte/js/booking.js')}}"></script>
    <script type="text/javascript" src="{{asset('adminlte/js/script.min.js')}}"></script>

    <script type="text/javascript" src="{{asset('adminlte/js/apply_room.js')}}"></script>

    <script type="text/javascript" src="{{asset('adminlte/js/uploadfile.js')}}"></script>

  </head>

  <body class=" skin-green sidebar-mini sidebar-collapse">

      @yield('content')

      <script type="text/javascript" src="{{asset('adminlte/table/bootstrap-datepicker.js')}}"></script>

      <script type="text/javascript" src="{{asset('adminlte/table/datepicker.js')}}"></script>

      <script type="text/javascript">

        $(document).ready(function(){


              $(".text-search").attr('name','textSearch');

              $(".text-search").val($("#projectNum").val());

        });

      </script>

    </div>

  </body>

</html>

