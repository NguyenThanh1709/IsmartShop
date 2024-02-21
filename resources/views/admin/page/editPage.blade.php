@extends('layouts.admin')

@section('title', 'Thông tin chi tiết trang')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                THÔNG TIN CHI TIẾT TRANG
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
                {!! Form::open(['route' => ['update.page', $page->id], 'method' => 'POST']) !!} @csrf
                <div class="form-group">
                    {!! Form::label('name', 'Tên trang (*)', ['class' => 'title']) !!}
                    {!! Form::text('name', $page->name, ['class' => 'form-control', 'id' => 'name']) !!}
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    {!! Form::label('title', 'Tiêu đề trang (*)', ['class' => 'title']) !!}
                    {!! Form::text('title', $page->title, ['class' => 'form-control', 'id' => 'title']) !!}
                    @error('title')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    {!! Form::label('content', 'Nội dung (*)', ['class' => 'title']) !!}
                    {!! Form::textarea('content', $page->content, ['class' => 'form-control', 'id' => 'content', 'rows' => 10]) !!}
                    @error('content')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    {!! Form::label('exampleRadios1', 'Trạng thái (*)', ['class' => 'title']) !!}
                    <div class="form-check">
                        {!! Form::radio('status', 'pending', $page->status == 'pending', [
                            'class' => 'form-check-input',
                            'id' => 'exampleRadios1',
                        ]) !!}
                        {!! Form::label('exampleRadios1', 'Chờ duyệt', ['class' => 'form-check-label']) !!}
                    </div>
                    <div class="form-check">
                        {!! Form::radio('status', 'public', $page->status == 'public', [
                            'class' => 'form-check-input',
                            'id' => 'exampleRadios2',
                        ]) !!}
                        {!! Form::label('exampleRadios2', 'Công khai', ['class' => 'form-check-label']) !!}
                    </div>
                    @error('exampleRadios')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-sm-4 form-group">
                        {!! Form::label('create_at', 'Ngày tạo', ['class' => 'title']) !!}
                        {!! Form::text('create_at', $page->created_at, [
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
                        {!! Form::text('updated_at', $page->updated_at, [
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
                        {!! Form::text('user-created', $page->user->name, [
                            'id' => 'user-created',
                            'class' => 'form-control',
                            'disabled' => 'disabled',
                        ]) !!}
                        @error('update_at')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                {!! Form::submit('Cập nhật', ['class' => 'btn btn-primary btn-submit', 'name' => 'btn_add']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
