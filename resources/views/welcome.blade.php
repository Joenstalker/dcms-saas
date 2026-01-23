@extends('layouts.app')

@section('content')
<div class="hero min-h-screen bg-base-100">
    <div class="hero-content text-center">
        <div class="max-w-md">
            <h1 class="mb-5 text-5xl font-bold">Himo natag Multi-Tenant Dental Clinic Management System</h1>
            <p class="mb-5 text-xl">
                Dental Clinic Management App<br>
                Made by BSIT Students, for every Filipino Dentist.
            </p>
            <a href="{{ route('tenant.registration.index') }}" class="btn btn-primary btn-lg">GET STARTED</a>
            <p class="mt-4 text-sm text-base-content/70">The app is now live!</p>
        </div>
    </div>
</div>
@endsection
