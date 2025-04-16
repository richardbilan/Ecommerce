@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1>Payment Cancelled</h1>
    <p>Your payment has been cancelled. If you have any questions, please contact our support team.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Return to Home</a>
</div>
@endsection
