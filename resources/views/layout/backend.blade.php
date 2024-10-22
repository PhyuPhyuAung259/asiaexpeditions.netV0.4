<!DOCTYPE html>
<?php 
  $comadd = App\Company::find(1);
?>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" type="image/x-icon" href="{{url('storage/avata')}}/{{$comadd->logo}}">
    <link rel="stylesheet" type="text/css" href="{{asset('adminlte/table/datatables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('adminlte/table/datepicker.css')}}">
    
    <link rel="stylesheet" type="text/css" href="{{asset('adminlte/css/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('adminlte/css/all.css')}}">
    
    <title>@yield('title') | {{$comadd->title}}</title>    
    <style type="text/css">
        font.open:before {
            /*display: inline-block;*/
            font: normal normal normal 14px/1 FontAwesome;
            font-size: inherit;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
            
            content: "\f06e";
            transition: all 0.5s;
        }
        font.open.active:before {
          -webkit-transform: rotate(90deg);
          -moz-transform: rotate(90deg);
          transform: rotate(90deg);
          transition: all 0.5s;
          content: "\f070";
          
        } 
        table.table_account_statement tbody tr.active{
            /*display:none;*/
             /*position: relative;*/
            /*transition': all .3s ease-out;*/
            /*-webkit-transition: height 0.5s linear;
             -moz-transition: height 0.5s linear;
              -ms-transition: height 0.5s linear;
               -o-transition: height 0.5s linear;
                  transition: height 0.5s linear;*/
             /*opacity: 0;*/
          -moz-transition: opacity 0.4s ease-in-out;
          -o-transition: opacity 0.4s ease-in-out;
          -webkit-transition: opacity 0.4s ease-in-out;
          transition: opacity 0.4s ease-in-out;
          }
    </style>
    <!-- <meta name="csrf-token" content="{{ csrf_token() }}" /> -->
    <!--Analytics google-->
    <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-LX73YLDD4V"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'G-LX73YLDD4V');
        </script>
  </head>

  <body class=" skin-green sidebar-mini sidebar-collapse">
    
      <script type="text/javascript" src="{{asset('adminlte/js/jquery.min.js')}}"></script>
      <script type="text/javascript" src="{{asset('adminlte/js/all.js')}}"></script>
      <script type="text/javascript" src="{{asset('adminlte/js/script.js')}}"></script>
      <script type="text/javascript" src="{{asset('adminlte/js/booking.js')}}"></script>
      <script type="text/javascript" src="{{asset('adminlte/js/apply_room.js')}}"></script>
      <script type="text/javascript" src="{{asset('adminlte/js/uploadfile.js')}}"></script>

      <script type="text/javascript" src="{{asset('adminlte/table/datatables.min.js')}}"></script>
      <!-- <script type="text/javascript" src="{{asset('adminlte/table/bootstrap-datepicker.js')}}"></script>
      <script type="text/javascript" src="{{asset('adminlte/table/datepicker.js')}}"></script> -->
      
      @yield('content')
      <script type="text/javascript" src="{{asset('adminlte/table/bootstrap-datepicker.js')}}"></script>
      <script type="text/javascript" src="{{asset('adminlte/table/datepicker.js')}}"></script>

      <script type="text/javascript">
          $(document).ready(function(){
            $(".text-search").attr('name','textSearch');
            $(".text-search").val($("#projectNum").val());
            $("#goTotop").click(function (){
              $('html, body').animate({
                scrollTop: $(".sidebar-mini").offset().top
              }, 300);
            });
            $(window).scroll(function(){
              if($(window).scrollTop() > 250 ){
                $("#goTotop").css('display','block');
              }else{
                $("#goTotop").css('display','none');
              }
            });
          });          
      </script>
      <a id="goTotop" class="hidden-print" style="position: fixed; right: 19px; display: none; bottom: 0px; font-size: 35px; z-index: 999999999;" href="javascript:void(0)"><span class="fa fa-chevron-circle-up"></span></a>


  </body>

  <script type="text/javascript">
    $(document).ready(function(){
        $("#check_all").click(function () {
          if($(this).is(':checked')){
             // Code in the case checkbox is checked.
            $(".checkall").prop('checked', true);
            $(".btnPreview").removeAttr("disabled");
          } else {
               // Code in the case checkbox is NOT checked.
            $(".checkall").prop('checked', false);
            $(".btnPreview").attr("disabled", true);
          }
        });

        $(".checkall").on("click", function(){
          if($(this).is(':checked')){
            $("#check_all").prop("checked", true);
            $(".btnPreview").removeAttr("disabled");
          }else{
            var somethingChecked = false;
            $('.checkall:checked').each(function(index, elem) {
              if($(this).is(':checked')){
                somethingChecked = true;
              }
            }); 
            if (!somethingChecked) {
              $(".btnPreview").attr("disabled", true);
              $("#check_all").prop("checked", false);
            }
          }
        });



      $(".open").on("click", function(){
          var className = $(this).data('acc_type');
          var no_of_room = $("tr."+ className);
          $(no_of_room, ".active").toggle(130);
          if ($(this).hasClass("active")) {
            $(this).removeClass('active');
            $(no_of_room).removeClass('active');
          }else{
            $(this).addClass('active');
            $(no_of_room).addClass('active');
          }
      });
    });
  </script>

</html>

