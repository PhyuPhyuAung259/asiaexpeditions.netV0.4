
<!-- @foreach(\App\Department::where(['status'=>1, 'type'=>3])->orderBy('order', 'ASC')->get() as $key => $dep)
 <div class="col-sm-3 col-md-3 col-lg-1 col-xs-4 acc_no-padding">
    <a href="{{route('getAccount')}}/{{$dep->slug}}">
      <div class="small-box btn btn-default {{$dep->slug==$active?'active':''}}" style="{{$dep->style}}">
        <div class="icon"><i class="{{$dep->icon}}"></i></div>
        <div><span>{{$dep->name}}</span></div>  
      </div>
    </a>
</div>     
@endforeach

 -->