@extends('layouts.admin')

@section('title', 'Thêm bài viết')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm bài viết
            </div>
            <div class="card-body">
                {!! Form::open(['url' => 'admin/post/store', 'method' => 'POST', 'files' => true]) !!}
                @csrf
                <div class="form-group">
                    {!! Form::label('name', 'Tiêu đề bài viết (*)', ['class' => 'title']) !!}
                    {!! Form::text('name', '', ['class' => 'form-control']) !!}
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    {!! html_entity_decode(
                        Form::label('slug', 'Slug (*) <span class="autofill title text-success">[Tự động điền]</span>', ['class' => 'title']),
                    ) !!}
                    {!! Form::text('slug', '', ['class' => 'form-control']) !!}
                    @error('slug')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    {!! Form::label('content', 'Nội dung (*)', ['class' => 'title']) !!}
                    {!! Form::textarea('content', '', ['class' => 'form-control', 'id' => 'content', 'rows' => 10]) !!}
                    @error('content')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    {!! Form::label('cat', 'Danh mục (*)', ['class' => 'title']) !!}
                    <select class="form-control" name="cat" id="">
                        <option value="">---Chọn chuyên mục---</option>
                        @foreach ($list_cat_tree as $item)
                            <option @if (old('cat') == $item->id) @selected(true) @endif value="{{ $item->id }}">
                                {{ str_repeat('--', $item['level']) . $item['name'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('cat')
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
                {!! Form::submit('Thêm mới', ['class' => 'btn btn-primary btn-submit']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
