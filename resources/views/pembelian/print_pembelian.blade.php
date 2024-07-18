<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pembelian</title>
    <link href="{{ URL::asset('/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ URL::asset('/assets/css/icons.min.css') }}" id="icons-style" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ URL::asset('/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    {{-- <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" /> --}}
</head>
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

<body>
    <div id="dataPembelian">
        @foreach ($detail_pembelian as $dp)
        @endforeach
        @if (count($detail_pembelian) != 0)
            <div class="d-flex col-12 mb-3">
                <div class="col-6">
                    <div class="float-start">
                        <h6 class="text-secondary mb-1">Tanggal &emsp;&emsp;&nbsp;:&emsp;{{ $dp->tanggal }}
                        </h6>
                        <h6 class="text-secondary mb-1">Supplier &emsp;&emsp;:&emsp;{{ $dp->supplier }}</h6>
                    </div>
                </div>

                <div class="col-6">
                    <div class="float-end">
                        <h6>{{ $dp->kode_pembelian }}</h6>
                    </div>
                </div>
            </div>
        @endif

        @foreach ($detail_pembelian_group as $i)
            <div class="col-12">
                <h6 class="text-secondary">{{ $i->jenis_muatan }}</h6>
            </div>

            <div class="mb-3">
                <table id="datatable{{ $i->id_master_mentah }}" class="dataTable cell-border dt-responsive nowrap "
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead class="bg-soft-secondary text-dark">
                        <tr>
                            <th>Model</th>
                            <th></th>
                            <th>Pakem</th>
                            <th>Jumlah</th>
                            <th>Volume</th>
                            <th>Harga</th>
                            <th>Total</th>
                        </tr>

                    </thead>

                    <tbody>
                        @php
                            $jumlah = [];
                            $vol = [];
                            $total = [];
                        @endphp
                        @foreach ($detail_pembelian as $item)
                            @if ($i->id_master_mentah == $item->id_master_mentah)
                                @php
                                    array_push($jumlah, $item->jumlah);
                                    array_push($vol, $item->vol);
                                    array_push($total, $item->total_harga);
                                @endphp
                                <tr>

                                    <td>
                                        @if ($item->status != 'afkir')
                                            {{ $item->kelas_model }}
                                        @else
                                            Afkir
                                        @endif
                                    </td>
                                    <td>{{ $item->model }}</td>
                                    <td>{{ $item->pakem_pembulatan }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>{{ $item->vol }}</td>
                                    <td>{{ $item->harga_model }}</td>
                                    <td>{{ $item->total_harga }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                    <tbody class="bg-light">
                        <tr>
                            <th>Total</th>
                            <th></th>
                            <th></th>
                            <th>{{ array_sum($jumlah) }}</th>
                            <th>{{ array_sum($vol) }}</th>
                            <th></th>
                            <th>{{ 'Rp ' . number_format(array_sum($total), 0, ',', '.') }}</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endforeach
        <div class="mb-3 mt-3">
            <table class="dataTable cell-border table-bordered border-light w-100">
                <thead>
                    <tr class="bg-light">
                        <th>Jenis Kayu</th>
                        <th>Jumlah</th>
                        <th>Volume</th>
                        <th>Rupiah</th>
                    </tr>
                </thead>
                @php
                    $total_jumlah = [];
                    $total_volume = [];
                    $total_rupiah = [];
                @endphp
                @foreach ($detail_pembelian_group as $i)
                    @php
                        $jumlah = [];
                        $volume = [];
                        $rupiah = [];
                    @endphp
                    @foreach ($detail_pembelian as $item)
                        @if ($i->id_master_mentah == $item->id_master_mentah)
                            @php
                                array_push($jumlah, $item->jumlah);
                                array_push($volume, $item->vol);
                                array_push($rupiah, $item->total_harga);
                            @endphp
                        @endif
                    @endforeach
                    @php
                        array_push($total_jumlah, array_sum($jumlah));
                        array_push($total_volume, array_sum($volume));
                        array_push($total_rupiah, array_sum($rupiah));
                    @endphp
                    <tbody>
                        <tr>
                            <td>{{ $i->jenis_muatan }}</td>
                            <td>{{ array_sum($jumlah) }}</td>
                            <td>{{ array_sum($volume) }}</td>
                            <td>{{ 'Rp ' . number_format(array_sum($rupiah), 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                @endforeach
                <tbody>
                    <tr class="bg-light">
                        <th>Total</th>
                        <th>{{ array_sum($total_jumlah) }}</th>
                        <th>{{ array_sum($total_volume) }}</th>
                        <th>{{ 'Rp ' . number_format(array_sum($total_rupiah), 0, ',', '.') }}</th>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-6"></div>
            <div class="col-6 text-center">
                @foreach ($reg as $r)
                    <b>
                        {{-- {{ $r->supplier }} --}}
                        <br>
                        {{ $r->nama }}
                        <br>
                        {{ $r->bank }}
                        <br>
                        {{ $r->rek }}
                    </b>
                @endforeach
            </div>

        </div>
    </div>
</body>

</html>
<script src="{{ URL::asset('/assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/metismenu/metismenu.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/node-waves/node-waves.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/waypoints/waypoints.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/jquery-counterup/jquery-counterup.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/moment.js') }}"></script>
<script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
{{-- <script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script> --}}
<script src="//cdn.rawgit.com/ashl1/datatables-rowsgroup/v2.0.0/dataTables.rowsGroup.js"></script>
<script>
    $(document).ready(function() {
        $('#datatable1').DataTable({
            dom: '<"table-responsive w-100"<t>>',
            order: [
                [0, 'desc']
            ],
            rowsGroup: [0],
            paging: false,
            columnDefs: [{
                render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                targets: [5, 6]
            }]
        });
    });
    $(document).ready(function() {
        $('#datatable2').DataTable({
            dom: '<"table-responsive w-100"<t>>',
            order: [
                [0, 'desc']
            ],
            rowsGroup: [0],
            paging: false,
            columnDefs: [{
                render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                targets: [5, 6]
            }]
        });
    });
    $(document).ready(function() {
        $('#datatable3').DataTable({
            dom: '<"table-responsive w-100"<t>>',
            order: [
                [0, 'desc']
            ],
            rowsGroup: [0],
            paging: false,
            columnDefs: [{
                render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                targets: [5, 6]
            }]
        });
    });
    $(document).ready(function() {
        $('#datatable4').DataTable({
            dom: '<"table-responsive w-100"<t>>',
            order: [
                [0, 'desc']
            ],
            rowsGroup: [0],
            paging: false,
            columnDefs: [{
                render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                targets: [5, 6]
            }]
        });
    });
    $(document).ready(function() {
        $('#datatable5').DataTable({
            dom: '<"table-responsive w-100"<t>>',
            order: [
                [0, 'desc']
            ],
            rowsGroup: [0],
            paging: false,
            columnDefs: [{
                render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                targets: [5, 6]
            }]
        });
    });
    $(document).ready(function() {
        $('#datatable6').DataTable({
            dom: '<"table-responsive w-100"<t>>',
            order: [
                [0, 'desc']
            ],
            rowsGroup: [0],
            paging: false,
            columnDefs: [{
                render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                targets: [5, 6]
            }]
        });
    });
    $(document).ready(function() {
        window.print()
    })
</script>
