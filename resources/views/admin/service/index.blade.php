@extends('Layouts.BackEnd')
@section('title', 'Dashboard')
@php($active = "account")
@php($sub_active = "role")

@section('content')
<div class="wrapper">
    <!-- Navbar -->
    @include('admin.portails.Navbar')
    <!-- /.navbar -->
    @include('admin.portails.LeftSidebar')
    
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @include('admin.roles.roleJs')
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Roles </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="float-sm-right">
                            <!-- <li class="breadcrumb-item"><a href="#">Home</a></li> -->
                          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop" data-title="Add New Role">
                          <i class="fa fa-plus-square"></i> Add Role</button>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <table class="table table-bordered " style="border-top:solid 1px rgb(0,101,116); table-layout: fixed ">
                    <thead class="etaf-table-thead">
                        <tr>
                            <th width="40" class="text-center">No.</th>
                            <th>Name</th>
                            <th>Descriptions</th>
                            <th width="87">Status</th>
                            <th class="text-center">Modify</th>
                        </tr>
                    </thead>
                    <tbody class="etaf-table-tbody"><tr><td colspan='5' class='text-center'><div class='loading'><span class='fa fa-spinner fa-spin'></span></div><div><b>Loading...</b></div></td></tr></tbody>
                </table>
            </div><!--/. container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    @include('admin.portails.Footer')
</div>
@endsection