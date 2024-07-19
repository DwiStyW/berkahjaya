@extends('layout.master')
@section('title')
    Pembelian
@endsection
@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        table.dataTable.cell-border thead th {
            border: 1px solid rgba(0, 0, 0, 0.25);
            text-align: center;
        }

        table.dataTable.cell-border tbody th,
        table.dataTable.cell-border tbody td {
            border-top: 1px solid rgba(0, 0, 0, 0.15);
            border-right: 1px solid rgba(0, 0, 0, 0.15);
            border-bottom: 1px solid rgba(0, 0, 0, 0.15);
            padding: 5px 10px 5px 10px;
        }

        table.dataTable.cell-border thead tr th {
            padding: 5px 10px 5px 10px;
        }

        table.dataTable.cell-border tbody tr th:first-child,
        table.dataTable.cell-border tbody tr td:first-child {
            border-left: 1px solid rgba(0, 0, 0, 0.15);
        }

        .dataTable>thead>tr>th[class*="sort"]:before,
        .dataTable>thead>tr>th[class*="sort"]:after {
            content: "" !important;
        }
    </style>
@endsection
@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            Berkah Jaya
        @endslot
        @slot('title')
            Pembelian
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                @foreach ($penjualan as $item)
                    <div class="card-body ms-5 me-5 mt-3">
                        <div class="position-absolute" style="right:0;margin-right:20px">

                            <a href="/printPenjualan/{{ $id }}" target="_blank" class="btn btn-sm btn-warning">
                                <i class="uil uil-print"></i>
                                Cetak
                            </a>
                        </div>
                        <div class="row mb-5">
                            <div class="col-md-8">
                                <div class="float-start">
                                    <h1>INVOICE</h1>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="float-start">
                                    <span class="d-flex">
                                        <h6>Tagihan &ensp;</h6>:&ensp;
                                    </span>
                                    <span class="d-flex">
                                        <h6>Tanggal &ensp;</h6>:&ensp;<h6>{{ $item->tanggal }}</h6>
                                    </span>
                                    <span class="d-flex">
                                        <h6>P.O &emsp;</h6>&emsp;&ensp;:&ensp;
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <div class="float-start">
                                    <h6>Dari &ensp; :</h6>
                                    <h5>UD. BERKAH JAYA</h5>
                                    <h6>Dsn. TIRU LOR Ds. Sentul</h6>
                                    <h6>Kec. GURAH KEDIRI</h6>
                                    <h6>081-246281104</h6>
                                    <a href="#">BERKAHJAYA@GMAIL.COM</a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="float-start">
                                    <h6>Tagihan Kepada &ensp; :</h6>
                                    <h5>{{ $item->supplier }}</h5>
                                    <h5></h5>
                                    <h6>{{ $item->alamat }}</h6>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <table id="datatable" class="dataTable cell-border w-100">
                                <thead>
                                    <tr>
                                        <th rowspan="2">No</th>
                                        <th colspan="5">Deskripsi</th>
                                        <th rowspan="2">Crate</th>
                                        <th rowspan="2">Pcs</th>
                                        <th rowspan="2">Vol(m3)</th>
                                        <th rowspan="2">Price/m3</th>
                                        <th rowspan="2">Total Price</th>
                                    </tr>
                                    <tr>
                                        <th>Grade</th>
                                        <th>Jenis Kayu</th>
                                        <th colspan="3">Size</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>{{ $item->grade }}</td>
                                        <td>{{ $item->jenis_kayu }}</td>
                                        <td>{{ $item->ukuran1 }}</td>
                                        <td>{{ $item->ukuran2 }}</td>
                                        <td>{{ $item->ukuran3 }}</td>
                                        <td>{{ $item->crate }}</td>
                                        <td>{{ $item->pcs }}</td>
                                        <td>{{ $item->vol_m3 }}</td>
                                        <td>{{ $item->harga_vol_m3 }}</td>
                                        <td>{{ $item->total_harga }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="float-start">
                                    <h6>NB &ensp; :</h6>
                                    <span class="d-flex">
                                        <h6>Transfer &ensp;:&ensp; </h6>
                                        <h6><b>Rek BCA / 6170254901</b></h5>
                                    </span>
                                    <h6>&emsp;&emsp;&emsp;&emsp;&emsp;&ensp;<b>A/N EKO ADITIYO</b></h6>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="float-start">
                                    <br>
                                    <h6>Kediri, &ensp;{{ $item->tanggal }} </h6>
                                    <h6>&emsp;&emsp;Hormat Kami</h6>
                                    <br>
                                    <br>
                                    <br>
                                    <h6>&emsp;<b>PAVEL ZALIANTY</b></h6>
                                </div>
                            </div>
                        </div>
                @endforeach
            </div>
            {{-- </div> <!-- end col --> --}}
        </div> <!-- end col -->
    </div> <!-- end row -->
    {{-- @include('pembelian.delete-pembelian') --}}
@endsection
@section('script')
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    {{-- <script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script> --}}
    <script src="//cdn.rawgit.com/ashl1/datatables-rowsgroup/v2.0.0/dataTables.rowsGroup.js"></script>
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                dom: '<"table-responsive w-100"<t>>',
                columnDefs: [{
                    render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                    targets: [9, 10]
                }]
            });
        });
    </script>
@endsection
