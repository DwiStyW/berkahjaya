@extends('layout.master')
@section('title')
    Tambah Data Pembelian
@endsection
@section('css')
    <!-- plugin css -->
    {{-- <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" /> --}}
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/spectrum-colorpicker/spectrum-colorpicker.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/datepicker/datepicker.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
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

        /* table.dataTable.cell-border tbody tr:first-child th,
                                                        table.dataTable.cell-border tbody tr:first-child td {
                                                            border-top: none;
                                                        } */
    </style>
@endsection
@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            Berkah Jaya
        @endslot
        @slot('title')
            Tambah Data Pembelian
        @endslot
    @endcomponent
    <div class="row">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="/detailpembelian-store" method="POST">
                            @csrf
                            <div class="mb-3 row">
                                <label class="col-md-2 col-form-label">Tanggal</label>
                                <div class="col-md-10">
                                    <div class="input-group" id="datepicker2">
                                        <input type="hidden" class="form-control" placeholder="dd M, yyyy"
                                            data-date-format="dd M, yyyy" data-date-container='#datepicker2'
                                            data-provide="datepicker" data-date-autoclose="true" id="tanggal_field" required
                                            disabled>
                                        <input type="text" class="form-control" placeholder="dd M, yyyy"
                                            data-date-format="dd M, yyyy" data-date-container='#datepicker2'
                                            data-provide="datepicker" data-date-autoclose="true" id="tanggal"
                                            name="tanggal" onchange="setTanggal()" required>

                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div><!-- input-group -->
                            </div>
                            <div class="mb-3 row">
                                <label for="supplier" class="col-md-2 col-form-label">Supplier</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="hidden" placeholder="nama supplier"
                                        id="supplier_field" name="supplier_field" disabled>
                                    <input class="form-control" type="text" placeholder="nama supplier" id="supplier"
                                        name="supplier" oninput="setSupplier()">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="jenis_kayu" class="col-md-2 col-form-label">Jenis Kayu</label>
                                <div class="col-md-10">
                                    <select class="form-control select2" name="id_master_mentah" id="id_master_mentah"
                                        onchange="pilihMaster()">
                                        <option value="null" disabled selected>Pilih Jenis Kayu</option>
                                        @foreach ($mastermentah as $iMaster)
                                            <option value="{{ $iMaster->id }}">{{ $iMaster->jenis_muatan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="tampil_model" style="display:none">
                                <div class="mb-3 row">
                                    <div class="col-md-12 text-center">
                                        <div class="d-flex col-12">
                                            <div class="col-2 border  bg-soft-secondary">
                                                <label class="col-form-label">Model</label>
                                            </div>
                                            <div class="col-1 border  bg-soft-secondary">
                                                <label class="col-form-label">Afkir</label>
                                            </div>
                                            <div class="col-1 border  bg-soft-secondary">
                                                <label class="col-form-label">Pakem</label>
                                            </div>
                                            <div class="col-2 border  bg-soft-secondary">
                                                <label class="col-form-label">Jumlah</label>
                                            </div>
                                            <div class="col-2 border  bg-soft-secondary">
                                                <label class="col-form-label">Volume</label>
                                            </div>
                                            <div class="col-2 border  bg-soft-secondary">
                                                <label class="col-form-label">Harga</label>
                                            </div>
                                            <div class="col-2 border  bg-soft-secondary">
                                                <label class="col-form-label">Rupiah</label>
                                            </div>
                                        </div>
                                        <div id="model_field"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="float-end">
                                <a href="javascript:history.back()" class="btn btn-md btn-secondary">back</a>
                                <button type="submit" class="btn btn-md btn-primary">Tambahkan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> <!-- end col -->
            {{-- <div class="row"> --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @foreach ($detail_pembelian as $dp)
                        @endforeach
                        @if (count($detail_pembelian) != 0)
                            <h6 class="text-secondary mb-1">{{ $dp->tanggal }} {{ $dp->supplier }}</h6>
                        @endif

                        @foreach ($detail_pembelian_group as $i)
                            <h6 class="text-secondary">{{ $i->jenis_muatan }}</h6>
                            <div class="mb-3">
                                <table id="datatable{{ $i->id_master_mentah }}"
                                    class="dataTable cell-border table-bordered dt-responsive nowrap border-secondary"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead class="bg-soft-secondary text-dark">
                                        <tr>
                                            <th>Model</th>
                                            <th></th>
                                            <th>Pakem</th>
                                            <th>Jumlah</th>
                                            <th>Volume</th>
                                            <th>Harga</th>
                                            <th>Rupiah</th>
                                            <th>Aksi</th>
                                        </tr>

                                    </thead>

                                    <tbody>
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
                                                <tr>

                                                    <td>
                                                        @if ($item->status != 'afkir')
                                                            {{ $item->kelas_model }}
                                                        @else
                                                            {{ $item->status }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->model }}</td>
                                                    <td>{{ $item->pakem_pembulatan }}</td>
                                                    <td>{{ $item->jumlah }}</td>
                                                    <td>{{ $item->vol }}</td>
                                                    <td>{{ $item->harga_model }}</td>
                                                    <td>{{ $item->total_harga }}</td>
                                                    <td>
                                                        <ul class="list-inline mb-0">
                                                            <li class="list-inline-item">
                                                                <a onclick="hapus('{{ Crypt::encrypt($item->id) }}')"
                                                                    data-bs-toggle="modal" data-bs-target="#hapusmodal"
                                                                    class="px-2 text-danger">
                                                                    <i class="uil uil-trash-alt font-size-18"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>
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
                                            <th>{{ array_sum($volume) }}</th>
                                            <th></th>
                                            <th>{{ 'Rp ' . number_format(array_sum($rupiah), 0, ',', '.') }}</th>
                                            <th></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
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

                        <div class="float-end mt-3">
                            <a href="/pembelian-store" onclick="resetVal()" id="simpan_produksi"
                                class="btn btn-md btn-primary">Simpan</a>
                        </div>
                    </div>
                </div>
                {{-- </div> <!-- end col --> --}}
            </div>
        </div>
    </div>
    @include('pembelian.delete-detail-pembelian')
@endsection
@section('script')
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/spectrum-colorpicker/spectrum-colorpicker.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/datepicker/datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/form-advanced.init.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/gh/ashl1/datatables-rowsgroup@v2.0.0/dataTables.rowsGroup.js"></script>

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
        // $.fn.dataTable.ext.errMode = 'none';

        $("input[data-type='currency']").on({
            keyup: function() {
                formatCurrency($(this));
            },
            blur: function() {
                formatCurrency($(this), "blur");
            },
            change: function() {
                formatCurrency($(this));
            }
        });


        function formatNumber(n) {
            // format number 1000000 to 1,234,567
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        }


        function formatCurrency(input, blur) {
            // appends $ to value, validates decimal side
            // and puts cursor back in right position.

            // get input value
            var input_val = input.val();

            // don't validate empty input
            if (input_val === "") {
                return;
            }

            // original length
            var original_len = input_val.length;

            // initial caret position
            var caret_pos = input.prop("selectionStart");

            // check for decimal
            if (input_val.indexOf(",") >= 0) {

                // get position of first decimal
                // this prevents multiple decimals from
                // being entered
                var decimal_pos = input_val.indexOf(",");

                // split number by decimal point
                var left_side = input_val.substring(0, decimal_pos);
                var right_side = input_val.substring(decimal_pos);

                // add commas to left side of number
                left_side = formatNumber(left_side);

                // validate right side
                right_side = formatNumber(right_side);

                // On blur make sure 2 numbers after decimal
                if (blur === "blur") {
                    right_side += "00";
                }

                // Limit decimal to only 2 digits
                right_side = right_side.substring(0, 2);

                // join number by .
                input_val = "Rp " + left_side + "," + right_side;

            } else {
                // no decimal entered
                // add commas to number
                // remove all non-digits
                input_val = formatNumber(input_val);
                input_val = "Rp " + input_val;

                // final formatting
                if (blur === "blur") {
                    input_val += ",00";
                }
            }

            // send updated string to input
            input.val(input_val);

            // put caret back in the right position
            var updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        }
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });



        function pilihMaster() {
            var id_master = document.getElementById('id_master_mentah').value;
            document.getElementById('tampil_model').style.display = 'block';
            // console.log(id_master);
            $('#id_model').empty().trigger("change");

            $.ajax({
                type: 'POST',
                url: "{{ route('getModel') }}",
                data: {
                    id_master: id_master,
                },
                success: function(data) {
                    console.log(data);
                    str = '';
                    str += '<input name="indexLoop" hidden class="form-control" value="' + data.length + '">';
                    for (i = 0; i < data.length; i++) {
                        if (data[i].kelas_model == null) {
                            kelas_model = '';
                        } else {
                            kelas_model = data[i].kelas_model;
                        }

                        // pakem
                        pakem = Number(data[i].pakem)
                        roundedString = pakem.toFixed(3);
                        rounded = Number(roundedString);

                        // rupiah
                        var rupiah = new Intl.NumberFormat("id-ID", {
                            style: "currency",
                            currency: "IDR"
                        }).format(data[i].harga);
                        str += '<div class="col-12 d-flex">';
                        str += '<div class="col-2 d-flex border">';
                        str += '<div class="col-6">';
                        str += '<input id="kelas_model' + i + '" name="kelas_model' + i +
                            '" class="form-control" disabled value="' + kelas_model + '">';
                        str += '<input name="id_model' + i + '" hidden class="form-control" value="' + data[i]
                            .id + '">';
                        str += '</div>';
                        str += '<div class="col-6">';
                        str += '<input id="model' + i + '" name="model' + i +
                            '" class="form-control" disabled value="' + data[i].model + '">';
                        str += '</div>';
                        str += '</div>';
                        str += '<div class="col-1 pt-2 border border-light bg-soft-light">';
                        str +=
                            '<input type="checkbox" class=" form-check-input" name="afkir' + i + '" id="afkir' +
                            i + '" onclick="cekAfkir(' + i + ',' + data[i].harga + ')">';
                        str += '</div>';
                        str += '<div class="col-1 border">';
                        str += '<input class="form-control" disabled value="' + rounded + '">';
                        str += '<input id="pakem' + i + '" name="pakem' + i +
                            '" class="form-control" hidden value="' + data[i]
                            .pakem + '">';
                        str += '</div>';
                        str += '<div class="col-2 ">';
                        str += '<input id="jumlah' + i + '" name="jumlah' + i +
                            '" class="form-control" type="number" oninput="hitung(' + i + ')">';
                        str += '</div>';
                        str += '<div class="col-2 border">';
                        str += '<input id="volume' + i + '" name="volume' + i + '" class="form-control">';
                        str += '</div>';
                        str += '<div class="col-2 border">';
                        str += '<input id="harga_rupiah' + i + '" class="form-control" disabled value="' +
                            rupiah + '">';
                        str += '<input id="harga' + i + '" name="harga' + i +
                            '" hidden class="form-control" value="' + data[i].harga + '">';
                        str += '</div>';
                        str += '<div class="col-2 border">';
                        str += '<input id="rupiah' + i + '" name="rupiah' + i + '" class="form-control">';
                        str += '</div>';
                        str += '</div>';
                    }
                    document.getElementById('model_field').innerHTML = str;
                }
            });
        }

        function rp(n) {
            var rupiah = new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
            }).format(n);
            return rupiah;
        }

        function hitung(n) {
            var pakem = document.getElementById('pakem' + n).value;
            var jumlah = document.getElementById('jumlah' + n).value;
            var harga = document.getElementById('harga' + n).value;

            var volume = jumlah * pakem;
            // var total = volume * harga;
            if (document.getElementById('afkir' + n).checked == true) {
                var total = volume * 200000;
            } else {
                var total = volume * harga;
            }

            var totalVolume = volume.toFixed(4);
            var totalHarga = total.toFixed(0);

            var rupiah = new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
            }).format(totalHarga);
            console.log(jumlah);
            if (jumlah == '') {
                document.getElementById('volume' + n).value = '';
                document.getElementById('rupiah' + n).value = '';
            } else {
                document.getElementById('volume' + n).value = totalVolume;
                document.getElementById('rupiah' + n).value = rupiah;
            }

        }


        function setTanggal() {
            localStorage.setItem("tanggal", document.getElementById('tanggal').value);
        }

        function setSupplier() {
            localStorage.setItem("supplier", document.getElementById('supplier').value);
        }

        if (@json($detail_pembelian).length > 0) {

            if (localStorage.getItem("tanggal") != '') {
                document.getElementById('tanggal').value = localStorage.getItem("tanggal");
                document.getElementById('tanggal_field').value = localStorage.getItem("tanggal");
                document.getElementById('tanggal').setAttribute("type", "hidden");
                document.getElementById('tanggal_field').setAttribute("type", "text");


            }
            if (localStorage.getItem("supplier") != '') {
                document.getElementById('supplier').value = localStorage.getItem("supplier");
                document.getElementById('supplier_field').value = localStorage.getItem("supplier");
                document.getElementById('supplier').setAttribute("type", "hidden");
                document.getElementById('supplier_field').setAttribute("type", "text");
            }
            document.getElementById('simpan_produksi').classList.remove('disabled');
        } else if (@json($detail_pembelian).length == 0) {
            resetVal()
            document.getElementById('tanggal').removeAttribute("disabled");
            document.getElementById('supplier').removeAttribute("disabled");
            $('#supplier_field').next(".select2").hide();
            $('#supplier').next(".select2").show();
            document.getElementById('simpan_produksi').classList.add('disabled');

        }

        function resetVal() {
            localStorage.removeItem("tanggal");
            localStorage.removeItem("supplier");
        }

        function cekAfkir(n, harga) {
            if (document.getElementById('afkir' + n).checked == true) {
                document.getElementById('harga' + n).value = 200000;
                document.getElementById('harga_rupiah' + n).value = rp(200000);
            } else {
                document.getElementById('harga' + n).value = harga;
                document.getElementById('harga_rupiah' + n).value = rp(harga);
            }
            hitung(n)
        }
    </script>
@endsection
