@extends('layouts.admin')

@section('title', 'Danh mục')

@section('content')
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-4">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Thêm thương hiệu
                    </div>
                    <div class="card-body">
                        {!! Form::open(['url' => 'admin/product/brand/storeBrand', 'method' => 'POST']) !!}
                        @csrf
                        <div class="form-group">
                            {!! Form::label('name', 'Tên thương hiệu (*)', ['class' => 'title']) !!}
                            {!! Form::text('name', '', ['id' => 'name', 'class' => 'form-control']) !!}
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            {!! html_entity_decode(
                                Form::label('slug', 'Slug (*) <span class="autofill title text-success">[Tự động điền]</span>', [
                                    'class' => 'title',
                                ]),
                            ) !!}
                            {!! Form::text('slug', '', ['class' => 'form-control', 'id' => 'slug']) !!}
                            @error('slug')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="title" for="">Trạng thái (*)</label>
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
                        {!! Form::submit('Thêm mới', ['class' => 'btn btn-primary', 'name' => 'btn_add']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-8">
                <div class="card">
                    <div class="card-header font-weight-bold ">
                        Danh sách thương hiệu
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
                        <table class="table table-striped table-responsive-sm">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tên thương hiệu</th>
                                    <th scope="col">Trạng thái</th>
                                    <th scope="col">Cập nhật gần nhất</th>
                                    <th scope="col">Tác vụ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($listBrands->count() > 0)
                                    @php $temp = 0; @endphp
                                    @foreach ($listBrands as $brand)
                                        @php $temp++ @endphp
                                        <tr>
                                            <th scope="row">{{ $temp }}</th>
                                            <td>{{ str_repeat('|---', $brand['level']) . $brand['name'] }}</td>
                                            @if ($brand->status == 'pending')
                                                <td><span class="text-badge badge badge-warning">Chờ duyệt</span></td>
                                            @else
                                                <td><span class="text-badge badge badge-success">Công khai</span></td>
                                            @endif
                                            <td>{{ $brand->updated_at }}</td>
                                            <td>
                                                <a class="btn-info-brand btn btn-success btn-sm rounded-0 text-white"
                                                    type="button" data-toggle="modal" data-target="#exampleModalCenter"
                                                    data-url="{{ route('brand.edit.product', $brand->id) }}"
                                                    data-id="{{ $brand->id }}" data-placement="top" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                                <a href="{{ route('brand.delete.product', $brand->id) }}"
                                                    class="btn btn-danger btn-delete btn-sm rounded-0 text-white"
                                                    type="button" data-toggle="tooltip" data-placement="top"
                                                    title="Delete">
                                                    <i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center bg-white">Không có dữ liệu bản ghi nào!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $listBrands->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">THÔNG TIN THƯƠNG HIỆU</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {!! Form::open(['', 'method' => 'POST', 'id' => 'form-modal']) !!}@csrf
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::label('title', 'Tên thương hiệu (*)', ['class' => 'title']) !!}
                        {!! Form::text('title', '', ['id' => 'name_modal', 'class' => 'form-control', 'placeholder' => 'Tên thương hiệu']) !!}
                        @error('title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        {!! Form::label('slug_modal', 'Slug (*)', ['class' => 'title']) !!}
                        {!! Form::text('slug', '', ['id' => 'slug_modal', 'class' => 'form-control', 'placeholder' => 'Slug']) !!}
                        @error('slug')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="title" for="">Trạng thái (*)</label>
                        <div class="row">
                            <div class="col-sm-4 form-check">
                                {!! Form::radio('status', 'pending', $checked = false, [
                                    'class' => 'form-check-input',
                                    'id' => 'status-pending',
                                ]) !!}
                                {!! Form::label('status-pending', 'Chờ duyệt', ['class' => 'form-check-label']) !!}
                            </div>
                            <div class="col-sm-4 form-check">
                                {!! Form::radio('status', 'public', $checked = false, ['class' => 'form-check-input', 'id' => 'status-public']) !!}
                                {!! Form::label('status-public', 'Công khai', ['class' => 'form-check-label']) !!}
                            </div>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            {!! Form::label('user_create', 'Người tạo', ['class' => 'title']) !!}
                            {!! Form::text('user', '', [
                                'id' => 'user_create',
                                'class' => 'form-control',
                                'placeholder' => 'Người tạo',
                                'disabled' => 'disabled',
                            ]) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            {!! Form::label('create_at', 'Ngày tạo', ['class' => 'title']) !!}
                            {!! Form::text('create_at', '', [
                                'id' => 'create_at',
                                'class' => 'form-control',
                                'placeholder' => 'Ngày tạo',
                                'disabled' => 'disabled',
                            ]) !!}
                        </div>
                        <div class="col-sm-6 form-group">
                            {!! Form::label('update_at', 'Cập nhật gần nhất', ['class' => 'title']) !!}
                            {!! Form::text('update_at', '', [
                                'id' => 'update_at',
                                'class' => 'form-control',
                                'placeholder' => 'Người tạo',
                                'disabled' => 'disabled',
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-close" data-dismiss="modal">Huỷ</button>
                    <button type="button" class="btn btn-primary btn-update-brand" id=""
                        data-url="{{ route('brand.update.product', '') }}">Lưu thay đổi</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
