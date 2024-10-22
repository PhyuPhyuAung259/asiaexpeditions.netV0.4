<aside class="main-sidebar">
  <section class="sidebar">
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN NAVIGATION</li>
   
      @if(Auth::check())
        <?php 
          $depart_id  = [];
          $roleDepart = \App\Role::find(\Auth::user()->role_id);
          if (!empty($roleDepart->department )) {
            foreach ($roleDepart->department as $key => $dep) {
              $depart_id[] = $dep->pivot->department_id;
            } 
          }            
        ?>
        @foreach(\App\Department::where(['status'=>1, 'type'=>2, 'status'=>1])->whereIn('id', $depart_id)->orderBy('order','ASC')->get() as $key => $dep)
              <?php
                $getSubDepartment = \App\DepartmentMenu::getDepartment_menu($dep->id);
              ?>
            @if($getSubDepartment->count() > 0)
              <li class="{{Auth::user()->role_id == 3 ? '':'treeview'}} {{$dep->slug == $active ? 'active':''}}"> 
                  <a href="{{Auth::user()->role_id == 3 ? route('finance').'/'.$dep->slug:'javascript:void(0)'}}">
                    <i class="{{$dep->icon}}" style="font-size: 15px;"></i>
                    <span>{{$dep->name}}</span>
                      @if($dep->role->count() > 0 || $dep->slug == "suppliers" )
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                      @endif     
                  </a>
                  
                  <ul class="treeview-menu">
                    @foreach($getSubDepartment as $subdep)
                      <li {{$subdep->slug == $subactive ? 'class=active': ''}}><a {{ empty($subdep->link) ? '' : "target=_blank" }} href="{{route('adminhome')}}/{{$subdep->slug}}"><i class="fa fa-circle-o"></i>{{$subdep->name}}</a></li>
                    @endforeach
                  </ul>      
              </li>
            @endif   
        @endforeach
      @endif
       <li><a href="https://docs.google.com/document/d/1Qo_czNlLRplkc-rtz0hhYcJSFKZ1SL9N/edit?usp=drive_link&ouid=112190418804677049856&rtpof=true&sd=true"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
    </ul>
  </section>
</aside>