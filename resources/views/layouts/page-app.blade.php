<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }}</title>

            <link href="{{ url('assets/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
           <!-- Layout config Js -->
            <script src="{{ url('assets/js/layout.js') }}"></script>
            <!-- Bootstrap Css -->
            <link href="{{ url('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
            <!-- Icons Css -->
            <link href="{{ url('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
            <!-- App Css-->
            <link href="{{ url('assets/css/app.css') }}" rel="stylesheet" type="text/css" />
            <!-- App Css-->
            <link href="{{ url('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
            <!-- custom Css-->
            <link href="{{ url('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
            <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

            <!--datatable css-->
            <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
            <!--datatable responsive css-->
            <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

            <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
            <!-- apexcharts -->
            <script src="{{ url('assets/js/apexcharts.min.js') }}"></script>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        
    </head>

    <body>
        <div class="layout-wrapper">
            <header id="page-topbar">
                <div class="layout-width">
                    <div class="navbar-header">

                        <div class="d-flex align-items-center">
                            <div class="dropdown ms-sm-3 header-item topbar-user">
                                <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="d-flex align-items-center">
                                        <img class="rounded-circle header-profile-user" src="{{ url('assets/images/avatar-1.jpeg') }}" alt="Header Avatar">
                                        <span class="text-start ms-xl-2">
                                            <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">Welcome! Ichelon</span>
                                        </span>
                                    </span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <!-- item-->
                                    <!-- <a class="dropdown-item" href="pages-profile.html"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Profile</span></a> -->
                                    <a class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" href="{{ route('logout') }}"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Logout</span></a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex message_box">
                        @if($message = Session::get("message"))
                            {{$message}}
                            {{Session::forget("message")}}
                        @endif

                        @if($error = Session::get("error"))
                            {{$error}}
                            {{Session::forget("error")}}
                        @endif
                        </div>
                    </div>
                </div>
            </header>
            
            <div class="app-menu navbar-menu">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <!-- Dark Logo-->
                    <a href="{{url('/')}}" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{url('assets/images/ICG-logo.webp')}}" alt="">
                        </span>
                    </a>
                    <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
                        <i class="ri-record-circle-line"></i>
                    </button>
                </div>

                <div id="scrollbar" data-simplebar="init" class="h-100 simplebar-scrollable-y simplebar-mouse-entered"><div class="simplebar-wrapper" style="margin: 0px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: 0px; bottom: 0px;"><div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: 100%; overflow: hidden scroll;"><div class="simplebar-content" style="padding: 0px;">
                    <div class="container-fluid">

                        <div id="two-column-menu">
                        </div>
                        <ul class="navbar-nav simplebar-mouse-entered" id="navbar-nav" data-simplebar="init"><div class="simplebar-wrapper" style="margin: 0px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: 0px; bottom: 0px;"><div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: auto; overflow: hidden;"><div class="simplebar-content" style="padding: 0px;">
                            <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                            
                            <li class="nav-item">
                                <a class="nav-link menu-link {{ Request::is('/') ? 'active' : '' }}" href="{{url('/')}}" role="button" aria-expanded="true" aria-controls="sidebarDashboards">
                                    <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboards</span>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link menu-link {{ Request::is('clients') ? 'active' : '' }}" href="#sidebarDashboards" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="sidebarDashboards">
                                    <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Manage Clients</span>
                                </a>
                                <div class="collapse menu-dropdown show" id="sidebarDashboards">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="{{url('clients')}}" class="nav-link {{ Request::is('clients') ? 'active' : '' }}" data-key="t-analytics">Clients</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link menu-link {{ Request::is('tags') ? 'active' : '' }}" href="#sidebarTags" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="sidebarTags">
                                    <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Manage Tags</span>
                                </a>
                                <div class="collapse menu-dropdown show" id="sidebarTags">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="{{url('tags')}}" class="nav-link {{ Request::is('tags') ? 'active' : '' }}" data-key="t-analytics">Tags</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link menu-link {{ Request::is('groups') ? 'active' : '' }}" href="#sidebarGroups" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="sidebarGroups">
                                    <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Manage Groups</span>
                                </a>
                                <div class="collapse menu-dropdown show" id="sidebarGroups">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="{{url('groups')}}" class="nav-link {{ Request::is('groups') ? 'active' : '' }}" data-key="t-analytics">Groups</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{url('assign-group')}}" class="nav-link {{ Request::is('assign-group') ? 'active' : '' }}" data-key="t-analytics">Assign Group</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                        </div></div></div></div><div class="simplebar-placeholder" style="width: 249px; height: 1086px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="width: 0px; display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: hidden;"><div class="simplebar-scrollbar" style="height: 0px; display: none;"></div></div><div class="simplebar-track simplebar-horizontal"><div class="simplebar-scrollbar"></div></div><div class="simplebar-track simplebar-vertical"><div class="simplebar-scrollbar"></div></div><div class="simplebar-track simplebar-horizontal"><div class="simplebar-scrollbar"></div></div><div class="simplebar-track simplebar-vertical"><div class="simplebar-scrollbar"></div></div><div class="simplebar-track simplebar-horizontal"><div class="simplebar-scrollbar"></div></div><div class="simplebar-track simplebar-vertical"><div class="simplebar-scrollbar"></div></div></ul>
                    </div>
                    <!-- Sidebar -->
                </div></div></div></div><div class="simplebar-placeholder" style="width: 249px; height: 1086px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="width: 0px; display: none; transform: translate3d(0px, 0px, 0px);"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: visible;"><div class="simplebar-scrollbar" style="height: 67px; transform: translate3d(0px, 0px, 0px); display: block;"></div></div></div>

                <div class="sidebar-background"></div>
            </div>
            <div class="vertical-overlay"></div>
            <div class="main-content">
                @yield("content")
            </div>
            <!-- footer -->
            <footer class="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <p class="mb-0 text-muted">&copy;
                                    <?php echo date('Y'); ?> Crafted with <i class="mdi mdi-heart text-danger"></i> by Ichelon
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->
        </div>
        <!-- JAVASCRIPT -->
        <script src="{{ url('assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ url('assets/js/simplebar.min.js') }}"></script>
        <script src="{{ url('assets/js/waves.min.js') }}"></script>
        <script src="{{ url('assets/js/feather.min.js') }}"></script>
        <script src="{{ url('assets/js/lord-icon-2.1.0.js') }}"></script>
        <script src="{{ url('assets/js/plugins.js') }}"></script>
        <script src="{{ url('assets/js/prism.js') }}"></script>
        
        <!--datatable js-->
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="{{ url('assets/js/datatables.init.js') }}"></script>

        <!-- App js -->
        <script src="{{ url('assets/js/app.js') }}"></script>

        
    </body>
</html>
