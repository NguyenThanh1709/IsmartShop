@extends('layouts.admin')

@section('title', 'Thống kê - báo cáo')

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                <div class="row">
                    <h5 class="m-0 col-md-8 col-xs-12">Thống kê - báo cáo doanh thu</h5>
                    {{-- <form action="#"
                        class="form-search form d-flex justify-content-end align-items-center col-md-4 col-xs-12 mr-0">
                        {!! Form::text('keyword', request()->input('keyword'), [
                            'class' => 'form-control form-search mr-1',
                            'placeholder' => 'Tìm kiếm',
                        ]) !!}
                        {!! Form::submit('Tìm kiếm', ['class' => 'btn btn-primary']) !!}
                    </form> --}}
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
                <form action="#">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-action form-inline py-3">
                            <select class="form-control mr-1 list-option-responsive" name="month" id="month">
                                <option value="">---Chọn Tháng---</option>
                                @foreach ($months as $month)
                                    {
                                    <option @if (request()->input('month') == $month->month) selected @endif value="{{ $month->month }}">
                                        Tháng {{ $month->month }}</option>
                                    }
                                @endforeach
                            </select>
                            <select class="form-control mr-1 list-option-responsive" name="year" id="year">
                                <option value="">---Chọn Năm---</option>
                                @foreach ($years as $year)
                                    {
                                    <option @if (request()->input('year') == $year->year) selected @endif value="{{ $year->year }}">
                                        Năm
                                        {{ $year->year }}</option>
                                    }
                                @endforeach
                            </select>
                            {!! Form::submit('Lọc dữ liệu', ['class' => 'btn btn-primary']) !!}
                             <button type="button" class="btn btn-primary ml-1" data-toggle="modal"
                                data-target="#modelExcel"><i class="fa-solid fa-print"></i> Xuất Excel</button>
                        </div>
                        <div class="btn-add--responsive">
                            <a class="btn btn-primary" href="{{ route('order.add') }}" role="button"><i
                                    class="fa-solid fa-plus"></i></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5 col-12">
                            <h6 class="text-uppercase text-dark">Doanh thu các ngày trong tháng <span
                                    class="font-weight-bold">{{ $dateMonth . '/'. $dateYear }}</span></h6>
                            <table class="table-revenue table table-striped table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Thời gian</th>
                                        <th scope="col">Tổng doanh thu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($revenueOfDay->total() > 0)
                                        @php $temp = (($revenueOfDay->currentpage() - 1) * $revenueOfDay->perpage() + 1) - 1 @endphp
                                        @foreach ($revenueOfDay as $item)
                                            @php
                                                $temp++;
                                            @endphp
                                            <tr>
                                                <td class="font-weight-bold">{{ $temp }}</td>
                                                <td class="date" data-url="{{ route('order.detailProductSaleOneDate') }}">
                                                    {{ $item->date }}</td>
                                                <td>{{ number_format($item->total_price_on_day, 0, ',') . 'đ' }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="8" class="text-center bg-white">Không có bản ghi nào được tìm
                                                thấy!
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            {{-- Phân trang --}}
                            {{ $revenueOfDay->appends(['month' => request()->input('month'), 'year' => request()->input('year')])->links() }}

                        </div>
                        <div class="col-sm-7 col-12">
                            <h6 class="text-uppercase text-dark">Chi tiết sản phẩm bán ra tháng <span
                                    class="font-weight-bold">{{ $dateMonth . '/'. $dateYear }}</span></h6>
                            <table class="table table-striped table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Tên sản phẩm</th>
                                        <th scope="col">S.lượng</th>
                                        <th scope="col">Doanh thu</th>
                                    </tr>
                                </thead>
                                <tbody id='detailSaleProduct'>
                                    @php
                                        $temp = 0;
                                    @endphp
                                    @foreach ($revenueOfProduct as $product)
                                        @php
                                            $subTotal = $product->price * $product->quantity;
                                        @endphp
                                        @php
                                            $temp++;
                                        @endphp
                                        <tr>
                                            <td class="font-weight-bold">{{ $temp }}</td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->quantity }}</td>
                                            <td>{{ number_format($subTotal, 0, ',') . 'đ' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
        <!-- Modal -->
    <div class="modal fade" id="modelExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Xuất báo cáo - thống kê</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Lựa chọn xuất theo:</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" checked id="monthCheckbox" value="month">
                            <label class="form-check-label" for="monthCheckbox">Tháng</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="quarterCheckbox" value="quarter">
                            <label class="form-check-label" for="quarterCheckbox">Quý</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="yearCheckbox" value="year">
                            <label class="form-check-label" for="yearCheckbox">Năm</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="selectMonth">Chọn Tháng:</label>
                                <select class="form-control" id="selectMonth">
                                    @foreach ($months as $month)
                                        <option @if (request()->input('month', date('m')) == $month->month) selected @endif
                                            value="{{ $month->month }}">
                                            Tháng {{ $month->month }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="selectQuarter">Chọn Quý:</label>
                                <select class="form-control" id="selectQuarter">
                                    <option value="1">Quý 1</option>
                                    <option value="2">Quý 2</option>
                                    <option value="3">Quý 3</option>
                                    <option value="4">Quý 4</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="selectYear">Chọn Năm:</label>
                                <select class="form-control" id="selectYear">
                                    @foreach ($years as $year)
                                        <option @if (request()->input('year', date('Y')) == $year->year) selected @endif
                                            value="{{ $year->year }}">
                                            Năm {{ $year->year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <a href="{{ route('order.export') }}" type="button" id="btn-export" class="btn btn-primary">Xuất Excel</a>
                </div>
            </div>
        </div>
    </div>
@endsection
