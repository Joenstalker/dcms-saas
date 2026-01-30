@extends('layouts.app')

@section('content')
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
                    <img src="{{ asset('images/BukSU-logo.png') }}" alt="BukSU" class="h-10 w-auto">
                    <img src="{{ asset('images/COT-logo.png') }}" alt="COT" class="h-10 w-auto">
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
                        <img src="{{ asset('images/dentist-model.png') }}" alt="Filipino Dentist" class="w-full h-auto scale-110 hover:scale-100 transition-transform duration-700">
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
<div id="features" class="py-24 bg-base-200/50 relative overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full opacity-5 pointer-events-none">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <defs>
                <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                    <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" stroke-width="0.5"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)" />
        </svg>
    </div>

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
                        <img src="{{ asset('images/OneTop.png') }}" alt="OneTap Feature" class="relative rounded-[2rem] shadow-2xl border-4 border-white hover:scale-[1.02] transition-transform duration-500">
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
                        <img src="{{ asset('images/dental-smile-for-landingpage.png') }}" alt="Dental Smile" class="relative rounded-[2rem] shadow-2xl border-4 border-white hover:scale-[1.02] transition-transform duration-500">
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

<div id="pricing" class="py-24 relative overflow-hidden bg-cover bg-fixed bg-center" style="background-image: url('{{ asset('images/landingpage-background.png') }}')">
    <div class="absolute inset-0 bg-base-100/90 backdrop-blur-sm"></div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center mb-20 overflow-visible">
            <h2 class="text-5xl font-black mb-6 tracking-tight">Simple, Transparent Pricing</h2>
            <p class="text-xl text-base-content/70 italic">"No hidden costs. Choose the perfect plan for your clinic."</p>
        </div>

        <div class="flex flex-wrap justify-center gap-6 max-w-[95rem] mx-auto items-stretch px-4">
            @foreach($pricingPlans as $index => $plan)
            @php
                $colors = ['primary', 'secondary', 'accent', 'info'];
                $color = $colors[$index % count($colors)];
            @endphp
            <div class="group relative flex flex-col pt-8 w-full md:w-[calc(50%-1.5rem)] lg:w-[calc(25%-1.5rem)] min-w-[220px] max-w-[320px]">
                <!-- Floating Effect Background -->
                <div class="absolute inset-0 bg-base-100 rounded-[2rem] shadow-lg group-hover:shadow-[0_20px_50px_rgba(0,0,0,0.1)] transition-all duration-500 border-b-4 border-{{ $color }} group-hover:-translate-y-2"></div>
                
                <div class="relative flex flex-col flex-1 p-6">
                    @if($plan->is_popular)
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2 w-full flex justify-center">
                            <span class="badge badge-primary shadow-xl font-black italic tracking-widest px-4 py-3 h-auto scale-105">POPULAR</span>
                        </div>
                    @endif
                    
                    <div class="mb-4">
                        <h3 class="text-xl font-black italic {{ $plan->is_popular ? 'text-primary' : 'text-'.$color }}">{{ $plan->name }}</h3>
                        <div class="flex items-baseline gap-1 mt-2">
                            <span class="text-3xl font-black tracking-tighter">{{ $plan->getFormattedPrice() }}</span>
                            <span class="text-base-content/40 font-bold uppercase text-[10px]">/ {{ $plan->getFormattedBillingCycle() }}</span>
                        </div>
                    </div>
                    
                    <p class="text-sm text-base-content/60 mb-6 min-h-[40px] leading-relaxed italic line-clamp-2">{{ $plan->description }}</p>

                    <div class="h-px w-full bg-base-content/5 mb-6"></div>

                    <ul class="space-y-3 mb-8 flex-1">
                        @if($plan->storage_limit_mb)
                        <li class="flex items-start gap-3 group/item">
                            <div class="w-5 h-5 shrink-0 rounded-full bg-{{ $color }}/10 flex items-center justify-center text-{{ $color }} group-hover/item:bg-{{ $color }} group-hover/item:text-white transition-colors duration-300">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" /></svg>
                            </div>
                            <span class="font-bold text-xs text-base-content/80 group-hover/item:text-base-content transition-colors">{{ $plan->getFormattedStorage() }}</span>
                        </li>
                        @endif
                        @foreach($plan->features as $feature)
                            <li class="flex items-start gap-3 group/item">
                                <div class="w-5 h-5 shrink-0 rounded-full bg-{{ $color }}/10 flex items-center justify-center text-{{ $color }} group-hover/item:bg-{{ $color }} group-hover/item:text-white transition-colors duration-300">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                <span class="font-bold text-xs text-base-content/80 group-hover/item:text-base-content transition-colors line-clamp-1">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-auto">
                        <a href="{{ route('tenant.registration.index', ['plan' => $plan->id]) }}" 
                           class="btn btn-sm btn-block h-12 rounded-xl font-black italic shadow-md hover:shadow-{{ $color }}/30 active:scale-95 transition-all {{ $plan->is_popular ? 'btn-primary' : 'btn-outline border-2 hover:bg-'.$color.' hover:text-white hover:border-'.$color }}">
                            Choose Plan
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
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
                        <img src="{{ asset('images/dcms-logo.png') }}" alt="DCMS Logo" class="w-24 h-auto">
                    </div>
                </div>
                <div class="text-center lg:text-left space-y-6">
                    <h3 class="text-3xl font-black italic">About the Developers</h3>
                    <p class="text-lg text-base-content/70 leading-relaxed italic">
                        "We are a group of passionate BSIT Students from <span class="text-primary font-bold">Bukidnon State University</span>. This project is a culmination of our dedication to providing modern technological solutions for our local healthcare heroes."
                    </p>
                    <div class="flex flex-wrap justify-center lg:justify-start gap-4 pt-4">
                        <div class="flex items-center gap-2 px-4 py-2 bg-base-100 rounded-xl shadow-sm border border-base-300">
                            <img src="{{ asset('images/BukSU-logo.png') }}" alt="BukSU" class="h-6 w-auto">
                            <span class="text-xs font-bold uppercase tracking-wider">BukSU</span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-base-100 rounded-xl shadow-sm border border-base-300">
                            <img src="{{ asset('images/COT-logo.png') }}" alt="COT" class="h-6 w-auto">
                            <span class="text-xs font-bold uppercase tracking-wider">College of Tech</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
