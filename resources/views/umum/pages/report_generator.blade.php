@extends('layouts.main')

@section('content-heading')
    @if (auth()->user()->hak_akses == '3')
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal_add_report">
            <em class="si si-plus"></em> Add New Report
        </button>
    @endif
@endsection

@section('content')
    @if (auth()->user()->hak_akses == '3')
        {{-- MODAL ADD REPORT  --}}
        <div class="modal" id="modal_add_report" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="block block-rounded block-transparent mb-0">
                        <form action="" method="post" id="form_add_report">
                            @csrf
                            <div class="block-header block-header-default">
                                <h3 class="block-title">Add New Report</h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                        <i class="fa fa-fw fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="block-content fs-sm">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="report_name">Report Name</label>
                                    <input type="text" name="report_name" id="report_name" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label" for="report_qry">Report Query</label>
                                    <input type="text" name="report_qry" id="report_qry" class="form-control">
                                    <span class="text-danger">Replace date with <strong>$tgl1</strong> and
                                        <strong>$tgl2</strong></span>
                                </div>
                            </div>
                            <div class="block-content block-content-full text-end bg-body">
                                <input type="hidden" name="add_report">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="block-header block-header-default">
        <h3 class="block-title">
            Report Generator <small><em>Beta</em></small>
        </h3>
    </div>
    <div class="block-content">
        <form action="" method="POST" id="form-report" class="no-loader">
            @csrf
            <div class="form-group row mt-1">
                <label for="date_m" class="col-md-5 col-form-label">{{ __('contents.tgl_mulai') }}</label>
                <label for="date_s" class="col-md-5 col-form-label">{{ __('contents.tgl_selesai') }}</label>
            </div>
            <div class="form-group row mb-3">
                <div class="col-md-5">
                    <input type="date" name="date_s" id="date_s"
                        class="form-control @error('date_s') is-invalid @enderror"
                        value="{{ session('date_s') ? session()->get('date_s') : date('Y-m-d') }}" required>
                </div>
                <div class="col-md-5">
                    <input type="date" name="date_e" id="date_e"
                        class="form-control @error('date_e') is-invalid @enderror"
                        value="{{ session('date_e') ? session()->get('date_e') : date('Y-m-d') }}" required>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="left-options" class="col-form-label">Report List</label>
                <select name="selected_report" id="left-options" class="form-control select2bs4" data-parent="">
                    @foreach ($reports as $report)
                        <option value="{{ $report->id }}">{{ $report->report_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary" name="view">Preview</button>
                <button type="submit" class="btn btn-alt-success" name="generate">Generate Excel</button>
            </div>
        </form>

        <div class="table-responsive">
            @if (session('data'))
                <table id="DT-responsive-with-btn" class="table table-bordered table-hover table-vcenter fs-sm mt-3">
                    <caption></caption>
                    <thead class="table-primary">
                        @foreach (session('data') as $data_column)
                            @foreach ($data_column as $column_name => $column_value)
                                <th>{{ $column_name }}</th>
                            @endforeach
                        @endforeach
                    </thead>
                    <tbody>
                        @foreach (session('data') as $data_column)
                            <tr>
                                @foreach ($data_column as $column_name => $column_value)
                                    <td>{{ $column_value }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>


    </div>
@endsection

@section('scripts')
    <script nonce="{{ $nonce }}">
        $(document).ready(function() {
            $("#toRight").click(function() {
                var $container = $(this).closest(".container");
                var $leftComboBox = $('#left-options');
                var $rightComboBox = $('#right-options')
                var selectedOptions = $leftComboBox.find("option:selected");

                selectedOptions.each(function() {
                    var value = $(this).val();
                    var text = $(this).text();

                    $rightComboBox.append("<option value='" + value + "'>" + text + "</option>");
                    $(this).remove();
                });
            });

            $("#toLeft").click(function() {
                var $container = $(this).closest(".container");
                var $leftComboBox = $('#left-options');
                var $rightComboBox = $('#right-options')
                var selectedOptions = $rightComboBox.find("option:selected");

                selectedOptions.each(function() {
                    var value = $(this).val();
                    var text = $(this).text();

                    $leftComboBox.append("<option value='" + value + "'>" + text + "</option>");
                    $(this).remove();
                });
            });

            validateFormAlert('form_add_report');
        });
    </script>
@endsection
