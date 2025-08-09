<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ $title ?? 'Sistem Distribusi Soal' }}</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('sb') }}/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('sb') }}/css/sb-admin-2.min.css" rel="stylesheet">

    @livewireStyles
    @stack('css')

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">

                <div class="sidebar-brand-text mx-3">Sistem Distribusi Soal
                </div>

                {{-- <div class="sidebar-brand-text mx-3">Distribusi Soal</div> --}}
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            @can('access-master')
                <!-- Heading -->
                <div class="sidebar-heading">
                    Master Data
                </div>

                <li class="nav-item {{ Route::is('data-user') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('data-user') }}">
                        <i class="fas fa-fw fa-layer-group"></i>
                        <span>Data User</span></a>
                </li>
                <li class="nav-item {{ Route::is('peminatan') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('peminatan') }}">
                        <i class="fas fa-fw fa-layer-group"></i>
                        <span>Data Peminatan</span></a>
                </li>
                <li class="nav-item {{ Route::is('periode') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('periode') }}">
                        <i class="fas fa-fw fa-layer-group"></i>
                        <span>Data Periode</span></a>
                </li>
                <li class="nav-item {{ Route::is('mata-kuliah') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('mata-kuliah') }}">
                        <i class="fas fa-fw fa-layer-group"></i>
                        <span>Data Mata Kuliah</span></a>
                </li>
                <li class="nav-item {{ Route::is('pengampu') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('pengampu') }}">
                        <i class="fas fa-fw fa-layer-group"></i>
                        <span>Data Pengampu</span></a>
                </li>
            @endcan


            <!-- Heading -->
            <div class="sidebar-heading">
                Manage Soal
            </div>

            @if (Auth::user()->role == 'dosen')
                <li class="nav-item {{ Route::is('all-mata-kuliah') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('all-mata-kuliah') }}">
                        <i class="fas fa-fw fa-layer-group"></i>
                        <span>Semua Mata Kuliah</span></a>
                </li>
            @else
                <li class="nav-item {{ Route::is('pencarian-soal') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('pencarian-soal') }}">
                        <i class="fas fa-fw fa-layer-group"></i>
                        <span>Pencarian Soal</span></a>
                </li>
            @endif


            <!-- Divider -->

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="d-none d-md-inline text-center">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light topbar static-top mb-4 bg-white shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline ml-md-3 my-md-0 mw-100 navbar-search my-2 mr-auto">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light small border-0"
                                placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right animated--grow-in p-3 shadow"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline w-100 navbar-search mr-auto">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light small border-0"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right animated--grow-in shadow"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">

                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">A new monthly report is ready to
                                            download!</span>
                                    </div>
                                </a>

                                <a class="dropdown-item small text-center text-gray-500" href="#">Show All
                                    Alerts</a>
                            </div>
                        </li>



                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span
                                    class="d-none d-lg-inline small mr-2 text-gray-600">{{ Auth::user()->name }}</span>
                                <img class="img-profile rounded-circle"
                                    src="{{ asset('sb') }}/img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right animated--grow-in shadow"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                {{-- <a class="dropdown-item" href="#">
																		<i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
																		Settings
																</a>
																<a class="dropdown-item" href="#">
																		<i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
																		Activity Log
																</a>
																<div class="dropdown-divider"></div> --}}
                                <a id="btn-logout" class="dropdown-item" href="#" data-toggle="modal"
                                    data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                {{-- <div class="container-fluid">
										<h1 class="h3 mb-4 text-gray-800">Blank Page</h1>
								</div> --}}
                {{ $slot }}
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright my-auto text-center">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ingin keluar ?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Pilih "Logout" di bawah jika Anda siap untuk mengakhiri sesi Anda saat ini.
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    @livewireScripts
    @stack('jsu')

    <script src="{{ asset('sb') }}/vendor/jquery/jquery.min.js"></script>
    <script src="{{ asset('sb') }}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('sb') }}/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('sb') }}/js/sb-admin-2.min.js"></script>




    @stack('js')

</body>

</html>
