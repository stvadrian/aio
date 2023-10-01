<form action="" class="form-horizontal" id="form-tgl" method="post">
    @csrf
    <div class="form-group row mt-1">
        <label for="date_s" class="col-md-4 col-form-label">Start</label>
        <label for="date_e" class="col-md-4 col-form-label">End</label>
    </div>
    <div class="form-group row mb-3">
        <div class="col-md-4">
            <input type="date" name="date_s" id="date_s"
                class="form-control @error('date_s') is-invalid @enderror"
                value="{{ session('date_s') ? session()->get('date_s') : date('Y-m-d') }}" required>
        </div>
        <div class="col-md-4">
            <input type="date" name="date_e" id="date_e"
                class="form-control @error('date_e') is-invalid @enderror"
                value="{{ session('date_e') ? session()->get('date_e') : date('Y-m-d') }}" required>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Find</button>
        </div>
    </div>
</form>
