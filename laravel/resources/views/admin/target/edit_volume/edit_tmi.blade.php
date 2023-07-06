@extends('layout')

@section('title', 'Taman Mini')
@section('tmi_link', 'active')
@section('isadmin', 1)
@section('isdash', 0)

@section('script')
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
                <h4>Edit Target</h4>
            </div>
        </div>
        <div class="card-body">
            <form action="/admin/tamanmini/edit/{{ $target->id }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="text" class="form-label">Kategori</label>
                    <select name="kategori" id="kategori" class="form-control"
                        required>
                        <option selected disabled value="">--Pilih Kategori--
                        </option>
                        @foreach ($kat_vol as $item)
                            <option @if($target->id_category == $item->id) selected @endif value="{{ $item->id }}">{{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="text" class="form-label">Bulan</label>
                    <input type="month" class="form-control" id="text"
                        aria-describedby="text" placeholder="THBL" name="thbl"
                        required value="{{$target->thbl}}">
                </div>
                <div class="form-group">
                    <label for="text" class="form-label">Volume</label>
                    <input type="number" class="form-control" id="text"
                        aria-describedby="text" placeholder="Target" name="target"
                        required value="{{$target->target}}">
                </div>
                <div class="text-end mt-2">
                    <button type="submit" class="btn btn-primary"
                        style="border:none;background: #00A7E6;">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection