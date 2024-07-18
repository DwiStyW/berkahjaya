<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ url('index') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('/assets/images/logo/logokotak.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('/assets/images/logo/logohitam.png') }}" alt="" height="30">
                    </span>
                </a>

                <a href="{{ url('index') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('/assets/images/logo/logokotak.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('/assets/images/logo/logoputih.png') }}" alt="" height="30">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item waves-effect waves-light"
                data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <!-- App Search-->
            {{-- <form class="app-search d-none d-lg-block">
                <div class="position-relative">
                    <input type="text" class="form-control" placeholder="@lang('translation.Search')...">
                    <span class="uil-search"></span>
                </div>
            </form> --}}
        </div>

        <div class="d-flex">

            {{-- <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="uil-search"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0"
                    aria-labelledby="page-header-search-dropdown">

                    <form class="p-3">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="@lang('translation.Search')..."
                                    aria-label="Recipient's username">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i
                                            class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div> --}}

            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="fullscreen">
                    <i class="uil-minus-path"></i>
                </button>
            </div>

            {{-- <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect"
                    id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <div id="lonceng">
                        <i class="uil-bell"></i>
                    </div>
                    @if (count($notif) > 0)
                        <span id="badge-notif" class="badge bg-danger rounded-pill">{{ count($notif) }}</span>
                    @else
                        <div id="badge-notif"></div>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="m-0 font-size-16"> @lang('translation.Notifications') </h5>
                            </div>
                            <div id="readall" class="col-auto"
                                @if (count($notif) > 0) style="display: none;" @endif>
                                <a href="#!" class="small"> @lang('translation.Mark_read')</a>
                            </div>

                        </div>
                    </div>
                    <div data-simplebar style="max-height: 230px;">
                        <div id="list-notif">

                        </div>
                        <div id="tidakadanotif" @if (count($notif) == 0) style="display: block;" @endif>
                            <h6 class="text-center text-muted p-5">
                                Tidak ada notifikasi yang tersedia
                            </h6>
                        </div>
                    </div>
                    <div class="p-2 border-top d-grid">
                        <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                            <i class="uil-arrow-circle-right me-1"></i> @lang('translation.View_More')..
                        </a>
                    </div>
                </div>
            </div> --}}

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user"
                        src="{{ URL::asset('/assets/images/users/avatar-4.jpg') }}" alt="Header Avatar">
                    <span
                        class="d-none d-xl-inline-block ms-1 fw-medium font-size-15">{{ Str::ucfirst(Auth::user()->name) }}</span>
                    <i class="uil-angle-down d-none d-xl-inline-block font-size-15"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    {{-- <a class="dropdown-item" href="/profile"><i
                            class="uil uil-user-circle font-size-18 align-middle text-muted me-1"></i> <span
                            class="align-middle">@lang('translation.View_Profile')</span></a>
                    <a class="dropdown-item" href="#"><i
                            class="uil uil-wallet font-size-18 align-middle me-1 text-muted"></i> <span
                            class="align-middle">@lang('translation.My_Wallet')</span></a>
                    <a class="dropdown-item d-block" href="#"><i
                            class="uil uil-cog font-size-18 align-middle me-1 text-muted"></i> <span
                            class="align-middle">@lang('translation.Settings')</span> <span
                            class="badge bg-soft-success rounded-pill mt-1 ms-2">03</span></a>
                    <a class="dropdown-item" href="#"><i
                            class="uil uil-lock-alt font-size-18 align-middle me-1 text-muted"></i> <span
                            class="align-middle">@lang('translation.Lock_screen')</span></a> --}}
                    <a class="dropdown-item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                            class="uil uil-sign-out-alt font-size-18 align-middle me-1 text-muted"></i> <span
                            class="align-middle">@lang('translation.Sign_out')</span></a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>

            <div id="theme"></div>
        </div>
    </div>
    @include('menu.menu-top')
</header>
<style>
    .rotateable {
        transition: all 100ms;
    }
</style>
@section('script-notif')
    <script>
        Notification.requestPermission((result) => {
            console.log(result);
        });
    </script>
    <script type="module">
        const notif = @json($notif),
            jumlah_notif = notif.length;
        const auth_id = {{ Auth::user()->id }};
        //
        // createListNotif(notif);

        Echo.private(`notification.${auth_id}`)
            .listen('NotificationSent', (e) => {
                notif.push({
                    id: e.notification.id,
                    sender_id: e.notification.sender_id,
                    receiver_id: e.notification.receiver_id,
                    type: e.notification.type,
                    data: e.notification.data,
                    read_at: e.notification.read_at,
                    created_at: e.notification.created_at,
                    updated_at: e.notification.updated_at,
                })
                if (jumlah_notif == 0) {
                    var str = '<span id="badge-notif" class="badge bg-danger rounded-pill">' + notif.length + '</span>';
                    document.getElementById('badge-notif').innerHTML = str;
                    notifada();
                } else {
                    document.getElementById('badge-notif').innerHTML = notif.length;
                }

                const data = JSON.parse(e.notification.data);
                var type = e.notification.type;
                if (type == 'new') {
                    var bg_style = 'bg-info';
                    var text = 'WO Baru';
                } else if (type == 'aprove') {
                    var bg_style = 'bg-primary';
                    var text = 'WO Aproved';
                } else if (type == 'proses') {
                    var bg_style = 'bg-warning';
                    var text = 'WO Dalam Proses';
                } else if (type == 'closed') {
                    var bg_style = 'bg-success';
                    var text = 'WO Close';
                } else if (type == 'reject') {
                    var bg_style = 'bg-warning';
                    var text = 'WO Rejected';
                }

                let title = text + ' ' + data.no_tiket || "Title";
                let body = data.text || "Text";
                let icon = "http://127.0.0.1:8000/assets/images/logo/iconkotak.png";
                let image = "";

                createNotification({
                    title,
                    body,
                    icon,
                    image,
                    silent: true,
                    dir: "auto",
                })

                var mp3 = "http://127.0.0.1:8000/assets/mp3/notif_wo2.mp3";
                playSound(mp3);
                createListNotif(notif);
                var el = document.getElementById('lonceng');
                var readall = document.getElementById('readall');
                rotateElement(el);
                readall.style.display = 'block';
            });

        Echo.channel(`ticket`)
            .listen('TicketSent', (e) => {
                if (document.getElementById('no_tiket') != undefined) {
                    document.getElementById('no_tiket').value = e.ticket;
                }
                // console.log(e);
            });

        var nullnotif = document.getElementById('tidakadanotif');

        function notifnull() {
            nullnotif.style.display = 'block'
        }

        function notifada() {
            nullnotif.style.display = 'none'
        }

        //reload function every 1 second
        window.onload = function() {
            createListNotif(notif);
            setInterval(function() {
                createListNotif(notif);
            }, 10000);

            var el = document.getElementById('lonceng');
            var readall = document.getElementById('readall');
            console.log(nullnotif);
            if (jumlah_notif != 0) {
                rotateElement(el);
                setInterval(function() {
                    rotateElement(el);
                }, 20000);
                readall.style.display = 'block';
                nullnotif.style.display = 'none';
            } else {
                readall.style.display = 'none';
                notifnull();
            }
        }
        // check notification permission
        const notificationPermission = new Promise((response) => {
            if ("Notification" in window) {
                if (Notification.permission === "granted") {
                    response({
                        status: "success",
                        text: "user accepted the notifications",
                    })
                } else {
                    Notification.requestPermission()
                        .then(permission => {
                            if (permission === "granted") {
                                response({
                                    status: "success",
                                    text: "user accepted the notifications",
                                })
                            } else {
                                response({
                                    status: "error",
                                    text: "User did not accept notifications",
                                })
                            }
                        })
                }
            } else {
                response({
                    status: "error",
                    text: "This Browser does not support desktop notification !",
                })
            }
        })

        let userPermission;
        async function checkNotificationPermission() {
            const permission = await notificationPermission;
            if (permission.status === "success") {
                userPermission = true;
            } else {
                console.warn("User did not accept notifications !");
                userPermission = false;
            }
        }
        checkNotificationPermission();

        // show notification
        let notify;

        function createNotification(data) {
            if (notify) {
                notify.close();
            }
            notify = new Notification(data.title, {
                ...data,
            });
            console.log(notify);
            // window.location.reload(false);
        }

        function playSound(url) {
            const audio = new Audio(url);
            audio.play();
        }
    </script>
    <script>
        // theme
        function themelight() {
            var str = '';
            str += '<button type="button" onclick="themedark()" class="btn header-item noti-icon waves-effect">';
            str +=
                '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-moon-fill" viewBox="0 0 16 16">';
            str +=
                '<path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>';
            str += '</svg>';
            str += '</button>';

            document.getElementsByTagName('html')[0].removeAttribute("dir");
            document.getElementById('bootstrap-style').href = '/assets/css/bootstrap.min.css';
            document.getElementById('app-style').href = '/assets/css/app.min.css';


            document.getElementById('theme').innerHTML = str;
            sessionStorage.setItem("is_visited", "light-mode-switch");

        }

        function themedark() {
            var str = '';
            str += '<button type="button" onclick="themelight()" class="btn header-item noti-icon waves-effect">';
            str +=
                '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-brightness-low-fill" viewBox="0 0 16 16">';
            str +=
                '<path d="M12 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM8.5 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 11a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm5-5a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm-11 0a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm9.743-4.036a.5.5 0 1 1-.707-.707.5.5 0 0 1 .707.707zm-7.779 7.779a.5.5 0 1 1-.707-.707.5.5 0 0 1 .707.707zm7.072 0a.5.5 0 1 1 .707-.707.5.5 0 0 1-.707.707zM3.757 4.464a.5.5 0 1 1 .707-.707.5.5 0 0 1-.707.707z"/>';
            str += '</svg>';
            str += '</button>';

            document.getElementsByTagName('html')[0].removeAttribute("dir");
            document.getElementById('bootstrap-style').href = '/assets/css/bootstrap-dark.min.css';
            document.getElementById('app-style').href = '/assets/css/app-dark.min.css';

            document.getElementById('theme').innerHTML = str;
            sessionStorage.setItem("is_visited", "dark-mode-switch");
        }
    </script>
    <script>
        // theme ready
        if (sessionStorage.getItem("is_visited") == 'dark-mode-switch') {
            themedark()
        } else {
            themelight()
        }
    </script>
    <script>
        // create list
        function createListNotif(data) {
            // console.log(data);
            data.sort((a, b) => b.id - a.id);
            var html = '';
            for (let i = 0; i < data.length; i++) {
                var type = data[i].type;
                if (type == 'new') {
                    var bg_style = 'bg-info';
                    var title = 'WO Baru';
                } else if (type == 'aprove') {
                    var bg_style = 'bg-primary';
                    var title = 'WO Aproved';
                } else if (type == 'proses') {
                    var bg_style = 'bg-warning';
                    var title = 'WO Dalam Proses';
                } else if (type == 'closed') {
                    var bg_style = 'bg-success';
                    var title = 'WO Close';
                } else if (type == 'reject') {
                    var bg_style = 'bg-warning';
                    var title = 'WO Rejected';
                }

                var pesan = JSON.parse(data[i].data);
                var created = data[i].created_at;
                var date = new Date(created);
                var momen = moment(date).fromNow();

                html += '<a href="/detail-ticket/' + data[i].id + '/' + pesan.no_tiket +
                    '" class="text-reset notification-item">';
                html += '    <div class="d-flex align-items-start">';
                html += '        <div class="avatar-xs me-3">';
                html += '            <span class="avatar-title rounded-circle font-size-16 ' + bg_style + '">';
                html +=
                    '                <img src="{{ URL::asset('/assets/images/logo/logokotak.png') }}" alt="" height="16">';
                html += '            </span>';
                html += '        </div>';
                html += '        <div class="flex-1">';
                html += '            <h6 class="mt-0 mb-1">' + title + ' ' + pesan.no_tiket + '</h6>';
                html += '            <div class="font-size-12 text-muted">';
                html += '                <p class="mb-1">' + truncate(pesan.text) + '</p>';
                html += '                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> ' + momen;
                html += '                </p>';
                html += '            </div>';
                html += '        </div>';
                html += '    </div>';
                html += '</a>';
            }

            document.getElementById('list-notif').innerHTML = html;

            function truncate(input) {
                if (input.length > 60) {
                    return input.substring(0, 60) + '...';
                }
                return input;
            };
        }
    </script>
    <script>
        // gerak lonceng
        const wiggletime = 100;

        function rotateElement(el) {
            el.classList.add('rotateable');
            el.style.transform = 'rotate(20deg)';

            setTimeout(function() {
                el.style.transform = 'rotate(-20deg)';
                setTimeout(function() {
                    el.style.transform = 'rotate(10deg)';
                    setTimeout(function() {
                        el.style.transform = 'rotate(-10deg)';
                        setTimeout(function() {
                            el.style.transform = 'rotate(0deg)';
                        }, wiggletime);
                    }, wiggletime);
                }, wiggletime);
            }, wiggletime);
            // console.log(el);
            return true;
        }
    </script>
@endsection
