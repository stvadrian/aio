<form action="/{{ $rootname }}/export/excel" class="form-horizontal" id="form-excel" method="post" target="_blank">
    @csrf
    <input type="hidden" name="excel_name" id="excel_name">
    @if (session('excel_data'))
        <input type="hidden" name="data" value="{{ session('excel_data') }}">
    @else
        <input type="hidden" name="data" value="{{ $excel_data }}">
    @endif
    <button type="submit" class="btn btn-alt-success">Export Excel</button>
</form>
