@extends('layout')

@section('title', 'Borobudur')
@section('bdr_link', 'active')
@section('isadmin', 1)
@section('isdash', 0)

@php
    $datetime = date('l jS \of F Y h:i:s A');
@endphp

@section('css')
    <style>
        th,
        td {
            text-align: center;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
@endsection

@section('script')
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>

    @if ($message = Session::get('sukses'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil Simpan!',
            })
        </script>
    @endif
    @if ($message = Session::get('duplikat'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Tahun, Bulan dan Kategori sudah ada.',
            })
        </script>
    @endif
@endsection

@section('main')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="header-title">
                    <div class="row align-middle">
                        <div class="col-sm-6">
                            <a href="#" class=" text-center btn btn-primary btn-icon mt-lg-0 mt-md-0 mt-3"
                                data-bs-toggle="modal" data-bs-target="#modal-new"
                                style="border:none;background: #00A7E6;">
                                <i class="btn-inner">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </i>
                                <span>New</span>
                            </a>
                            <div class="modal fade" id="modal-new" data-bs-backdrop="static"
                                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                style="display: none;" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Tambah Target</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/admin/borobudur" method="post">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="text" class="form-label">Kategori</label>
                                                    <select name="kategori" id="kategori" class="form-control"
                                                        required>
                                                        <option selected disabled value="">--Pilih Kategori--
                                                        </option>
                                                        @foreach ($kat_vol as $item)
                                                            <option @if(old('kategori') == $item->id) selected @endif value="{{ $item->id }}">{{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="text" class="form-label">Bulan</label>
                                                    <input type="month" class="form-control" id="text"
                                                        aria-describedby="text" placeholder="THBL" name="thbl"
                                                        required value="{{old('thbl')}}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="text" class="form-label">Volume</label>
                                                    <input type="number" class="form-control" id="text"
                                                        aria-describedby="text" placeholder="Target" name="target"
                                                        required value="{{old('target')}}">
                                                </div>
                                                <div class="text-end mt-2">
                                                    <button type="submit" class="btn btn-primary"
                                                        style="border:none;background: #00A7E6;">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 d-flex flex-row-reverse">
                            <h4 class="p-1">Target Volume</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table" data-toggle="data-table">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>THBL</th>
                                <th>Volume</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($target_vol as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->thbl }}</td>
                                    <td class="text-end">{{ number_format($item->target, 0, '', '.') }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-icon" data-bs-toggle="tooltip"
                                            href="/admin/borobudur/edit/{{ $item->id }}" aria-label="Edit"
                                            data-bs-original-title="Edit" style="color: #00A7E6;">
                                            <span class="btn-inner">
                                                <svg class="icon-20" width="20" viewBox="0 0 24 24"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341"
                                                        stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z"
                                                        stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M15.1655 4.60254L19.7315 9.16854"
                                                        stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
