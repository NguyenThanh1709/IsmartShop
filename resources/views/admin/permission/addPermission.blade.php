@extends('layouts.admin')

@section('title', 'Thêm quyền')

@section('content')
<div id="content" class="container-fluid">
    <div class="row">
        <div class="col-12 col-sm-4">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Thêm quyền
                </div>
                <div class="card-body">
                    {!! Form::open(['url' => 'admin/permission/store', 'method' => 'POST']) !!}
                    @csrf
                    <div class="form-group">
                        {!! Form::label('name', 'Tên quyền (*)', ['class' => 'title']) !!}
                        {!! Form::text('name', '', ['id' => 'name', 'class' => 'form-control']) !!}
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        {!! Form::label('slug', 'Slug (*)', ['class' => 'title']) !!}
                        <small class="form-text text-muted">Ví dụ: post.add</small>
                        {!! Form::text('slug', '', ['class' => 'form-control', 'id' => 'slug']) !!}
                        @error('slug')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        {!! Form::label('description', 'Mô tả (*)', ['class' => 'title']) !!}
                        {!! Form::textarea('description', '', ['class'=>'form-control']) !!}
                    </div>
                    {!! Form::submit('Thêm mới', ['class' => 'btn btn-primary', 'name' => 'btn_add']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-8">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Danh sách quyền
                </div>
                @if (session('status'))
                    <div class="alert alert-success">
                        <i class="fa-solid fa-bullhorn"></i> {{ session('status') }}
                    </div>
                @endif
                @if (session('status-danger'))
                    <div class="alert alert-danger">
                        <i class="fa-solid fa-bullhorn"></i> {{ session('status-danger') }}
                    </div>
                @endif
                <div class="card-body">
                    <table class="table table-striped table-responsive-sm">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tên quyền</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $temp = 1;
                            @endphp
                            @foreach ($permissions as $moduleName => $modulePermissions)
                                <tr>
                                    <td></td>
                                    <td><strong>Module {{ ucfirst($moduleName) }}</strong></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @foreach ($modulePermissions as $permission)
                                    <tr>
                                        <td>{{$temp ++ }}</td>
                                        <td>|---{{$permission->name}}</td>
                                        <td>{{$permission->slug}}</td>
                                        <td>
                                            <a class="btn-info-permission btn btn-success btn-sm rounded-0 text-white"
                                                type="button" data-toggle="modal" data-target="#exampleModalCenter"
                                                data-url="{{route('permission.edit', $permission->id)}}"
                                                data-id="" data-placement="top" title="Edit"><i
                                                    class="fa fa-edit"></i></a>
                                            <a href="{{route('permission.delete', $permission->id)}}"
                                                class="btn btn-danger btn-delete btn-sm rounded-0 text-white"
                                                type="button" data-toggle="tooltip" data-placement="top"
                                                title="Delete">
                                                <i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">THÔNG TIN QUYỀN</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {!! Form::open(['', 'method' => 'POST', 'id' => 'form-modal']) !!}@csrf
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            {!! Form::label('name', 'Tên quyền (*)', ['class' => 'title']) !!}
                            {!! Form::text('name', '', ['id' => 'name_modal', 'class' => 'form-control']) !!}
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            {!! Form::label('slug', 'Slug (*)', ['class' => 'title']) !!}
                            <small class="form-text text-muted">Ví dụ: post.add</small>
                            {!! Form::text('slug', '', ['class' => 'form-control', 'id' => 'slug_modal']) !!}
                            @error('slug')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            {!! Form::label('description', 'Mô tả (*)', ['class' => 'title']) !!}
                            {!! Form::textarea('description', '', ['class'=>'form-control', 'id' => 'description_modal']) !!}
                        </div>
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                {!! Form::label('create_at', 'Ngày tạo', ['class' => 'title']) !!}
                                {!! Form::text('create_at', '', [
                                    'id' => 'create_at',
                                    'class' => 'form-control',
                                    'placeholder' => 'Ngày tạo',
                                    'disabled' => 'disabled',
                                ]) !!}
                            </div>
                            <div class="col-sm-6 form-group">
                                {!! Form::label('update_at', 'Cập nhật gần nhất', ['class' => 'title']) !!}
                                {!! Form::text('update_at', '', [
                                    'id' => 'update_at',
                                    'class' => 'form-control',
                                    'placeholder' => 'Người tạo',
                                    'disabled' => 'disabled',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-close" data-dismiss="modal">Huỷ</button>
                    <button type="button" class="btn btn-primary btn-update-permission" id=""
                        data-url="{{ route('permission.update') }}" data-id="">Lưu thay đổi</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
