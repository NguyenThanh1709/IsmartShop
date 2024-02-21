@extends('layouts.admin')

@section('title', 'Thông tin bài viết')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thông tin chi tiết bài viết
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
                {!! Form::open(['url' => 'admin/post/update/' . $post->id, 'method' => 'POST', 'files' => true]) !!}
                @csrf
                <div class="form-group">
                    {!! Form::label('name', 'Tiêu đề bài viết (*)', ['class' => 'title']) !!}
                    {!! Form::text('name', $post->title, ['class' => 'form-control']) !!}
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    {!! html_entity_decode(
                        Form::label('slug', 'Slug (*) <span class="autofill title text-success">[Tự động điền]</span>', ['class' => 'title']),
                    ) !!}
                    {!! Form::text('slug', $post->slug, ['class' => 'form-control']) !!}
                    @error('slug')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    {!! Form::label('content', 'Nội dung (*)', ['class' => 'title']) !!}
                    {!! Form::textarea('content', $post->content, ['class' => 'form-control', 'id' => 'content', 'rows' => 10]) !!}
                    @error('content')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="title" for="">Danh mục (*)</label>
                    <select class="form-control" name="cat" id="">
                        <option>---Chọn chuyên mục---</option>
                        @foreach ($list_cat_tree as $item)
                            <option @if ($post->post_cat_id == $item->id) selected @endif value="{{ $item->id }}">
                                {{ str_repeat('--', $item['level']) . $item['name'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('cat')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="title" for="">Trạng thái (*)</label>
                    <div class="row">
                        <div class="col-sm-2 form-check">
                            {!! Form::radio('status', 'pending', $status == 'pending', [
                                'class' => 'form-check-input',
                                'id' => 'exampleRadios1',
                            ]) !!}
                            {!! Form::label('exampleRadios1', 'Chờ duyệt', ['class' => 'form-check-label']) !!}
                        </div>
                        <div class="col-sm-2 form-check">
                            {!! Form::radio('status', 'public', $status == 'public', [
                                'class' => 'form-check-input',
                                'id' => 'exampleRadios2',
                            ]) !!}
                            {!! Form::label('exampleRadios2', 'Chờ duyệt', ['class' => 'form-check-label']) !!}
                        </div>
                    </div>
                    @error('status')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-sm-4 form-group">
                        {!! Form::label('create_at', 'Ngày tạo', ['class' => 'title']) !!}
                        {!! Form::text('create_at', $post->created_at, [
                            'class' => 'form-control',
                            'id' => 'create_at',
                            'disabled' => 'disabled',
                        ]) !!}
                    </div>
                    <div class="col-sm-4 form-group">
                        {!! Form::label('updated_at', 'Cập nhật gần nhất', ['class' => 'title']) !!}
                        {!! Form::text('updated_at', $post->updated_at, [
                            'class' => 'form-control',
                            'id' => 'updated_at',
                            'disabled' => 'disabled',
                        ]) !!}
                    </div>
                    <div class="col-sm-4 form-group">
                        {!! Form::label('user_create', 'Người khởi tạo', ['class' => 'title']) !!}
                        {!! Form::text('user_create', $post->user->name, [
                            'class' => 'form-control',
                            'id' => 'updated_at',
                            'disabled' => 'disabled',
                        ]) !!}
                    </div>
                </div>
                <div class="mb-3">
                    {!! Form::label('formFile', 'Ảnh đã tải lên', ['class' => 'title form-label']) !!}
                    <div class="wp_image_uploaded">
                        <img class="" id="image-chooseFile" src="{{ asset($post->thumbnail) }}" alt="Ảnh đã upload">
                    </div>
                    {!! Form::file('file', ['class' => 'form-control', 'id' => 'formFile']) !!}
                    @error('file')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                {!! Form::submit('Cập nhật', ['class' => 'btn btn-primary btn-submit']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
