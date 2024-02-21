@extends('layouts.admin')
@section('title', 'Thêm trang')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm trang
            </div>
            <div class="card-body">
                <form action="{{ route('page.store') }}" method="post">@csrf
                    <div class="form-group">
                        {!! Form::label('name', 'Tên trang (*)', ['class' => 'title']) !!}
                        {!! Form::text('name', '', ['class' => 'form-control', 'id' => 'name']) !!}
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        {!! Form::label('title', 'Tiêu đề trang (*)', ['class' => 'title']) !!}
                        {!! Form::text('title', '', ['class' => 'form-control', 'id' => 'title']) !!}
                        @error('title')
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
                        {!! Form::label('exampleRadios1', 'Trạng thái (*)', ['class' => 'title']) !!}
                        <div class="form-check">
                            {!! Form::radio('status', 'pending', $checked = true, [
                                'class' => 'form-check-input',
                                'id' => 'exampleRadios1',
                            ]) !!}
                            {!! Form::label('exampleRadios1', 'Chờ duyệt', ['class' => 'form-check-label']) !!}
                        </div>
                        <div class="form-check">
                            {!! Form::radio('status', 'public', '', [
                                'class' => 'form-check-input',
                                'id' => 'exampleRadios2',
                            ]) !!}
                            {!! Form::label('exampleRadios2', 'Công khai', ['class' => 'form-check-label']) !!}
                        </div>
                        @error('exampleRadios')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-submit">Thêm mới</button>
                </form>
            </div>
        </div>
    </div>
@endsection
