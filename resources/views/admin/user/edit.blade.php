@extends('layouts.admin')

@section('title', 'Thông tin tài khoản')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="wp-title card-header font-weight-bold">
                Chỉnh sửa thông tin người dùng
                <div class="wp-change-pw">
                    <a class="btn-change-pw btn btn-primary text-white" data-toggle="modal" data-target="#exampleModal">Đổi mật
                        khẩu</a>
                </div>
            </div>
            <div class="card-body">
                {!! Form::open(['url' => 'admin/user/update/' . $user->id, 'method' => 'POST']) !!}
                @csrf
                <div class="form-group">
                    {!! Form::label('name', 'Họ và tên (*)', ['class' => 'title']) !!}
                    {!! Form::text('name', $user->name, ['class' => 'form-control']) !!}
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    {!! Form::label('email', 'Email (*)', ['class' => 'title']) !!}
                    {!! Form::email('email', $user->email, ['class' => 'form-control']) !!}
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    {!! Form::label('roles', 'Nhóm quyền (*)', ['class' => 'title']) !!}
                    {!! Form::select('roles[]', $list_roles, $selectOption, ['class' => 'form-control', 'id' => 'role', 'multiple' => true]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('created_at', 'Ngày tạo', ['class' => 'title']) !!}
                    {!! Form::text('created_at', $user->created_at, [
                        'class' => 'form-control',
                        'id' => 'created_at',
                        'disabled' => 'disabled',
                    ]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('updated_at', 'Ngày tạo', ['class' => 'title']) !!}
                    {!! Form::text('updated_at', $user->updated_at, [
                        'class' => 'form-control',
                        'id' => 'updated_at',
                        'disabled' => 'disabled',
                    ]) !!}
                </div>
                {!! Form::submit('Cập nhật', ['class' => 'btn btn-primary', 'name' => 'btn-add']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    {{-- Modal --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ĐỔI MẬT KHẨU</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {!! Form::open(['url' => 'admin/user/change_password/' . $user->id, 'method' => 'POST']) !!}@csrf
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::label('password_new', 'Mật khẩu mới', ['class' => 'title']) !!}
                        {!! Form::password('password_new', ['placeholder' => 'Nhập mật khẩu mới', 'class' => 'form-control']) !!}
                        @error('password_new')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        {!! Form::label('password_confirmation_new', 'Mật khẩu mới', ['class' => 'title']) !!}
                        {!! Form::password('password_confirmation_new', [
                            'placeholder' => 'Nhập lại mật khẩu mới',
                            'class' => 'form-control',
                        ]) !!}
                        @error('password_confirmation_new')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Huỷ</button>
                    {!! Form::submit('Lưu thay đổi', ['class' => 'btn btn-primary btn-submit']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
