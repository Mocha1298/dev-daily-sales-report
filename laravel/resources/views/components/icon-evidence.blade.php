@if ($master->dokumen == true)
<a style="border:none;background: #00A7E6;color:white"
    class="btn btn-sm btn-icon" data-bs-toggle="tooltip"
    data-bs-placement="top" title="Dokumen" href="#">
    D
</a>
@endif
@if ($master->kuesioner == true)
<a style="border:none;background: #00A7E6;color:white"
    class="btn btn-sm btn-icon" data-bs-toggle="tooltip"
    data-bs-placement="top" title="Kuesioner" href="#">
    K
</a>
@endif
@if ($master->wawancara == true)
<a style="border:none;background: #00A7E6;color:white"
    class="btn btn-sm btn-icon" data-bs-toggle="tooltip"
    data-bs-placement="top" title="Wawancara" href="#">
    W
</a>
@endif
@if ($master->observasi == true)
<a style="border:none;background: #00A7E6;color:white"
    class="btn btn-sm btn-icon" data-bs-toggle="tooltip"
    data-bs-placement="top" title="Observasi" href="#">
    O
</a>
@endif