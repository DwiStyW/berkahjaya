<div class="modal fade" id="hapusmodal" tabindex="-1" aria-labelledby="hapusmodal" aria-hidden="true">
    <div class="modal-dialog" style="width:400px;top:150px">
        <div class="modal-content">
            <div class="alert alert-border alert-border-warning alert-dismissible fade show mt-4 px-4 mb-0 text-center"
                role="alert">
                <i class="uil uil-exclamation-triangle d-block display-4 mt-2 mb-2 text-warning"></i>
                <h5 class="text-warning">Apa kamu yakin?</h5>
                <p>Data yang terhubung dengan data ini seperti riwayat akan terhapus!</p>
                <p>Anda tidak akan dapat mengembalikan data ini!</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
                <div class="d-flex justify-content-evenly">
                    <div id="buttonhapus"></div>
                    <div>
                        <button type="button" class="btn btn-md btn-danger" data-bs-dismiss="modal"> Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function hapus(id) {
        var id = id;
        var str = '<a href="/detailpembelian-delete/' + id + '" class="btn btn-md btn-success">Ya, hapus!</a>';
        document.getElementById('buttonhapus').innerHTML = str;
    }
</script>
