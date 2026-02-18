

<?php $__env->startSection('title', 'Appointments'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-base-content">Appointments</h1>
            <p class="text-base-content/60">Manage patient schedules and bookings</p>
        </div>
        <button onclick="appointment_modal.showModal()" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New Appointment
        </button>
    </div>

    <!-- Online Booking Section -->
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body">
            <h2 class="card-title">Online Booking</h2>
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tenant->pricingPlan && $tenant->pricingPlan->hasFeature('Online Booking (QR Code)')): ?>
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="bg-white p-2 rounded-lg border border-base-200">
                        <!-- QR Code using API for demo -->
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo e(urlencode(route('login'))); ?>" alt="Booking QR Code" class="w-32 h-32">
                    </div>
                    <div>
                        <p class="mb-2">Share this QR code or link with your patients to book appointments online.</p>
                        <div class="join w-full max-w-md">
                            <input type="text" value="<?php echo e(route('login')); ?>" class="input input-bordered join-item w-full" readonly>
                            <button class="btn btn-primary join-item" onclick="navigator.clipboard.writeText('<?php echo e(route('login')); ?>'); alert('Link copied!')">Copy</button>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <div class="flex-1">
                        <h3 class="font-bold">Enable Online Booking</h3>
                        <div class="text-xs">Upgrade to Pro or Ultimate plan to enable Online Appointment Booking via QR Code.</div>
                    </div>
                    <button onclick="document.getElementById('subscription_modal').showModal()" class="btn btn-sm">Upgrade Now</button>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="stats shadow bg-base-100">
            <div class="stat">
                <div class="stat-title">Today's Appointments</div>
                <div class="stat-value text-primary"><?php echo e($appointments->where('scheduled_at', '>=', now()->startOfDay())->where('scheduled_at', '<=', now()->endOfDay())->count()); ?></div>
                <div class="stat-desc">For <?php echo e(now()->format('M d, Y')); ?></div>
            </div>
        </div>
        <div class="stats shadow bg-base-100">
            <div class="stat">
                <div class="stat-title">Pending Confirmation</div>
                <div class="stat-value text-warning"><?php echo e($appointments->where('status', 'scheduled')->count()); ?></div>
            </div>
        </div>
        <div class="stats shadow bg-base-100">
            <div class="stat">
                <div class="stat-title">Confirmed</div>
                <div class="stat-value text-success"><?php echo e($appointments->where('status', 'confirmed')->count()); ?></div>
            </div>
        </div>
        <div class="stats shadow bg-base-100">
            <div class="stat">
                <div class="stat-title">Total This Week</div>
                <div class="stat-value"><?php echo e($appointments->where('scheduled_at', '>=', now()->startOfWeek())->where('scheduled_at', '<=', now()->endOfWeek())->count()); ?></div>
            </div>
        </div>
    </div>

    <!-- Appointment List -->
    <div class="card bg-base-100 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Dentist</th>
                        <th>Date & Time</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar placeholder">
                                        <div class="bg-neutral text-neutral-content rounded-full w-8">
                                            <span><?php echo e(substr($appointment->patient->first_name, 0, 1)); ?><?php echo e(substr($appointment->patient->last_name, 0, 1)); ?></span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold"><?php echo e($appointment->patient->full_name); ?></div>
                                        <div class="text-xs opacity-50"><?php echo e($appointment->patient->phone); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo e($appointment->dentist->name); ?></td>
                            <td>
                                <div class="font-medium"><?php echo e(\Carbon\Carbon::parse($appointment->scheduled_at)->format('M d, Y')); ?></div>
                                <div class="text-xs opacity-50"><?php echo e(\Carbon\Carbon::parse($appointment->scheduled_at)->format('h:i A')); ?></div>
                            </td>
                            <td><?php echo e($appointment->reason ?? 'General Checkup'); ?></td>
                            <td>
                                <div class="badge <?php if($appointment->status === 'confirmed'): ?> badge-success <?php elseif($appointment->status === 'cancelled'): ?> badge-error <?php elseif($appointment->status === 'completed'): ?> badge-info <?php else: ?> badge-ghost <?php endif; ?>">
                                    <?php echo e(ucfirst($appointment->status)); ?>

                                </div>
                            </td>
                            <td class="text-right">
                                <div class="dropdown dropdown-left">
                                    <label tabindex="0" class="btn btn-ghost btn-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </label>
                                    <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                                        <li>
                                            <form action="<?php echo e(route('tenant.appointments.update-status', [$tenant->slug, $appointment->id])); ?>" method="POST">
                                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" class="text-success">Confirm</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="<?php echo e(route('tenant.appointments.update-status', [$tenant->slug, $appointment->id])); ?>" method="POST">
                                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="text-info">Mark Completed</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="<?php echo e(route('tenant.appointments.update-status', [$tenant->slug, $appointment->id])); ?>" method="POST">
                                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="text-error">Cancel</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center py-10">
                                <div class="flex flex-col items-center opacity-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p>No appointments found</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Appointment Modal -->
<dialog id="appointment_modal" class="modal">
    <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-lg mb-6">Schedule New Appointment</h3>
        <form action="<?php echo e(route('tenant.appointments.store', $tenant->slug)); ?>" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Patient</span></label>
                    <select name="patient_id" required class="select select-bordered w-full">
                        <option value="">Select Patient</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($patient->id); ?>"><?php echo e($patient->full_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Dentist</span></label>
                    <select name="dentist_id" required class="select select-bordered w-full">
                        <option value="">Select Dentist</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $dentists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dentist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($dentist->id); ?>"><?php echo e($dentist->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Scheduled Date & Time</span></label>
                    <input type="datetime-local" name="scheduled_at" required class="input input-bordered w-full">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Reason for Visit</span></label>
                    <input type="text" name="reason" class="input input-bordered w-full" placeholder="e.g. Tooth Extraction">
                </div>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Notes</span></label>
                <textarea name="notes" class="textarea textarea-bordered h-24" placeholder="Any special instructions..."></textarea>
            </div>
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn btn-ghost">Cancel</button>
                </form>
                <button type="submit" class="btn btn-primary px-8">Schedule Appointment</button>
            </div>
        </form>
    </div>
</dialog>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tenant', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/tenant/appointments/index.blade.php ENDPATH**/ ?>