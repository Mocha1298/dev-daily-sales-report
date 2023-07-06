<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" href="/ico.png" />
    <title>@yield('title')</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="/assets/images/favicon.ico" />
    <!-- Hope Ui Design System Css -->
    <link rel="stylesheet" href="/assets/css/hope-ui.min.css?v=2.0.0" />
    <link href=" https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Library / Plugin Css Build -->
    {{-- <link rel="stylesheet" href="/assets/css/libs.min.css" /> --}}
    <style>
        .sidebar.sidebar-default .nav-link:not(.static-item).active,
        .sidebar.sidebar-default .nav-link:not(.static-item)[aria-expanded=true] {
            background: #00A7E6;
        }

        .sidebar .sidebar-toggle {
            background: #00A7E6;
        }

        .nav .sidebar-toggle {
            background: #00A7E6;
        }

        button.default {
            background: #00A7E6;
        }

        .nav-item a.nav-link:hover+.item-name {
            color: #00A7E6;
        }

        .simple-tree-table-handler.simple-tree-table-icon {
            margin-right: 5px;
            margin-left: -10px;
        }

        td {
            text-align: end;
        }

        table {
            font-size: 12px;
        }
    </style>
    @yield('css')
</head>

<body class="">
    <!-- loader Start -->
    <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body"></div>
        </div>
    </div>
    <!-- loader END -->
    <aside class="sidebar sidebar-default sidebar-white sidebar-base navs-rounded-all ">
        <div class="sidebar-header d-flex align-items-center justify-content-start">
            <a href="#" class="navbar-brand">
                <div class="logo-main">
                    <div class="logo-normal">
                        <img src="/logo.png" alt="" width="40px">
                    </div>
                    <div class="logo-mini">
                        <img src="/logo.png" alt="" width="40px">
                    </div>
                </div>
                @if (Auth::user())
                    <h4 class="logo-title">Target</h4>
                @endif
            </a>
            <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
                <i class="icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </i>
            </div>
        </div>
        <div class="sidebar-body pt-0 data-scrollbar">
            <div class="sidebar-list">
                <!-- Sidebar Menu Start -->
                <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#sidebar-form" role="button"
                            aria-expanded="false" aria-controls="sidebar-form">
                            <i class="icon">
                                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.4"
                                        d="M16.191 2H7.81C4.77 2 3 3.78 3 6.83V17.16C3 20.26 4.77 22 7.81 22H16.191C19.28 22 21 20.26 21 17.16V6.83C21 3.78 19.28 2 16.191 2Z"
                                        fill="currentColor"></path>
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M8.07996 6.6499V6.6599C7.64896 6.6599 7.29996 7.0099 7.29996 7.4399C7.29996 7.8699 7.64896 8.2199 8.07996 8.2199H11.069C11.5 8.2199 11.85 7.8699 11.85 7.4289C11.85 6.9999 11.5 6.6499 11.069 6.6499H8.07996ZM15.92 12.7399H8.07996C7.64896 12.7399 7.29996 12.3899 7.29996 11.9599C7.29996 11.5299 7.64896 11.1789 8.07996 11.1789H15.92C16.35 11.1789 16.7 11.5299 16.7 11.9599C16.7 12.3899 16.35 12.7399 15.92 12.7399ZM15.92 17.3099H8.07996C7.77996 17.3499 7.48996 17.1999 7.32996 16.9499C7.16996 16.6899 7.16996 16.3599 7.32996 16.1099C7.48996 15.8499 7.77996 15.7099 8.07996 15.7399H15.92C16.319 15.7799 16.62 16.1199 16.62 16.5299C16.62 16.9289 16.319 17.2699 15.92 17.3099Z"
                                        fill="currentColor"></path>
                                </svg>
                            </i>
                            <span class="item-name">Unit</span>
                            <i class="right-icon">
                                <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </i>
                        </a>
                        <ul class="sub-nav collapse" id="sidebar-form" data-bs-parent="#sidebar-menu">
                            <li class="nav-item">
                                <a class="nav-link @yield('bdr_link')" href="#"
                                    onclick="tanggal('borobudur',@yield('isadmin'))">
                                    <i class="icon">
                                        <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                            viewBox="0 0 24 24" fill="currentColor">
                                            <g>
                                                <circle cx="12" cy="12" r="8"
                                                    fill="currentColor"></circle>
                                            </g>
                                        </svg>
                                    </i>
                                    <i class="sidenav-mini-icon"> B </i>
                                    <span class="item-name">Borobudur</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @yield('prb_link')" href="#"
                                    onclick="tanggal('prambanan',@yield('isadmin'))">
                                    <i class="icon">
                                        <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                            viewBox="0 0 24 24" fill="currentColor">
                                            <g>
                                                <circle cx="12" cy="12" r="8"
                                                    fill="currentColor"></circle>
                                            </g>
                                        </svg>
                                    </i>
                                    <i class="sidenav-mini-icon"> P </i>
                                    <span class="item-name">Prambanan</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @yield('rtb_link')" href="#"
                                    onclick="tanggal('ratuboko',@yield('isadmin'))">
                                    <i class="icon">
                                        <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                            viewBox="0 0 24 24" fill="currentColor">
                                            <g>
                                                <circle cx="12" cy="12" r="8"
                                                    fill="currentColor"></circle>
                                            </g>
                                        </svg>
                                    </i>
                                    <i class="sidenav-mini-icon"> R </i>
                                    <span class="item-name">Ratu Boko</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @yield('tmi_link')" href="#"
                                    onclick="tanggal('tamanmini',@yield('isadmin'))">
                                    <i class="icon">
                                        <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                            viewBox="0 0 24 24" fill="currentColor">
                                            <g>
                                                <circle cx="12" cy="12" r="8"
                                                    fill="currentColor"></circle>
                                            </g>
                                        </svg>
                                    </i>
                                    <i class="sidenav-mini-icon"> T </i>
                                    <span class="item-name">TMII</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @yield('mnh_link')" href="#"
                                    onclick="tanggal('manohara',@yield('isadmin'))">
                                    <i class="icon">
                                        <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                            viewBox="0 0 24 24" fill="currentColor">
                                            <g>
                                                <circle cx="12" cy="12" r="8"
                                                    fill="currentColor"></circle>
                                            </g>
                                        </svg>
                                    </i>
                                    <i class="sidenav-mini-icon"> M </i>
                                    <span class="item-name">Manohara</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @yield('tnp_link')" href="#"
                                    onclick="tanggal('teapen',@yield('isadmin'))">
                                    <i class="icon">
                                        <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10"
                                            viewBox="0 0 24 24" fill="currentColor">
                                            <g>
                                                <circle cx="12" cy="12" r="8"
                                                    fill="currentColor"></circle>
                                            </g>
                                        </svg>
                                    </i>
                                    <i class="sidenav-mini-icon"> T </i>
                                    <span class="item-name">Teater Pentas</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <!-- Sidebar Menu End -->
            </div>
        </div>
        <div class="sidebar-footer"></div>
    </aside>
    <main class="main-content">
        <div class="position-relative iq-banner">
            <!--Nav Start-->
            <nav class="nav navbar navbar-expand-lg navbar-light iq-navbar">
                <div class="container-fluid navbar-inner">
                    <a href="#" class="navbar-brand">
                        <div class="logo-main">
                            <div class="logo-normal">
                                <img src="/logo.png" alt="" width="40px">
                            </div>
                            <div class="logo-mini">
                                <img src="/logo.png" alt="" width="40px">
                            </div>
                        </div>
                    </a>
                    <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
                        <i class="icon">
                            <svg width="20px" class="icon-20" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
                            </svg>
                        </i>
                    </div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon">
                            <span class="mt-2 navbar-toggler-bar bar1"></span>
                            <span class="navbar-toggler-bar bar2"></span>
                            <span class="navbar-toggler-bar bar3"></span>
                        </span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="mb-2 navbar-nav ms-auto align-items-center navbar-list mb-lg-0">
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link" id="mail-drop" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.4"
                                            d="M16.8843 5.11485H13.9413C13.2081 5.11969 12.512 4.79355 12.0474 4.22751L11.0782 2.88762C10.6214 2.31661 9.9253 1.98894 9.19321 2.00028H7.11261C3.37819 2.00028 2.00001 4.19201 2.00001 7.91884V11.9474C1.99536 12.3904 21.9956 12.3898 21.9969 11.9474V10.7761C22.0147 7.04924 20.6721 5.11485 16.8843 5.11485Z"
                                            fill="currentColor"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M20.8321 6.54353C21.1521 6.91761 21.3993 7.34793 21.5612 7.81243C21.8798 8.76711 22.0273 9.77037 21.9969 10.7761V16.0292C21.9956 16.4717 21.963 16.9135 21.8991 17.3513C21.7775 18.1241 21.5057 18.8656 21.0989 19.5342C20.9119 19.8571 20.6849 20.1553 20.4231 20.4215C19.2383 21.5089 17.665 22.0749 16.0574 21.9921H7.93061C6.32049 22.0743 4.74462 21.5086 3.55601 20.4215C3.2974 20.1547 3.07337 19.8566 2.88915 19.5342C2.48475 18.8661 2.21869 18.1238 2.1067 17.3513C2.03549 16.9142 1.99981 16.4721 2 16.0292V10.7761C1.99983 10.3374 2.02357 9.89902 2.07113 9.46288C2.08113 9.38636 2.09614 9.31109 2.11098 9.23659C2.13573 9.11241 2.16005 8.99038 2.16005 8.86836C2.25031 8.34204 2.41496 7.83116 2.64908 7.35101C3.34261 5.86916 4.76525 5.11492 7.09481 5.11492H16.8754C18.1802 5.01401 19.4753 5.4068 20.5032 6.21522C20.6215 6.3156 20.7316 6.4254 20.8321 6.54353ZM6.97033 15.5412H17.0355H17.0533C17.2741 15.5507 17.4896 15.4717 17.6517 15.3217C17.8137 15.1716 17.9088 14.963 17.9157 14.7425C17.9282 14.5487 17.8644 14.3577 17.7379 14.2101C17.5924 14.0118 17.3618 13.8935 17.1155 13.8907H6.97033C6.51365 13.8907 6.14343 14.2602 6.14343 14.7159C6.14343 15.1717 6.51365 15.5412 6.97033 15.5412Z"
                                            fill="currentColor"></path>
                                    </svg>
                                    <span class="bg-primary count-mail"></span>
                                </a>
                                <div class="p-0 sub-drop dropdown-menu dropdown-menu-end" aria-labelledby="mail-drop">
                                    <div class="m-0 shadow-none card">
                                        <div class="py-3 card-header d-flex justify-content-between"
                                            style="background: #00A7E6;">
                                            <div class="header-title">
                                                <h5 class="mb-0 text-white">Data Source</h5>
                                            </div>
                                        </div>
                                        <div class="p-0 card-body ">
                                            <a href="#" class="iq-sub-card">
                                                <div class="d-flex align-items-center">
                                                    <div class="ms-3">
                                                        <h6 class="mb-0 ">Inventory Ticket</h6>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="p-0 card-body ">
                                            <a href="#" class="iq-sub-card">
                                                <div class="d-flex align-items-center">
                                                    <div class="ms-3">
                                                        <h6 class="mb-0 ">ERP System</h6>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="p-0 card-body ">
                                            <a href="#" class="iq-sub-card">
                                                <div class="d-flex align-items-center">
                                                    <div class="ms-3">
                                                        <h6 class="mb-0 ">Goers Ticketing System</h6>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                @if (Auth::user())
                                    <a href="{{ route('signout') }}" class="nav-link" id="mail-drop">
                                        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.4"
                                                d="M2 6.447C2 3.996 4.03024 2 6.52453 2H11.4856C13.9748 2 16 3.99 16 6.437V17.553C16 20.005 13.9698 22 11.4744 22H6.51537C4.02515 22 2 20.01 2 17.563V16.623V6.447Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M21.7787 11.4548L18.9329 8.5458C18.6388 8.2458 18.1655 8.2458 17.8723 8.5478C17.5802 8.8498 17.5811 9.3368 17.8743 9.6368L19.4335 11.2298H17.9386H9.54826C9.13434 11.2298 8.79834 11.5748 8.79834 11.9998C8.79834 12.4258 9.13434 12.7698 9.54826 12.7698H19.4335L17.8743 14.3628C17.5811 14.6628 17.5802 15.1498 17.8723 15.4518C18.0194 15.6028 18.2113 15.6788 18.4041 15.6788C18.595 15.6788 18.7868 15.6028 18.9329 15.4538L21.7787 12.5458C21.9199 12.4008 21.9998 12.2048 21.9998 11.9998C21.9998 11.7958 21.9199 11.5998 21.7787 11.4548Z"
                                                fill="currentColor"></path>
                                        </svg>
                                        <span class="bg-primary count-mail"></span>
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="nav-link" id="mail-drop">
                                        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.4"
                                                d="M7.29639 6.446C7.29639 3.995 9.35618 2 11.8878 2H16.9201C19.4456 2 21.5002 3.99 21.5002 6.436V17.552C21.5002 20.004 19.4414 22 16.9098 22H11.8775C9.35205 22 7.29639 20.009 7.29639 17.562V16.622V6.446Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M16.0374 11.4538L13.0695 8.54482C12.7627 8.24482 12.2691 8.24482 11.9634 8.54682C11.6587 8.84882 11.6597 9.33582 11.9654 9.63582L13.5905 11.2288H3.2821C2.85042 11.2288 2.5 11.5738 2.5 11.9998C2.5 12.4248 2.85042 12.7688 3.2821 12.7688H13.5905L11.9654 14.3628C11.6597 14.6628 11.6587 15.1498 11.9634 15.4518C12.1168 15.6028 12.3168 15.6788 12.518 15.6788C12.717 15.6788 12.9171 15.6028 13.0695 15.4538L16.0374 12.5448C16.1847 12.3998 16.268 12.2038 16.268 11.9998C16.268 11.7948 16.1847 11.5988 16.0374 11.4538Z"
                                                fill="currentColor"></path>
                                        </svg>
                                        <span class="bg-primary count-mail"></span>
                                    </a>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </nav> <!-- Nav Header Component Start -->
            <div class="iq-navbar-header" style="height: 100px;">
                <div class="container-fluid iq-container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="flex-wrap d-flex justify-content-between align-items-center">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="iq-header-img">
                    <img src="/assets/images/dashboard/top-header.png" alt="header"
                        class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX">
                    <img src="/assets/images/dashboard/top-header1.png" alt="header"
                        class="theme-color-purple-img img-fluid w-100 h-100 animated-scaleX">
                    <img src="/assets/images/dashboard/top-header2.png" alt="header"
                        class="theme-color-blue-img img-fluid w-100 h-100 animated-scaleX">
                    <img src="/assets/images/dashboard/top-header3.png" alt="header"
                        class="theme-color-green-img img-fluid w-100 h-100 animated-scaleX">
                    <img src="/assets/images/dashboard/top-header4.png" alt="header"
                        class="theme-color-yellow-img img-fluid w-100 h-100 animated-scaleX">
                    <img src="/assets/images/dashboard/top-header5.png" alt="header"
                        class="theme-color-pink-img img-fluid w-100 h-100 animated-scaleX">
                </div>
            </div> <!-- Nav Header Component End -->
            <!--Nav End-->
        </div>
        @if ($__env->yieldContent('isadmin') == 1)
            @if ($__env->yieldContent('isdash') == 0)
                <div class="conatiner-fluid content-inner">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card mb-1">
                                <div class="card-body p-3 align-middle">
                                    <div class="header-title">
                                        <div class="row align-middle">
                                            <div class="col-sm-6">
                                                <h4 class="p-1">Target: @yield('title')</h4>
                                            </div>
                                            {{-- <div class="col-sm-6 d-flex flex-row-reverse">
                                                <button class="btn btn-primary" onclick="byDate('@yield('title')')"
                                                    style="padding: 0 15px">
                                                    <svg class="icon-16" width="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="11.7669" cy="11.7666" r="8.98856"
                                                            stroke="currentColor" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round"></circle>
                                                        <path d="M18.0186 18.4851L21.5426 22" stroke="currentColor"
                                                            stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round"></path>
                                                    </svg>
                                                </button>&nbsp;&nbsp;&nbsp;
                                                <input type="date" class="form-control tanggal" id="sampai"
                                                    value={{ $end }} style="width: auto"
                                                    onclick="setmin()">&nbsp;
                                                <p style="font-size: 20px">_</p> &nbsp;
                                                <input type="date" class="form-control tanggal" id="dari"
                                                    value={{ $start }} style="width: auto" onclick="setmax()">
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @yield('main')
                    </div>
                </div>
            @else
                @yield('welcome')
            @endif
        @else
            @if ($__env->yieldContent('isdash') == 0)
                <div class="conatiner-fluid content-inner">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card mb-1">
                                <div class="card-body p-3 align-middle">
                                    <div class="header-title">
                                        <div class="row align-middle">
                                            <div class="col-sm-6">
                                                <h4 class="p-1">DAILY REPORT: @yield('title')</h4>
                                            </div>
                                            <div class="col-sm-6 d-flex flex-row-reverse">
                                                {{-- <label for="" class="mt-2"></label> &nbsp; --}}
                                                <button class="btn btn-primary" onclick="byDate('@yield('title')')"
                                                    style="padding: 0 15px">
                                                    <svg class="icon-16" width="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="11.7669" cy="11.7666" r="8.98856"
                                                            stroke="currentColor" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round"></circle>
                                                        <path d="M18.0186 18.4851L21.5426 22" stroke="currentColor"
                                                            stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round"></path>
                                                    </svg>
                                                </button>&nbsp;&nbsp;&nbsp;
                                                <input type="date" class="form-control tanggal" id="sampai"
                                                    value={{ $end }} style="width: auto"
                                                    onclick="setmin()">&nbsp;
                                                <p style="font-size: 20px">_</p> &nbsp;
                                                <input type="date" class="form-control tanggal" id="dari"
                                                    value={{ $start }} style="width: auto" onclick="setmax()">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @yield('main')
                    </div>
                </div>
            @else
                @yield('welcome')
            @endif
        @endif
    </main>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
    <!-- Library Bundle Script -->
    <script src="/assets/js/core/libs.min.js"></script>
    <!-- External Library Bundle Script -->
    <script src="/assets/js/core/external.min.js"></script>
    <!-- App Script -->
    <script src="/assets/js/hope-ui.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="/assets/js/jquery-simple-tree-table.js"></script>
    @yield('script')
    @php
        use Carbon\Carbon;
        $now = Carbon::now();
        $start1 = $now->format('Y-m-d');
        $end1 = $now->format('Y-m-d');
        $tanggal = '/' . $start1 . '/' . $end1;
    @endphp
    <script>
        function tanggal(unit, isadmin) {
            if (isadmin == 1) {
                window.location.href = '/admin/' + unit;
            } else {
                window.location.href = '/unit/' + unit + '{!! $tanggal !!}';
            }
        }

        $('#basic').simpleTreeTable({
            expander: $('#expander'),
            collapser: $('#collapser'),
            store: 'session',
            storeKey: 'simple-tree-table-basic'
        });
        $('#open1').on('click', function() {
            $('#basic').data('simple-tree-table').openByID("1");
        });
        $('#close1').on('click', function() {
            $('#basic').data('simple-tree-table').closeByID("1");
        });

        $('#basic2').simpleTreeTable({
            expander: $('#expander'),
            collapser: $('#collapser'),
            store: 'session',
            storeKey: 'simple-tree-table-basic'
        });
        $('#open1').on('click', function() {
            $('#basic2').data('simple-tree-table').openByID("1");
        });
        $('#close1').on('click', function() {
            $('#basic2').data('simple-tree-table').closeByID("1");
        });
        // TANGGAL
        function setmax() {
            var value = $("#sampai")[0].value;
            $("#dari")[0].max = value;
            // console.log($("#dari")[0].max);
        }

        function setmin() {
            var value = $("#dari")[0].value;
            $("#sampai")[0].min = value;
            // console.log($("#sampai")[0].min);
        }
        $('table').simpleTreeTable({
            opened: []
        });
    </script>
    @if (session('gagal') == 'tahun')
        <script>
            Swal.fire({
                title: 'Tahun Harus Sama',
                text: 'Tidak disarankan untuk menarik data beda tahun',
                icon: 'error',
                confirmButtonText: 'OK'
            })
        </script>
    @endif
    <script>
        function byDate(unit) {
            if (unit == "Borobudur") {
                unit = "borobudur"
            } else if (unit == "Prambanan") {
                unit = "prambanan"
            } else if (unit == "Ratu Boko") {
                unit = "ratuboko"
            } else if (unit == "TMII") {
                unit = "tamanmini"
            } else if (unit == "Manohara") {
                unit = "manohara"
            } else if (unit == "Teater Pentas") {
                unit = "teapen"
            }
            var tgl_start = document.getElementById("dari").value;
            var tgl_end = document.getElementById("sampai").value;
            window.location.href = '/unit/' + unit + '/' + tgl_start + '/' + tgl_end;
        }

        function timer() {
            let timerInterval
            Swal.fire({
                title: 'Sedang Memuat Data',
                timer: 30000,
                // timerProgressBar: true,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log('I was closed by the timer')
                }
            })
        }
    </script>
</body>

</html>
