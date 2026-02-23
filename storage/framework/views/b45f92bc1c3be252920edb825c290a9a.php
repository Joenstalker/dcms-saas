<div class="flex items-center justify-between mb-6">
    <h3 class="text-lg font-bold flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z" />
        </svg>
        Patient Appointments
    </h3>
</div>

<div role="tablist" class="tabs tabs-lifted tabs-sm mb-6">
    <input type="radio" name="appt_tabs" role="tab" class="tab" aria-label="Upcoming" checked />
    <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
        <div class="space-y-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $upcoming_appointments ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex items-center justify-between p-4 rounded-2xl border border-primary/10 bg-primary/5">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-primary text-primary-content rounded-xl font-bold text-center leading-tight">
                        <div class="text-[10px] uppercase"><?php echo e($appt->scheduled_at->format('M')); ?></div>
                        <div class="text-lg"><?php echo e($appt->scheduled_at->format('d')); ?></div>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm"><?php echo e($appt->reason ?? 'Check-up'); ?></h4>
                        <p class="text-xs opacity-60"><?php echo e($appt->scheduled_at->format('h:i A')); ?> • Dr. <?php echo e($appt->dentist->last_name ?? 'TBD'); ?></p>
                    </div>
                </div>
                <div class="badge badge-primary badge-sm"><?php echo e(ucfirst($appt->status)); ?></div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-10 opacity-40 italic text-sm">No upcoming appointments scheduled.</div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <input type="radio" name="appt_tabs" role="tab" class="tab" aria-label="Previous" />
    <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
        <div class="space-y-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $previous_appointments ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex items-center justify-between p-4 rounded-xl border border-base-200">
                <div class="flex items-center gap-4">
                    <div class="p-2 bg-base-200 rounded-lg text-center leading-tight opacity-70">
                        <div class="text-[8px] uppercase font-bold"><?php echo e($appt->scheduled_at->format('M')); ?></div>
                        <div class="text-sm font-black"><?php echo e($appt->scheduled_at->format('d')); ?></div>
                    </div>
                    <div>
                        <h4 class="font-bold text-xs"><?php echo e($appt->reason ?? 'Clinical Visit'); ?></h4>
                        <p class="text-[10px] opacity-60"><?php echo e($appt->scheduled_at->format('M d, Y')); ?> • <?php echo e($appt->status); ?></p>
                    </div>
                </div>
                <button class="btn btn-ghost btn-xs">View Summary</button>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-10 opacity-40 italic text-sm">No previous visit history available.</div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/tenant/patients/patient_appointment.blade.php ENDPATH**/ ?>