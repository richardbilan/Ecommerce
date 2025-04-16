@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1 class="text-danger">Payment Failed</h1>
    <p>Unfortunately, your payment could not be processed. Please try again or contact support if the issue persists.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Return to Home</a>
</div>
@endsection
