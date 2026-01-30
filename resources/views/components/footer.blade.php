<footer class="footer footer-center p-10 bg-base-200 text-base-content border-t border-base-300">
    <aside class="space-y-4">
        <div class="flex items-center justify-center gap-6 mb-2">
            <img src="{{ asset('images/BukSU-logo.png') }}" alt="BukSU Logo" class="h-16 w-auto drop-shadow-md transition-transform hover:scale-110 duration-300">
            <div class="h-12 w-px bg-base-content/20"></div>
            <img src="{{ asset('images/COT-logo.png') }}" alt="COT Logo" class="h-16 w-auto drop-shadow-md transition-transform hover:scale-110 duration-300">
        </div>
        <div class="space-y-1">
            <p class="font-bold text-xl tracking-tight">DCMS - Dental Clinic Management System</p>
            <p class="text-base-content/70">
                Made by <span class="font-semibold text-primary">Bukidnon State University</span> 
                of <span class="font-semibold text-secondary">College of Technologies</span> 
                BSIT - Student.
            </p>
        </div>
        <p class="text-xs opacity-50">© {{ date('Y') }} - All rights reserved</p>
    </aside>
    <nav>
        <div class="grid grid-flow-col gap-6">
            <a href="#" class="link link-hover text-sm font-medium opacity-70 hover:opacity-100">Terms of Service</a>
            <a href="#" class="link link-hover text-sm font-medium opacity-70 hover:opacity-100">Privacy Policy</a>
            <a href="#about" class="link link-hover text-sm font-medium opacity-70 hover:opacity-100">About Us</a>
        </div>
    </nav>
</footer>
