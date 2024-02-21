@extends('layouts.admin')
@section('title', 'Danh sách Email đăng ký')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                <div class="row">
                    <h5 class="m-0 col-md-8 col-xs-12">Danh sách Email</h5>
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

                <form action="{{ url('admin/email/action') }}" method="post"> @csrf
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-action form-inline py-3">
                            <select class="form-control mr-1 list-option-responsive" name="act" id="">
                                <option>---Chọn tác vụ---</option>
                                {{-- @foreach ($list_act as $k => $act)
                                    <option value="{{ $k }}">{{ $act }}</option>
                                @endforeach --}}
                            </select>
                            {!! Form::submit('Áp dụng', ['name' => 'btn-search', 'class' => 'btn btn-primary']) !!}
                            <a href="{{ route('customer.email.send') }}" class="btn btn-primary ml-3"><i
                                    class="fa-solid fa-envelope p-1"></i>Gửi
                                Email</a>
                        </div>
                        <div class="btn-add">
                            <a class="btn btn-primary" href="" role="button">Thêm mới</a>
                        </div>
                        <div class="btn-add--responsive">
                            <a class="btn btn-primary" href="" role="button"><i class="fa-solid fa-plus"></i></a>
                        </div>
                    </div>
                    <table class="table table-striped table-checkall table-responsive-sm">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="checkall">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Email</th>
                                <th scope="col">Ngày đăng ký</th>
                                <th scope="col">Cập nhật gần nhất</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($listEmail->total() > 0)
                                @php $temp = (($listEmail->currentpage() - 1) * $listEmail->perpage() + 1) - 1 @endphp
                                @foreach ($listEmail as $email)
                                    @php $temp ++; @endphp
                                    <tr>
                                        <td><input type="checkbox" name="list_check[]" value="{{ $email->id }}"></td>
                                        <th scope="row">{{ $temp }}</th>
                                        <td>{{ $email->email }}</td>
                                        <td>{{ $email->created_at }}</td>
                                        <td>{{ $email->updated_at }}</td>
                                        <td>
                                            @if (Auth::id() != $email->id)
                                                <a href="{{ route('customer.email.delete', ['id' => $email->id]) }}"
                                                    class="btn btn-danger btn-delete btn-sm rounded-0 text-white"
                                                    type="button" data-toggle="tooltip" data-placement="top"
                                                    title="Delete">
                                                    <i class="fa fa-trash"></i></a>
                                            @endif
                                        </td>
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
                {{ $listEmail->links() }}
            </div>
        </div>
    </div>
@endsection
