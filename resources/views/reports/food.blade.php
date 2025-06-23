@extends('layouts.base')
@section('APP_TITLE')
    Food Reports
@endsection
@section('active_reports')
    active
@endsection
@section('APP_CONTENT')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Food Transaction Reports</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('food.reports.index') }}">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select name="report_type" id="reportType" class="form-control">
                                    <option value="daily" {{ $reportType == 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="monthly" {{ $reportType == 'monthly' ? 'selected' : '' }}>Monthly
                                    </option>
                                    <option value="yearly" {{ $reportType == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                </select>
                            </div>
                            <div class="col-md-3" id="monthFilter"
                                style="display: {{ $reportType == 'monthly' ? 'block' : 'none' }}">
                                <select name="month" class="form-control">
                                    @foreach (range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3" id="yearFilter"
                                style="display: {{ $reportType == 'yearly' || $reportType == 'monthly' ? 'block' : 'none' }}">
                                <select name="year" class="form-control">
                                    @foreach ($availableYears as $y)
                                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                            {{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Generate Report</button>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Transaction Count</th>
                                <th>Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($report->date)->format('Y-m-d') }}</td>
                                    <td>{{ $report->transaction_count }}</td>
                                    <td>{{ number_format($report->total_revenue, 2) }}</td>
                                </tr>
                            @endforeach
                            @if ($reports->isEmpty())
                                <tr>
                                    <td colspan="3" class="text-center">No data available</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('APP_SCRIPT')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#reportType').change(function() {
                var reportType = $(this).val();
                if (reportType === 'monthly') {
                    $('#monthFilter').show();
                    $('#yearFilter').show();
                } else if (reportType === 'yearly') {
                    $('#monthFilter').hide();
                    $('#yearFilter').show();
                } else {
                    $('#monthFilter').hide();
                    $('#yearFilter').hide();
                }
            });
        });
    </script>
@endsection
