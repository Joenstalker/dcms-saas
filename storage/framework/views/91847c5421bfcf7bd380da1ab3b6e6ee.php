

<?php $__env->startSection('title', 'Invoices & Billing'); ?>
<?php $__env->startSection('page-title', 'Billing & POS'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold">Invoices & Billing</h2>
            <p class="text-base-content/60">Manage your clinic's financial records and transactions.</p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('tenant.billing.create', $tenant->slug)); ?>" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Transaction
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="stat bg-base-100 border border-base-300 rounded-xl shadow-sm">
            <div class="stat-title text-base-content/60 font-bold uppercase text-[10px] tracking-widest">Total Revenue</div>
            <div class="stat-value text-primary text-2xl">Php <?php echo e(number_format($invoices->sum('grand_total'), 2)); ?></div>
            <div class="stat-desc">Lifetime processed</div>
        </div>
        <div class="stat bg-base-100 border border-base-300 rounded-xl shadow-sm">
            <div class="stat-title text-base-content/60 font-bold uppercase text-[10px] tracking-widest">Unpaid Balance</div>
            <div class="stat-value text-error text-2xl">Php <?php echo e(number_format($invoices->where('status', 'Unpaid')->sum('grand_total'), 2)); ?></div>
            <div class="stat-desc">Awaiting settlement</div>
        </div>
        <div class="stat bg-base-100 border border-base-300 rounded-xl shadow-sm">
            <div class="stat-title text-base-content/60 font-bold uppercase text-[10px] tracking-widest">Total Invoices</div>
            <div class="stat-value text-2xl"><?php echo e($invoices->total()); ?></div>
            <div class="stat-desc">System records</div>
        </div>
    </div>

    <!-- Search and Filter (Mockup for now) -->
    <div class="flex flex-col md:flex-row gap-4 bg-base-100 p-4 border border-base-300 rounded-xl shadow-sm">
        <div class="flex-1 relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-base-content/40">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input type="text" placeholder="Search invoices by number or patient..." class="input input-bordered w-full pl-10 focus:ring-primary focus:border-primary transition-all rounded-xl">
        </div>
        <select class="select select-bordered w-full md:w-48 rounded-xl focus:ring-primary focus:border-primary transition-all">
            <option disabled selected>Filter by Status</option>
            <option>All Invoices</option>
            <option>Paid</option>
            <option>Partial</option>
            <option>Unpaid</option>
        </select>
    </div>

    <!-- Invoices Table -->
    <div class="bg-base-100 border border-base-300 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-base-200/50">
                        <th class="pl-6 uppercase text-[10px] font-black tracking-widest opacity-40">Invoice #</th>
                        <th class="uppercase text-[10px] font-black tracking-widest opacity-40">Patient</th>
                        <th class="uppercase text-[10px] font-black tracking-widest opacity-40">Date</th>
                        <th class="uppercase text-[10px] font-black tracking-widest opacity-40">Status</th>
                        <th class="text-right pr-6 uppercase text-[10px] font-black tracking-widest opacity-40">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr onclick="window.location='<?php echo e(route('tenant.billing.show', [$tenant->slug, $invoice->id])); ?>'" class="hover:bg-primary/5 cursor-pointer transition-colors group border-b border-base-200 last:border-0">
                        <td class="pl-6 py-4">
                            <div class="font-bold text-base-content group-hover:text-primary transition-colors">
                                <?php echo e($invoice->invoice_number); ?>

                            </div>
                        </td>
                        <td>
                            <div class="flex items-center space-x-3">
                                <div class="avatar placeholder">
                                    <div class="bg-primary/10 text-primary rounded-lg w-8 h-8 font-bold text-xs">
                                        <?php echo e(substr($invoice->patient->first_name, 0, 1)); ?><?php echo e(substr($invoice->patient->last_name, 0, 1)); ?>

                                    </div>
                                </div>
                                <div class="font-medium opacity-80 group-hover:opacity-100 transition-opacity">
                                    <?php echo e($invoice->patient->first_name); ?> <?php echo e($invoice->patient->last_name); ?>

                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="text-sm opacity-60">
                                <?php echo e($invoice->created_at->format('M d, Y')); ?>

                            </div>
                        </td>
                        <td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->status === 'Paid'): ?>
                                <span class="badge badge-success badge-sm font-bold rounded-lg px-3">PAID</span>
                            <?php elseif($invoice->status === 'Partial'): ?>
                                <span class="badge badge-warning badge-sm font-bold rounded-lg px-3">PARTIAL</span>
                            <?php else: ?>
                                <span class="badge badge-error badge-sm font-bold rounded-lg px-3">UNPAID</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td class="text-right pr-6">
                            <div class="font-black text-base-content">
                                Php <?php echo e(number_format($invoice->grand_total, 2)); ?>

                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-20 bg-base-100/50">
                            <div class="flex flex-col items-center text-base-content/40">
                                <div class="w-16 h-16 rounded-full bg-base-200 flex items-center justify-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <p class="font-medium">No invoices found.</p>
                                <p class="text-sm">Start by creating a new transaction for a patient.</p>
                                <a href="<?php echo e(route('tenant.billing.create', $tenant->slug)); ?>" class="btn btn-primary btn-sm mt-6 rounded-xl shadow-lg shadow-primary/20">New Transaction</a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoices->hasPages()): ?>
        <div class="p-4 bg-base-200/30 border-t border-base-200">
            <?php echo e($invoices->links()); ?>

        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tenant', ['tenant' => $tenant], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/tenant/billing/index.blade.php ENDPATH**/ ?>