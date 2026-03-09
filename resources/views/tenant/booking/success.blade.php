@extends('layouts.guest', ['title' => 'Booking Successful'])

@section('content')
<div class="flex items-center justify-center min-h-screen p-4">
    <div class="card w-full max-w-md bg-base-100 shadow-xl overflow-hidden">
        <div class="card-body items-center text-center p-10">
            <div class="w-20 h-20 bg-success/20 text-success rounded-full flex items-center justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            
            <h2 class="card-title text-2xl font-bold mb-2">Request Submitted!</h2>
            <p class="text-base-content/70 mb-8">
                {{ session('success_message') ?? 'Your appointment request has been sent to ' . $tenant->name . '. We will contact you shortly to confirm your schedule.' }}
            </p>

            <div class="divider"></div>

            <div class="space-y-4 w-full">
                <p class="text-sm font-medium">Clinic Information:</p>
                <div class="bg-base-200 p-4 rounded-lg text-sm text-left">
                    <p class="font-bold">{{ $tenant->name }}</p>
                    <p>{{ $tenant->address }}</p>
                    @if($tenant->phone)
                        <p class="mt-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            {{ $tenant->phone }}
                        </p>
                    @endif
                </div>

                <a href="http://{{ $tenant->slug }}.{{ env('LOCAL_BASE_DOMAIN', 'dcmsapp.local') }}/book" class="btn btn-outline btn-block mt-4">
                    Book Another Appointment
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
