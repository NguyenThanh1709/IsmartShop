@extends('layouts.admin')
@section('title', 'Danh sách thành viên')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                <div class="row">
                    <h5 class="m-0 col-md-8 col-xs-12">Danh sách thành viên</h5>
                    <form action="#"
                        class="form-search form d-flex justify-content-end align-items-center col-md-4 col-xs-12 mr-0">
                        {!! Form::text('keyword', request()->input('keyword'), [
                            'class' => 'form-control form-search mr-1',
                            'placeholder' => 'Tìm kiếm',
                        ]) !!}
                        {!! Form::submit('Tìm kiếm', ['class' => 'btn btn-primary']) !!}
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
                <div class="analytic">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}"
                        class="text-primary {{ $status == 'active' ? 'active-bottom' : '' }}">Kích hoạt<span
                            class="text-muted">({{ $count[0] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'trash']) }}"
                        class="text-primary {{ $status == 'trash' ? 'active-bottom' : '' }}">Vô hiệu hoá<span
                            class="text-muted">({{ $count[1] }})</span></a>
                </div>
                <form action="{{ url('admin/user/action') }}" method="post"> @csrf
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-action form-inline py-3">
                            <select class="form-control mr-1 list-option-responsive" name="act" id="">
                                <option>---Chọn tác vụ---</option>
                                @foreach ($list_act as $k => $act)
                                    <option value="{{ $k }}">{{ $act }}</option>
                                @endforeach
                            </select>
                            {!! Form::submit('Áp dụng', ['name' => 'btn-search', 'class' => 'btn btn-primary']) !!}
                        </div>
                        <div class="btn-add">
                            <a class="btn btn-primary" href="{{ route('user.add') }}" role="button">Thêm mới</a>
                        </div>
                        <div class="btn-add--responsive">
                            <a class="btn btn-primary" href="{{ route('user.add') }}" role="button"><i
                                    class="fa-solid fa-plus"></i></a>
                        </div>
                    </div>
                    <table class="table table-striped table-checkall table-responsive-sm">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="checkall">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Họ tên</th>
                                <th scope="col">Email</th>
                                <th scope="col">Quyền</th>
                                <th scope="col">Ngày tạo</th>
                                @if ($status == 'trash')
                                    <th scope="col">Trạng thái</th>
                                @else
                                    <th scope="col">Tác vụ</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if ($users->total() > 0)
                                @php $temp = (($users->currentpage() - 1) * $users->perpage() + 1) - 1 @endphp
                                @foreach ($users as $user)
                                    @php $temp ++; @endphp
                                    <tr>
                                        <td><input type="checkbox" name="list_check[]" value="{{ $user->id }}"></td>
                                        <th scope="row">{{ $temp }}</th>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @foreach ($user->roles as $item)
                                                <span class="badge badge-warning">{{ $item->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ $user->created_at }}</td>
                                        @if ($status == 'trash')
                                            <td><span class="badge badge-danger">Vô hiệu hoá</span></td>
                                        @else
                                            <td>
                                                <a href="{{ route('edit_user', $user->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                                @if (Auth::id() != $user->id)
                                                    <a href="{{ route('delete_user', $user->id) }}"
                                                        class="btn btn-danger btn-delete btn-sm rounded-0 text-white"
                                                        type="button" data-toggle="tooltip" data-placement="top"
                                                        title="Delete">
                                                        <i class="fa fa-trash"></i></a>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" class="text-center bg-white">Không có bản ghi nào được tìm thấy!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </form>
                {{ $users->appends(['keyword' => request()->input('keyword'), 'status' => $status])->links() }}
            </div>
        </div>
    </div>
@endsection
