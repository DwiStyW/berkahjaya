@extends('layout.master')
@section('title')
    Tambah Log Opc
@endsection
@section('css')
    <!-- plugin css -->
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/spectrum-colorpicker/spectrum-colorpicker.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/datepicker/datepicker.min.css') }}">
@endsection
@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            Berkah Jaya
        @endslot
        @slot('title')
            Tambah Log Opc
        @endslot
    @endcomponent
    <div class="row">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="/logopc-store" method="POST">
                            @csrf
                            <div class="mb-3 row">
                                <label for="kategori" class="col-md-2 col-form-label">Kategori</label>
                                <div class="col-md-10">
                                    <select class="form-control select2" name="kategori" id="kategori" required
                                        onchange="pilihKategori()">
                                        <option value="null" disabled selected>Pilih Kategori</option>
                                        <option value="pembelian">Pembelian</option>
                                        <option value="penjualan">Penjualan</option>
                                        <option value="stock">Stock</option>
                                    </select>
                                </div>
                            </div>
                            <div id="form">
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Tanggal</label>
                                    <div class="col-md-10">
                                        <div class="input-group" id="datepicker2">
                                            <input type="text" class="form-control" placeholder="dd M, yyyy"
                                                data-date-format="dd M, yyyy" data-date-container='#datepicker2'
                                                data-provide="datepicker" data-date-autoclose="true" id="tanggal"
                                                name="tanggal" required>

                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div><!-- input-group -->
                                </div>
                                <div class="mb-3 row">
                                    <label for="supplier" class="col-md-2 col-form-label">Supplier</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="nama supplier"
                                            id="supplier" name="supplier">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="vol" class="col-md-2 col-form-label">Total Volume</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="0.1" id="vol"
                                            name="vol">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="harga" class="col-md-2 col-form-label">Total Harga</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" data-type="currency"
                                            placeholder="Rp 0,00" id="harga" name="harga">
                                    </div>
                                </div>
                            </div>
                            <div class="float-end">
                                <a href="javascript:history.back()" class="btn btn-md btn-secondary">back</a>
                                <button type="submit" class="btn btn-md btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('/assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/spectrum-colorpicker/spectrum-colorpicker.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/datepicker/datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/form-advanced.init.js') }}"></script>
    <script>
        function pilihKategori() {
            var kategori = document.getElementById('kategori').value;
            if (kategori == 'penjualan') {
                str = '<div class="mb-3 row">';
                str += '    <label class="col-md-2 col-form-label">Tanggal</label>';
                str += '    <div class="col-md-10">';
                str += '        <div class="input-group" id="datepicker2">';
                str += '            <input type="text" class="form-control" placeholder="dd M, yyyy"';
                str += '                data-date-format="dd M, yyyy" data-date-container="#datepicker2"';
                str += '                data-provide="datepicker" data-date-autoclose="true" id="tanggal"';
                str += '                name="tanggal" required>';

                str += '            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>';
                str += '        </div>';
                str += '    </div><!-- input-group -->';
                str += '</div>';
                str += '<div class="mb-3 row">';
                str += '    <label for="supplier" class="col-md-2 col-form-label">Buyer</label>';
                str += '    <div class="col-md-10">';
                str += '        <input class="form-control" type="text" placeholder="Nama Buyer" id="supplier"';
                str += '            name="supplier">';
                str += '    </div>';
                str += '</div>';
                str += '<div class="mb-3 row">';
                str += '    <label for="alamat" class="col-md-2 col-form-label">Alamat</label>';
                str += '    <div class="col-md-10">';
                str += '        <input class="form-control" type="text" placeholder="Alamat" id="alamat"';
                str += '            name="alamat">';
                str += '    </div>';
                str += '</div>';
                str += '<div class="mb-3 row">';
                str += '    <label for="grade" class="col-md-2 col-form-label">Grade</label>';
                str += '    <div class="col-md-10">';
                str += '        <input class="form-control" type="text" placeholder="Grade" id="grade"';
                str += '            name="grade">';
                str += '    </div>';
                str += '</div>';
                str += '<div class="mb-3 row">';
                str += '    <label for="jenis_kayu" class="col-md-2 col-form-label">Jenis Kayu</label>';
                str += '    <div class="col-md-10">';
                str += '        <input class="form-control" type="text" placeholder="Jenis Kayu" id="jenis_kayu"';
                str += '            name="jenis_kayu">';
                str += '    </div>';
                str += '</div>';
                str += '<div class="mb-3 row">';
                str += '    <label for="vol" class="col-md-2 col-form-label">Size</label>';
                str += '    <div class="col-md-10">';
                str += '        <div class="input-group">';
                str += '            <input class="form-control" type="text" placeholder="000" id="ukuran1"';
                str += '                onchange="hitungVol()" required name="ukuran1">';
                str += '            <span class="input-group-text">*</span>';
                str += '            <input class="form-control" type="text" placeholder="000" id="ukuran2"';
                str += '                onchange="hitungVol()" required name="ukuran2">';
                str += '            <span class="input-group-text">*</span>';
                str += '            <input class="form-control" type="text" placeholder="000" id="ukuran3"';
                str += '                onchange="hitungVol()" required name="ukuran3">';
                str += '        </div>';
                str += '    </div>';
                str += '</div>';
                str += '<div class="mb-3 row">';
                str += '    <label for="pcs" class="col-md-2 col-form-label">Pcs</label>';
                str += '    <div class="col-md-10">';
                str += '        <input class="form-control" type="text" placeholder="000" id="pcs"';
                str += '            oninput="hitungVol()" name="pcs">';
                str += '    </div>';
                str += '</div>';
                str += '<div class="mb-3 row">';
                str += '    <label for="vol" class="col-md-2 col-form-label">Vol/m3</label>';
                str += '    <div class="col-md-10">';
                str += '        <input class="form-control" type="text" placeholder="0.000" id="vol"';
                str += '            name="vol">';
                str += '    </div>';
                str += '</div>';
                str += '<div class="mb-3 row">';
                str += '    <label for="crate" class="col-md-2 col-form-label">Crate</label>';
                str += '    <div class="col-md-10">';
                str += '        <input class="form-control" type="text" placeholder="0" id="crate"';
                str += '            name="crate">';
                str += '    </div>';
                str += '</div>';
                str += '<div class="mb-3 row">';
                str += '    <label for="harga" class="col-md-2 col-form-label">Harga/m3</label>';
                str += '    <div class="col-md-10">';
                str +=
                    '        <input class="form-control" type="text" data-type="currency" placeholder="Rp 0,00" id="harga"';
                str += '            name="harga" oninput="hitungTotal()">';
                str += '    </div>';
                str += '</div>';
                str += '<div class="mb-3 row">';
                str += '    <label for="total_harga" class="col-md-2 col-form-label">Total Harga</label>';
                str += '    <div class="col-md-10">';
                str += '        <input class="form-control" type="text" placeholder="Rp 0,00" id="total_harga"';
                str += '            name="total_harga">';
                str += '    </div>';
                str += '</div>';

                document.getElementById('form').innerHTML = str
            } else if (kategori == 'stock') {
                str = '<div class="mb-3 row">';
                str += '    <label class="col-md-2 col-form-label">Tanggal</label>';
                str += '    <div class="col-md-10">';
                str += '        <div class="input-group" id="datepicker2">';
                str += '            <input type="text" class="form-control" placeholder="dd M, yyyy"';
                str += '                data-date-format="dd M, yyyy" data-date-container="#datepicker2"';
                str += '                data-provide="datepicker" data-date-autoclose="true" id="tanggal"';
                str += '                name="tanggal" required>';

                str += '            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>';
                str += '        </div>';
                str += '    </div><!-- input-group -->';
                str += '</div>';
                str += '<div class="mb-3 row">';
                str += '    <label for="supplier" class="col-md-2 col-form-label">Uraian</label>';
                str += '    <div class="col-md-10">';
                str += '        <input class="form-control" type="text" placeholder="Stock"';
                str += '            id="supplier" name="supplier">';
                str += '    </div>';
                str += '</div>';
                str += '<div class="mb-3 row">';
                str += '    <label for="vol" class="col-md-2 col-form-label">Total Volume</label>';
                str += '    <div class="col-md-10">';
                str += '        <input class="form-control" type="text" placeholder="0.1" id="vol"';
                str += '            name="vol" oninput="Total()">';
                str += '    </div>';
                str += '</div>';
                str += '<div class="mb-3 row">';
                str += '    <label for="harga" class="col-md-2 col-form-label">Total Harga</label>';
                str += '    <div class="col-md-10">';
                str +=
                    '        <input class="form-control" type="text" data-type="currency" placeholder="Rp 0,00" id="harga"';
                str += '            name="harga">';
                str += '    </div>';
                str += '</div>';
                document.getElementById('form').innerHTML = str

            } else {
                str = '<div class="mb-3 row">';
                str += '    <label class="col-md-2 col-form-label">Tanggal</label>';
                str += '    <div class="col-md-10">';
                str += '        <div class="input-group" id="datepicker2">';
                str += '            <input type="text" class="form-control" placeholder="dd M, yyyy"';
                str += '                data-date-format="dd M, yyyy" data-date-container="#datepicker2"';
                str += '                data-provide="datepicker" data-date-autoclose="true" id="tanggal"';
                str += '                name="tanggal" required>';

                str += '            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>';
                str += '        </div>';
                str += '    </div><!-- input-group -->';
                str += '</div>';
                str += '<div class="mb-3 row">';
                str += '    <label for="supplier" class="col-md-2 col-form-label">Supplier</label>';
                str += '    <div class="col-md-10">';
                str += '        <input class="form-control" type="text" placeholder="Nama Supplier"';
                str += '            id="supplier" name="supplier">';
                str += '    </div>';
                str += '</div>';
                str += '<div class="mb-3 row">';
                str += '    <label for="vol" class="col-md-2 col-form-label">Total Volume</label>';
                str += '    <div class="col-md-10">';
                str += '        <input class="form-control" type="text" placeholder="0.1" id="vol"';
                str += '            name="vol">';
                str += '    </div>';
                str += '</div>';
                str += '<div class="mb-3 row">';
                str += '    <label for="harga" class="col-md-2 col-form-label">Total Harga</label>';
                str += '    <div class="col-md-10">';
                str += '    <input class="form-control" type="text" data-type="currency" placeholder="Rp 0,00" id="harga"';
                str += '            name="harga">';
                str += '    </div>';
                str += '</div>';
                document.getElementById('form').innerHTML = str
            }
            $("input[data-type='currency']").on({
                keyup: function() {
                    formatCurrency($(this));
                },
                blur: function() {
                    formatCurrency($(this), "blur");
                }
            });
        }

        function hitungVol() {
            var ukuran1 = document.getElementById('ukuran1').value;
            var ukuran2 = document.getElementById('ukuran2').value;
            var ukuran3 = document.getElementById('ukuran3').value;
            var pcs = document.getElementById('pcs').value;
            // console.log(pcs);
            if (ukuran1 != '' && ukuran2 != '' && ukuran3 != '' && pcs != '') {
                var vol = ukuran1 * ukuran2 * ukuran3 * pcs / 100000000
                document.getElementById('vol').value = vol.toFixed(4);
            }
        }

        function hitungTotal() {
            var vol = document.getElementById('vol').value;
            var harga = document.getElementById('harga').value;
            var number = Number(harga.replace(/[^0-9,-]+/g, ""));
            var total = vol * number;
            var rupiah = new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
            }).format(total);
            console.log(total);
            document.getElementById('total_harga').value = rupiah
        }

        function Total() {
            var vol = document.getElementById('vol').value;
            var total = vol * 1450000;
            var rupiah = new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
            }).format(total);
            document.getElementById('harga').value = rupiah
        }

        $("input[data-type='currency']").on({
            keyup: function() {
                formatCurrency($(this));
            },
            blur: function() {
                formatCurrency($(this), "blur");
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
@endsection
