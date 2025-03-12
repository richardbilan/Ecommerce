@extends('layouts.sidebar')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="card-header"> <h1>ADMIN DASHBOARD</h1> </div>
                    <link rel="stylesheet" href="{{ asset('css/style_dashboard.css') }}">
                    <div class="summary">
                        <div class="card">Today's Sales <br> <span>P 1680</span></div>
                        <div class="card">Total Orders Today <br> <span>30</span></div>
                        <div class="card">Pending Orders <br> <span>5</span></div>
                        <div class="card">Complete Orders Today <br> <span>20</span></div>
                        <div class="card">Delivery Orders Today <br> <span>12</span></div>
                    </div>
                    <div class="charts">
                        <div class="chart">Sales Growth - Daily for the Week</div>
                        <div class="chart">Sales Growth - Daily for the Week (Americano)</div>
                    </div>
                    <div class="best-selling">
                        <div class="card">Best-Selling Item <br> <span>Americano</span></div>
                        <div class="card">Least-Selling Item <br> <span>Espresso</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
