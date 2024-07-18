@extends('layout.master')
@section('title')
    Edit Hasil Produk
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
            Edit Hasil Produk
        @endslot
    @endcomponent
    <div class="row">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @foreach ($pembelian as $item)
                            <form action="/logopc-pembelian-update/{{ Crypt::encrypt($item->id) }}" method="POST">
                                @csrf
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Tanggal</label>
                                    <div class="col-md-10">
                                        <div class="input-group" id="datepicker2">
                                            <input type="text" class="form-control" placeholder="dd M, yyyy"
                                                data-date-format="dd M, yyyy" data-date-container='#datepicker2'
                                                data-provide="datepicker" data-date-autoclose="true" id="tanggal"
                                                name="tanggal" required disabled>

                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div><!-- input-group -->
                                </div>
                                <div class="mb-3 row">
                                    <label for="supplier" class="col-md-2 col-form-label">Supplier</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="nama supplier"
                                            id="supplier" name="supplier" value="{{ $item->supplier }}">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="vol" class="col-md-2 col-form-label">Total Volume</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="0.1" id="vol"
                                            name="vol" value="{{ $item->vol }}">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="harga" class="col-md-2 col-form-label">Total Harga</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" data-type="currency"
                                            placeholder="Rp 0,00" id="harga" name="harga"
                                            value="{{ $item->total_harga }}">
                                    </div>
                                </div>
                                <div class="float-end">
                                    <a href="javascript:history.back()" class="btn btn-md btn-secondary">back</a>
                                    <button type="submit" class="btn btn-md btn-primary">Save</button>
                                </div>
                            </form>
                        @endforeach
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
        var pembelian = @json($pembelian);
        console.log(pembelian);
        var tanggal = pembelian[0].tanggal,
            harga = pembelian[0].total_harga;
        let dateFormat1 = moment(tanggal).format('DD MMM, yyyy');
        console.log(dateFormat1);
        document.getElementById('tanggal').value = dateFormat1;

        var rupiah = new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR"
        }).format(harga).replace(",00", "");
        document.getElementById('harga').value = rupiah;

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
                    // right_side += "00";
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
                    // input_val += ",00";
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
