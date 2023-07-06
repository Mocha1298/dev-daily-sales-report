@extends('layout')


@section('title', 'Teater Pentas')
@section('tnp_link', 'active')
@section('isadmin', 0)
@section('isdash', 0)

@php
    $datetime = date('l jS \of F Y h:i:s A');
@endphp

@section('main')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Daily Report Pengguna Jasa</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic" class="table mb-0" role="grid">
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
                        @for ($i = 0; $i <= 3; $i++)
                            <tr data-node-id="{{ $tabel_income[$i]['id_link'] }}">
                                <td class="text-start">{{ $tabel_pengguna_jasa[$i]['nama'] }}</td>
                                <td>{{ number_format($tabel_pengguna_jasa[$i]['aktual_d'], 0, '', '.') }}</td>
                                <td>{{ number_format($tabel_pengguna_jasa[$i]['aktual_m'], 0, '', '.') }}</td>
                                <td>{{ number_format($tabel_pengguna_jasa[$i]['target_m'], 0, '', '.') }}</td>
                                <td>{{ number_format($tabel_pengguna_jasa[$i]['persen_m'], 2, '.', '') }}</td>
                                <td>{{ number_format($tabel_pengguna_jasa[$i]['aktual_y'], 0, '', '.') }}</td>
                                <td>{{ number_format($tabel_pengguna_jasa[$i]['target_y'], 0, '', '.') }}</td>
                                <td>{{ number_format($tabel_pengguna_jasa[$i]['persen_y'], 2, '.', '') }}</td>
                            </tr>
                            @if ($tabel_pengguna_jasa[$i]['id_link'] == 1)
                                <tr data-node-pid="1">
                                    <th colspan="5">Detail</th>
                                    <th colspan="3" class="text-center">Total</th>
                                </tr>
                                @foreach ($detail_pj_oa as $item)
                                    <tr data-node-pid="1">
                                        <th colspan="5">
                                            {{ $item->trf_name }}
                                        </th>
                                        <td colspan="3" class="text-center">
                                            {{ number_format($item->total_trx, 0, '', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @elseif($tabel_pengguna_jasa[$i]['id_link'] == 2)
                                <tr data-node-pid="2">
                                    <th colspan="5">Detail</th>
                                    <th colspan="3" class="text-center">Total</th>
                                </tr>
                                @foreach ($detail_pj_rt as $item)
                                    <tr data-node-pid="2">
                                        <th colspan="5">
                                            {{ $item->trf_name }}
                                        </th>
                                        <td colspan="3" class="text-center">
                                            {{ number_format($item->total_trx, 0, '', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @elseif($tabel_pengguna_jasa[$i]['id_link'] == 3)
                                <tr data-node-pid="3">
                                    <th colspan="5">Detail</th>
                                    <th colspan="3" class="text-center">Total</th>
                                </tr>
                                @foreach ($detail_pj_rj as $item)
                                    <tr data-node-pid="3">
                                        <th colspan="5">
                                            {{ $item->trf_name }}
                                        </th>
                                        <td colspan="3" class="text-center">
                                            {{ number_format($item->total_trx, 0, '', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @elseif($tabel_pengguna_jasa[$i]['id_link'] == 4)
                                <tr data-node-pid="4">
                                    <th colspan="5">Detail</th>
                                    <th colspan="3" class="text-center">Total</th>
                                </tr>
                                @foreach ($detail_pj_pp as $item)
                                    <tr data-node-pid="4">
                                        <th colspan="5">
                                            {{ $item->trf_name }}
                                        </th>
                                        <td colspan="3" class="text-center">
                                            {{ number_format($item->total_trx, 0, '', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endfor
                        <tr>
                            <td></td>
                            <td>{{ number_format($total_pengguna_jasa[0]['aktual_d'], 0, '', '.') }}</td>
                            <td>{{ number_format($total_pengguna_jasa[0]['aktual_m'], 0, '', '.') }}</td>
                            <td>{{ number_format($total_pengguna_jasa[0]['target_m'], 0, '', '.') }}</td>
                            <td>{{ number_format($total_pengguna_jasa[0]['persen_m'], 2, '.', '') }}</td>
                            <td>{{ number_format($total_pengguna_jasa[0]['aktual_y'], 0, '', '.') }}</td>
                            <td>{{ number_format($total_pengguna_jasa[0]['target_y'], 0, '', '.') }}</td>
                            <td>{{ number_format($total_pengguna_jasa[0]['persen_y'], 2, '.', '') }}</td>
                        </tr>
                    </table>
                </div>
                <p style="margin-left: 20px; font-size: 12px">Data generated at: {{ $datetime }}</p>
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
                    <table id="basic2" class="table mb-0" role="grid">
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
                        @for ($i = 0; $i <= 3; $i++)
                            <tr data-node-id="2.{{ $tabel_income[$i]['id_link'] }}">
                                <td class="text-start">{{ $tabel_income[$i]['nama'] }}</td>
                                <td>{{ number_format($tabel_income[$i]['aktual_d'], 0, '', '.') }}</td>
                                <td>{{ number_format($tabel_income[$i]['aktual_m'], 0, '', '.') }}</td>
                                <td>{{ number_format($tabel_income[$i]['target_m'], 0, '', '.') }}</td>
                                <td>{{ number_format($tabel_income[$i]['persen_m'], 2, '.', '') }}</td>
                                <td>{{ number_format($tabel_income[$i]['aktual_y'], 0, '', '.') }}</td>
                                <td>{{ number_format($tabel_income[$i]['target_y'], 0, '', '.') }}</td>
                                <td>{{ number_format($tabel_income[$i]['persen_y'], 2, '.', '') }}</td>
                            </tr>
                            @if ($tabel_income[$i]['id_link'] == 1)
                                <tr data-node-pid="2.1">
                                    <th colspan="5">Detail</th>
                                    <th colspan="3" class="text-center">Total</th>
                                </tr>
                                @foreach ($detail_in_oa as $item)
                                    <tr data-node-pid="2.1">
                                        <th colspan="5">
                                            {{ $item->trf_name }}
                                        </th>
                                        <td colspan="3" class="text-center">
                                            {{ number_format($item->total_nom, 0, '', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @elseif($tabel_income[$i]['id_link'] == 2)
                                <tr data-node-pid="2.2">
                                    <th colspan="5">Detail</th>
                                    <th colspan="3" class="text-center">Total</th>
                                </tr>
                                @foreach ($detail_in_rt as $item)
                                    <tr data-node-pid="2.2">
                                        <th colspan="5">
                                            {{ $item->trf_name }}
                                        </th>
                                        <td colspan="3" class="text-center">
                                            {{ number_format($item->total_nom, 0, '', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @elseif($tabel_income[$i]['id_link'] == 3)
                                <tr data-node-pid="2.3">
                                    <th colspan="5">Detail</th>
                                    <th colspan="3" class="text-center">Total</th>
                                </tr>
                                @foreach ($detail_in_rj as $item)
                                    <tr data-node-pid="2.3">
                                        <th colspan="5">
                                            {{ $item->trf_name }}
                                        </th>
                                        <td colspan="3" class="text-center">
                                            {{ number_format($item->total_nom, 0, '', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @elseif($tabel_income[$i]['id_link'] == 4)
                                <tr data-node-pid="2.4">
                                    <th colspan="5">Detail</th>
                                    <th colspan="3" class="text-center">Total</th>
                                </tr>
                                @foreach ($detail_in_pp as $item)
                                    <tr data-node-pid="2.4">
                                        <th colspan="5">
                                            {{ $item->trf_name }}
                                        </th>
                                        <td colspan="3" class="text-center">
                                            {{ number_format($item->total_nom, 0, '', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @elseif($tabel_income[$i]['id_link'] == 5)
                                <tr data-node-pid="2.4">
                                    <th colspan="5">Detail</th>
                                    <th colspan="3" class="text-center">Total</th>
                                </tr>
                                @foreach ($detailnonpaket as $item)
                                    <tr data-node-pid="2.4">
                                        <th colspan="5">
                                            {{ $item->trf_name }}
                                        </th>
                                        <td colspan="3" class="text-center">
                                            {{ number_format($item->total_nom, 0, '', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endfor
                        <tr>
                            <td></td>
                            <td>{{ number_format($total_income[0]['aktual_d'], 0, '', '.') }}</td>
                            <td>{{ number_format($total_income[0]['aktual_m'], 0, '', '.') }}</td>
                            <td>{{ number_format($total_income[0]['target_m'], 0, '', '.') }}</td>
                            <td>{{ number_format($total_income[0]['persen_m'], 2, '.', '') }}</td>
                            <td>{{ number_format($total_income[0]['aktual_y'], 0, '', '.') }}</td>
                            <td>{{ number_format($total_income[0]['target_y'], 0, '', '.') }}</td>
                            <td>{{ number_format($total_income[0]['persen_y'], 2, '.', '') }}</td>
                        </tr>
                    </table>
                </div>
                <p style="margin-left: 20px; font-size: 12px">Data generated at: {{ $datetime }}</p>
            </div>
        </div>
    </div>
@endsection
