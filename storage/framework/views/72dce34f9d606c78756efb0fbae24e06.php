

<?php $__env->startSection('content'); ?>
<div class="hero min-h-[70vh] bg-gradient-to-br from-primary/5 via-base-100 to-secondary/5">
    <div class="hero-content text-center">
        <div class="max-w-2xl">
            <h1 class="mb-5 text-5xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">Multi-Tenant Dental Clinic Management System</h1>
            <p class="mb-8 text-xl text-base-content/80">
                A comprehensive solution made by BSIT Students, designed for every Filipino Dentist to manage their clinics efficiently.
            </p>
            <div class="flex gap-4 justify-center">
                <a href="#pricing" class="btn btn-primary btn-lg shadow-lg hover:shadow-primary/30 transition-all">View Plans</a>
                <a href="#features" class="btn btn-ghost btn-lg">Learn More</a>
            </div>
            <p class="mt-4 text-sm text-base-content/60 font-medium">The app is now live!</p>
        </div>
    </div>
</div>

<div id="pricing" class="py-20 bg-base-100">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4">Simple, Transparent Pricing</h2>
            <p class="text-lg text-base-content/70">Choose the perfect plan for your clinic</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $pricingPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <div class="card bg-base-100 shadow-xl border border-base-200 hover:border-primary/50 transition-all duration-300 hover:shadow-2xl <?php echo e($plan->is_popular ? 'md:-mt-4 border-primary ring-2 ring-primary ring-offset-2' : ''); ?>">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($plan->is_popular): ?>
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                        <span class="badge badge-primary badge-lg shadow-md font-bold uppercase tracking-wide">Most Popular</span>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <div class="card-body">
                    <h3 class="text-xl font-bold <?php echo e($plan->is_popular ? 'text-primary' : ''); ?>"><?php echo e($plan->name); ?></h3>
                    <div class="my-4">
                        <span class="text-4xl font-extrabold"><?php echo e($plan->getFormattedPrice()); ?></span>
                        <span class="text-base-content/60">/ <?php echo e($plan->getFormattedBillingCycle()); ?></span>
                    </div>
                    
                    <p class="text-sm text-base-content/70 mb-6 min-h-[48px]"><?php echo e($plan->description); ?></p>

                    <ul class="space-y-3 mb-8 flex-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $plan->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-success shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm"><?php echo e($feature); ?></span>
                            </li>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </ul>

                    <div class="card-actions justify-center mt-auto">
                        <a href="<?php echo e(route('tenant.registration.index', ['plan' => $plan->id])); ?>" 
                           class="btn btn-block <?php echo e($plan->is_popular ? 'btn-primary shadow-lg shadow-primary/20' : 'btn-outline'); ?>">
                            Get Started
                        </a>
                    </div>
                </div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/welcome.blade.php ENDPATH**/ ?>