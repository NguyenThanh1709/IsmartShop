@extends('layouts.admin')
@section('title', 'Thêm bài viết')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thông tin banner
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
                {!! Form::open(['route' => ['update.banner', $banner->id], 'method' => 'POST', 'files' => true]) !!} @csrf
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            {!! Form::label('name', 'Tên Banner (*)', ['class' => 'title']) !!}
                            {!! Form::text('name', $banner->name, ['class' => 'form-control', 'id' => 'name']) !!}
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! html_entity_decode(
                                Form::label('slug', 'Slug (*) <span class="autofill title text-success">[Tự động điền]</span>', ['class' => 'title']),
                            ) !!}
                            {!! Form::text('slug', $banner->slug, ['class' => 'form-control', 'id' => 'slug']) !!}
                            @error('slug')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('link', 'Link URL (*)', ['class' => 'title']) !!}
                            {!! Form::text('link', $banner->link, ['id' => 'link', 'class' => 'form-control']) !!}
                            @error('link')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('content', 'Nội dung (*)', ['class' => 'title']) !!}
                    {!! Form::textarea('content', $banner->content, ['class' => 'form-control', 'id' => 'content', 'rows' => 10]) !!}
                    @error('content')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    {!! Form::label('exampleRadios1', 'Trạng thái (*)', ['class' => 'title']) !!}
                    <div class="form-check">
                        {!! Form::radio('status', 'pending', $banner->status == 'pending', [
                            'class' => 'form-check-input',
                            'id' => 'exampleRadios1',
                        ]) !!}
                        {!! Form::label('exampleRadios1', 'Chờ duyệt', ['class' => 'form-check-label']) !!}
                    </div>
                    <div class="form-check">
                        {!! Form::radio('status', 'public', $banner->status == 'public', [
                            'class' => 'form-check-input',
                            'id' => 'exampleRadios2',
                        ]) !!}
                        {!! Form::label('exampleRadios2', 'Công khai', ['class' => 'form-check-label']) !!}
                    </div>
                    @error('status')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-sm-4 form-group">
                        {!! Form::label('create_at', 'Ngày tạo', ['class' => 'title']) !!}
                        {!! Form::text('create_at', $banner->created_at, [
                            'id' => 'create_at',
                            'class' => 'form-control',
                            'disabled' => 'disabled',
                        ]) !!}
                        @error('create_at')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-sm-4 form-group">
                        {!! Form::label('updated_at', 'Cập nhật gần nhất', ['class' => 'title']) !!}
                        {!! Form::text('updated_at', $banner->updated_at, [
                            'id' => 'updated_at',
                            'class' => 'form-control',
                            'disabled' => 'disabled',
                        ]) !!}
                        @error('update_at')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-sm-4 form-group">
                        {!! Form::label('user-created', 'Người tạo', ['class' => 'title']) !!}
                        {!! Form::text('user-created', $banner->user->name, [
                            'id' => 'user-created',
                            'class' => 'form-control',
                            'disabled' => 'disabled',
                        ]) !!}
                        @error('update_at')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    {!! Form::label('formFile', 'Ảnh đã tải lên', ['class' => 'title form-label']) !!}
                    <div class="wp_image_uploaded">
                        <img id="image-chooseFile" src="{{ asset($banner->thumbnail) }}" alt="Ảnh đã upload"
                            onchange=chooseFile(this)>
                    </div>
                    {!! Form::file('file', ['class' => 'form-control', 'id' => 'formFile']) !!}
                    @error('file')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                {!! Form::submit('Cập nhật', ['class' => 'btn btn-primary btn-submit', 'name' => 'btn_add']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
