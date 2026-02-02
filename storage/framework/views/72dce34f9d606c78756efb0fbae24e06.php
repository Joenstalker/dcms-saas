

<?php $__env->startSection('content'); ?>
<div class="relative overflow-hidden bg-base-100 py-12 lg:py-24">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-primary/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-secondary/10 rounded-full blur-3xl"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <!-- Text Content -->
            <div class="flex-1 text-center lg:text-left space-y-8">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary text-sm font-bold tracking-wide uppercase">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                    </span>
                    Now Live for Filipino Dentists
                </div>
                
                <h1 class="text-5xl lg:text-7xl font-black leading-tight">
                    Smart Dental Care <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">Management</span>
                </h1>
                
                <p class="text-lg lg:text-xl text-base-content/70 max-w-xl mx-auto lg:mx-0">
                    A comprehensive, modern solution designed by BSIT Students to help Filipino Dentists streamline their practice and focus on patient care.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="#pricing" class="btn btn-primary btn-lg px-8 shadow-xl shadow-primary/20 hover:scale-105 transition-transform group">
                        Get Started Now
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                    </a>
                    <a href="#features" class="btn btn-ghost btn-lg px-8">Explores Features</a>
                </div>
                
                <div class="flex items-center justify-center lg:justify-start gap-6 pt-4 opacity-60 grayscale hover:grayscale-0 transition-all duration-500">
                    <img src="<?php echo e(asset('images/BukSU-logo.png')); ?>" alt="BukSU" class="h-10 w-auto">
                    <img src="<?php echo e(asset('images/COT-logo.png')); ?>" alt="COT" class="h-10 w-auto">
                </div>
            </div>

            <!-- Image/Visual -->
            <div class="flex-1 relative">
                <div class="relative w-full max-w-lg mx-auto">
                    <!-- Floating Design Elements -->
                    <div class="absolute -top-10 -left-10 w-32 h-32 bg-secondary/20 rounded-2xl rotate-12 animate-pulse"></div>
                    <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-primary/20 rounded-full animate-bounce" style="animation-duration: 4s"></div>
                    
                    <!-- Main Image with specialized background -->
                    <div class="relative z-20 overflow-hidden rounded-[2rem] border-8 border-white shadow-2xl skew-y-2 hover:skew-y-0 transition-transform duration-700">
                        <img src="<?php echo e(asset('images/dentist-model.png')); ?>" alt="Filipino Dentist" class="w-full h-auto scale-110 hover:scale-100 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-primary/40 to-transparent opacity-30"></div>
                    </div>
                    
                    <!-- Floating Stats Card -->
                    <div class="absolute -bottom-5 -left-10 bg-base-100 p-4 rounded-2xl shadow-2xl z-30 border border-base-200 animate-float">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-success/10 flex items-center justify-center text-success">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div>
                                <div class="text-xs text-base-content/60 font-bold uppercase tracking-wider">Patient Smiles</div>
                                <div class="text-lg font-black tracking-tight">100% Guaranteed</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
    }
    .animate-float {
        animation: float 5s ease-in-out infinite;
    }
</style>

<!-- Expanded Features Section -->
<div id="features" class="py-24 bg-base-100 relative overflow-hidden">
    <!-- Premium Gradient Blobs -->
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-primary/5 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 -left-20 w-80 h-80 bg-secondary/5 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[40rem] h-[40rem] bg-accent/5 rounded-full blur-[150px] pointer-events-none"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center max-w-4xl mx-auto mb-20 space-y-6">
            <h2 class="text-4xl lg:text-5xl font-black tracking-tight leading-tight">
                All-in-One <span class="text-primary italic">Smart Solution</span> for <br class="hidden lg:block">
                Modern Dental Clinics
            </h2>
            <div class="flex flex-wrap justify-center gap-4 text-sm font-bold uppercase tracking-widest text-base-content/50">
                <span class="px-4 py-2 bg-base-100 rounded-lg shadow-sm">Save Time</span>
                <span class="px-4 py-2 bg-base-100 rounded-lg shadow-sm">Stay Secure</span>
                <span class="px-4 py-2 bg-base-100 rounded-lg shadow-sm">Go Digital</span>
                <span class="px-4 py-2 bg-base-100 rounded-lg shadow-sm">Gain Analytics</span>
            </div>
            <p class="text-lg lg:text-xl text-base-content/70 leading-relaxed italic">
                Transform the way your dental clinic operates with a complete, smart management platform designed for efficiency, security, and growth. From patient registration to treatment tracking and business insights—everything you need is in one place.
            </p>
        </div>

        <!-- 4 Pillars Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-32">
            <!-- Save Time -->
            <div class="card bg-base-100 shadow-xl border border-base-200 hover:border-primary/50 transition-all duration-300 hover:-translate-y-2 group">
                <div class="card-body">
                    <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary mb-4 group-hover:scale-110 transition-transform duration-500 shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-black italic mb-2">Save Time</h3>
                    <p class="text-sm text-base-content/70 leading-relaxed">
                        Automate appointments, billing, patient records, and staff workflows. Reduce manual tasks and focus more on patient care while your clinic runs smoothly in the background.
                    </p>
                </div>
            </div>

            <!-- Stay Secure -->
            <div class="card bg-base-100 shadow-xl border border-base-200 hover:border-secondary/50 transition-all duration-300 hover:-translate-y-2 group">
                <div class="card-body">
                    <div class="w-14 h-14 rounded-2xl bg-secondary/10 flex items-center justify-center text-secondary mb-4 group-hover:scale-110 transition-transform duration-500 shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h3 class="text-xl font-black italic mb-2">Stay Secure</h3>
                    <p class="text-sm text-base-content/70 leading-relaxed">
                        Protect sensitive patient data with advanced security, encrypted records, and role-based access. Stay compliant and confident with a system built for healthcare privacy.
                    </p>
                </div>
            </div>

            <!-- Go Digital -->
            <div class="card bg-base-100 shadow-xl border border-base-200 hover:border-accent/50 transition-all duration-300 hover:-translate-y-2 group">
                <div class="card-body">
                    <div class="w-14 h-14 rounded-2xl bg-accent/10 flex items-center justify-center text-accent mb-4 group-hover:scale-110 transition-transform duration-500 shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" /></svg>
                    </div>
                    <h3 class="text-xl font-black italic mb-2">Go Digital</h3>
                    <p class="text-sm text-base-content/70 leading-relaxed">
                        Go paperless with digital patient records, prescriptions, imaging, and cloud access. Manage your clinic anytime, anywhere, from any device.
                    </p>
                </div>
            </div>

            <!-- Smart Analytics -->
            <div class="card bg-base-100 shadow-xl border border-base-200 hover:border-info/50 transition-all duration-300 hover:-translate-y-2 group">
                <div class="card-body">
                    <div class="w-14 h-14 rounded-2xl bg-info/10 flex items-center justify-center text-info mb-4 group-hover:scale-110 transition-transform duration-500 shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    </div>
                    <h3 class="text-xl font-black italic mb-2">Smart Analytics</h3>
                    <p class="text-sm text-base-content/70 leading-relaxed">
                        Make better decisions with real-time reports and insights. Track clinic performance, revenue, patient trends, and treatment outcomes—all through easy-to-read dashboards.
                    </p>
                </div>
            </div>
        </div>

        <div class="space-y-32">
            <!-- Feature Showcase 1: OneTap -->
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-1/2 space-y-6">
                    <h3 class="text-4xl font-black italic">Manage with OneTap Efficiency</h3>
                    <p class="text-lg text-base-content/70 leading-relaxed">
                        Our specialized <span class="font-bold text-primary">OneTap Interface</span> reduces administrative overhead. Scheduling, billing, and patient records are all just a single tap away. Designed for speed, so you spend less time on screen and more time with patients.
                    </p>
                    <div class="p-6 bg-primary/5 rounded-3xl border border-primary/10">
                        <p class="text-primary font-bold italic">"One platform. One dashboard. Total control of your dental clinic."</p>
                    </div>
                </div>
                <div class="lg:w-1/2">
                    <div class="relative group">
                        <div class="absolute -inset-4 bg-gradient-to-r from-primary to-secondary rounded-[2.5rem] blur opacity-25 group-hover:opacity-40 transition-opacity"></div>
                        <img src="<?php echo e(asset('images/OneTop.png')); ?>" alt="OneTap Feature" class="relative rounded-[2rem] shadow-2xl border-4 border-white hover:scale-[1.02] transition-transform duration-500">
                    </div>
                </div>
            </div>

            <!-- Feature Showcase 2: Dental Smile -->
            <div class="flex flex-col lg:flex-row-reverse items-center gap-16">
                <div class="lg:w-1/2 space-y-6 text-right">
                    <h3 class="text-4xl font-black italic">The Secret to Every Dental Smile</h3>
                    <p class="text-lg text-base-content/70 leading-relaxed">
                        Happy patients start with an organized clinic. Our system ensures personalized follow-ups, easy dental records access, and professional treatment plans that build trust and bring more smiles to your practice.
                    </p>
                    <div class="flex gap-4 justify-end">
                        <div class="avatar-group -space-x-6 rtl:space-x-reverse">
                            <div class="avatar border-2 border-white"><div class="w-12"><img src="https://i.pravatar.cc/150?u=1" /></div></div>
                            <div class="avatar border-2 border-white"><div class="w-12"><img src="https://i.pravatar.cc/150?u=2" /></div></div>
                            <div class="avatar border-2 border-white"><div class="w-12"><img src="https://i.pravatar.cc/150?u=3" /></div></div>
                            <div class="avatar placeholder border-2 border-white"><div class="bg-primary text-primary-content w-12"><span>+99</span></div></div>
                        </div>
                    </div>
                </div>
                <div class="lg:w-1/2">
                    <div class="relative group">
                        <div class="absolute -inset-4 bg-gradient-to-r from-secondary to-primary rounded-[2.5rem] blur opacity-25 group-hover:opacity-40 transition-opacity"></div>
                        <img src="<?php echo e(asset('images/dental-smile-for-landingpage.png')); ?>" alt="Dental Smile" class="relative rounded-[2rem] shadow-2xl border-4 border-white hover:scale-[1.02] transition-transform duration-500">
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-32 text-center">
            <div class="inline-flex items-center gap-4 px-8 py-4 bg-primary/10 rounded-full border border-primary/20 animate-bounce">
                <span class="text-primary font-black italic">One platform. One dashboard. Total control of your dental clinic.</span>
            </div>
        </div>
    </div>
</div>

<div id="pricing" class="min-h-screen py-32 relative flex flex-col justify-center bg-white overflow-hidden">
    <!-- Subtle Gradient Backgrounds -->
    <div class="absolute top-0 left-0 w-[60rem] h-[60rem] bg-indigo-50/50 rounded-full blur-[140px] -ml-96 -mt-96 pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-[50rem] h-[50rem] bg-emerald-50/50 rounded-full blur-[120px] -mr-64 -mb-64 pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-24">
            <h2 class="text-3xl lg:text-5xl font-extrabold mb-4 tracking-tight text-gray-900">Choose the plan that's right for you</h2>
            <p class="text-lg text-gray-500 max-w-xl mx-auto">Get the best experience for your practice with our flexible plans.</p>
        </div>

        <div class="flex flex-wrap justify-center gap-6 lg:gap-8 max-w-[95rem] mx-auto items-stretch">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $pricingPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $colors = ['primary', 'secondary', 'accent', 'info'];
                $color = $colors[$index % count($colors)];
                // Mapping colors to Tailwind specific for better control if needed, but keeping dynamic
            ?>
            <div class="flex flex-col w-full sm:w-[calc(50%-1.5rem)] lg:w-[calc(33.33%-1.5rem)] xl:w-[calc(25%-1.5rem)] max-w-[360px] min-w-[280px]">
                <div class="group relative h-full bg-white rounded-[2rem] p-10 flex flex-col transition-all duration-500 border border-gray-100 hover:border-<?php echo e($color); ?>/40 shadow-sm hover:shadow-[0_20px_40px_rgba(0,0,0,0.06)] <?php echo e($plan->is_popular ? 'ring-2 ring-primary ring-opacity-10' : ''); ?>">
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($plan->is_popular): ?>
                        <div class="absolute -top-3 right-8 z-20">
                            <span class="bg-primary text-white text-[10px] font-black tracking-widest px-4 py-1.5 rounded-full shadow-md uppercase">MOST POPULAR</span>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <div class="mb-10">
                        <h3 class="text-3xl font-extrabold text-gray-900 mb-6"><?php echo e($plan->name); ?></h3>
                        
                        <div class="flex items-baseline gap-1 mb-4">
                            <span class="text-4xl lg:text-5xl font-black text-gray-900 tracking-tight"><?php echo e($plan->getFormattedPrice()); ?></span>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($plan->price > 0): ?>
                                <span class="text-gray-400 font-semibold text-sm">/ <?php echo e($plan->getFormattedBillingCycle()); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        
                        <p class="text-[15px] text-gray-500 font-medium leading-relaxed min-h-[45px]">
                            <?php echo e($plan->description); ?>

                        </p>
                    </div>

                    <div class="space-y-4 mb-12 flex-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($plan->storage_limit_mb): ?>
                        <div class="flex items-start gap-4">
                            <div class="mt-1 flex-shrink-0">
                                <svg class="w-5 h-5 text-<?php echo e($color); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <span class="text-[15px] text-gray-600 font-medium"><?php echo e($plan->getFormattedStorage()); ?> storage</span>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $plan->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-start gap-4">
                                <div class="mt-1 flex-shrink-0">
                                    <svg class="w-5 h-5 text-<?php echo e($color); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                <span class="text-[15px] text-gray-600 font-medium leading-snug"><?php echo e($feature); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="mt-auto pt-8">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($plan->price == 0): ?>
                            <a href="<?php echo e(route('tenant.registration.index', ['plan' => $plan->id])); ?>" 
                               class="btn btn-ghost btn-block h-14 rounded-2xl border-2 border-gray-100 font-bold hover:bg-gray-50 hover:border-gray-200 text-gray-700 transition-all active:scale-95">
                                Get Started
                            </a>
                        <?php else: ?>
                            <a href="<?php echo e(route('tenant.registration.index', ['plan' => $plan->id])); ?>" 
                               class="btn btn-neutral btn-block h-14 rounded-2xl font-black italic shadow-lg hover:gradient-<?php echo e($color); ?> transition-all active:scale-95 hover:shadow-<?php echo e($color); ?>/20">
                                Upgrade to <?php echo e($plan->name); ?>

                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>

<!-- About Section Placeholder -->
<div id="about" class="py-24 bg-base-100 relative overflow-hidden">
    <div class="absolute top-1/2 left-0 -translate-y-1/2 w-64 h-64 bg-secondary/5 rounded-full blur-3xl"></div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto bg-base-200/50 rounded-[3rem] p-12 lg:p-20 border border-base-200 shadow-xl overflow-hidden relative">
            <div class="absolute top-0 right-0 p-8 opacity-10">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H15.017C14.4647 8 14.017 8.44772 14.017 9V12C14.017 12.5523 13.5693 13 13.017 13H11.017C10.4647 13 10.017 12.5523 10.017 12V9C10.017 7.34315 11.3601 6 13.017 6H19.017C20.6739 6 22.017 7.34315 22.017 9V15C22.017 18.3137 19.3307 21 16.017 21H14.017ZM3.017 21H5.017C8.33071 21 11.017 18.3137 11.017 15V9C11.017 7.34315 9.67386 6 8.017 6H2.017C0.360147 6 -0.982998 7.34315 -0.982998 9V12C-0.982998 12.5523 -0.535282 13 0.0170021 13H2.017C2.56929 13 3.017 13.4477 3.017 14V17C3.017 17.5523 2.56929 18 2.017 18H1.017L1.017 21H3.017Z" /></svg>
            </div>
            
            <div class="flex flex-col lg:flex-row items-center gap-12 relative z-10">
                <div class="w-32 h-32 lg:w-48 lg:h-48 rounded-full bg-gradient-to-br from-primary to-secondary p-1 shrink-0 shadow-2xl">
                    <div class="w-full h-full rounded-full bg-base-100 flex items-center justify-center overflow-hidden">
                        <img src="<?php echo e(asset('images/dcms-logo.png')); ?>" alt="DCMS Logo" class="w-24 h-auto">
                    </div>
                </div>
                <div class="text-center lg:text-left space-y-6">
                    <h3 class="text-3xl font-black italic">About the Developers</h3>
                    <p class="text-lg text-base-content/70 leading-relaxed italic">
                        "We are a group of passionate BSIT Students from <span class="text-primary font-bold">Bukidnon State University</span>. This project is a culmination of our dedication to providing modern technological solutions for our local healthcare heroes."
                    </p>
                    <div class="flex flex-wrap justify-center lg:justify-start gap-4 pt-4">
                        <div class="flex items-center gap-2 px-4 py-2 bg-base-100 rounded-xl shadow-sm border border-base-300">
                            <img src="<?php echo e(asset('images/BukSU-logo.png')); ?>" alt="BukSU" class="h-6 w-auto">
                            <span class="text-xs font-bold uppercase tracking-wider">BukSU</span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-base-100 rounded-xl shadow-sm border border-base-300">
                            <img src="<?php echo e(asset('images/COT-logo.png')); ?>" alt="COT" class="h-6 w-auto">
                            <span class="text-xs font-bold uppercase tracking-wider">College of Tech</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/welcome.blade.php ENDPATH**/ ?>