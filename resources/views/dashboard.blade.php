@extends('layouts.base')
@section('APP_TITLE')
    Dashboard
@endsection
@section('active_home')
    active
@endsection
@section('APP_CONTENT')
    @php
        $userRole = Auth::user()->user_role; // Get current user role
        $isOwner = $userRole === 'Owner';
        $isFrontDeskHotel = $userRole === 'Front Desk - Hotel';
        $isFrontDeskResort = $userRole === 'Front Desk - Resort';
    @endphp

    <div class="row">
        <div class="col-md-4">
            <label>Type:</label>
            <select id="viewType" class="form-control">
                @if ($isOwner)
                    <option value="hotel">Hotel</option>
                    <option value="resort">Resort</option>
                @elseif($isFrontDeskHotel)
                    <option value="hotel" selected>Hotel</option>
                @elseif($isFrontDeskResort)
                    <option value="resort" selected>Resort</option>
                @endif
            </select>
        </div>
        <div class="col-md-4">
            <label>Category:</label>
            <select id="categoryFilter" class="form-control">
                <option value="">All</option>
            </select>
        </div>
        <div class="col-md-4">
            <label>Date Range:</label>
            <select id="dateRange" class="form-control">
                <option value="7">Last 7 Days</option>
                <option value="30" selected>Last 30 Days</option>
                <option value="90">Last 90 Days</option>
            </select>
        </div>
    </div>

    <canvas id="viewsChart" width="400" height="200"></canvas>
@endsection

@section('APP_SCRIPT')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            let chartInstance;
            const ctx = $("#viewsChart")[0].getContext("2d");

            // Get the user role from Laravel
            const userRole = @json(Auth::user()->user_role);

            // If user is not Owner, disable the viewType dropdown
            if (userRole === "Front Desk - Hotel" || userRole === "Front Desk - Resort") {
                $("#viewType").prop("disabled", true);
            }

            // Initialize Select2
            $("#viewType, #categoryFilter").select2();

            function loadCategories() {
                const type = $("#viewType").val();

                $.get(`/api/${type}-types`, function(data) {
                    let options = `<option value="">All</option>`;
                    $.each(data, function(index, item) {
                        options += `<option value="${item.type_name}">${item.type_name}</option>`;
                    });

                    $("#categoryFilter").html(options).trigger("change");
                }).fail(function() {
                    console.error("Failed to load categories.");
                });
            }

            function fetchChartData() {
                const type = $("#viewType").val();
                const category = $("#categoryFilter").val();
                const date = $("#dateRange").val();

                $.get(`/analytics/views`, {
                    type,
                    category,
                    date_range: date
                }, function(data) {
                    if (!data.length) {
                        console.warn("No data found for the selected filters.");
                        return;
                    }

                    const labels = data.map(item => item.room_name || item.cottage_name);
                    const viewCounts = data.map(item => item.views_count);

                    if (chartInstance) chartInstance.destroy();

                    chartInstance = new Chart(ctx, {
                        type: "bar",
                        data: {
                            labels: labels,
                            datasets: [{
                                label: `${type.charAt(0).toUpperCase() + type.slice(1)} Views`,
                                data: viewCounts,
                                backgroundColor: "rgba(75, 192, 192, 0.5)",
                                borderColor: "rgba(75, 192, 192, 1)",
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }).fail(function(xhr) {
                    console.error("Failed to fetch chart data.", xhr.responseText);
                });
            }

            // Event Listeners
            $("#viewType").change(function() {
                loadCategories();
                fetchChartData();
            });

            $("#categoryFilter, #dateRange").change(fetchChartData);

            // Initial Load
            loadCategories();
            fetchChartData();
        });
    </script>
@endsection
