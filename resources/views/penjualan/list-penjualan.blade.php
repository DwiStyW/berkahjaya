@extends('layout.master')
@section('title')
    Penjualan
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

        table.dataTable.cell-border tbody tr th:first-child,
        table.dataTable.cell-border tbody tr td:first-child {
            border-left: 1px solid rgba(0, 0, 0, 0.15);
        }

        table.dataTable.cell-border tbody tr:first-child th,
        table.dataTable.cell-border tbody tr:first-child td {
            border-top: none;
        }
    </style>
@endsection
@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            Berkah Jaya
        @endslot
        @slot('title')
            Penjualan
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="mb-3">
                        <a href="/penjualan-add" class="p-2 btn-sm btn-success">
                            <i class="uil uil-file-plus font-size-18 me-1"></i>Tambah Data Penjualan
                        </a>
                    </div>
                    <table id="datatable" class="cell-border dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Buyer</th>
                                <th>Uraian</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($penjualan as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->supplier }}</td>
                                    <td>{{ $item->vol_m3 }}</td>
                                    <td>{{ $item->total_harga }}</td>
                                    <td>
                                        <ul class="list-inline mb-0">
                                            <li class="list-inline-item">
                                                <a href="penjualan-detail/{{ Crypt::encrypt($item->id) }}"
                                                    class="px-2 btn-sm btn-warning"><i class="uil uil-eye font-size-18"></i>
                                                    Detail</a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a href="penjualan-edit/{{ Crypt::encrypt($item->id) }}"
                                                    class="px-2 text-primary"><i class="uil uil-pen font-size-18"></i></a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a onclick="hapus('{{ Crypt::encrypt($item->id) }}')" data-bs-toggle="modal"
                                                    data-bs-target="#hapusmodal" class="px-2 text-danger">
                                                    <i class="uil uil-trash-alt font-size-18"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
    @include('penjualan.delete-Penjualan')
@endsection
@section('script')
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    {{-- <script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script> --}}
    <script>
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                columnDefs: [{
                    render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                    targets: [4]
                }]
            });
        });
    </script>
@endsection
