@extends('layouts.app')

@section('content')
    <div class="login-container">
        <!-- Left Column (Image) -->
        <div class="left-column"></div>

        <!-- Right Column (Form) -->
        <div class="right-column">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <h2 class="text-center mb-4">Login</h2>

                <!-- Email Field -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" required autofocus>
                </div>

                <!-- Password Field -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100">Submit</button>

                <!-- Forgot Password Link -->
                <div class="mt-3 text-center">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-muted">Forgot Your Password?</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
