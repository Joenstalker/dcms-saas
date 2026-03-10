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
                    <button type="button" onclick="openRegistrationModal()" class="btn btn-primary btn-lg px-8 shadow-xl shadow-primary/20 hover:scale-105 transition-transform group">
                        Get Started Now
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                    </button>
                    <a href="#features" class="btn btn-ghost btn-lg px-8">Explore Features</a>
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
                        @foreach($plan->features ?? [] as $feature)
                            <li class="flex items-start gap-3 group/item">
                                <div class="w-5 h-5 shrink-0 rounded-full bg-{{ $color }}/10 flex items-center justify-center text-{{ $color }} group-hover/item:bg-{{ $color }} group-hover/item:text-white transition-colors duration-300">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                <span class="font-bold text-xs text-base-content/80 group-hover/item:text-base-content transition-colors line-clamp-1">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-auto">
                        <button type="button" onclick="openRegistrationModal('{{ $plan->id }}')" 
                           class="btn btn-sm btn-block h-12 rounded-xl font-black italic shadow-md hover:shadow-{{ $color }}/30 active:scale-95 transition-all {{ $plan->is_popular ? 'btn-primary' : 'btn-outline border-2 hover:bg-'.$color.' hover:text-white hover:border-'.$color }}">
                            Choose Plan
                        </button>
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

<!-- ==================== ADMIN LOGIN MODAL ==================== -->
<div id="adminLoginModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-md relative">
        <button type="button" onclick="document.getElementById('adminLoginModal').classList.remove('modal-open')" class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>

        <div class="text-center mb-6">
            <div class="flex justify-center mb-4">
                @if(file_exists(public_path('images/dcms-logo.png')))
                    <img src="{{ asset('images/dcms-logo.png') }}" alt="DCMS Logo" class="h-14 w-auto">
                @else
                    <svg class="w-14 h-14 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                @endif
            </div>
            <h3 class="text-2xl font-extrabold tracking-tight">Admin Login</h3>
            <p class="text-sm text-base-content/60 mt-1">Sign in to the admin dashboard</p>
        </div>

        <form id="modalAdminLoginForm" class="space-y-4">
            @csrf
            <div class="form-control">
                <input type="email" name="email" placeholder="Email address" required autofocus
                    class="input input-bordered w-full" />
            </div>
            <div class="form-control">
                <input type="password" name="password" placeholder="Password" required
                    class="input input-bordered w-full" />
            </div>
            <div class="flex items-center justify-between">
                <label class="cursor-pointer label justify-start gap-2">
                    <input type="checkbox" name="remember" class="checkbox checkbox-sm checkbox-primary" />
                    <span class="label-text text-sm">Remember Me</span>
                </label>
            </div>

            <!-- Inline reCAPTCHA -->
            <div class="flex justify-center">
                <div id="loginRecaptchaWidget"></div>
            </div>
            <p id="recaptchaHint" class="text-center text-xs text-base-content/50">Please complete the reCAPTCHA above to enable login.</p>

            <button type="submit" id="modalLoginBtn" class="btn btn-primary w-full" disabled>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path id="loginBtnIconPath" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Log In
            </button>
        </form>

        <div class="divider text-xs text-base-content/40">OR</div>
        <p class="text-center text-sm">
            <a href="#" onclick="event.preventDefault(); document.getElementById('adminLoginModal').classList.remove('modal-open'); openRegistrationModal();" class="link link-primary font-medium">Not yet registered? Click Here.</a>
        </p>
    </div>
    <div onclick="document.getElementById('adminLoginModal').classList.remove('modal-open')" class="modal-backdrop bg-base-content/10 backdrop-blur-md"></div>
</div>

<!-- ==================== REGISTRATION MULTI-STEP MODAL ==================== -->
<div id="registrationModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-3xl w-[95vw] relative p-6 sm:p-10">
        <button type="button" onclick="document.getElementById('registrationModal').classList.remove('modal-open')" class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4 z-10">✕</button>

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-3">
                @if(file_exists(public_path('images/dcms-logo.png')))
                    <img src="{{ asset('images/dcms-logo.png') }}" alt="DCMS Logo" class="h-12 w-auto">
                @endif
            </div>
            <h3 class="text-3xl font-extrabold tracking-tight text-primary">Register Your Clinic</h3>
            <p class="text-base text-base-content/60 mt-2">Create your clinic account in just 2 simple steps</p>

            <!-- Step Indicator -->
            <div class="flex justify-center items-center gap-4 mt-6">
                <div id="stepIndicator1" class="flex items-center gap-2">
                    <span class="w-10 h-10 rounded-full bg-primary text-primary-content flex items-center justify-center text-base font-bold shadow-md">1</span>
                    <span class="text-base font-semibold">Clinic Info</span>
                </div>
                <div class="w-12 border-t-2 border-base-300"></div>
                <div id="stepIndicator2" class="flex items-center gap-2">
                    <span class="w-10 h-10 rounded-full bg-base-300 text-base-content/50 flex items-center justify-center text-base font-bold">2</span>
                    <span class="text-base font-semibold text-base-content/50">Owner Info</span>
                </div>
            </div>
        </div>

        <form id="modalRegistrationForm">
            @csrf
            <input type="hidden" name="pricing_plan_id" id="regPlanId" value="">

            <!-- ===== STEP 1: Clinic Information ===== -->
            <div id="regStep1" class="space-y-5">
                <div class="bg-primary/5 rounded-2xl p-4 border border-primary/10 mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-base">Tell us about your clinic</h4>
                            <p class="text-sm text-base-content/60">This information will be used for your clinic's profile.</p>
                        </div>
                    </div>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text text-base font-semibold">Clinic Name <span class="text-error">*</span></span></label>
                    <input type="text" name="clinic_name" class="input input-bordered input-lg w-full text-base" placeholder="e.g., Smile Dental Clinic" required>
                    <label class="label py-1"><span class="label-text-alt text-base-content/50">The official name of your dental clinic</span></label>
                    <span class="error-text text-error text-sm mt-1"></span>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text text-base font-semibold">Desired Subdomain <span class="text-error">*</span></span></label>
                    <div class="join w-full">
                        <input type="text" name="desired_subdomain" class="input input-bordered input-lg join-item flex-1 text-base" placeholder="smiledental" required>
                        <span class="join-item bg-base-200 px-4 flex items-center border border-base-300 text-base font-medium">.{{ env('LOCAL_BASE_DOMAIN', 'lvh.me') }}</span>
                    </div>
                    <label class="label py-1"><span class="label-text-alt text-base-content/50">This will be your clinic's unique web address (URL)</span></label>
                    <span class="error-text text-error text-sm mt-1"></span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text text-base font-semibold">City <span class="text-error">*</span></span></label>
                        <input type="text" name="city" class="input input-bordered input-lg text-base" placeholder="e.g., Malaybalay" required>
                        <span class="error-text text-error text-sm mt-1"></span>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text text-base font-semibold">State / Province <span class="text-error">*</span></span></label>
                        <input type="text" name="state_province" class="input input-bordered input-lg text-base" placeholder="e.g., Bukidnon" required>
                        <span class="error-text text-error text-sm mt-1"></span>
                    </div>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text text-base font-semibold">Address <span class="text-base-content/40 font-normal">(optional)</span></span></label>
                    <input type="text" name="address" class="input input-bordered input-lg w-full text-base" placeholder="e.g., 123 Rizal Street, Barangay 1">
                    <span class="error-text text-error text-sm mt-1"></span>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text text-base font-semibold">Phone Number <span class="text-base-content/40 font-normal">(optional)</span></span></label>
                    <input type="tel" name="phone_number" class="input input-bordered input-lg w-full text-base" placeholder="e.g., 09171234567">
                    <span class="error-text text-error text-sm mt-1"></span>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="button" id="regNextBtn" class="btn btn-primary btn-lg px-10 text-base gap-2 shadow-lg">
                        Next Step
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </button>
                </div>
            </div>

            <!-- ===== STEP 2: Owner Information ===== -->
            <div id="regStep2" class="space-y-5 hidden">
                <div class="bg-secondary/5 rounded-2xl p-4 border border-secondary/10 mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-secondary/10 flex items-center justify-center text-secondary shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-base">Your account details</h4>
                            <p class="text-sm text-base-content/60">This will be your login credentials for the clinic portal.</p>
                        </div>
                    </div>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text text-base font-semibold">Full Name <span class="text-error">*</span></span></label>
                    <input type="text" name="full_name" class="input input-bordered input-lg w-full text-base" placeholder="e.g., Dr. Juan Dela Cruz" required>
                    <label class="label py-1"><span class="label-text-alt text-base-content/50">Your name as the clinic owner</span></label>
                    <span class="error-text text-error text-sm mt-1"></span>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text text-base font-semibold">Email Address <span class="text-error">*</span></span></label>
                    <input type="email" name="email" class="input input-bordered input-lg w-full text-base" placeholder="e.g., juan@email.com" required>
                    <label class="label py-1"><span class="label-text-alt text-base-content/50">We'll use this for your login and notifications</span></label>
                    <span class="error-text text-error text-sm mt-1"></span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text text-base font-semibold">Password <span class="text-error">*</span></span></label>
                        <input type="password" name="password" class="input input-bordered input-lg text-base" placeholder="Create a strong password" required>
                        <label class="label py-1"><span class="label-text-alt text-base-content/50">At least 8 characters</span></label>
                        <span class="error-text text-error text-sm mt-1"></span>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text text-base font-semibold">Confirm Password <span class="text-error">*</span></span></label>
                        <input type="password" name="password_confirmation" class="input input-bordered input-lg text-base" placeholder="Type your password again" required>
                        <label class="label py-1"><span class="label-text-alt text-base-content/50">Must match the password above</span></label>
                        <span class="error-text text-error text-sm mt-1"></span>
                    </div>
                </div>

                <div class="form-control bg-base-200/50 rounded-xl p-4">
                    <label class="label cursor-pointer justify-start gap-4">
                        <input type="checkbox" name="terms_accepted" id="regTermsCheckbox" value="1" class="checkbox checkbox-primary" required>
                        <span class="label-text text-base">I agree to the <a href="#" onclick="event.preventDefault(); openTermsModal();" class="link link-primary font-semibold">Terms of Service</a> and <a href="#" onclick="event.preventDefault(); openPrivacyModal();" class="link link-primary font-semibold">Privacy Policy</a></span>
                    </label>
                    <span class="error-text text-error text-sm mt-1"></span>
                </div>

                <div class="flex justify-between items-center pt-4">
                    <button type="button" id="regBackBtn" class="btn btn-ghost btn-lg gap-2 text-base">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                        Back
                    </button>
                    <button type="submit" id="regSubmitBtn" class="btn btn-primary btn-lg px-10 text-base gap-2 shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Register Clinic
                    </button>
                </div>
            </div>
        </form>

        <p class="text-center text-sm text-base-content/50 mt-6">
            Already have a clinic? <a href="#" class="link link-primary font-medium">Use your clinic URL to log in</a>
        </p>
    </div>
    <div onclick="document.getElementById('registrationModal').classList.remove('modal-open')" class="modal-backdrop bg-base-content/10 backdrop-blur-md"></div>
</div>

<!-- ==================== TERMS OF SERVICE MODAL ==================== -->
<div id="termsModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-2xl text-left p-6 sm:p-10">
        <div class="flex items-center justify-between mb-6 border-b pb-4">
            <h3 class="text-2xl font-bold text-primary">Terms of Service</h3>
            <button type="button" onclick="document.getElementById('termsModal').classList.remove('modal-open')" class="btn btn-sm btn-circle btn-ghost">✕</button>
        </div>
        
        <div class="prose prose-sm max-w-none h-96 overflow-y-auto pr-4 custom-scrollbar">
            @include('legal.terms')
        </div>

        <div class="modal-action">
            <button type="button" onclick="document.getElementById('termsModal').classList.remove('modal-open')" class="btn btn-primary w-full">I Understand</button>
        </div>
    </div>
    <div onclick="document.getElementById('termsModal').classList.remove('modal-open')" class="modal-backdrop bg-base-content/10 backdrop-blur-md"></div>
</div>

<!-- ==================== PRIVACY POLICY MODAL ==================== -->
<div id="privacyModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-2xl text-left p-6 sm:p-10">
        <div class="flex items-center justify-between mb-6 border-b pb-4">
            <h3 class="text-2xl font-bold text-secondary">Privacy Policy</h3>
            <button type="button" onclick="document.getElementById('privacyModal').classList.remove('modal-open')" class="btn btn-sm btn-circle btn-ghost">✕</button>
        </div>

        <div class="prose prose-sm max-w-none h-96 overflow-y-auto pr-4 custom-scrollbar">
            @include('legal.privacy')
        </div>

        <div class="modal-action">
            <button type="button" onclick="document.getElementById('privacyModal').classList.remove('modal-open')" class="btn btn-secondary w-full">Close Privacy Policy</button>
        </div>
    </div>
    <div onclick="document.getElementById('privacyModal').classList.remove('modal-open')" class="modal-backdrop bg-base-content/10 backdrop-blur-md"></div>
</div>

<!-- ==================== STRIPE PAYMENT MODAL ==================== -->
<div id="paymentModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-2xl p-0 overflow-hidden relative flex flex-col max-h-[95vh] sm:max-h-[90vh]">
        <!-- Header (Fixed) -->
        <div class="bg-primary px-8 py-6 text-primary-content shrink-0 shadow-md z-20">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold">Secure Payment</h3>
                    <div class="flex items-center gap-2 text-primary-content/90 text-sm mt-1">
                        <span>Activate your</span>
                        <div class="dropdown dropdown-bottom">
                            <div tabindex="0" role="button" class="flex items-center gap-1 font-bold bg-white/20 hover:bg-white/30 px-2 py-0.5 rounded cursor-pointer transition-colors border border-white/10">
                                <span id="paymentPlanName">Select Plan</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            <ul tabindex="0" class="dropdown-content z-[30] menu p-2 shadow-2xl bg-base-100 rounded-box w-52 text-base-content mt-2 border border-base-200">
                                <li class="menu-title text-[10px] uppercase tracking-widest opacity-50 px-4 py-2">Switch Plan</li>
                                @foreach($pricingPlans as $plan)
                                    @if($plan->price > 0)
                                    <li>
                                        <a href="#" onclick="event.preventDefault(); updatePaymentPlan('{{ $plan->id }}', '{{ $plan->name }}', {{ $plan->price }})" class="flex justify-between items-center py-3 hover:bg-primary/5 active:bg-primary/10">
                                            <span class="font-bold">{{ $plan->name }}</span>
                                            <span class="text-xs opacity-60">₱{{ number_format($plan->price, 0) }}</span>
                                        </a>
                                    </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm opacity-80 uppercase tracking-widest font-bold">Amount Due</p>
                    <p class="text-3xl font-black">₱<span id="paymentAmount"></span></p>
                </div>
            </div>
        </div>

        <!-- Body (Scrollable) -->
        <div class="p-8 overflow-y-auto custom-scrollbar flex-1 bg-base-100">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <!-- Card (Active) -->
                <div class="payment-method-card active rounded-2xl p-4 flex flex-col items-center justify-center gap-2 text-center border-primary bg-primary/5">
                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span class="text-xs font-bold uppercase tracking-wider">Card</span>
                </div>
                <!-- GCash (Disabled) -->
                <div class="payment-method-card disabled opacity-50 rounded-2xl p-4 flex flex-col items-center justify-center gap-2 text-center border-base-300 bg-base-200">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/59/GCash_logo.svg/2560px-GCash_logo.svg.png" class="h-6 w-auto grayscale" alt="GCash">
                    <span class="text-[10px] font-bold text-base-content/40 uppercase">Unavailable</span>
                </div>
                <!-- PayMaya (Disabled) -->
                <div class="payment-method-card disabled opacity-50 rounded-2xl p-4 flex flex-col items-center justify-center gap-2 text-center border-base-300 bg-base-200">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Maya_logo.svg/2560px-Maya_logo.svg.png" class="h-6 w-auto grayscale" alt="Maya">
                    <span class="text-[10px] font-bold text-base-content/40 uppercase">Unavailable</span>
                </div>
                <!-- Bank (Disabled) -->
                <div class="payment-method-card disabled opacity-50 rounded-2xl p-4 flex flex-col items-center justify-center gap-2 text-center border-base-300 bg-base-200">
                    <svg class="w-8 h-8 text-base-content/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span class="text-[10px] font-bold text-base-content/40 uppercase">Unavailable</span>
                </div>
            </div>

            <form id="payment-form">
                <div id="payment-element" class="min-h-[200px]">
                    <!-- Stripe Elements will be inserted here -->
                </div>
                <div id="payment-errors" class="text-error text-sm mt-4 hidden bg-error/10 p-3 rounded-lg border border-error/20"></div>
            </form>
        </div>

        <!-- Footer (Fixed) -->
        <div class="p-6 bg-base-200 border-t border-base-300 flex flex-col sm:flex-row gap-4 justify-between items-center shrink-0 z-20">
            <div class="flex items-center gap-2 text-[11px] text-base-content/50 uppercase tracking-widest font-bold">
                <svg class="w-4 h-4 text-success" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                Secure AES-256 Payment
            </div>
            <div class="flex gap-3 w-full sm:w-auto">
                <button type="button" onclick="closePaymentModal()" class="btn btn-ghost px-6 flex-1 sm:flex-none">Cancel</button>
                <button type="submit" form="payment-form" id="stripeSubmitBtn" class="btn btn-primary px-10 shadow-xl shadow-primary/30 flex-1 sm:flex-none h-12">
                    <span id="button-text">Confirm & Pay</span>
                    <span id="payment-spinner" class="loading loading-spinner hidden"></span>
                </button>
            </div>
        </div>
    </div>
    <div onclick="closePaymentModal()" class="modal-backdrop bg-base-content/10 backdrop-blur-md"></div>
</div>

<!-- ==================== PROCESSING MODAL ==================== -->
<div id="processingModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-md text-center p-10">
        <div class="flex flex-col items-center gap-6">
            <span class="loading loading-spinner loading-lg text-primary scale-125"></span>
            <div>
                <h3 class="text-2xl font-bold text-primary">Creating Your Clinic</h3>
                <p class="text-base-content/60 mt-3 text-lg leading-relaxed">
                    Please wait while we set up your dental practice portal. 
                    <span class="block mt-1 font-medium text-base-content/40 text-sm">This usually takes just a few seconds...</span>
                </p>
            </div>
        </div>
    </div>
    <div class="modal-backdrop bg-base-content/20 backdrop-blur-md"></div>
</div>

<!-- ==================== PLAN SELECTION MODAL ==================== -->
<div id="planSelectionModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-6xl p-0 overflow-hidden relative bg-base-200">
        <div class="bg-primary px-8 py-10 text-primary-content text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <h3 class="text-4xl font-black italic mb-4">Select Your Subscription</h3>
                <p class="text-primary-content/80 text-lg max-w-2xl mx-auto">
                    Almost there! Choose a plan to finalize your clinic registration and unlock your practice management dashboard.
                </p>
            </div>
        </div>

        <div class="p-8 lg:p-12">
            <div class="flex flex-wrap justify-center gap-6 items-stretch">
                @foreach($pricingPlans as $index => $plan)
                @php
                    $colors = ['primary', 'secondary', 'accent', 'info'];
                    $color = $colors[$index % count($colors)];
                @endphp
                <div class="group relative flex flex-col pt-8 w-full md:w-[calc(50%-1.5rem)] lg:w-[calc(33.33%-1.5rem)] min-w-[280px] max-w-[340px]">
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
                        
                        <ul class="space-y-3 mb-8 flex-1">
                            @foreach(array_slice($plan->features ?? [], 0, 4) as $feature)
                                <li class="flex items-start gap-3">
                                    <div class="w-5 h-5 shrink-0 rounded-full bg-{{ $color }}/10 flex items-center justify-center text-{{ $color }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                    <span class="font-bold text-xs text-base-content/70 line-clamp-1">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-auto">
                            <button type="button" onclick="selectPlanAndSubmit('{{ $plan->id }}')" 
                               class="btn btn-sm btn-block h-12 rounded-xl font-black italic shadow-md active:scale-95 transition-all {{ $plan->is_popular ? 'btn-primary' : 'btn-outline border-2 hover:bg-'.$color.' hover:text-white hover:border-'.$color }}">
                                Choose {{ $plan->name }}
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-12 text-center">
                <button type="button" onclick="document.getElementById('planSelectionModal').classList.remove('modal-open'); document.getElementById('registrationModal').classList.add('modal-open');" class="btn btn-ghost font-bold italic opacity-60 hover:opacity-100 transition-opacity">
                    ← Back to Registration
                </button>
            </div>
        </div>
    </div>
    <div class="modal-backdrop bg-base-content/20 backdrop-blur-md"></div>
</div>

@endsection

@push('styles')
<style>
    /* Force SweetAlert2 and Sub-modals to stay on top of EVERYTHING */
    .swal2-container, #termsModal, #privacyModal {
        z-index: 100000 !important;
    }
    
    /* Force reCAPTCHA challenge even higher */
    div[style*="z-index: 2000000000"],
    .g-recaptcha-bubble-wrapper,
    iframe[title*="recaptcha challenge"] {
        z-index: 2000000001 !important;
    }

    /* Custom scrollbar for terms */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(var(--p), 0.2);
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(var(--p), 0.4);
    }

    /* Ensure modal backdrop doesn't obscure the challenge */
    .modal {
        z-index: 900 !important;
    }
    .modal-box {
        z-index: 901 !important;
        border: 1px solid rgba(var(--p), 0.1);
    }
    /* Payment Modal Styles */
    #payment-element {
        margin-bottom: 24px;
    }
    .payment-method-card {
        @apply border-2 transition-all cursor-pointer;
    }
    .payment-method-card.active {
        @apply border-primary bg-primary/5;
    }
    .payment-method-card.disabled {
        @apply opacity-50 cursor-not-allowed grayscale;
    }
</style>
@endpush

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ===================== ADMIN LOGIN MODAL LOGIC =====================
let loginRecaptchaToken = null;
let loginRecaptchaWidgetId = null;

// Initialize inline reCAPTCHA and reset state
// We'll use a MutationObserver to detect when the modal is closed via class change
const loginModalObserver = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
        if (mutation.attributeName === 'class') {
            const modal = mutation.target;
            if (!modal.classList.contains('modal-open')) {
                // Reset state when closed
                loginRecaptchaToken = null;
                const btn = document.getElementById('modalLoginBtn');
                if (btn) {
                    btn.disabled = true;
                    btn.classList.add('btn-disabled');
                }
                const hint = document.getElementById('recaptchaHint');
                if (hint) hint.classList.remove('hidden');
                const iconPath = document.getElementById('loginBtnIconPath');
                if (iconPath) {
                    iconPath.setAttribute('d', 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z');
                }
                if (loginRecaptchaWidgetId !== null && typeof grecaptcha !== 'undefined') {
                    try { grecaptcha.reset(loginRecaptchaWidgetId); } catch(e) {}
                }
            }
        }
    });
});

const adminLoginModalEl = document.getElementById('adminLoginModal');
if (adminLoginModalEl) {
    loginModalObserver.observe(adminLoginModalEl, { attributes: true });
}

function initLoginRecaptcha() {
    const container = document.getElementById('loginRecaptchaWidget');
    if (!container || loginRecaptchaWidgetId !== null) return;

    if (typeof grecaptcha !== 'undefined' && grecaptcha.render) {
        loginRecaptchaWidgetId = grecaptcha.render('loginRecaptchaWidget', {
            'sitekey': '{{ config("services.recaptcha.site_key") }}',
            'callback': function(token) {
                loginRecaptchaToken = token;
                const btn = document.getElementById('modalLoginBtn');
                if (btn) {
                    btn.disabled = false;
                    btn.classList.remove('btn-disabled');
                }
                const iconPath = document.getElementById('loginBtnIconPath');
                if (iconPath) {
                    // Unlocked path
                    iconPath.setAttribute('d', 'M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z');
                }
                const hint = document.getElementById('recaptchaHint');
                if (hint) hint.classList.add('hidden');
            },
            'expired-callback': function() {
                loginRecaptchaToken = null;
                const btn = document.getElementById('modalLoginBtn');
                if (btn) {
                    btn.disabled = true;
                    btn.classList.add('btn-disabled');
                }
                const iconPath = document.getElementById('loginBtnIconPath');
                if (iconPath) {
                    // Locked path
                    iconPath.setAttribute('d', 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z');
                }
                const hint = document.getElementById('recaptchaHint');
                if (hint) hint.classList.remove('hidden');
            }
        });
    }
}

// Try to init reCAPTCHA once the Google script loads
if (typeof grecaptcha !== 'undefined') {
    initLoginRecaptcha();
} else {
    // Wait for reCAPTCHA script to load
    const checkRecaptcha = setInterval(() => {
        if (typeof grecaptcha !== 'undefined' && grecaptcha.render) {
            clearInterval(checkRecaptcha);
            initLoginRecaptcha();
        }
    }, 500);
    // Stop checking after 15 seconds
    setTimeout(() => clearInterval(checkRecaptcha), 15000);
}

document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('modalAdminLoginForm');
    if (!loginForm) return;

    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();

        function doLogin() {
            Swal.fire({
                title: 'Logging in...',
                html: 'Please wait while we verify your credentials',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });

            const formData = new FormData(loginForm);
            if (loginRecaptchaToken) {
                formData.append('g-recaptcha-response', loginRecaptchaToken);
            }

            fetch("{{ route('admin.login.submit') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(async response => {
                const data = await response.json();
                if (response.ok) {
                    document.getElementById('adminLoginModal').classList.remove('modal-open');
                    
                    const redirectUrl = data.redirect || "{{ route('admin.dashboard') }}";
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Welcome!',
                        text: data.message || 'Redirecting to dashboard...',
                        timer: 1000,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didClose: () => {
                            // Use Turbo if available for faster transition, fallback to standard redirect
                            if (window.Turbo) {
                                window.Turbo.visit(redirectUrl);
                            } else {
                                window.location.href = redirectUrl;
                            }
                        }
                    });

                    // Backup redirect in case didClose doesn't trigger
                    setTimeout(() => {
                        if (window.Turbo) {
                            window.Turbo.visit(redirectUrl);
                        } else {
                            window.location.href = redirectUrl;
                        }
                    }, 1500);
                } else {
                    // Reset reCAPTCHA on failure
                    loginRecaptchaToken = null;
                    const btn = document.getElementById('modalLoginBtn');
                    if (btn) { btn.disabled = true; btn.classList.add('btn-disabled'); }
                    const hint = document.getElementById('recaptchaHint');
                    if (hint) hint.classList.remove('hidden');
                    if (loginRecaptchaWidgetId !== null && typeof grecaptcha !== 'undefined') {
                        try { grecaptcha.reset(loginRecaptchaWidgetId); } catch(e) {}
                    }

                    let title = 'Login Failed';
                    let text = data.message || 'An unexpected error occurred.';
                    if (response.status === 422 && data.errors) {
                        text = Object.values(data.errors).flat()[0];
                    } else if (response.status === 403) {
                        title = 'Access Denied';
                    }
                    Swal.fire({ icon: 'error', title, text, confirmButtonColor: '#6366f1' });
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Request Failed',
                    text: 'Could not connect to the server. Please check your connection.',
                    confirmButtonColor: '#6366f1'
                });
            });
        }

        doLogin();
    });
});

// ===================== REGISTRATION MODAL LOGIC =====================
// ===================== TERMS & PRIVACY LOGIC =====================
function openTermsModal() {
    document.getElementById('termsModal').classList.add('modal-open');
}

function openPrivacyModal() {
    document.getElementById('privacyModal').classList.add('modal-open');
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.remove('modal-open');
}

// Strictly regulate the submission button based on terms checkbox
document.addEventListener('DOMContentLoaded', function() {
    const termsCheckbox = document.getElementById('regTermsCheckbox');
    const submitBtn = document.getElementById('regSubmitBtn');
    
    if (termsCheckbox && submitBtn) {
        // Initial state
        submitBtn.disabled = !termsCheckbox.checked;
        if (submitBtn.disabled) submitBtn.classList.add('btn-disabled');

        termsCheckbox.addEventListener('change', function() {
            submitBtn.disabled = !this.checked;
            if (submitBtn.disabled) {
                submitBtn.classList.add('btn-disabled');
            } else {
                submitBtn.classList.remove('btn-disabled');
            }
        });
    }
});

// ===================== NAVIGATION HELPERS =====================
function openAdminLoginModal() {
    document.getElementById('adminLoginModal').classList.add('modal-open');
}

function openRegistrationModal(planId) {
    const modal = document.getElementById('registrationModal');
    const planInput = document.getElementById('regPlanId');
    if (planInput) {
        planInput.value = planId || '';
    }
    // Reset to step 1
    showRegStep(1);
    modal.classList.add('modal-open');
}

function selectPlanAndSubmit(planId) {
    const planInput = document.getElementById('regPlanId');
    if (planInput) {
        planInput.value = planId;
    }
    // Hide plan selection modal
    document.getElementById('planSelectionModal').classList.remove('modal-open');
    // Submit the registration form
    const regForm = document.getElementById('modalRegistrationForm');
    if (regForm) {
        // Trigger submit event
        regForm.requestSubmit();
    }
}

function showRegStep(step) {
    const step1 = document.getElementById('regStep1');
    const step2 = document.getElementById('regStep2');
    const ind1 = document.getElementById('stepIndicator1');
    const ind2 = document.getElementById('stepIndicator2');

    if (step === 1) {
        step1.classList.remove('hidden');
        step2.classList.add('hidden');
        ind1.querySelector('span:first-child').className = 'w-10 h-10 rounded-full bg-primary text-primary-content flex items-center justify-center text-base font-bold shadow-md';
        ind1.querySelector('span:last-child').className = 'text-base font-semibold';
        ind2.querySelector('span:first-child').className = 'w-10 h-10 rounded-full bg-base-300 text-base-content/50 flex items-center justify-center text-base font-bold';
        ind2.querySelector('span:last-child').className = 'text-base font-semibold text-base-content/50';
    } else {
        step1.classList.add('hidden');
        step2.classList.remove('hidden');
        ind1.querySelector('span:first-child').className = 'w-10 h-10 rounded-full bg-success text-success-content flex items-center justify-center text-base font-bold shadow-md';
        ind1.querySelector('span:last-child').className = 'text-base font-semibold text-success';
        ind2.querySelector('span:first-child').className = 'w-10 h-10 rounded-full bg-primary text-primary-content flex items-center justify-center text-base font-bold shadow-md';
        ind2.querySelector('span:last-child').className = 'text-base font-semibold';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Next button — validate Step 1 then go to Step 2
    document.getElementById('regNextBtn')?.addEventListener('click', function() {
        const step1 = document.getElementById('regStep1');
        const required = step1.querySelectorAll('[required]');
        let valid = true;
        
        // Clear errors
        step1.querySelectorAll('.error-text').forEach(el => el.textContent = '');
        step1.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));

        required.forEach(field => {
            if (!field.value.trim()) {
                valid = false;
                field.classList.add('input-error');
                const errorEl = field.closest('.form-control')?.querySelector('.error-text');
                if (errorEl) errorEl.textContent = 'This field is required.';
            }
        });

        if (!valid) {
            Swal.fire({ 
                title: 'Required Fields', 
                text: 'Please fill in the clinic name and desired subdomain before proceeding.', 
                icon: 'warning', 
                confirmButtonColor: '#6366f1' 
            });
            return;
        }
        showRegStep(2);
    });

    // Back button
    document.getElementById('regBackBtn')?.addEventListener('click', function() {
        showRegStep(1);
    });

    // Registration form submit
    const regForm = document.getElementById('modalRegistrationForm');
    if (!regForm) return;

    regForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('regSubmitBtn');
        const step2 = document.getElementById('regStep2');

        // Validate step 2
        const required = step2.querySelectorAll('[required]');
        let valid = true;
        step2.querySelectorAll('.error-text').forEach(el => el.textContent = '');
        step2.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));

        required.forEach(field => {
            if (!field.value && (field.type !== 'checkbox' || !field.checked)) {
                if (field.type === 'checkbox' && !field.checked) {
                    valid = false;
                    const errorEl = field.closest('.form-control')?.querySelector('.error-text');
                    if (errorEl) errorEl.textContent = 'You must accept the terms.';
                } else if (!field.value.trim()) {
                    valid = false;
                    field.classList.add('input-error');
                    const errorEl = field.closest('.form-control')?.querySelector('.error-text');
                    if (errorEl) errorEl.textContent = 'This field is required.';
                }
            }
        });

        // Password match check
        const pw = regForm.querySelector('[name="password"]').value;
        const pwc = regForm.querySelector('[name="password_confirmation"]').value;
        if (pw && pwc && pw !== pwc) {
            valid = false;
            Swal.fire({ title: 'Oops!', text: 'Passwords do not match.', icon: 'warning', confirmButtonColor: '#6366f1' });
            return;
        }

        if (!valid) {
            Swal.fire({ 
                title: 'Missing Information', 
                text: 'Please complete all required fields and accept the terms of service.', 
                icon: 'warning', 
                confirmButtonColor: '#6366f1' 
            });
            return;
        }

        // --- NEW PLAN CHECK ---
        const planId = document.getElementById('regPlanId')?.value;
        if (!planId) {
            // Close registration modal temporarily
            document.getElementById('registrationModal').classList.remove('modal-open');
            // Show plan selection modal
            setTimeout(() => {
                document.getElementById('planSelectionModal').classList.add('modal-open');
            }, 300);
            return;
        }

        // Submit
        submitBtn.disabled = true;
        
        // Show dedicated processing modal
        document.getElementById('processingModal').classList.add('modal-open');

        try {
            const formData = new FormData(regForm);
            const response = await fetch("{{ route('tenant.registration.store') }}", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData,
            });

            const result = await response.json();

            // Hide processing modal before showing next step
            document.getElementById('processingModal').classList.remove('modal-open');

            if (response.status === 422) {
                Swal.fire({ title: 'Validation Error', text: result.message || 'Please correct the errors.', icon: 'error', confirmButtonColor: '#6366f1' });

                if (result.errors) {
                    // Check if we need to go back to step 1 for clinic errors
                    const step1Fields = ['clinic_name', 'desired_subdomain', 'city', 'state_province', 'address', 'phone_number'];
                    let hasStep1Error = false;

                    Object.entries(result.errors).forEach(([field, messages]) => {
                        if (step1Fields.includes(field)) hasStep1Error = true;
                        const input = regForm.querySelector(`[name="${field}"]`);
                        if (input) {
                            const errorEl = input.closest('.form-control')?.querySelector('.error-text');
                            if (errorEl) {
                                errorEl.textContent = Array.isArray(messages) ? messages[0] : messages;
                                input.classList.add('input-error');
                            }
                        }
                    });

                    if (hasStep1Error) showRegStep(1);
                }
                return;
            }

            if (response.status === 500) {
                Swal.fire({ title: 'Oops!', text: result.message || 'Something went wrong. Please try again.', icon: 'error', confirmButtonColor: '#6366f1' });
                return;
            }

            if (result.success) {
                // If payment is required
                if (result.payment_required) {
                    // Close the registration step modal
                    document.getElementById('registrationModal').classList.remove('modal-open');
                    // Open and initialize Stripe Modal
                    initStripePayment(result);
                    return;
                }

                // If no payment (free plan), show success and redirect
                document.getElementById('registrationModal').classList.remove('modal-open');

                Swal.fire({
                    title: 'Welcome to DCMS 🎉',
                    text: result.message || 'Your clinic has been created! Redirecting...',
                    icon: 'success',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didClose: () => {
                        if (result.redirect_url || result.auto_login_url) {
                            window.location.href = result.redirect_url || result.auto_login_url;
                        }
                    }
                });
            }
        } catch (error) {
            console.error('Registration error:', error);
            // Hide loading modal if error occurs
            document.getElementById('processingModal').classList.remove('modal-open');
            Swal.fire({ title: 'Connection Error', text: 'Could not reach the server. Please try again.', icon: 'error', confirmButtonColor: '#6366f1' });
        } finally {
            submitBtn.disabled = false;
        }
    });
});

// ===================== STRIPE PAYMENT LOGIC =====================
let stripe = null;
let elements = null;
let paymentElement = null;

async function initStripePayment(data) {
    if (!data.stripe_key || !data.client_secret) {
        Swal.fire('Error', 'Could not initialize payment system. Please contact support.', 'error');
        return;
    }

    // Update Modal Info
    document.getElementById('paymentPlanName').textContent = data.plan_name;
    document.getElementById('paymentAmount').textContent = parseFloat(data.amount).toLocaleString(undefined, {minimumFractionDigits: 2});
    
    // Show Modal
    document.getElementById('paymentModal').classList.add('modal-open');

    // Initialize Stripe
    if (!stripe) {
        stripe = Stripe(data.stripe_key);
    }

    mountStripeElement(data.client_secret);

    // Handle Form Submit
    const form = document.getElementById('payment-form');
    if (form) {
        form.onsubmit = async (e) => {
            e.preventDefault();
            await confirmStripePayment(data.auto_login_url || data.redirect_url);
        };
    }
}

function mountStripeElement(clientSecret) {
    const options = {
        clientSecret: clientSecret,
        appearance: {
            theme: 'stripe',
            variables: {
                colorPrimary: '#6366f1',
                colorBackground: '#ffffff',
                colorText: '#1f2937',
                colorDanger: '#ef4444',
                fontFamily: 'Inter, system-ui, sans-serif',
                borderRadius: '12px',
            },
        },
    };

    if (paymentElement) {
        paymentElement.unmount();
    }

    elements = stripe.elements(options);
    paymentElement = elements.create('payment');
    paymentElement.mount('#payment-element');
}

async function updatePaymentPlan(planId, planName, planPrice) {
    // Show loading state
    const amountEl = document.getElementById('paymentAmount');
    const planNameEl = document.getElementById('paymentPlanName');
    const originalAmount = amountEl.textContent;
    const originalPlanName = planNameEl.textContent;

    amountEl.innerHTML = '<span class="loading loading-dots loading-xs"></span>';
    
    try {
        const response = await fetch("{{ route('tenant.registration.update-plan') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ pricing_plan_id: planId })
        });

        const data = await response.json();

        if (data.success) {
            // Update UI
            planNameEl.textContent = data.plan_name;
            amountEl.textContent = parseFloat(data.amount).toLocaleString(undefined, {minimumFractionDigits: 2});
            
            // Re-mount Stripe with new client secret
            mountStripeElement(data.client_secret);
            
            // Close dropdown
            const activeDropdown = document.activeElement;
            if (activeDropdown) activeDropdown.blur();

            Toast.fire({
                icon: 'success',
                title: `Plan switched to ${data.plan_name}`
            });
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        console.error('Plan update error:', error);
        amountEl.textContent = originalAmount;
        planNameEl.textContent = originalPlanName;
        Swal.fire('Update Failed', error.message || 'Could not update plan. Please try again.', 'error');
    }
}

function closePaymentModal() {
    confirmCancelRegistration();
}

function confirmCancelRegistration() {
    Swal.fire({
        title: 'Cancel Registration?',
        text: "Are you sure you want to cancel? Your progress will be lost and your registration will be deleted.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, cancel it!',
        cancelButtonText: 'No, stay here',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Perform cleanup on backend
            fetch("{{ route('tenant.registration.cancel') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).finally(() => {
                document.getElementById('paymentModal').classList.remove('modal-open');
                // Redirect or refresh to clear state
                window.location.reload(); 
            });
        }
    });
}

async function confirmStripePayment(redirectUrl) {
    const errorEl = document.getElementById('payment-errors');
    if (!errorEl) return;

    setLoading(true);
    errorEl.classList.add('hidden');

    try {
        const { error, paymentIntent } = await stripe.confirmPayment({
            elements,
            confirmParams: {
                return_url: window.location.origin + "/register/payment-success", 
            },
            redirect: 'if_required' 
        });

        if (error) {
            handlePaymentError(error);
        } else if (paymentIntent && paymentIntent.status === 'succeeded') {
            // Show processing modal as requested
            Swal.fire({
                title: 'Processing Payment...',
                text: 'Please wait while we activate your clinic.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Notify backend to activate tenant
            try {
                const response = await fetch("{{ route('tenant.registration.confirm-payment') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        title: 'Payment Successful! 🎉',
                        text: 'Your clinic is now active. Redirecting you to your portal...',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didClose: () => {
                            window.location.href = redirectUrl;
                        }
                    });
                } else {
                    throw new Error(data.message || 'Failed to activate clinic.');
                }
            } catch (backendError) {
                console.error('Backend activation error:', backendError);
                Swal.fire({
                    icon: 'error',
                    title: 'Activation Issue',
                    text: 'Payment was successful, but we had trouble activating your clinic. Please contact support.',
                });
            }
        }
    } catch (e) {
        console.error(e);
        errorEl.textContent = "Connection error. Please try again.";
        errorEl.classList.remove('hidden');
        setLoading(false);
    }

    function handlePaymentError(error) {
        if (error.type === "card_error" || error.type === "validation_error") {
            errorEl.textContent = error.message;
        } else {
            errorEl.textContent = "An unexpected error occurred.";
        }
        errorEl.classList.remove('hidden');
        setLoading(false);
    }

    function setLoading(isLoading) {
        const submitBtn = document.getElementById('stripeSubmitBtn');
        const spinner = document.getElementById('payment-spinner');
        const btnText = document.getElementById('button-text');
        
        if (submitBtn) submitBtn.disabled = isLoading;
        if (isLoading) {
            if (spinner) spinner.classList.remove('hidden');
            if (btnText) btnText.textContent = 'Processing...';
        } else {
            if (spinner) spinner.classList.add('hidden');
            if (btnText) btnText.textContent = 'Confirm & Pay';
        }
    }
}
</script>
@endpush
