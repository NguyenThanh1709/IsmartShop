@extends('layouts.admin')

@section('title', 'Danh sách vai trò')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                <div class="row">
                    <h5 class="m-0 col-md-8 col-xs-12">Danh sách vai trò</h5>
                    <form action="#"
                        class="form-search form d-flex justify-content-end align-items-center col-md-4 col-xs-12 mr-0">
                        <input type="search" name="keyword" class="form-control form-search mr-1"
                            value="{{ request()->input('keyword') }}" placeholder="Tìm kiếm">
                        <input type="submit" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
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
                <form action="{{ route('role.deleteMultiple') }}" method="POST">@csrf
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-action form-inline py-3">
                            <select class="form-control mr-1 list-option-responsive" name="act" id="">
                                <option>---Chọn tác vụ---</option>
                                @foreach ($list_option as $k => $act)
                                <option value="{{ $k }}">{{ $act }}</option>
                                @endforeach
                            </select>
                            {!! Form::submit('Áp dụng', ['name' => 'btn-search', 'class' => 'btn btn-primary']) !!}
                        </div>
                        <div class="btn-add">
                            <a class="btn btn-primary" href="{{ route('add.page') }}" role="button">Thêm mới</a>
                        </div>
                        <div class="btn-add--responsive">
                            <a class="btn btn-primary" href="{{ route('add.page') }}" role="button"><i
                                    class="fa-solid fa-plus"></i></a>
                        </div>
                    </div>
                    <table class="table table-striped table-checkall table-responsive-sm">
                        <thead>
                            <tr>
                                <th scope="col"><input name="checkall" type="checkbox"></th>
                                <th scope="col">#</th>
                                <th scope="col">Tên vai trò</th>
                                <th scope="col">Mô tả</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $temp = (($listRole->currentpage() - 1) * $listRole->perpage() + 1) - 1 @endphp
                            @forelse ($listRole as $role)
                                @php $temp++; @endphp
                                <tr>
                                    <td><input type="checkbox" name="list_check[]" value="{{ $role->id }}"></td>
                                    <th>{{ $temp }}</th>
                                    <td scope="row">{{ $role->name }}</td>
                                    <td>{{ $role->description }}</td>
                                    <td>{{ $role->created_at }}</td>
                                    <td>
                                        <a href="{{ route('role.edit', $role->id) }}" class="btn btn-success btn-sm rounded-0 text-white" type="button"data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                        <a href="{{ route('role.delete', $role->id) }}" class="btn btn-danger btn-delete btn-sm rounded-0 text-white"type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center bg-white">Không có bản ghi nào được tìm thấy!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </form>
                {{ $listRole->appends(['keyword' => request()->input('keyword')])->links() }}
            </div>
        </div>
    </div>
@endsection
