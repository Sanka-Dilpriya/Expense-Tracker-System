@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-4 col-lg-5 col-md-7">
        <div class="card card-hero p-4">
            <div class="card-body">
                <h2 class="h4 mb-3">Welcome back</h2>
                <p class="text-secondary mb-4">Login to manage your expenses and view professional reports.</p>

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button class="btn btn-primary w-100 py-2">Sign In</button>
                </form>

                <p class="text-center text-muted mt-4 mb-0">Don't have an account? <a href="{{ route('register') }}">Create one</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
