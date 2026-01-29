<!-- HOME TAB -->
<div x-show="activeTab === 'home'" class="space-y-6">
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stats shadow bg-base-100 border-l-4 border-primary">
            <div class="stat">
                <div class="stat-title text-xs font-bold uppercase tracking-wider">Today's Appointments</div>
                <div class="stat-value text-primary text-2xl"><?php echo e($stats['today_appointments']); ?></div>
                <div class="stat-desc mt-1">Check your schedule</div>
            </div>
        </div>
        <div class="stats shadow bg-base-100 border-l-4 border-secondary">
            <div class="stat">
                <div class="stat-title text-xs font-bold uppercase tracking-wider">Total Patients</div>
                <div class="stat-value text-secondary text-2xl"><?php echo e($stats['total_patients']); ?></div>
                <div class="stat-desc mt-1">Registered in clinic</div>
            </div>
        </div>
        <div class="stats shadow bg-base-100 border-l-4 border-success">
            <div class="stat">
                <div class="stat-title text-xs font-bold uppercase tracking-wider">Income Today</div>
                <div class="stat-value text-success text-2xl">₱<?php echo e(number_format($stats['income_today'], 2)); ?></div>
                <div class="stat-desc mt-1">Updated in real-time</div>
            </div>
        </div>
        <div class="stats shadow bg-base-100 border-l-4 border-accent">
            <div class="stat">
                <div class="stat-title text-xs font-bold uppercase tracking-wider">New Messages</div>
                <div class="stat-value text-accent text-2xl">0</div>
                <div class="stat-desc mt-1">Check your inbox</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <!-- Upcoming Schedule -->
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body p-0">
                    <div class="p-4 border-b border-base-200 flex justify-between items-center bg-base-200/20">
                        <h3 class="font-bold">Upcoming Appointments</h3>
                        <button class="btn btn-primary btn-sm" onclick="add_appointment_modal.showModal()">+ New Booking</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <tr>
                                    <td>
                                        <div class="font-bold"><?php echo e($appointment->patient->full_name); ?></div>
                                        <div class="text-xs opacity-50"><?php echo e($appointment->patient->phone); ?></div>
                                    </td>
                                    <td><?php echo e($appointment->scheduled_at->format('h:i A')); ?></td>
                                    <td><span class="badge badge-sm badge-outline"><?php echo e($appointment->status); ?></span></td>
                                    <td class="text-right">
                                        <button class="btn btn-ghost btn-xs">View</button>
                                    </td>
                                </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <tr>
                                    <td colspan="4" class="text-center py-8 opacity-50">No appointments for today</td>
                                </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="space-y-6">
            <!-- Quick Search -->
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="font-bold mb-4">Quick Patient Search</h3>
                    <div class="join w-full">
                        <input class="input input-bordered join-item w-full" placeholder="Name or Phone..."/>
                        <button class="btn btn-primary join-item">Search</button>
                    </div>
                </div>
            </div>

            <!-- Recent Patients -->
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body p-0">
                    <div class="p-4 border-b border-base-200 bg-base-200/20">
                        <h3 class="font-bold">Recent Patients</h3>
                    </div>
                    <div class="divide-y divide-base-200">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recent_patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="p-4 flex items-center gap-3 hover:bg-base-200/50 cursor-pointer transition-colors">
                            <div class="avatar placeholder">
                                <div class="bg-neutral text-neutral-content rounded-full w-8">
                                    <span class="text-xs"><?php echo e(substr($patient->first_name, 0, 1)); ?><?php echo e(substr($patient->last_name, 0, 1)); ?></span>
                                </div>
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <div class="font-bold text-sm truncate"><?php echo e($patient->first_name); ?> <?php echo e($patient->last_name); ?></div>
                                <div class="text-[10px] opacity-60"><?php echo e($patient->phone); ?></div>
                            </div>
                            <button class="btn btn-ghost btn-xs">Edit</button>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <div class="p-8 text-center opacity-50 text-sm italic">No recent patients</div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="p-2 border-t border-base-200 text-center">
                        <button class="btn btn-ghost btn-xs w-full" @click="activeTab = 'patients'">View All Patients</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH D:\dentistmng\dcms-saas\resources\views/tenant/dentist/tabs/home.blade.php ENDPATH**/ ?>