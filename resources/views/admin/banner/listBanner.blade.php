@extends('layouts.admin')

@section('title', 'Danh sách banner')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                <div class="row">
                    <h5 class="col-md-8">Danh sách banner</h5>
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
                <div class="analytic wp-list-status">
                    <a href="{{ route('index.banner') }}" class="text-primary {{ !$status ? 'active-bottom' : '' }}">Hiệu
                        lực<span class="text-muted ">({{ $count[0] + $count[1] }})</span></a>
                    <a href="?status=public" class="text-primary {{ $status == 'public' ? 'active-bottom' : '' }}">Công
                        khai<span class="text-muted">({{ $count[0] }})</span></a>
                    <a href="?status=pending" class="text-primary {{ $status == 'pending' ? 'active-bottom' : '' }}">Chờ
                        duyệt<span class="text-muted">({{ $count[1] }})</span></a>
                    <a href="?status=trash" class="text-primary {{ $status == 'trash' ? 'active-bottom' : '' }}">Thùng
                        rác<span class="text-muted">({{ $count[2] }})</span></a>
                </div>
                <form action="{{ route('action.banner') }}" method="banner">@csrf
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
                            <a class="btn btn-primary" href="{{ route('add.banner') }}" role="button">Thêm mới</a>
                        </div>
                        <div class="btn-add--responsive">
                            <a class="btn btn-primary" href="{{ route('add.banner') }}" role="button"><i
                                    class="fa-solid fa-plus"></i></a>
                        </div>
                    </div>
                    <table class="table table-striped table-checkall table-responsive-sm">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <input name="checkall" type="checkbox">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Ảnh</th>
                                <th scope="col">Tiêu đề</th>
                                <th scope="col">Link-URL</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Ngày tạo</th>
                                @if ($status == 'trash')
                                    <th scope="col">Ngày xoá</th>
                                @else
                                    <th scope="col">Tác vụ</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if ($listBanner->total() > 0)
                                @php $temp = (($listBanner->currentpage() - 1) * $listBanner->perpage() + 1) - 1 @endphp
                                @foreach ($listBanner as $banner)
                                    @php $temp++; @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="list_check[]" value="{{ $banner->id }}">
                                        </td>
                                        <td scope="row">{{ $temp }}</td>
                                        <td class="wp_thumbnail"><a href="{{ $banner->link }}"><img src="{{ asset($banner->thumbnail) }} "class="img_thumbnail"alt=""></a>

                                        </td>
                                        <td><a
                                                href="{{ route('edit.banner', $banner->id) }}">{{ Str::limit($banner->name, 50) }}</a>
                                        </td>
                                        <td><a href="{{ $banner->link }}" target="_blank">{{ $banner->link }}</a></td>
                                        @if ($banner->status == 'pending')
                                            <td><span class="text-badge badge badge-warning">Chờ duyệt</span></td>
                                        @else
                                            <td><span class="text-badge badge badge-success">Công khai</span></td>
                                        @endif
                                        <td>{{ $banner->created_at }}</td>
                                        @if ($status == 'trash')
                                            <td>{{ $banner->deleted_at }}</td>
                                        @else
                                            <td>
                                                <a href="{{ route('edit.banner', $banner->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                                <a href="{{ route('delete.banner', $banner->id) }}"
                                                    class="btn btn-danger btn-delete btn-sm rounded-0 text-white"
                                                    type="button" data-toggle="tooltip" data-placement="top"
                                                    title="Delete">
                                                    <i class="fa fa-trash"></i></a>
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
                {{ $listBanner->appends(['keyword' => request()->input('keyword'), 'status' => $status])->links() }}
            </div>
        </div>
    </div>
@endsection
