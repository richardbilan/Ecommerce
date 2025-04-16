@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <h1 class="text-success">Payment Successful!</h1>
    <p>Thank you for your purchase. Your payment has been processed successfully.</p>
    <a href="{{ route('home') }}" class="btn btn-primary mt-3">Return to Home</a>
</div>
@endsection