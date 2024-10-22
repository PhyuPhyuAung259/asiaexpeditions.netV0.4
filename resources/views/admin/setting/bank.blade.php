@extends('layout.backend')
@section('title', 'Banks')
<?php $active = 'setting-options';
$subactive ='bank'; 
use App\component\Content;
?>

@section('content')
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
          <form action="POST">
            <section class="col-lg-12 connectedSortable">
                <h3 class="border">Banks List <span style=" font-size: 22px;" class="fa fa-angle-double-right"></span> <a href="{{route('getBankForm')}}" class="btn btn-primary btn-sm">Add New Bank Info</a></h3>
                <table class=" table table-hover table-striped">
                  <thead>
                    <tr>
                      <th>Title</th>                     
                      <th>Created at</th>
                      <th width="120px" class="text-center">Status</th>
                      <th width="100px" class="text-center">action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($banks as $ab)
                    <tr>                     
                      <td><a target="_blank" href="{{route('getBankPreview', ['preview_bank' => $ab->id])}}">{{$ab->name}}</a></td>
                      <td>{{Content::dateformat($ab->updated_at)}}</td>
                      <td class="text-center">{!! $ab->status > 0 ? '<span class="badge">Enable</span>':'<span class="badge label-warning">Disabled</span>' !!}</td>
                      <td class="text-right">
                        <a href="{{route('getBankForm', ['bank_id'=> $ab->id])}}"  title="Edit bank info">
                          <label style="cursor: pointer;" class="icon-list ic_edit"></label>
                        </a>         
                        <a href="javascript:void(0)" class="RemoveHotelRate" data-type="bank" data-id="{{$ab->id}}" title="Remove this user?">
                            <label style="cursor: pointer;" class="icon-list ic_remove"></label>
                        </a>
                      </td>                     
                    </tr>
                    @endforeach
                  </tbody>
                </table>                
            </section>
          </form>
        </div>
    </section>
  </div>  
@endsection

