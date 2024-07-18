<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
    {{-- <img src="{{ URL::asset('/assets/images/bg-kayu.jpg') }}" alt=""
        style="z-index: -1000;position: absolute;height:height:100% !important;width:250px"> --}}
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ url('#') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('/assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <div class="d-flex mt-4">
                    <img src="{{ URL::asset('/assets/images/logo-sm.png') }}" alt="" height="20">
                    <h5 class="ps-2"><b>BERKAH JAYA</b></h5>
                </div>
            </span>
        </a>

        <a href="{{ url('#') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('/assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <div class="d-flex mt-4">
                    <img src="{{ URL::asset('/assets/images/logo-sm.png') }}" alt="" height="20">
                    <h5 class="ps-2"><b>BERKAH JAYA</b></h5>
                </div>
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn">
        <i class="fa fa-fw fa-bars"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li>
                    <a href="{{ url('/dashboard') }}">
                        <i class="uil-home-alt"></i>
                        {{-- <span class="badge rounded-pill bg-primary float-end">01</span> --}}
                        <span>Dashboard</span>
                    </a>
                </li>
                @if (Auth::user()->role == '1')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="uil-window-section"></i>
                            <span>Master</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ url('/mastermentah') }}">Master Barang Mentah</a></li>
                            <li><a href="{{ url('/hasilproduk') }}">Master Barang Jadi</a></li>
                        </ul>
                    </li>
                    {{-- <li>
                        <a href="{{ url('/mastermentah') }}">
                            <i class="uil-window-section"></i>
                            <span>Master Barang Mentah</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/hasilproduk') }}">
                            <i class="uil-window-section"></i>
                            <span>Master Barang Jadi</span>
                        </a>
                    </li> --}}
                @endif
                <li>
                    <a href="{{ url('/produksi') }}">
                        <i class="uil-files-landscapes-alt"></i>
                        <span>Produksi</span>
                    </a>
                </li>

                @if (Auth::user()->role == '1')
                    <li>
                        <a href="{{ url('/pembelian') }}">
                            <i class="uil-folder-download"></i>
                            <span>Barang Masuk</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/penjualan') }}">
                            <i class="uil-folder-upload"></i>
                            <span>Barang Keluar</span>
                        </a>
                    </li>
                @endif
                <li>
                    <a href="{{ url('/logopc') }}">
                        <i class="uil-files-landscapes"></i>
                        <span>Log OPC</span>
                    </a>
                </li>
                {{-- <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="uil-files-landscapes"></i>
                        <span>Log</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ url('/logopc') }}">Log OPC</a></li>
                        <li><a href="ecommerce-product-detail">Log PPC</a></li>
                        <li><a href="ecommerce-orders">Log MK</a></li>
                    </ul>
                </li> --}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="uil-files-landscapes"></i>
                        <span>Stock</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="/stockmasuk">Stock Log Masuk</a></li>
                        <li><a href="/stockmasukkeras">Stock Log Masuk Keras</a></li>
                    </ul>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
