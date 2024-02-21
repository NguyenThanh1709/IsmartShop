@extends('layouts.admin')

@section('title', 'Thêm vai trò')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                <div class="row">
                    <h5 class="m-0 col-md-8 col-xs-12">Thêm mới vai trò</h5>
                </div>
            </div>
            <div class="card-body">
                {!! Form::open(['route' => 'role.store', 'method' => 'POST', 'files' => true]) !!}
                <div class="form-group">
                    {!! Form::label('name', 'Tên vai trò (*)', ['class' => 'text-strong title']) !!}
                    {!! Form::text('name', '', ['class' => 'form-control', 'id' => 'name']) !!}
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    {!! Form::label('description', 'Mô tả (*)', ['class' => 'text-strong title']) !!}
                    {!! Form::text('description', '', ['class' => 'form-control', 'id' => 'description']) !!}
                    @error('description')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <p class="title pb-0">Vai trò này có quyền gì?</p>
                <small class="text-black">Check vào module hoặc các hoạt động bên dưới để chọn quyền</small>

                @foreach ($permissions as $moduleName => $modulePermissions)
                    <div class="card my-4 boder">
                        <div class="card-header">
                            {!! Form::checkbox($moduleName, '', '', ['class' => 'check-all', 'id' => $moduleName]) !!}
                            {!! Form::label($moduleName, 'MODULE ' . $moduleName, ['class' => 'm-0 title']) !!}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($modulePermissions as $permission)
                                    <div class="col-md-3 pb-3">
                                        {!! Form::checkbox($permission->slug, $permission->id, '', [
                                            'class' => 'permission',
                                            'name' => 'permission_id[]',
                                            'id' => $permission->slug,
                                        ]) !!}
                                        {!! Form::label($permission->slug, $permission->name, ['class' => 'm-0 title']) !!}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
                {!! Form::submit('Thêm mới', ['class' => 'btn btn-primary']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
