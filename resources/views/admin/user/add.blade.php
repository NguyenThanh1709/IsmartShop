@extends('layouts.admin')
@section('title', 'Thêm tài khoản')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm người dùng
            </div>
            <div class="card-body">
                {!! Form::open(['url' => 'admin/user/store', 'method' => 'POST']) !!} @csrf
                <div class="form-group">
                    {!! Form::label('name', 'Họ và tên (*)', ['class' => 'title']) !!}
                    {!! Form::text('name', '', ['class' => 'form-control']) !!}
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    {!! Form::label('email', 'Email (*)', ['class' => 'title']) !!}
                    {!! Form::email('email', '', ['class' => 'form-control', 'id' => 'email']) !!}
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    {!! Form::label('password', 'Mật khẩu (*)', ['class' => 'title']) !!}
                    {!! Form::password('password', ['class' => 'form-control', 'id' => 'password']) !!}
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    {!! Form::label('password_confirmation', 'Xác nhận mật khẩu (*)', ['class' => 'title']) !!}
                    {!! Form::password('password_confirmation', ['class' => 'form-control', 'id' => 'password_confirmation']) !!}
                    @error('password_confirmation')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    {!! Form::label('role', 'Nhóm quyền (*)', ['class' => 'title']) !!}
                    {!! Form::select('roles', $list_roles, '', ['class' => 'form-control', 'id' => 'role']) !!}
                    @error('role')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                {!! Form::submit('Thêm mới', ['class' => 'btn btn-primary btn-submit', 'name' => 'btn-add']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
