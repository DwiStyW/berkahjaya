@extends('layout.master')
@section('title')
    Detail Master Mentah
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

        table.dataTable thead th,
        tavle.dataTable tbody td {
            max-width: 100px;
        }
    </style>
@endsection
@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            Berkah Jaya
        @endslot
        @slot('title')
            Detail Master Mentah
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if (Auth::user()->role == '1')
                        <div class="mb-3">
                            <a href="/detailmastermentah-add/{{ $id }}" class="p-2 btn-sm btn-success">
                                <i class="uil uil-file-plus font-size-18 me-1"></i>Tambah Model
                            </a>
                        </div>
                    @endif
                    <table id="datatable" class="cell-border dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th style="width:10px">No</th>
                                <th>Model</th>
                                <th></th>
                                <th>Pakem</th>
                                <th>Harga Baru</th>
                                <th>Harga Lama</th>
                                <th>Aksi</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($detail_mastermentah as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $item->kelas_model }}</td>
                                    <td>{{ $item->model }}</td>
                                    <td>{{ round($item->pakem_pembulatan, 3) }}</td>
                                    <td>{{ $item->harga }}</td>
                                    <td>{{ $item->harga_khusus }}</td>
                                    <td>
                                        <ul class="list-inline mb-0">
                                            <li class="list-inline-item">
                                                <a href="/detailmastermentah-edit/{{ Crypt::encrypt($item->id) }}"
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
                                    <td>
                                        <ul class="list-inline mb-0">
                                            <li class="list-inline-item">
                                                @php
                                                    if ($item->kelas_model == null) {
                                                        $kelas_model = 'null';
                                                    } else {
                                                        $kelas_model = $item->kelas_model;
                                                    }
                                                @endphp
                                                <a href="/detailmastermentah-edit-permodel/{{ $item->id_master_mentah }}/{{ $kelas_model }}"
                                                    class="px-2 btn btn-sm btn-warning ">Edit Permodel <i
                                                        class="uil uil-pen font-size-18"></i></a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3 float-end">
                        <a href="/mastermentah" class="p-2 btn-sm btn-secondary">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
    @include('detail-master-mentah.delete-detail-master-mentah')
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
                dom: 'fr<"table-responsive w-100"<t>>',
                rowsGroup: [7],
                paging: false
            });
        });
    </script>
@endsection
