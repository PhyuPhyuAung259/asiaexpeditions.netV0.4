
   <ul class="sidebar-menu" data-widget="tree">
      	@if(Auth::check())
	        @foreach(\App\Department::where(['status'=>1, 'type'=>2])->orderBy('order','ASC')->get() as $key=>$dep)
		        <li class="{{Auth::user()->role_id == 3 ? '':'treeview'}} {{$dep->name == $depart ? 'active':''}}" style="border-bottom: 1px dashed rgba(0,0,0,.1);">            
		            <?php
		                $getSubDepartment = \App\DepartmentMenu::where('department_id', $dep->id)->orderBy('order', 'ASC')->get();
		                $getBusinissName = \App\Business::where('category_id', 0)->get();
		            ?>	              
	                <a href="#">
	                  <!-- <i class="{{$dep->icon}}"></i> -->
	                  <span class="menu">{{$dep->name}}</span>
	                    @if($getSubDepartment->count() > 0 || $dep->slug == "suppliers" )
	                      <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
	                    @endif     
	                </a>
	                @if($getSubDepartment->count() > 0)
	                  <ul class="treeview-menu">
	                    @foreach($getSubDepartment as $subdep)
		                    @if($subdep->name != "Documentation")
		                      <li {{$subdep->name == $sub_depart ? 'class=active': ''}}> <a href="{{route('getDetail_doc', ['view' => $subdep->slug])}}"></i>{{$subdep->name}}</a></li>
		                    @endif	
	                    @endforeach
	                  </ul>
	                @endif
		        </li>
	        @endforeach
      	@endif
    </ul>
