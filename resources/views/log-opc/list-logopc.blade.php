@extends('layout.master')
@section('title')
    Log Opc
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
            Log Opc
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if (Auth::user()->role == '1')
                        <div class="d-flex mb-3">
                            <a href="/logopc-add" class="p-2 btn-sm btn-success">
                                <i class="uil uil-file-plus font-size-18 me-1"></i>Tambah Log Opc
                            </a>
                        </div>
                    @endif
                    <table id="datatable" class="dataTable cell-border" style="width: 100%;">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>uraian</th>
                                <th></th>
                                <th></th>
                                @if (Auth::user()->role == '1')
                                    <th>Debet</th>
                                    <th>Kredit</th>
                                    <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($datalog as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->supplier }}</td>
                                    <td>
                                        @if ($item->ket == 'beli')
                                            {{ $item->uraian }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->ket == 'jual' || $item->ket == 'stock')
                                            {{ $item->uraian }}
                                        @endif
                                    </td>
                                    @if (Auth::user()->role == '1')
                                        <td>
                                            @if ($item->ket == 'jual' || $item->ket == 'stock')
                                                {{ $item->harga }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->ket == 'beli')
                                                {{ $item->harga }}
                                            @endif
                                        </td>

                                        {{-- <td>{{ $item->harga }}</td> --}}
                                        <td>
                                            <ul class="list-inline mb-0">
                                                <li class="list-inline-item">
                                                    <a href="logopc-edit/{{ Crypt::encrypt($item->id) }}"
                                                        class="px-2 text-primary"><i
                                                            class="uil uil-pen font-size-18"></i></a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a onclick="hapus('{{ Crypt::encrypt($item->id) }}')"
                                                        data-bs-toggle="modal" data-bs-target="#hapusmodal"
                                                        class="px-2 text-danger">
                                                        <i class="uil uil-trash-alt font-size-18"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
    @include('log-opc.delete-logopc')
@endsection
@section('script')
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    {{-- <script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script> --}}
    <script src="//cdn.rawgit.com/ashl1/datatables-rowsgroup/v2.0.0/dataTables.rowsGroup.js"></script>
    <script src="//cdn.datatables.net/plug-ins/2.0.8/sorting/currency.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                rowsGroup: [1],
                // pageLength: '20',
                // dom: '<"row justify-content-between"<><"row"<f><B>>><"table-responsive"<t>><"row justify-content-between"ip>'
                dom: 'fr<"table-responsive w-100"<t>>ip',
                @if (Auth::user()->role == '1')
                    columnDefs: [{
                        render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                        targets: [5, 6]
                    }]
                @endif
            });
        });
    </script>
@endsection
