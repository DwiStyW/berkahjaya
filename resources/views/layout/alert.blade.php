{{-- alert message --}}
<style>
    .bg {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1040;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.1);
    }
</style>
@if (Session::get('success'))
    <div id="berhasil" onload="time()">
        <div class="bg">
            <div
                style="transition: opacity .15s linear;transform: translate(-50%, -50%);width:250px;position:fixed;z-index:100;top:350px;left:50%">
                <div class="alert alert-success alert-dismissible fade show mt-4 px-4 mb-0 text-center" role="alert">
                    <i class="uil uil-check-circle d-block display-4 mt-2 mb-3 text-success"></i>
                    <h5 class="text-success">Success</h5>
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">

                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        const waktu = setTimeout(time, 3000);

        function time() {
            document.getElementById('berhasil').innerHTML = '';
        }
    </script>
@endif
@if (Session::get('failed'))
    <div id="gagal" onload="time()">
        <div class="bg">
            <div
                style="transition: opacity .15s linear;transform: translate(-50%, -50%);width:250px;position:fixed;z-index:100;top:300px;left:50%">
                <div class="alert alert-danger alert-dismissible fade show mt-4 px-4 mb-0 text-center" role="alert">
                    <i class="uil uil-exclamation-octagon d-block display-4 mt-2 mb-3 text-danger"></i>
                    <h5 class="text-danger">Error</h5>
                    {{ Session::get('failed') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">

                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        const waktu = setTimeout(time, 3000);

        function time() {
            document.getElementById('gagal').innerHTML = '';
        }
    </script>
@endif
