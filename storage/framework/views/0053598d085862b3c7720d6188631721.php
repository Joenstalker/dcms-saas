<?php $__env->startSection('content'); ?>
<div class="p-6" x-data="{ activeTab: 'home' }">
    <!-- Header with Tabs -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-base-content">Dentist Dashboard</h1>
            <p class="text-base-content/60 text-sm">Welcome back, Dr. <?php echo e(auth()->user()->name); ?></p>
        </div>
        
        <div class="tabs tabs-boxed bg-base-100 p-1">
            <a class="tab" :class="{ 'tab-active bg-primary text-primary-content': activeTab === 'home' }" @click="activeTab = 'home'">Home</a>
            <a class="tab" :class="{ 'tab-active bg-primary text-primary-content': activeTab === 'patients' }" @click="activeTab = 'patients'">Patients</a>
            <a class="tab" :class="{ 'tab-active bg-primary text-primary-content': activeTab === 'appointments' }" @click="activeTab = 'appointments'">Appointments</a>
            <a class="tab" :class="{ 'tab-active bg-primary text-primary-content': activeTab === 'finance' }" @click="activeTab = 'finance'">Finance</a>
            <a class="tab" :class="{ 'tab-active bg-primary text-primary-content': activeTab === 'concern' }" @click="activeTab = 'concern'">Concern</a>
            <a class="tab" :class="{ 'tab-active bg-primary text-primary-content': activeTab === 'report' }" @click="activeTab = 'report'">Report</a>
        </div>
    </div>

    <!-- Tab Contents -->
    
    <!-- HOME TAB -->
    <div x-show="activeTab === 'home'">
        <?php echo $__env->make('tenant.dentist.tabs.home', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <!-- PATIENTS TAB -->
    <div x-show="activeTab === 'patients'">
        <?php echo $__env->make('tenant.dentist.tabs.patients', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <!-- APPOINTMENTS TAB -->
    <div x-show="activeTab === 'appointments'">
        <?php echo $__env->make('tenant.dentist.tabs.appointments', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <!-- FINANCE TAB -->
    <div x-show="activeTab === 'finance'">
        <?php echo $__env->make('tenant.dentist.tabs.finance', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <!-- CONCERN TAB -->
    <div x-show="activeTab === 'concern'">
        <?php echo $__env->make('tenant.dentist.tabs.concern', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <!-- REPORT TAB -->
    <div x-show="activeTab === 'report'">
        <?php echo $__env->make('tenant.dentist.tabs.report', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>

<!-- Detailed Appointment Modal -->
<dialog id="add_appointment_modal" class="modal">
    <div class="modal-box max-w-2xl p-0 overflow-hidden">
        <div class="bg-primary p-6 text-primary-content">
            <h3 class="font-bold text-xl">New Appointment</h3>
            <p class="text-primary-content/80 text-sm">Schedule a patient visit</p>
        </div>
        <form action="<?php echo e(route('tenant.appointments.store', $tenant->slug)); ?>" method="POST" class="p-6 space-y-4">
            <?php echo csrf_field(); ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control md:col-span-2">
                            <label class="label"><span class="label-text font-bold">Patient Name</span></label>
                            <div class="join w-full">
                                <select name="patient_id" class="select select-bordered join-item flex-1">
                                    <option value="">Select Existing Patient</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $all_patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <option value="<?php echo e($patient->id); ?>"><?php echo e($patient->first_name); ?> <?php echo e($patient->last_name); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                        <div class="join-item bg-base-200 px-4 flex items-center">
                            <label class="label cursor-pointer gap-2">
                                <input type="checkbox" name="is_new_patient" class="checkbox checkbox-sm checkbox-primary" />
                                <span class="label-text text-xs">New Patient?</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">Contact Number</span></label>
                    <input type="text" name="phone" placeholder="09xxxxxxxxx" class="input input-bordered w-full" />
                </div>
                
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">Email Address</span></label>
                    <input type="email" name="email" placeholder="patient@example.com" class="input input-bordered w-full" />
                    <label class="label"><span class="label-text-alt opacity-60 italic">To receive appointment day info</span></label>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">Birthday</span></label>
                    <input type="date" name="dob" class="input input-bordered w-full" />
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">Gender</span></label>
                    <select name="gender" class="select select-bordered w-full">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label"><span class="label-text font-bold">Address</span></label>
                    <input type="text" name="address" placeholder="Full address" class="input input-bordered w-full" />
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">Date Start</span></label>
                    <input type="datetime-local" name="scheduled_at" required class="input input-bordered w-full" />
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">Assigned Dentist</span></label>
                    <select name="dentist_id" class="select select-bordered w-full" required>
                        <option value="<?php echo e(auth()->user()->id); ?>">Dr. <?php echo e(auth()->user()->name); ?> (You)</option>
                        <!-- Other available dentists would be listed here -->
                    </select>
                </div>

                <div class="form-control md:col-span-2">
                    <label class="label"><span class="label-text font-bold">Reason for Visit</span></label>
                    <textarea name="reason" class="textarea textarea-bordered h-20" placeholder="e.g. Tooth Extraction"></textarea>
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="add_appointment_modal.close()">Cancel</button>
                <button type="submit" class="btn btn-primary px-8">Save Appointment</button>
            </div>
        </form>
    </div>
</dialog>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tenant', [
    'tenant' => $tenant,
    'navbarComponent' => 'tenant.dentist.components.navbar',
    'sidebarComponent' => 'tenant.dentist.components.sidebar'
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/tenant/dentist/dashboard.blade.php ENDPATH**/ ?>