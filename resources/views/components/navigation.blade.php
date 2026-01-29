<nav class="navbar bg-base-100/80 backdrop-blur-md sticky top-0 z-50 border-b border-base-200">
    <div class="navbar-start">
        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
            </div>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="#pricing">Pricing</a></li>
                <li><a href="#">About</a></li>
                @auth
                    @if(!auth()->user()->is_system_admin)
                        <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">Logout</a></li>
                        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                    @endif
                @else
                    <li><a href="{{ route('admin.login') }}">Login</a></li>
                @endauth
            </ul>
        </div>
        <a class="btn btn-ghost text-xl gap-2" href="{{ route('home') }}">
            @if(file_exists(public_path('images/dcms-logo.png')))
                <img src="{{ asset('images/dcms-logo.png') }}" alt="DCMS Logo" class="h-8 w-auto">
            @else
                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
                <span class="font-bold">DCMS</span>
            @endif
        </a>
    </div>
    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1 font-medium">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="#features">Features</a></li>
            <li><a href="#pricing">Pricing</a></li>
            <li><a href="#">About</a></li>
        </ul>
    </div>
    <div class="navbar-end">
         @auth
            @if(!auth()->user()->is_system_admin)
                <a href="{{ route('logout') }}" class="btn btn-ghost" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
            @else
                 <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Dashboard</a>
            @endif
        @else
            <a href="{{ route('admin.login') }}" class="btn btn-primary px-8">Login</a>
        @endauth
    </div>
</nav>
