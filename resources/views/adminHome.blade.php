@extends('layouts.sidebar')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card p-4">

                <!-- Header -->
                <div class="card-header text-center mb-4">
                    <h1>ADMIN DASHBOARD</h1>
                </div>

                <!-- Custom CSS -->
                <link rel="stylesheet" href="{{ asset('css/style_dashboard.css') }}">

                <!-- Status Alert -->
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Summary Section -->
                <div class="summary d-flex flex-wrap gap-3 mb-4">
                    <div class="card flex-fill p-3">Today's Sales<br><span>P 1,680</span></div>
                    <div class="card flex-fill p-3">Total Orders Today<br><span>30</span></div>
                    <div class="card flex-fill p-3">Pending Orders<br><span>5</span></div>
                    <div class="card flex-fill p-3">Completed Orders Today<br><span>20</span></div>
                    <div class="card flex-fill p-3">Delivery Orders Today<br><span>12</span></div>
                </div>

                <!-- Charts Section -->
                <div class="charts d-flex flex-wrap gap-4 mb-4">
                    <!-- Daily Sales Chart -->
                    <div class="chart p-3 bg-white rounded shadow-sm flex-fill">
                        <h5 class="mb-3">Sales Growth - Daily for the Week</h5>
                        <canvas id="dailySalesChart" height="200"></canvas>
                    </div>

                    <!-- Americano Sales Chart -->
                    <div class="chart p-3 bg-white rounded shadow-sm flex-fill">
                        <h5 class="mb-3">Sales Growth - Americano (Daily)</h5>
                        <canvas id="americanoSalesChart" height="200"></canvas>
                    </div>

                    <!-- Product Performance Chart -->
                    <div class="chart p-3 bg-white rounded shadow-sm flex-fill">
                        <h5 class="mb-3">Product Performance</h5>
                        <canvas id="productPerformanceChart" height="200"></canvas>
                    </div>

                    <!-- Monthly Revenue Trend Chart -->
                    <div class="chart p-3 bg-white rounded shadow-sm flex-fill">
                        <h5 class="mb-3">Monthly Revenue Trend</h5>
                        <canvas id="monthlyRevenueChart" height="200"></canvas>
                    </div>
                </div>

                <!-- Printable Report Section -->
                <div class="result-summary bg-white p-4 mb-4 shadow-sm rounded">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4>Summary Report</h4>
                        <button onclick="window.print()" class="btn btn-outline-primary">
                            🖨️ Print Report
                        </button>
                    </div>

                    <p><strong>Date:</strong> {{ date('F d, Y') }}</p>
                    <hr>

                    <h5>Sales Overview</h5>
                    <ul class="list-unstyled mb-3">
                        <li>✅ <strong>Total Sales Today:</strong> ₱1,680</li>
                        <li>✅ <strong>Total Orders Today:</strong> 30</li>
                        <li>✅ <strong>Completed Orders:</strong> 20</li>
                        <li>✅ <strong>Pending Orders:</strong> 5</li>
                        <li>✅ <strong>Deliveries Scheduled Today:</strong> 12</li>
                    </ul>

                    <h5>Product Performance</h5>
                    <ul class="list-unstyled mb-3">
                        <li>🏆 <strong>Best-Selling Item:</strong> Americano (120 units sold)</li>
                        <li>⚠️ <strong>Least-Selling Item:</strong> Espresso (10 units sold)</li>
                    </ul>

                    <h5>Revenue Insight</h5>
                    <ul class="list-unstyled">
                        <li>📈 <strong>Highest Daily Sales Recorded:</strong> ₱1,680</li>
                        <li>📊 <strong>Monthly Revenue Trend:</strong> ₱25,000 in July</li>
                    </ul>
                </div>

                <!-- Best-Selling & Least-Selling Items (Visual Section) -->
                <div class="best-selling-section d-flex flex-wrap gap-3 mb-4">
                    <div class="card flex-fill p-3 bg-light">
                        <h5 class="mb-2">Best-Selling Item</h5>
                        <span class="fs-4 fw-bold text-success">Americano</span><br>
                        <small>Sold: 120 units</small>
                    </div>
                    <div class="card flex-fill p-3 bg-light">
                        <h5 class="mb-2">Least-Selling Item</h5>
                        <span class="fs-4 fw-bold text-danger">Espresso</span><br>
                        <small>Sold: 10 units</small>
                    </div>
                </div>

                <!-- Records Section -->
                <div class="records-section mt-4">
                    <h4 class="mb-3">Records</h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Record Name</th>
                                    <th>Details</th>
                                    <th>Last Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Best-Selling Product</td>
                                    <td>Americano - 120 sold</td>
                                    <td>Today</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Most Active Customer</td>
                                    <td>John Doe - 5 orders</td>
                                    <td>Today</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Highest Daily Sales</td>
                                    <td>₱ 1,680</td>
                                    <td>Today</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Pending Deliveries</td>
                                    <td>5 Orders</td>
                                    <td>Today</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Chart Configurations -->
<script>
    // Daily Sales Chart
    const ctxDailySales = document.getElementById('dailySalesChart').getContext('2d');
    new Chart(ctxDailySales, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Daily Sales (₱)',
                data: [500, 700, 800, 600, 900, 1200, 1680],
                borderColor: '#4CAF50',
                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });

    // Americano Sales Bar Chart
    const ctxAmericanoSales = document.getElementById('americanoSalesChart').getContext('2d');
    new Chart(ctxAmericanoSales, {
        type: 'bar',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Americano Sales',
                data: [10, 15, 8, 12, 20, 25, 30],
                backgroundColor: '#FF6384'
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });

    // Product Performance Pie Chart
    const ctxProductPerformance = document.getElementById('productPerformanceChart').getContext('2d');
    new Chart(ctxProductPerformance, {
        type: 'pie',
        data: {
            labels: ['Americano', 'Latte', 'Espresso', 'Cappuccino'],
            datasets: [{
                label: 'Sales Distribution',
                data: [30, 25, 10, 20],
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
            }]
        },
        options: { responsive: true }
    });

    // Monthly Revenue Trend Line Chart
    const ctxMonthlyRevenue = document.getElementById('monthlyRevenueChart').getContext('2d');
    new Chart(ctxMonthlyRevenue, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'Monthly Revenue (₱)',
                data: [10000, 15000, 13000, 17000, 20000, 22000, 25000],
                borderColor: '#FF9F40',
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endsection
