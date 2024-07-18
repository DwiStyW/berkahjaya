@extends('layout.master')
@section('title')
    Data Produksi
@endsection
@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    @include('produksi.css')
@endsection
@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            Berkah Jaya
        @endslot
        @slot('title')
            Data Produksi
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="mb-3">
                        <a href="produksi-add" class="p-2 btn-sm btn-success">
                            <i class="uil uil-file-plus font-size-18 me-1"></i>Tambah Produksi
                        </a>
                    </div>
                    <table id="datatable" class="cell-border" style="width: 100%;">
                        <thead class="bg-secondary text-white">
                            <tr>
                                {{-- <th rowspan="2">No</th> --}}
                                <th rowspan="2">Tanggal</th>
                                <th rowspan="2">Supplier</th>
                                <th rowspan="2">Persentase</th>
                                <th rowspan="2">Log OPC</th>
                                @if (Auth::user()->role == '1')
                                    <th rowspan="2">Harga</th>
                                    <th colspan="3">OPC</th>
                                    <th colspan="3">OPC B</th>
                                    <th colspan="2">PPC</th>
                                    <th colspan="2">MK</th>
                                    <th colspan="2">Ampulur</th>
                                @else
                                    <th colspan="2">OPC</th>
                                    <th colspan="2">OPC B</th>
                                    <th colspan="1">PPC</th>
                                    <th colspan="1">MK</th>
                                    <th colspan="1">Ampulur</th>
                                @endif

                                <th rowspan="2">Aksi</th>
                            </tr>
                            <tr>
                                <th data-dt-order="disable">Pcs</th>
                                <th data-dt-order="disable">m3</th>
                                @if (Auth::user()->role == '1')
                                    <th data-dt-order="disable">total harga</th>
                                @endif
                                <th data-dt-order="disable">Pcs</th>
                                <th data-dt-order="disable">m3</th>
                                @if (Auth::user()->role == '1')
                                    <th data-dt-order="disable">total harga</th>
                                @endif
                                <th data-dt-order="disable">m</th>
                                @if (Auth::user()->role == '1')
                                    <th data-dt-order="disable">total harga</th>
                                @endif
                                <th data-dt-order="disable">m</th>
                                @if (Auth::user()->role == '1')
                                    <th data-dt-order="disable">total harga</th>
                                @endif
                                <th data-dt-order="disable">pcs</th>
                                @if (Auth::user()->role == '1')
                                    <th data-dt-order="disable">total harga</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($produksi as $item)
                                <tr>
                                    {{-- <td>{{ $no++ }}</td> --}}
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->supplier }}<span class="d-none">{{ $item->kode_produksi }}</span></td>
                                    <td>{{ round($item->persentase) }}%</td>
                                    <td>{{ $item->log_opc }}</td>
                                    @if (Auth::user()->role == '1')
                                        <td>{{ $item->harga_log }}</td>
                                    @endif
                                    <td>{{ $item->opc_pcs }}</td>
                                    <td>{{ $item->opc_m3 }}</td>
                                    @if (Auth::user()->role == '1')
                                        <td>{{ $item->opc_total }}</td>
                                    @endif
                                    <td>{{ $item->opcb_pcs }}</td>
                                    <td>{{ $item->opcb_m3 }}</td>
                                    @if (Auth::user()->role == '1')
                                        <td>{{ $item->opcb_total }}</td>
                                    @endif
                                    <td>{{ $item->ppc_m }}</td>
                                    @if (Auth::user()->role == '1')
                                        <td>{{ $item->ppc_total }}</td>
                                    @endif
                                    <td>{{ $item->mk_m }}</td>
                                    @if (Auth::user()->role == '1')
                                        <td>{{ $item->mk_total }}</td>
                                    @endif
                                    <td>{{ $item->ampulur_pcs }}</td>
                                    @if (Auth::user()->role == '1')
                                        <td>{{ $item->ampulur_total }}</td>
                                    @endif
                                    <td>
                                        <ul class="list-inline mb-0">
                                            <li class="list-inline-item">
                                                <a href="/produksi-edit-perkode/{{ $item->kode_produksi }}"
                                                    class="px-2 text-primary"><i class="uil uil-pen font-size-18"></i></a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a onclick="hapus('{{ $item->kode_produksi }}')" data-bs-toggle="modal"
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


    @include('produksi.delete-produksi')
@endsection
@section('script')
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    {{-- <script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/gh/ashl1/datatables-rowsgroup@v2.0.0/dataTables.rowsGroup.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#datatable').DataTable({

                @if (Auth::user()->role == '1')
                    rowsGroup: [0, 1, 2, 3, 4, 17],
                    columnDefs: [{
                        render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                        targets: [4, 7, 10, 12, 14, 16]
                    }],
                @else
                    rowsGroup: [0, 1, 2, 3, 10],
                @endif
                // pageLength: '20',
                // dom: '<"row justify-content-between"<><"row"<f><B>>><"table-responsive"<t>><"row justify-content-between"ip>'
                dom: 'fr<"table-responsive w-100"<t>>ip'
            });
        });
    </script>
@endsection
