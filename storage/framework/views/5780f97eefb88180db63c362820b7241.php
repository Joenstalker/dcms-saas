<?php $__env->startSection('content'); ?>
<div class="p-6">
    <?php echo $__env->make('tenant.assistant.tabs.home', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>

<!-- Add Appointment Modal (Style only for now) -->
<dialog id="add_appointment_modal" class="modal">
    <div class="modal-box max-w-2xl p-0 overflow-hidden">
        <div class="bg-secondary p-6 text-secondary-content">
            <h3 class="font-bold text-xl text-white">Manual Patient Booking</h3>
            <p class="text-white/80 text-sm">Register a walk-in or phone-in appointment.</p>
        </div>
        <div class="p-8">
            <form action="<?php echo e(route('tenant.appointments.store', $tenant->slug)); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Patient Selection</span></label>
                        <select name="patient_id" class="select select-bordered w-full" required>
                            <option value="" disabled selected>Select Existing Patient</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($patient->id); ?>"><?php echo e($patient->first_name); ?> <?php echo e($patient->last_name); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Preferred Dentist</span></label>
                        <select name="dentist_id" class="select select-bordered w-full" required>
                            <option value="" disabled selected>Select Dentist</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $dentists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dentist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($dentist->id); ?>">Dr. <?php echo e($dentist->name); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Appointment Schedule</span></label>
                        <input type="datetime-local" name="scheduled_at" class="input input-bordered w-full" required />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Reason for Visit</span></label>
                        <input type="text" name="reason" placeholder="Cleaning, Extraction, etc." class="input input-bordered w-full" />
                    </div>

                    <div class="form-control md:col-span-2">
                        <label class="label"><span class="label-text font-bold">Notes</span></label>
                        <textarea name="notes" class="textarea textarea-bordered w-full" placeholder="Internal notes..."></textarea>
                    </div>
                </div>

                <div class="modal-action mt-8">
                    <button type="button" class="btn btn-ghost" onclick="add_appointment_modal.close()">Cancel</button>
                    <button type="submit" class="btn btn-secondary px-8 text-white">Save Booking</button>
                </div>
            </form>
        </div>
    </div>
</dialog>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tenant', [
    'tenant' => $tenant,
    'navbarComponent' => 'tenant.assistant.components.navbar',
    'sidebarComponent' => 'tenant.assistant.components.sidebar'
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/tenant/assistant/dashboard.blade.php ENDPATH**/ ?>