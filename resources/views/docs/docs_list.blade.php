@extends('layout.backend')
@section('title', 'All Province')
<?php
  $active = 'country'; 
  $subactive ='province';
  use App\component\Content;
?>
@section('content')
<div class="wrapper">
  @include('admin.include.header')
  @include('admin.include.menuleft')
  <div class="content-wrapper">
    <section class="content"> 
        <div class="row">
            <section class="col-lg-12 connectedSortable">
                <h3 class="border">Documentation List <span class="fa fa-angle-double-right"></span> <a href="{{route('createDocs',[ 'url' => 'Create'])}}" class="btn btn-default btn-sm">Create New Documentation</a></h3>
               
                <table class="datatable table table-hover table-striped">
                  <thead>
                    <tr>
                      <th>Title</th>
                      <th>User</th>
                      <th>Created Date</th>
                      <th class="text-center">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($docs as $doc)
                    <tr>
                    
                      <td>{{$doc->title}}</td>
                      <td>{{$doc->user->fullname}}</td>
                      <td>{{Content::dateformat($doc->updated_at)}}</td> 
                      <td class="text-right">           
                                  
                        <a href="{{route('createDocs',['url'=> $doc->id, 'eId'=> $doc->id])}}" title="Edit Provice">
                          <label class="icon-list ic_book_project"></label>
                        </a>
                      
                      </td>                     
                    </tr>
                    @endforeach
                  </tbody>
                </table>
            </section>
        </div>
    </section>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
     $(".datatable").DataTable();
  });
</script>
@endsection
