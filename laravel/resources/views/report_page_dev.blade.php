<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" href="/ico.png" />
    <title>
        @if ($unit == 1)
            Borobudur
        @elseif ($unit == 2)
            Prambanan
        @else
            Ratu Boko
        @endif
    </title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="/assets/images/favicon.ico" />
    <!-- Hope Ui Design System Css -->
    <link rel="stylesheet" href="/assets/css/hope-ui.min.css?v=2.0.0" />
    <link href=" https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .simple-tree-table-handler.simple-tree-table-icon {
            margin-right: 5px;
            margin-left: -10px;
        }

        /* th,
        td {
            padding-right: 3px;
            padding-left: 3px;
        } */

        td {
            text-align: end;
        }

        table {
            font-size: 14px;
        }

        table {
            color: #232d42;
        }
    </style>
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
                                <a class="nav-link @if ($unit == 1) active @endif"
                                    href="/dev/unit/borobudur">
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
                                <a class="nav-link @if ($unit == 2) active @endif"
                                    href="/dev/unit/prambanan">
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
                                <a class="nav-link @if ($unit == 3) active @endif"
                                    href="/dev/unit/ratuboko">
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
                                <a class="nav-link @if ($unit == 4) active @endif"
                                    href="/dev/unit/tmii">
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
                                <a class="nav-link @if ($unit == 5) active @endif"
                                    href="/dev/unit/manohara">
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
                                    <span class="item-name">Manohara</span>
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
                                <a class="py-0 nav-link d-flex align-items-center" href="#" id="navbarDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="caption ms-3 d-none d-md-block ">
                                        <h6 class="mb-0 caption-title">Data Source :</h6>
                                        <p class="mb-0 caption-sub-title">- Inventory Ticket</p>
                                        <p class="mb-0 caption-sub-title">- ERP System</p>
                                        <p class="mb-0 caption-sub-title">- Goers Ticketing System</p>
                                    </div>
                                </a>
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
        <div class="conatiner-fluid content-inner">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card mb-1">
                        <div class="card-body p-3 align-middle">
                            <div class="header-title">
                                <div class="row align-middle">
                                    <div class="col-sm-6">
                                        <h4 class="p-1">DAILY REPORT: @if ($unit == 1)
                                                Borobudur
                                            @elseif ($unit == 2)
                                                Prambanan
                                            @else
                                                Ratu Boko
                                            @endif
                                        </h4>
                                    </div>
                                    <div class="col-sm-6 d-flex flex-row-reverse">
                                        {{-- <label for="" class="mt-2"></label> &nbsp; --}}
                                        <button class="btn btn-primary" onclick="byDate({{ $unit }})"
                                            style="padding: 0 15px">
                                            <svg class="icon-16" width="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="11.7669" cy="11.7666" r="8.98856"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></circle>
                                                <path d="M18.0186 18.4851L21.5426 22" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg>
                                        </button>&nbsp;&nbsp;&nbsp;
                                        <input type="date" class="form-control tanggal" id="sampai"
                                            value={{ $tgl_end }} style="width: auto" onclick="setmin()">&nbsp;
                                        <p style="font-size: 20px">_</p> &nbsp;
                                        <input type="date" class="form-control tanggal" id="dari"
                                            value={{ $tgl_start }} style="width: auto" onclick="setmax()">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Daily Report Pengguna Jasa</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic" class="table table-striped mb-0" role="grid">
                                    <tr>
                                        {{-- <th> #</th> --}}
                                        <th> Desc</th>
                                        <th> Actual</th>
                                        <th> Actual MTD</th>
                                        <th> Monthly Target</th>
                                        <th> % MTD Achv</th>
                                        <th> Actual YTD</th>
                                        <th> Annual Target</th>
                                        <th> % YTD Achv</th>
                                    </tr>
                                    @php
                                        for ($i = 0; $i < $count; $i++) {
                                            $target_month[$i] = $target_pengguna_jasa[$i]->target_mountly;
                                            $target_year[$i] = $target_pengguna_jasa[$i]->target_yearly;
                                            $actual_month[$i] = $ticketing[$i]->actual_trx_month;
                                            $actual_year[$i] = $ticketing[$i]->actual_trx_year;
                                            if ($target_month[$i] != 0) {
                                                $percen_month[$i] = ($actual_month[$i] / $target_month[$i]) * 100;
                                            } else {
                                                $percen_month[$i] = 0;
                                            }
                                            if ($target_year[$i] != 0) {
                                                $percen_year[$i] = ($actual_year[$i] / $target_year[$i]) * 100;
                                            } else {
                                                $percen_year[$i] = 0;
                                            }
                                            $target_i_month[$i] = $target_income[$i]->target_mountly;
                                            $target_i_year[$i] = $target_income[$i]->target_yearly;
                                            $actual_i_month[$i] = $income[$i]->actual_nom_month;
                                            $actual_i_year[$i] = $income[$i]->actual_nom_year;
                                            if ($target_i_month[$i] != 0) {
                                                $percen_i_month[$i] = ($actual_i_month[$i] / $target_i_month[$i]) * 100;
                                            } else {
                                                $percen_i_month[$i] = 0;
                                            }
                                            if ($target_i_year[$i] != 0) {
                                                $percen_i_year[$i] = ($actual_i_year[$i] / $target_i_year[$i]) * 100;
                                            } else {
                                                $percen_i_year[$i] = 0;
                                            }
                                        }
                                    @endphp
                                    @for ($i = 0; $i < $count; $i++)
                                        <tr data-node-id="{{ $ticketing[$i]->id_link }}">
                                            {{-- <td>{{ $ticketing[$i]->id_link }}</td> --}}
                                            <th>{{ $ticketing[$i]->deskripsi }}</th>
                                            <td>{{ number_format($ticketing[$i]->actual_trx_date, 0, '', '.') }}
                                            </td>
                                            <td>{{ number_format($ticketing[$i]->actual_trx_month, 0, '', '.') }}
                                            </td>
                                            <td>{{ number_format($target_month[$i], 0, '', '.') }}</td>
                                            <td>
                                                @if ($percen_month[$i] == null)
                                                    0
                                                @else
                                                    {{ number_format($percen_month[$i], 2, '.', '') }}
                                                @endif
                                            </td>
                                            <td>{{ number_format($ticketing[$i]->actual_trx_year, 0, '', '.') }}
                                            </td>
                                            <td>{{ number_format($target_year[$i], 0, '', '.') }}</td>
                                            <td>
                                                @if ($percen_year[$i] == null)
                                                    0
                                                @else
                                                    {{ number_format($percen_year[$i], 2, '.', '') }}
                                                @endif
                                            </td>
                                        </tr>
                                        @if ($ticketing[$i]->id_link == 1)
                                            @php
                                                $x = 0;
                                            @endphp
                                            <tr data-node-pid="1">
                                                <th colspan="6">Detail</th>
                                                <th>Total</th>
                                            </tr>
                                            @php
                                                $x++;
                                            @endphp
                                            @foreach ($detail_pj_dom as $item)
                                                <tr data-node-pid="1">
                                                    <th colspan="6">
                                                        {{ $item->trf_name }}
                                                    </th>
                                                    <td>
                                                        {{ number_format($item->total_trx, 0, '', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @elseif($ticketing[$i]->id_link == 2)
                                            @php
                                                $x = 0;
                                            @endphp
                                            <tr data-node-pid="2">
                                                <th colspan="6">Detail</th>
                                                <th>Total</th>
                                            </tr>
                                            @php
                                                $x++;
                                            @endphp
                                            @foreach ($detail_pj_asg as $item)
                                                <tr data-node-pid="2">
                                                    <th colspan="6">
                                                        {{ $item->trf_name }}
                                                    </th>
                                                    <td>
                                                        {{ number_format($item->total_trx, 0, '', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @elseif($ticketing[$i]->id_link == 3)
                                            @php
                                                $x = 0;
                                            @endphp
                                            <tr data-node-pid="3">
                                                <th colspan="6">Detail</th>
                                                <th>Total</th>
                                            </tr>
                                            @php
                                                $x++;
                                            @endphp
                                            @foreach ($detail_pj_pkt as $item)
                                                <tr data-node-pid="3">
                                                    <th colspan="6">
                                                        {{ $item->trf_name }}
                                                    </th>
                                                    <td>
                                                        {{ number_format($item->total_trx, 0, '', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endfor
                                    @php
                                        $tot_act_d = 0;
                                        $tot_act_m = 0;
                                        $target_m = 0;
                                        $percen_m = 0;
                                        $tot_act_y = 0;
                                        $target_y = 0;
                                        $percen_y = 0;
                                        for ($i = 0; $i < $count; $i++) {
                                            $tot_act_d = $tot_act_d + $ticketing[$i]->actual_trx_date;
                                            $tot_act_m = $tot_act_m + $ticketing[$i]->actual_trx_month;
                                            $target_m = $target_m + $target_month[$i];
                                            $tot_act_y = $tot_act_y + $ticketing[$i]->actual_trx_year;
                                            $target_y = $target_y + $target_year[$i];
                                        }
                                    @endphp
                                    @if ($unit == 1)
                                        @php
                                            $tot_act_d = $tot_act_d + $naik_candi_act;
                                            $tot_act_m = $tot_act_m + $naik_candi_mtd;
                                            $target_m = $target_m + $tgt_naik_candi_m;
                                            $tot_act_y = $tot_act_y + $naik_candi_ytd;
                                            $target_y = $target_y + $tgt_naik_candi_y;
                                        @endphp
                                        <tr data-node-id="5">
                                            <td class="text-start">Naik Candi</td>
                                            <td>{{ number_format($naik_candi_act, 0, '', '.') }}</td>
                                            <td>{{ number_format($naik_candi_mtd, 0, '', '.') }}</td>
                                            {{-- <td>-</td> --}}
                                            <td>{{ number_format($tgt_naik_candi_m, 0, '', '.') }}</td>
                                            <td>{{ number_format(($naik_candi_mtd / $tgt_naik_candi_m) * 100, 2, '.', '') }}
                                            </td>
                                            <td>{{ number_format($naik_candi_ytd, 0, '', '.') }}</td>
                                            {{-- <td>-</td> --}}
                                            <td>{{ number_format($tgt_naik_candi_y, 0, '', '.') }}</td>
                                            <td>{{ number_format(($naik_candi_ytd / $tgt_naik_candi_y) * 100, 2, '.', '') }}
                                            </td>
                                        </tr>
                                        @php
                                            $x = 0;
                                        @endphp
                                        <tr data-node-pid="5">
                                            <th colspan="6">Detail</th>
                                            <th>Total</th>
                                        </tr>
                                        @php
                                            $x++;
                                        @endphp
                                        @foreach ($d_naik_candi as $item)
                                            <tr data-node-pid="5">
                                                <th colspan="6">
                                                    {{ $item->name }}
                                                </th>
                                                <td>
                                                    {{ number_format($item->total, 0, '', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    <tr>
                                        <td></td>
                                        <td>{{ number_format($tot_act_d, 0, '', '.') }}</td>
                                        <td>{{ number_format($tot_act_m, 0, '', '.') }}</td>
                                        <td>{{ number_format($target_m, 0, '', '.') }}</td>
                                        <td>{{ number_format(($tot_act_m / $target_m) * 100, 2, '.', '') }}</td>
                                        <td>{{ number_format($tot_act_y, 0, '', '.') }}</td>
                                        <td>{{ number_format($target_y, 0, '', '.') }}</td>
                                        <td>{{ number_format(($tot_act_y / $target_y) * 100, 2, '.', '') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Daily Report Income</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic2" class="table table-striped mb-0" role="grid">
                                    <thead>
                                        <tr>
                                            <th>Desc</th>
                                            <th>Actual</th>
                                            <th>Actual MTD</th>
                                            <th>Monthly Target</th>
                                            <th>% MTD Achv</th>
                                            <th>Actual YTD</th>
                                            <th>Annual Target</th>
                                            <th>% YTD Achv</th>
                                        </tr>
                                    </thead>
                                    @for ($i = 0; $i < $count; $i++)
                                        <tr data-node-id="{{ $income[$i]->id_link }}">
                                            <th>{{ $income[$i]->deskripsi }}</th>
                                            <td>{{ number_format($income[$i]->actual_nom_date, 0, '', '.') }}</td>
                                            <td>{{ number_format($income[$i]->actual_nom_month, 0, '', '.') }}</td>
                                            <td>{{ number_format($target_i_month[$i], 0, '', '.') }}</td>
                                            <td>
                                                @if ($percen_i_month[$i] == null)
                                                    0
                                                @else
                                                    {{ number_format($percen_i_month[$i], 2, '.', '') }}
                                                @endif
                                            </td>
                                            <td>{{ number_format($income[$i]->actual_nom_year, 0, '', '.') }}</td>
                                            <td>{{ number_format($target_i_year[$i], 0, '', '.') }}</td>
                                            <td>
                                                @if ($percen_i_year[$i] == null)
                                                    0
                                                @else
                                                    {{ number_format($percen_i_year[$i], 2, '.', '') }}
                                                @endif
                                            </td>
                                        </tr>
                                        @if ($income[$i]->id_link == 1)
                                            @php
                                                $x = 0;
                                            @endphp
                                            <tr data-node-id="2.{{ $x }}.1" data-node-pid="1">
                                                <th colspan="6">Detail</th>
                                                <th>Total</th>
                                            </tr>
                                            @php
                                                $x++;
                                            @endphp
                                            @foreach ($detail_in_dom as $item)
                                                <tr data-node-id="2.{{ $x }}.1" data-node-pid="1">
                                                    <th colspan="6">
                                                        {{ $item->trf_name }}
                                                    </th>
                                                    <td>
                                                        {{ number_format($item->total_nom, 0, '', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @elseif($income[$i]->id_link == 2)
                                            @php
                                                $x = 0;
                                            @endphp
                                            <tr data-node-id="2.{{ $x }}.2" data-node-pid="2">
                                                <th colspan="6">Detail</th>
                                                <th>Total</th>
                                            </tr>
                                            @php
                                                $x++;
                                            @endphp
                                            @foreach ($detail_in_asg as $item)
                                                <tr data-node-id="2.{{ $x }}.2" data-node-pid="2">
                                                    <th colspan="6">
                                                        {{ $item->trf_name }}
                                                    </th>
                                                    <td>
                                                        {{ number_format($item->total_nom, 0, '', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @elseif($income[$i]->id_link == 3)
                                            @php
                                                $x = 0;
                                            @endphp
                                            <tr data-node-id="2.{{ $x }}.3" data-node-pid="3">
                                                <th colspan="6">Detail</th>
                                                <th>Total</th>
                                            </tr>
                                            @php
                                                $x++;
                                            @endphp
                                            @foreach ($detail_in_pkt as $item)
                                                <tr data-node-id="2.{{ $x }}.3" data-node-pid="3">
                                                    <th colspan="6">
                                                        {{ $item->trf_name }}
                                                    </th>
                                                    <td>
                                                        {{ number_format($item->total_nom, 0, '', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endfor
                                    @php
                                        $tot_act_d_i = 0;
                                        $tot_act_mtd_i = 0;
                                        $tgt_act_m_i = 0;
                                        $acv_m_i = 0;
                                        $tot_act_ytd_i = 0;
                                        $tgt_act_y_i = 0;
                                        $acv_y_i = 0;
                                        for ($i = 0; $i < $count; $i++) {
                                            $tot_act_d_i = $tot_act_d_i + $income[$i]->actual_nom_date;
                                            $tot_act_mtd_i = $tot_act_mtd_i + $income[$i]->actual_nom_month;
                                            $tgt_act_m_i = $tgt_act_m_i + $target_i_month[$i];
                                            $tot_act_ytd_i = $tot_act_ytd_i + $income[$i]->actual_nom_year;
                                            $tgt_act_y_i = $tgt_act_y_i + $target_i_year[$i];
                                        }
                                    @endphp
                                    @for ($i = 0; $i < 1; $i++)
                                        <tr data-node-id="{{ $erp[$i]->id_link }}">
                                            <th>{{ $erp[$i]->deskripsi }}</th>
                                            <td>{{ number_format($erp[$i]->actual_nominal_date, 0, '', '.') }}</td>
                                            <td>{{ number_format($erp[$i]->actual_nominal_month, 0, '', '.') }}</td>
                                            <td>{{ number_format($target_income_non[$i]->target_mountly, 0, '', '.') }}
                                            </td>
                                            <td>{{ number_format(($erp[$i]->actual_nominal_month / $target_income_non[$i]->target_mountly) * 100, 2, '.', '') }}
                                            </td>
                                            <td>{{ number_format($erp[$i]->actual_nominal_year, 0, '', '.') }}</td>
                                            <td>{{ number_format($target_income_non[$i]->target_yearly, 0, '', '.') }}
                                            </td>
                                            <td>{{ number_format(($erp[$i]->actual_nominal_year / $target_income_non[$i]->target_yearly) * 100, 2, '.', '') }}
                                            </td>
                                        </tr>
                                        @php
                                            $x = 0;
                                        @endphp
                                        <tr data-node-id="{{ $erp[$i]->id_link }}.1"
                                            data-node-pid="{{ $erp[$i]->id_link }}">
                                            <th colspan="6">Detail</th>
                                            <th>Total</th>
                                        </tr>
                                        @php
                                            $x++;
                                            $tot_act_d_i = $tot_act_d_i + $erp[$i]->actual_nominal_date;
                                            $tot_act_mtd_i = $tot_act_mtd_i + $erp[$i]->actual_nominal_month;
                                            $tgt_act_m_i = $tgt_act_m_i + $target_income_non[$i]->target_mountly;
                                            $tot_act_ytd_i = $tot_act_ytd_i + $erp[$i]->actual_nominal_year;
                                            $tgt_act_y_i = $tgt_act_y_i + $target_income_non[$i]->target_yearly;
                                        @endphp
                                        @foreach ($detail_in_not as $item)
                                            <tr data-node-id="{{ $erp[$i]->id_link }}.2"
                                                data-node-pid="{{ $erp[$i]->id_link }}">
                                                <th colspan="6">
                                                    {{ $item->AcDesc }}
                                                </th>
                                                <td>
                                                    {{ number_format($item->actual_nominal_date, 0, '', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endfor
                                    @if ($unit == 1)
                                        @php
                                            $tot_act_d_i = $tot_act_d_i + $naik_candi_act_i;
                                            $tot_act_mtd_i = $tot_act_mtd_i + $naik_candi_mtd_i;
                                            $tgt_act_m_i = $tgt_act_m_i + $tgt_naik_candi_m_i;
                                            $tot_act_ytd_i = $tot_act_ytd_i + $naik_candi_ytd_i;
                                            $tgt_act_y_i = $tgt_act_y_i + $tgt_naik_candi_y_i;
                                            $acv_naik_candi_mtd_i = ($naik_candi_mtd_i / $tgt_naik_candi_m_i) * 100;
                                            $acv_naik_candi_ytd_i = ($naik_candi_ytd_i / $tgt_naik_candi_y_i) * 100;
                                        @endphp
                                        <tr>
                                            <td class="text-start">Naik Candi</td>
                                            <td>{{ number_format($naik_candi_act_i, 0, '', '.') }}</td>
                                            <td>{{ number_format($naik_candi_mtd_i, 0, '', '.') }}</td>
                                            {{-- <td>-</td> --}}
                                            <td>{{ number_format($tgt_naik_candi_m_i, 0, '', '.') }}</td>
                                            <td>{{ number_format($acv_naik_candi_mtd_i, 2, '.', '') }}</td>
                                            <td>{{ number_format($naik_candi_ytd_i, 0, '', '.') }}</td>
                                            {{-- <td>-</td> --}}
                                            <td>{{ number_format($tgt_naik_candi_y_i, 0, '', '.') }}</td>
                                            <td>{{ number_format($acv_naik_candi_ytd_i, 2, '.', '') }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td></td>
                                        <td>{{ number_format($tot_act_d_i, 0, '', '.') }}</td>
                                        <td>{{ number_format($tot_act_mtd_i, 0, '', '.') }}</td>
                                        <td>{{ number_format($tgt_act_m_i, 0, '', '.') }}</td>
                                        <td>{{ number_format(($tot_act_mtd_i / $tgt_act_m_i) * 100, 2, '.', '') }}</td>
                                        <td>{{ number_format($tot_act_ytd_i, 0, '', '.') }}</td>
                                        <td>{{ number_format($tgt_act_y_i, 0, '', '.') }}</td>
                                        <td>{{ number_format(($tot_act_ytd_i / $tgt_act_y_i) * 100, 2, '.', '') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
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
            if (unit == 1) {
                unit = "borobudur"
            } else if (unit == 2) {
                unit = "prambanan"
            } else if (unit == 3) {
                unit = "ratuboko"
            }
            var tgl_start = document.getElementById("dari").value;
            var tgl_end = document.getElementById("sampai").value;
            window.location.href = '/dev/filter/' + unit + '/' + tgl_start + '/' + tgl_end;
        }
    </script>
    <!-- Library Bundle Script -->
    <script src="/assets/js/core/libs.min.js"></script>
    <!-- External Library Bundle Script -->
    <script src="/assets/js/core/external.min.js"></script>
    <!-- App Script -->
    <script src="/assets/js/hope-ui.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="/assets/js/jquery-simple-tree-table.js"></script>
    <script>
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
    </script>
</body>

</html>
