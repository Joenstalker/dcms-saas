<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Tenant;
use App\Models\User;
use App\Rules\Recaptcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class GuestBookingController extends Controller
{
    /**
     * Show the public booking form.
     */
    public function show(Tenant $tenant): View
    {
        // Check if tenant has the feature
        if (!$tenant->hasFeature('Online Booking (QR Code)')) {
            abort(403, 'Online booking is not enabled for this clinic.');
        }

        $services = Service::where('is_active', true)->get();
        $dentists = User::where('tenant_id', $tenant->id)
            ->where('role', User::ROLE_DENTIST)
            ->where('status', 'active')
            ->get();

        return view('tenant.booking.show', compact('tenant', 'services', 'dentists'));
    }

    /**
     * Process the guest booking request.
     */
    public function store(Request $request, Tenant $tenant)
    {
        // Rate limiting is handled in the route definition
        
        $limitService = app(\App\Services\CheckPlanLimits::class);
        if ($limitService->hasReachedPatientLimit($tenant)) {
             return response()->json([
                'success' => false,
                'error' => 'This clinic has reached its patient limit for the current plan. Please contact the clinic directly.',
                'upgrade_required' => true,
            ], 403);
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'service_id' => 'required|string', // MongoDB ID
            'dentist_id' => 'nullable|string',
            'scheduled_at' => 'required|date|after:now',
            'notes' => 'nullable|string|max:1000',
            'recaptcha_token' => ['required', new Recaptcha],
        ]);

        try {
            // 1. Find or create patient
            $patient = Patient::where('tenant_id', $tenant->id)
                ->where(function ($query) use ($request) {
                    $query->where('email', $request->email)
                          ->orWhere('phone', $request->phone);
                })
                ->first();

            if (!$patient) {
                $patient = Patient::create([
                    'tenant_id' => $tenant->id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                ]);
            }

            // 2. Create appointment
            Appointment::create([
                'tenant_id' => $tenant->id,
                'patient_id' => $patient->id,
                'service_id' => $request->service_id,
                'dentist_id' => $request->dentist_id,
                'scheduled_at' => $request->scheduled_at,
                'status' => 'pending',
                'notes' => $request->notes,
                'is_guest_booking' => true,
            ]);

            return redirect()->route('tenant.booking.success', $tenant->slug)
                ->with('success_message', 'Your appointment request has been submitted successfully!');

        } catch (\Exception $e) {
            Log::error('Guest booking failed', [
                'tenant_id' => $tenant->id,
                'error' => $e->getMessage()
            ]);

            return back()->withInput()->with('error', 'Something went wrong. Please try again later.');
        }
    }

    /**
     * Show success page.
     */
    public function success(Tenant $tenant): View
    {
        return view('tenant.booking.success', compact('tenant'));
    }
}
