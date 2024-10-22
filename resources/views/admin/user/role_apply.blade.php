@extends('layout.backend')
@section('title', 'Users')
<?php $active = 'users';
  $subactive ='user/role'; 
  use App\component\Content;
?>

<?php $getDep = \App\Department::where(['status'=>1])->whereHas('role', function($query) {$query->where('status',1);})->orderBy('order','ASC')->get(); ?>
@section('content')
  @include('admin.include.header') 
  @include('admin.include.menuleft')
  <style type="text/css">
      div.panel-heading {
        padding: 0;
        border:0;
      }
      .panel-title>div, span.panel-title>div:active{
        display:block;
        cursor: pointer;
        padding:15px;
        color:#555;
        font-size:16px;
        font-weight:bold;
        text-transform:uppercase;
        letter-spacing:1px;
        word-spacing:3px;
        text-decoration:none;
      }
      div.panel-heading  div.department:before {
         font-family: 'Glyphicons Halflings';
         content: "\e257";
         float: left;
         transition: all 0.5s;
      }
      div.panel-heading.active div.department:before {
        -webkit-transform: rotate(90deg);
        -moz-transform: rotate(90deg);
        transform: rotate(90deg);
      } 

</style>
  <div class="content-wrapper">
    <section class="content"> 
          <div class="notify-message">
            <div class="col-md-12" >
              <div class="row alert alert-dismissible fade show success" role="alert" style="position: relative; padding-left: 53px;">
                <!--<i class="fa fa-sitemap" style="font-size: 30px; position:absolute; top: 5px; left: 10px;"></i>-->
                <div style="font-size: 13px;"><span> Set menu permissions, setting "{{{$role->name or ''}}}" Menu permissions.</span></div>
                <p></p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style=" position: absolute;right: 7px;top: 13px;">
                  <span aria-hidden="true" style="font-size: 22px;padding: 1px 8px;">&times;</span>
                </button>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <form action="{{route('menuApplied')}}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="rid" value="{{{$role->id or ''}}}">
            <div class="row">
              @foreach($getDep->chunk(5) as $depart)
                <div class="col-md-4 col-xs-6">
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    @foreach( $depart as $key=>$dep)
                      <div class="form-group">
                        <div class="panel panel-default">
                          <div class="panel-heading" role="tab" id="headingThree{{$key}}" style="padding: 0px; ">
                            <div class="panel-title" style=" height: 48px; position: relative;">
                              <div style="padding-bottom: 0px; width: 100%; height: 48px; font-size:13px; letter-spacing: normal; font-weight: 400; text-transform: capitalize;" class="collapsed department" data-toggle="collapse" data-parent="#accordion" href="#collapseThree{{$key}}" aria-expanded="false" aria-controls="collapseThree{{$key}}">&nbsp;{{$dep->name}}</div>
                                <label class="checkall_Department container-CheckBox pull-right" style="position: absolute;right: 9px;top: 15px;font-size: 13px;">Check All
                                    <input type="checkbox" class="checkall" name="depart[]" value="{{$dep->id}}" 
                                    {{in_array($dep->id, $depart_id) ? 'checked':''}} >
                                    <span class="checkmark"></span>
                                </label>
                            
                            </div>
                          </div>
                          <div id="collapseThree{{$key}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree{{$key}}">
                            <div class="panel-body">
                              <?php $getDepart = \App\DepartmentMenu::where('department_id', $dep->id)->orderBy('name', 'ASC')->get(); ?>
                              @if($getDepart->count() > 0)
                                <table style="font-size: 13px; margin-bottom: 0px; border-top: none; width: 100%;" class="table">
                                  @foreach($getDepart->chunk(2) as $departchunk)
                                    <tr>
                                      @foreach($departchunk as $mn)
                                        <td style="border-top: none; width: 50%;">
                                            <label class="container-CheckBox" >{{$mn->name}}
                                                <input type="checkbox" class="checkall" name="dep_menu[]" value="{{$mn->id}}" 
                                                {{in_array($mn->id, $departmenu_id) ? 'checked':''}} >
                                                <span class="checkmark"></span>
                                            </label>
                                        </td>
                                      @endforeach
                                    </tr>
                                  @endforeach
                                </table>     
                              @endif
                            </div> 
                          </div>
                        </div>
                      </div>
                    @endforeach
                    </div>
                </div>
              @endforeach
              <div class="clearfix"></div>
              <div class="notify-message">
                <div class="col-md-12 text-center">
                  <div class="alert alert-dismissible fade show " role="alert">
                      <button class="btn btn-primary">Save & Public</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
       
    </section>
  </div>  
<script type="text/javascript">
  $(document).ready(function(){
    $('.panel-collapse').on('show.bs.collapse', function () {
        $(this).siblings('.panel-heading').addClass('active');
    });

    $(".checkall_Department").click(function () {
        var menuSelected = $(this).closest(".panel-default").find(".panel-collapse").find(".panel-body")
                                    .find(".table").find('tr')
                                    .find("td");
        if( $(".checkall", this).prop("checked") == true ){
            $(menuSelected).find("input").prop('checked', true);    
        } else {
            $(menuSelected).find("input").prop("checked", false);
        }
    });

    $('.panel-collapse').on('hide.bs.collapse', function () {
        $(this).siblings('.panel-heading').removeClass('active');
    });


    $(".checkall").click(function () {
        if($(this).is(':checked')){
            var menuSelected = $(this).closest(".panel-default").find(".checkall_Department").find(".checkall");      
              $(menuSelected).prop("checked", true); 
          
        }
    });

  });
</script>

@endsection
