<div x-show="activeTab === 'appointments'" class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold">Appointments & Calendar</h2>
        <div class="flex gap-2">
            <button class="btn btn-primary btn-sm" onclick="add_appointment_modal.showModal()">+ Add New Appointment</button>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar for Today's list -->
        <div class="lg:col-span-1 space-y-4">
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body p-4">
                    <h3 class="font-bold text-sm mb-4">Today's List</h3>
                    <div class="space-y-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="flex items-start gap-3 p-2 rounded-lg hover:bg-base-200/50 cursor-pointer transition-colors border border-transparent hover:border-base-300">
                            <div class="w-12 text-center">
                                <div class="text-xs font-bold text-primary"><?php echo e($appointment->scheduled_at->format('h:i')); ?></div>
                                <div class="text-[10px] opacity-60 uppercase"><?php echo e($appointment->scheduled_at->format('A')); ?></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-xs truncate"><?php echo e($appointment->patient->full_name); ?></div>
                                <div class="text-[10px] opacity-60 truncate">Check-up</div>
                            </div>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <div class="text-center py-4 opacity-50 text-xs italic">No appointments today</div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="lg:col-span-3">
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body p-0">
                    <!-- Calendar Header -->
                    <div class="p-4 border-b border-base-200 flex justify-between items-center bg-base-200/10">
                        <div class="flex items-center gap-4">
                            <h3 class="font-bold"><?php echo e(now()->format('F Y')); ?></h3>
                            <div class="join">
                                <button class="btn btn-sm join-item"><</button>
                                <button class="btn btn-sm join-item">Today</button>
                                <button class="btn btn-sm join-item">></button>
                            </div>
                        </div>
                        <div class="join">
                            <button class="btn btn-sm join-item btn-active">Month</button>
                            <button class="btn btn-sm join-item">Week</button>
                            <button class="btn btn-sm join-item">Day</button>
                        </div>
                    </div>
                    <!-- Calendar Grid (Simulated) -->
                    <div class="grid grid-cols-7 border-b border-base-200">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="p-2 text-center text-[10px] font-bold uppercase opacity-50 border-r border-base-200 last:border-0"><?php echo e($day); ?></div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                    <div class="grid grid-cols-7 grid-rows-5 h-[500px]">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 35; $i++): ?>
                        <?php
                            $dayNum = $i - 5; // Simplified logic to start month on Wednesday
                            $isToday = $dayNum == now()->day;
                            $hasAppointment = in_array($dayNum, [12, 15, 20, 27]);
                        ?>
                        <div class="border-r border-b border-base-200 p-1 hover:bg-base-200/30 transition-colors relative group <?php echo e($dayNum < 1 || $dayNum > 31 ? 'bg-base-200/10' : ''); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($dayNum > 0 && $dayNum <= 31): ?>
                            <span class="text-[10px] font-medium <?php echo e($isToday ? 'bg-primary text-primary-content w-5 h-5 flex items-center justify-center rounded-full' : 'opacity-60'); ?>"><?php echo e($dayNum); ?></span>
                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasAppointment): ?>
                            <div class="mt-1 space-y-1">
                                <div class="bg-primary/10 text-primary text-[8px] p-0.5 rounded truncate border border-primary/20">9:00 AM - Patient...</div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($dayNum == 27): ?>
                                <div class="bg-secondary/10 text-secondary text-[8px] p-0.5 rounded truncate border border-secondary/20">2:30 PM - Extraction</div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <button class="absolute bottom-1 right-1 opacity-0 group-hover:opacity-100 btn btn-ghost btn-xs h-4 min-h-0 px-1 text-[8px]">+ Add</button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/tenant/dentist/tabs/appointments.blade.php ENDPATH**/ ?>