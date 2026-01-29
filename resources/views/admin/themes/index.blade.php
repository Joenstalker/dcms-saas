@extends('layouts.admin')

@section('page-title', 'Custom Themes')

@section('content')
<div class="flex flex-col gap-6">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">Custom Themes</h1>
            <p class="text-sm text-base-content/70 mt-1">Manage system-wide custom color palettes</p>
        </div>
        <a href="{{ route('admin.themes.builder') }}" class="btn btn-primary gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Create Theme
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($themes as $theme)
            <div class="card bg-base-100 shadow-xl border border-base-300 group hover:border-primary/50 transition-all duration-300">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="card-title text-lg">{{ $theme->name }}</h3>
                        <div class="flex gap-1">
                            <span class="w-4 h-4 rounded-full" style="background-color: {{ $theme->colors['primary'] }}"></span>
                            <span class="w-4 h-4 rounded-full" style="background-color: {{ $theme->colors['secondary'] }}"></span>
                            <span class="w-4 h-4 rounded-full" style="background-color: {{ $theme->colors['accent'] }}"></span>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-2 mb-6">
                        @foreach(['primary', 'secondary', 'accent', 'neutral', 'base-100'] as $key)
                            <div class="flex flex-col items-center gap-1">
                                <div class="w-10 h-10 rounded-lg border border-base-300 shadow-sm" style="background-color: {{ $theme->colors[$key] }}"></div>
                                <span class="text-[10px] uppercase font-bold opacity-40">{{ str_replace('base-', '', $key) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-actions justify-end pt-4 border-t border-base-200">
                        <form action="{{ route('admin.themes.destroy', $theme) }}" method="POST" onsubmit="return confirm('Delete this theme?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-ghost btn-sm text-error hover:bg-error/10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-base-100 rounded-2xl border-2 border-dashed border-base-300 flex flex-col items-center justify-center text-center">
                <div class="bg-base-200 p-6 rounded-full mb-4">
                    <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                </div>
                <h3 class="text-xl font-bold">No Custom Themes Yet</h3>
                <p class="text-base-content/60 max-w-sm mt-2">Build your first custom color palette to personalize the system experience.</p>
                <a href="{{ route('admin.themes.builder') }}" class="btn btn-primary mt-6">Start Building</a>
            </div>
        @endforelse
    </div>
</div>
@endsection
