@extends('layout.master')
@section('title')
    Tambah Produksi
@endsection
@section('css')
    <!-- plugin css -->
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
            Tambah Produksi
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="/produksi-temporary" method="POST">
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
                                        data-provide="datepicker" data-date-autoclose="true" id="tanggal" name="tanggal"
                                        onchange="setTanggal()" required>

                                    <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                </div>
                            </div><!-- input-group -->
                        </div>

                        <div class="mb-3 row">
                            <label for="supplier" class="col-md-2 col-form-label">Supplier</label>
                            <div class="col-md-10">
                                <select class="form-control select2 select2-multiple" multiple="multiple"
                                    id="supplier_field" data-placeholder="Pilih Supplier" onchange="find_log()">
                                    @foreach ($supplier as $sup)
                                        <option value="{{ $sup['id'] }}">{{ $sup['id'] }} {{ $sup['supplier'] }}
                                            @if ($sup['sengon'] != 0 && $sup['keras'] != 0)
                                                {{ $sup['sengon'] + $sup['keras'] }}
                                            @else
                                                {{ $sup['uraian'] }}
                                            @endif
                                            @if ($sup['status'] == null || $sup['status'] == 'proses')
                                                @if ($sup['sengon'] != 0 && $sup['stat_sengon'] != 'L')
                                                    &emsp; Sengon->{{ $sup['sengon'] }}
                                                @endif
                                                @if ($sup['keras'] != 0 && $sup['stat_keras'] != 'L')
                                                    &emsp; keras->{{ $sup['keras'] }}
                                                @endif
                                                {{-- @elseif (count($temporary) != 0)
                                                @foreach ($temporary as $t)
                                                @endforeach
                                                @if ($sup['status'] == 'proses2')
                                                    &emsp; {{ $t->jenis_kayu }}->{{ $t->log_opc }}
                                                @endif --}}
                                            @else
                                                @if ($sup['sengon'] != 0 && $sup['stat_sengon'] != 'L')
                                                    &emsp; Sengon->{{ $sup['sengon'] }}
                                                @endif
                                                @if ($sup['keras'] != 0 && $sup['stat_keras'] != 'L')
                                                    &emsp; keras->{{ $sup['keras'] }}
                                                @endif
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <select class="form-control select2 select2-multiple" multiple="multiple" name="supplier[]"
                                    id="supplier" data-placeholder="Pilih Supplier" onchange="find_log()">
                                    @foreach ($supplier as $sup)
                                        <option value="{{ $sup['id'] }}">{{ $sup['id'] }} {{ $sup['supplier'] }}
                                            @if ($sup['sengon'] != 0 && $sup['keras'] != 0)
                                                {{ $sup['sengon'] + $sup['keras'] }}
                                            @else
                                                {{ $sup['uraian'] }}
                                            @endif
                                            @if ($sup['status'] == null || $sup['status'] == 'proses')
                                                @if ($sup['sengon'] != 0 && $sup['stat_sengon'] != 'L')
                                                    &emsp; Sengon->{{ $sup['sengon'] }}
                                                @endif
                                                @if ($sup['keras'] != 0 && $sup['stat_keras'] != 'L')
                                                    &emsp; keras->{{ $sup['keras'] }}
                                                @endif
                                                {{-- @elseif (count($temporary) != 0)
                                                @foreach ($temporary as $t)
                                                @endforeach
                                                @if ($sup['status'] == 'proses2')
                                                    &emsp; {{ $t->jenis_kayu }}->{{ $t->log_opc }}
                                                @endif --}}
                                            @else
                                                @if ($sup['sengon'] != 0 && $sup['stat_sengon'] != 'L')
                                                    &emsp; Sengon->{{ $sup['sengon'] }}
                                                @endif
                                                @if ($sup['keras'] != 0 && $sup['stat_keras'] != 'L')
                                                    &emsp; keras->{{ $sup['keras'] }}
                                                @endif
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="d-flex col-12">
                                    <div class="col-6">
                                        <div id="logm3"></div>
                                    </div>
                                    <div class="col-6">
                                        <div id="detailm3"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="produk" class="col-md-2 col-form-label">Produk</label>
                            <div class="col-md-10">
                                <select class="form-control select2" name="produk" id="produk" onchange="ukuranproduk()"
                                    required>
                                    <option value="null" disabled selected>Pilih Produk</option>
                                    @foreach ($masterproduk as $produk)
                                        <option value="{{ $produk->id }}">{{ $produk->hasil_produksi }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="values"></div>
                        <div id="satuan_ukuran">
                            <div class="mb-3 row">
                                <label for="ukuran" class="col-md-2 col-form-label">Ukuran</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="number" placeholder="000" id="ukuran" disabled
                                        required name="ukuran">
                                </div>
                            </div>
                        </div>
                        <div id="div_pcs" class="mb-3 row">
                            <label for="pcs" class="col-md-2 col-form-label">Pcs</label>
                            <div class="col-md-10">
                                <input class="form-control" type="number" placeholder="000" id="pcs" name="pcs"
                                    required>
                            </div>
                        </div>

                        <div class="float-end">
                            <a href="javascript:history.back()" class="btn btn-md btn-secondary">back</a>
                            <button type="submit" class="btn btn-md btn-primary">Tambakan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Supplier</th>
                                <th>Produk</th>
                                <th>Pcs</th>
                                <th>Ukuran</th>
                                @if (Auth::user()->role == '1')
                                    <th>Harga</th>
                                    <th>Total</th>
                                @endif
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($temporary as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->supplier }}</td>
                                    <td>{{ $item->hasil_produksi }}</td>
                                    <td>{{ $item->pcs }}</td>
                                    <td>{{ $item->ukuran }}
                                        @if ($item->satuan == 'm' || $item->satuan == 'm3')
                                            ({{ $item->satuan }})
                                        @endif
                                    </td>
                                    @if (Auth::user()->role == '1')
                                        <td>{{ $item->harga }}</td>
                                        <td>{{ $item->total }}</td>
                                    @endif
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
                            @endforeach
                        </tbody>
                    </table>
                    <div class="float-end">
                        <a href="/produksi-store" onclick="resetVal()" id="simpan_produksi"
                            class="btn btn-md btn-primary">Simpan</a>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
    @include('produksi.delete-produksi-temporary')
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
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var data_produk = @json($masterproduk);

        function ukuranproduk() {
            var produk = document.getElementById('produk').value;
            var filt_produk = data_produk.filter(a => a.id == produk)
            // console.log(filt_produk);


            if (filt_produk[0].satuan == 'm') {
                str = '<div class="mb-3 row">';
                str += '    <label for="ukuran" class="col-md-2 col-form-label">Ukuran</label>';
                str += '    <div class="col-md-10">';
                str += '    <div class="input-group">';
                str +=
                    '    <input class="form-control" type="number" placeholder="000" id="ukuran" required name="ukuran">';
                str +=
                    '    <span class="input-group-text">Meter (' + filt_produk[0].satuan + ')</span>';
                str += '    </div>';
                str += '    </div>';
                str += '</div>';

                document.getElementById('div_pcs').innerHTML = '';
            } else if (filt_produk[0].satuan == 'm3') {
                str = '<div class="mb-3 row">';
                str += '    <label for="ukuran" class="col-md-2 col-form-label">Ukuran</label>';
                str += '    <div class="col-md-10">';
                str += '    <div class="input-group">';
                str +=
                    '    <input class="form-control" type="text" placeholder="000" id="ukuran1" required name="ukuran1">';
                str +=
                    '    <span class="input-group-text">*</span>';
                str +=
                    '    <input class="form-control" type="text" placeholder="000" id="ukuran2" required name="ukuran2">';
                str +=
                    '    <span class="input-group-text">*</span>';
                str +=
                    '    <input class="form-control" type="text" placeholder="000" id="ukuran3" required name="ukuran3">';
                str +=
                    '    <span class="input-group-text">Meter (' + filt_produk[0].satuan + ')</span>';
                str += '    </div>';
                str += '    </div>';
                str += '</div>';
            } else {
                str = '';
            }

            document.getElementById('satuan_ukuran').innerHTML = str;
        }

        function setTanggal() {
            localStorage.setItem("tanggal", document.getElementById('tanggal').value);
        }

        var log_opc = @json($supplier);
        var temp = @json($temporary);
        // console.log(log_opc);

        const isUniqueArr = arr => {
            const tmp = new Set(arr);
            if (tmp.size > 1) {
                return false;
            }
            return arr[0];
        }

        function find_log() {
            var select = document.getElementById('supplier');
            var selected = [...select.selectedOptions]
                .map(option => option.value);

            localStorage.setItem("supplier", selected);
            // console.log(selected);
            let logfind = [];
            let hargafind = [];
            let statusfind = [];
            let kodeMasuk = [];
            for (i = 0; i < selected.length; i++) {
                var log = log_opc.find(x => x.id == selected[i]);
                console.log(log);
                // statusfind.push(log.status);
                if (log.status == null || log.status == 'proses1') {
                    if ((log.keras != 0 && log.sengon != 0) && (log.stat_keras == null && log.stat_sengon == null)) {
                        logfind.push(Number(log.sengon) + Number(log.keras));
                        hargafind.push(Number(log.harga_sengon) + Number(log.harga_keras));

                    } else if ((log.stat_keras == 'L' && log.stat_sengon == null) || (log.stat_keras == 'P' && log
                            .stat_sengon == null)) {
                        logfind.push(Number(log.keras));
                        hargafind.push(Number(log.harga_keras));
                        // console.log('sini');
                    } else if ((log.stat_sengon == 'L' && log.stat_keras == null) || (log.stat_sengon == 'P' && log
                            .stat_keras == null)) {
                        logfind.push(Number(log.sengon));
                        hargafind.push(Number(log.harga_sengon));
                    } else {
                        logfind.push(Number(log.uraian));
                        hargafind.push(Number(log.harga));
                    }
                } else if (temp.length != 0) {
                    if (log.status == 'proses2') {
                        logfind = [Number(temp[0].log_opc)];
                        hargafind = [Number(temp[0].harga_log)];
                    }

                } else {
                    if (log.stat_keras == 'L' && log.stat_sengon == null) {
                        logfind.push(Number(log.sengon));
                        hargafind.push(Number(log.harga_sengon));
                    } else if (log.stat_sengon == 'L' && log.stat_keras == null) {
                        logfind.push(Number(log.keras));
                        hargafind.push(Number(log.harga_keras));
                    } else {
                        logfind.push(Number(log.uraian));
                        hargafind.push(Number(log.harga));
                    }
                }

                if (temp.length != 0) {
                    // if (log.status == 'proses2') {
                    logfind = [Number(temp[0].log_opc)];
                    hargafind = [Number(temp[0].harga_log)];
                    // }

                }
                kodeMasuk.push(log.kode);
            }
            console.log(temp);
            let sum_harga = 0;
            let sum = 0;
            logfind.forEach((el) => sum += el);
            hargafind.forEach((el) => sum_harga += el);
            console.log(sum_harga);
            if (sum > 0) {
                str = '<div class=" mt-3 col-12">';
                str += '<span class="input-group-text">' + sum.toFixed(4) + '</span>';
                str += '<input hidden id="log_kubik" name="log_kubik" value="' + sum.toFixed(4) + '">';
                str += '<input hidden id="log_harga" name="log_harga" value="' + sum_harga + '">';
                str += '</div>';
            } else {
                str = '';
            }

            document.getElementById('logm3').innerHTML = str;

            $.ajax({
                type: 'POST',
                url: "{{ route('getDetailpembelian') }}",
                data: {
                    kode: kodeMasuk,
                    status: log.status,
                },
                success: function(data) {
                    console.log(data);
                    // console.log(log.status);
                    if (log.status == 'null') {
                        let html = '<div class=" mt-3 form-control">';
                        for (n = 0; n < data.length; n++) {
                            html += '<div class="d-flex">';
                            if (data[n].vol != 0) {
                                html += '   <div class="col-6">';
                                html += '       <h6>' + data[n].jenis_kayu + '</h6>';
                                html += '   </div>';
                                html += '   <div class="col-6">';
                                html += '   <div class="col-12 d-flex">';
                                html += '       <h6>' + data[n].vol.toFixed(4) + '</h6>';
                                html += '   <input name="check' + data[n].jenis_kayu + '" id="check' + data[n]
                                    .jenis_kayu +
                                    '" type="checkbox" class="ms-1 form-check-input" checked onclick="cekJenisKayu()">';
                                html += '   </div>';
                                html += '   </div>';
                            }
                            html += '</div>';

                        }
                        html += '</div>';
                        document.getElementById('detailm3').innerHTML = html;

                    } else if (log.status == 'proses1') {
                        let html = '<div class="mt-3 form-control bg-light">';
                        // console.log(data);
                        for (n = 0; n < data.length; n++) {
                            html += '<div class="d-flex">';
                            if (data[n].vol != 0) {

                                if (log.stat_keras == 'P' && data[n].jenis_kayu == 'Keras') {
                                    html += '   <div class="col-6">';
                                    html += '       <h6>' + data[n].jenis_kayu + '</h6>';
                                    html += '   </div>';
                                    html += '   <div class="col-6">';
                                    html += '   <div class="col-12 d-flex">';
                                    html += '       <h6>' + data[n].vol.toFixed(4) + '</h6>';
                                    html += '   <input name="check' + data[n].jenis_kayu + '" id="check' + data[
                                            n]
                                        .jenis_kayu +
                                        '" type="checkbox" class="ms-1 form-check-input" checked onclick="cekJenisKayu()">';
                                    html += '   </div>';
                                    html += '   </div>';

                                } else if (log.stat_sengon == 'P' && data[n].jenis_kayu == 'Sengon') {
                                    html += '   <div class="col-6">';
                                    html += '       <h6>' + data[n].jenis_kayu + '</h6>';
                                    html += '   </div>';
                                    html += '   <div class="col-6">';
                                    html += '   <div class="col-12 d-flex">';
                                    html += '       <h6>' + data[n].vol.toFixed(4) + '</h6>';
                                    html += '   <input name="check' + data[n].jenis_kayu + '" id="check' + data[
                                            n]
                                        .jenis_kayu +
                                        '" type="checkbox" class="ms-1 form-check-input" checked onclick="cekJenisKayu()">';
                                    html += '   </div>';
                                    html += '   </div>';

                                } else {
                                    html += '   <input name="check' + data[n].jenis_kayu +
                                        '" id="check' + data[
                                            n]
                                        .jenis_kayu +
                                        '" type="checkbox" hidden class="ms-1 form-check-input" onclick="cekJenisKayu()">';
                                }


                            }
                            html += '</div>';

                        }
                        html += '</div>';
                        document.getElementById('detailm3').innerHTML = html;
                    } else if (temp.length != 0) {
                        const jenisKayu = temp[0].jenis_kayu;
                        const modStr = jenisKayu[0].toUpperCase() + jenisKayu.slice(1);
                        const logm3 = Number(temp[0].log_opc);


                        if (log.status == 'proses2') {
                            let html = '<div class=" mt-3 form-control bg-light">';
                            html += '<div class="d-flex">';
                            html += '   <div class="col-6">';
                            html += '       <h6>' + modStr + '</h6>';
                            html += '   </div>';
                            html += '   <div class="col-6">';
                            html += '   <div class="col-12 d-flex">';
                            html += '       <h6>' + logm3.toFixed(4) + '</h6>';
                            html += '   <input name="check' + modStr + '" id="check' + modStr +
                                '" type="checkbox" class="ms-1 form-check-input" checked onclick="cekJenisKayu()">';
                            html += '   </div>';
                            html += '   </div>';
                            html += '</div>';
                            document.getElementById('detailm3').innerHTML = html;
                        }
                    } else {
                        let html = '<div class=" mt-3 form-control bg-light">';
                        for (n = 0; n < data.length; n++) {
                            // console.log(log);
                            html += '<div class="d-flex">';
                            if (data[n].vol != 0) {


                                html += '   <div class="col-6">';
                                html += '       <h6>' + data[n].jenis_kayu + '</h6>';
                                html += '   </div>';
                                html += '   <div class="col-6">';
                                html += '   <div class="col-12 d-flex">';
                                html += '       <h6>' + data[n].vol.toFixed(4) + '</h6>';
                                html += '   <input name="check' + data[n].jenis_kayu + '" id="check' + data[
                                        n]
                                    .jenis_kayu +
                                    '" type="checkbox" class="ms-1 form-check-input" checked onclick="cekJenisKayu()">';
                                html += '   </div>';
                                html += '   </div>';

                            }
                            html += '</div>';

                        }
                        html += '</div>';
                        document.getElementById('detailm3').innerHTML = html;
                    }


                },
                error: function(data) {
                    document.getElementById('detailm3').innerHTML = '';
                }
            })

        }

        function cekJenisKayu() {
            if ((document.getElementById('checkSengon') != null && document.getElementById('checkKeras') != null) && (
                    document.getElementById('checkSengon').hidden != true && document.getElementById('checkKeras').hidden !=
                    true)) {
                if (document.getElementById('checkSengon').checked == true && document.getElementById('checkKeras')
                    .checked == true) {
                    find_log()
                } else if (document.getElementById('checkSengon').checked == true) {
                    console.log('ini sengon');
                    var select = document.getElementById('supplier');
                    var selected = [...select.selectedOptions]
                        .map(option => option.value);

                    let logfind = [];
                    let hargafind = [];
                    for (i = 0; i < selected.length; i++) {
                        var log = log_opc.find(x => x.id == selected[i]);
                        logfind.push(Number(log.sengon));
                        hargafind.push(Number(log.harga_sengon));
                    }
                    let sum_harga = 0;
                    let sum = 0;
                    logfind.forEach((el) => sum += el);
                    hargafind.forEach((el) => sum_harga += el);
                    if (sum > 0) {
                        str = '<div class=" mt-3 col-12">';
                        str += '<span class="input-group-text">' + sum.toFixed(4) + '</span>';
                        str += '<input hidden id="log_kubik" name="log_kubik" value="' + sum.toFixed(4) + '">';
                        str += '<input hidden id="log_harga" name="log_harga" value="' + sum_harga + '">';
                        str += '</div>';
                    } else {
                        str = '';
                    }
                    document.getElementById('logm3').innerHTML = str;
                } else if (document.getElementById('checkKeras').checked == true) {
                    console.log('ini keras');
                    var select = document.getElementById('supplier');
                    var selected = [...select.selectedOptions]
                        .map(option => option.value);

                    let logfind = [];
                    let hargafind = [];
                    for (i = 0; i < selected.length; i++) {
                        var log = log_opc.find(x => x.id == selected[i]);
                        logfind.push(Number(log.keras));
                        hargafind.push(Number(log.harga_keras));
                    }
                    let sum_harga = 0;
                    let sum = 0;
                    logfind.forEach((el) => sum += el);
                    hargafind.forEach((el) => sum_harga += el);
                    if (sum > 0) {
                        str = '<div class=" mt-3 col-12">';
                        str += '<span class="input-group-text">' + sum.toFixed(4) + '</span>';
                        str += '<input hidden id="log_kubik" name="log_kubik" value="' + sum.toFixed(4) + '">';
                        str += '<input hidden id="log_harga" name="log_harga" value="' + sum_harga + '">';
                        str += '</div>';
                    } else {
                        str = '';
                    }
                    document.getElementById('logm3').innerHTML = str;
                } else {
                    console.log('ini lain');
                    find_log()
                }
            } else {
                find_log()
            }
        }

        // console.log(localStorage.getItem("supplier"));
        if (@json($temporary).length > 0) {

            if (localStorage.getItem("tanggal") != '') {
                document.getElementById('tanggal').value = localStorage.getItem("tanggal");
                document.getElementById('tanggal_field').value = localStorage.getItem("tanggal");
                document.getElementById('tanggal').setAttribute("type", "hidden");
                document.getElementById('tanggal_field').setAttribute("type", "text");


            }
            if (localStorage.getItem("supplier") != '') {
                var valuesSelected = localStorage.getItem("supplier");
                const myArray = valuesSelected.split(",");
                // console.log(myArray);
                $('#supplier').val(myArray);
                $('#supplier').trigger('change');
                $('#supplier').next(".select2").hide();
                $('#supplier_field').val(myArray);
                $('#supplier_field').trigger('change');
                $('#supplier_field').next(".select2").show();
                document.getElementById('supplier_field').setAttribute("disabled", "disabled");
            }
            document.getElementById('simpan_produksi').classList.remove('disabled');
        } else if (@json($temporary).length == 0) {
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

        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                dom: '<"table-responsive w-100"<t>>',
                @if (Auth::user()->role == '1')
                    columnDefs: [{
                        render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                        targets: [6, 7]
                    }]
                @endif
            });
        });
    </script>
@endsection
