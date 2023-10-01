@extends('layouts.main')

@section('content')
    <div class="col-md-10 mb-4 fw-bolder">
        <p class="text-accent mb-1">{{ auth()->user()->departemen->nm_departemen }}</p>
        <div class="fs-6">Welcome, {{ auth()->user()->nm_user }}</div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="lead fw-semibold mb-4">Sample Page</div>
            <p class="mb-0">This is a sample pagee </p>

            <div class="row">
                <div class="col-md-5">
                    @component('components.chart')
                        @slot('chartId')
                            sampleChart
                        @endslot
                    @endcomponent
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-5">
                    @component('components.chart')
                        @slot('chartId')
                            sampleChart2
                        @endslot
                    @endcomponent
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script nonce="{{ $nonce }}">
        $(document).ready(function() {
            generateChart('#sampleChart', @json($barChartData))
            generateChart('#sampleChart2', @json($pieChartData))
        });
    </script>
@endsection
