@extends('layouts.admin')

@section('title', 'Thêm bài viết')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm Slider
            </div>
            <div class="card-body">
                {!! Form::open(['url' => 'admin/slider/store', 'method' => 'POST', 'files' => true]) !!} @csrf
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            {!! Form::label('name', 'Tên Slider (*)', ['class' => 'title']) !!}
                            {!! Form::text('name', '', ['id' => 'name', 'class' => 'form-control']) !!}
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
                            {!! Form::text('slug', '', ['class' => 'form-control', 'id' => 'slug']) !!}
                            @error('slug')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('link', 'Link URL (*)', ['class' => 'title']) !!}
                            {!! Form::text('link', '', ['id' => 'link', 'class' => 'form-control']) !!}
                            @error('link')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('content', 'Nội dung (*)', ['class' => 'title']) !!}
                    {!! Form::textarea('content', '', ['class' => 'form-control', 'id' => 'content', 'rows' => 10]) !!}
                    @error('content')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    {!! Form::label('exampleRadios1', 'Trạng thái (*)', ['class' => 'title']) !!}
                    <div class="form-check">
                        {!! Form::radio('status', 'pending', $checked = true, ['class' => 'form-check-input', 'id' => 'exampleRadios1']) !!}
                        {!! Form::label('exampleRadios1', 'Chờ duyệt', ['class' => 'form-check-label']) !!}
                    </div>
                    <div class="form-check">
                        {!! Form::radio('status', 'public', $checked = false, ['class' => 'form-check-input', 'id' => 'exampleRadios2']) !!}
                        {!! Form::label('exampleRadios2', 'Công khai', ['class' => 'form-check-label']) !!}
                    </div>
                    @error('status')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    {!! Form::label('formFile', 'Chọn hình (*)', ['class' => 'title form-label']) !!}
                    {!! Form::file('file', ['class' => 'form-control', 'id' => 'formFile']) !!}
                    @error('file')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                {!! Form::submit('Thêm mới', ['class' => 'btn btn-primary btn-submit', 'name' => 'btn_add']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
