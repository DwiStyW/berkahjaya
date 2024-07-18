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
                        @foreach ($pembelian as $p)
                        @endforeach
                        <form action="/detailpembelian-perkode-edit/{{ $p->kode_pembelian }}" method="POST">
                            @csrf
                            <div class="mb-3 row">
                                <label class="col-md-2 col-form-label">Tanggal</label>
                                <div class="col-md-10">
                                    <div class="input-group" id="datepicker2">
                                        <input type="text" class="form-control" placeholder="dd M, yyyy"
                                            data-date-format="dd M, yyyy" data-date-container='#datepicker2'
                                            data-provide="datepicker" data-date-autoclose="true" id="tanggal"
                                            name="tanggal" onchange="setTanggal()" required disabled>

                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div><!-- input-group -->
                            </div>
                            <div class="mb-3 row">
                                <label for="supplier" class="col-md-2 col-form-label">Supplier</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" placeholder="nama supplier" id="supplier"
                                        name="supplier" oninput="setSupplier()" disabled>
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
                            <div class="mb-3 row">
                                <label for="vol" class="col-md-2 col-form-label">Model</label>
                                <div class="col-md-10">
                                    <select class="form-control select2" name="id_model" id="id_model"
                                        onchange="pilihModel()">
                                        <option value="null" disabled selected>Pilih Model</option>
                                    </select>
                                    <div id="detail" class="input-group">

                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="vol" class="col-md-2 col-form-label">Afkir</label>
                                <div class="col-md-10">
                                    <div class="form-check pt-2">
                                        <input type="checkbox" class="form-check-input" name="afkir" id="afkir"
                                            onclick="cekAfkir()">
                                        <label class="form-check-label" for="afkir" id="afkir_label">Non Afkir</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="supplier" class="col-md-2 col-form-label">Jumlah</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="number" placeholder="jumlah total" id="jumlah"
                                        name="jumlah" oninput="hitungVolHarga()">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="supplier" class="col-md-2 col-form-label">Volume</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" placeholder="jumlah volume" id="vol"
                                        name="vol">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="harga" class="col-md-2 col-form-label">Rupiah</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" data-type="currency"
                                        placeholder="Rp 0,00" id="total_harga" name="total_harga">
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
                                    class="table table-bordered dt-responsive nowrap "
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead class="bg-secondary text-white">
                                        <tr>
                                            <th colspan="2">Model</th>
                                            <th>Pakem</th>
                                            <th>Jumlah</th>
                                            <th>Volume</th>
                                            <th>Harga</th>
                                            <th>Total</th>
                                            <th>Aksi</th>
                                        </tr>

                                    </thead>

                                    <tbody>
                                        @foreach ($detail_pembelian as $item)
                                            @if ($i->id_master_mentah == $item->id_master_mentah)
                                                <tr>

                                                    <td>{{ $item->kelas_model }}</td>
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

                                </table>
                            </div>
                        @endforeach
                        <div class="float-end">
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

                columnDefs: [{
                    render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                    targets: [5, 6]
                }]
            });
        });
        $(document).ready(function() {
            $('#datatable2').DataTable({
                dom: '<"table-responsive w-100"<t>>',

                columnDefs: [{
                    render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                    targets: [5, 6]
                }]
            });
        });
        $(document).ready(function() {
            $('#datatable3').DataTable({
                dom: '<"table-responsive w-100"<t>>',

                columnDefs: [{
                    render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                    targets: [5, 6]
                }]
            });
        });
        $(document).ready(function() {
            $('#datatable4').DataTable({
                dom: '<"table-responsive w-100"<t>>',

                columnDefs: [{
                    render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                    targets: [5, 6]
                }]
            });
        });
        $(document).ready(function() {
            $('#datatable5').DataTable({
                dom: '<"table-responsive w-100"<t>>',

                columnDefs: [{
                    render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                    targets: [5, 6]
                }]
            });
        });
        $(document).ready(function() {
            $('#datatable6').DataTable({
                dom: '<"table-responsive w-100"<t>>',

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
            console.log(id_master);
            $('#id_model').empty().trigger("change");

            $.ajax({
                type: 'POST',
                url: "{{ route('getModel') }}",
                data: {
                    id_master: id_master,
                },
                success: function(data) {
                    // console.log(data);
                    var dataModel = [];
                    for (i = 0; i < data.length; i++) {
                        if (data[i].kelas_model != null) {
                            var kelas_model = data[i].kelas_model;
                        } else {
                            var kelas_model = "";
                        }
                        dataModel.push({
                            id: data[i].id,
                            text: kelas_model + '     ' + data[i].model
                        })
                    }


                    $("#id_model").select2({
                        data: dataModel
                    });
                    $('#id_model').select2().trigger("change");
                }
            });
            document.getElementById('detail').innerHTML = '';
        }

        function rp(n) {
            var rupiah = new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
            }).format(n);
            return rupiah;
        }

        function pilihModel() {
            var id_model = document.getElementById('id_model').value;
            // console.log(id_model);
            $.ajax({
                type: 'POST',
                url: "{{ route('getDetailmodel') }}",
                data: {
                    id_model: id_model,
                },
                success: function(data) {
                    // console.log(data);


                    str = '<span class="input-group-text">Pakem</span>';
                    str += '<input id="pakem" type="hidden" value="' + data[0].pakem +
                        '">';
                    str += '<input id="pakem_pembulatan" class="form-control input-group-text" value="' +
                        data[0].pakem_pembulatan + '">';
                    str += '<span class="input-group-text">Harga</span>';
                    str += '<input id="harga" name="harga" type="hidden" value="' + data[0].harga +
                        '">';
                    str += '<input id="harga_rupiah" class="form-control input-group-text" value="' +
                        rp(data[0].harga) +
                        '">';

                    document.getElementById('detail').innerHTML = str;
                    hitungVolHarga()
                    cekAfkir()
                },
                error: function(e) {
                    console.log(e);
                }
            });

        }

        function hitungVolHarga() {
            var jumlah = document.getElementById('jumlah').value;
            var pakem = document.getElementById('pakem').value;
            var harga = document.getElementById('harga').value;

            var volume = jumlah * pakem;
            // var total = volume * harga;
            if (document.getElementById('afkir').checked == true) {
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

            document.getElementById('vol').value = totalVolume;
            document.getElementById('total_harga').value = rupiah;
        }

        function setTanggal() {
            localStorage.setItem("tanggal", document.getElementById('tanggal').value);
        }

        function setSupplier() {
            localStorage.setItem("supplier", document.getElementById('supplier').value);
        }

        function resetVal() {
            localStorage.removeItem("tanggal");
            localStorage.removeItem("supplier");
        }

        function cekAfkir() {
            if (document.getElementById('afkir').checked == true) {
                document.getElementById('afkir_label').innerHTML = 'Afkir';
                document.getElementById('harga').value = 200000;
                document.getElementById('harga_rupiah').value = rp(200000);
            } else {
                document.getElementById('afkir_label').innerHTML = 'Non Afkir';
                pilihModel()
            }
            hitungVolHarga()
        }
        var tanggal = @json($tanggal);
        var supplier = @json($supplier);
        if (tanggal != '') {
            let dateFormat1 = moment(tanggal).format('DD MMM, yyyy');
            document.getElementById('tanggal').value = dateFormat1;
        }
        if (supplier != '') {
            document.getElementById('supplier').value = supplier;
        }
    </script>
@endsection
