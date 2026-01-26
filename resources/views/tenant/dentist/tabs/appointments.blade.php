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
                        @forelse($appointments as $appointment)
                        <div class="flex items-start gap-3 p-2 rounded-lg hover:bg-base-200/50 cursor-pointer transition-colors border border-transparent hover:border-base-300">
                            <div class="w-12 text-center">
                                <div class="text-xs font-bold text-primary">{{ $appointment->scheduled_at->format('h:i') }}</div>
                                <div class="text-[10px] opacity-60 uppercase">{{ $appointment->scheduled_at->format('A') }}</div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-xs truncate">{{ $appointment->patient->full_name }}</div>
                                <div class="text-[10px] opacity-60 truncate">Check-up</div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 opacity-50 text-xs italic">No appointments today</div>
                        @endforelse
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
                            <h3 class="font-bold">{{ now()->format('F Y') }}</h3>
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
                        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                        <div class="p-2 text-center text-[10px] font-bold uppercase opacity-50 border-r border-base-200 last:border-0">{{ $day }}</div>
                        @endforeach
                    </div>
                    <div class="grid grid-cols-7 grid-rows-5 h-[500px]">
                        @for ($i = 1; $i <= 35; $i++)
                        @php
                            $dayNum = $i - 5; // Simplified logic to start month on Wednesday
                            $isToday = $dayNum == now()->day;
                            $hasAppointment = in_array($dayNum, [12, 15, 20, 27]);
                        @endphp
                        <div class="border-r border-b border-base-200 p-1 hover:bg-base-200/30 transition-colors relative group {{ $dayNum < 1 || $dayNum > 31 ? 'bg-base-200/10' : '' }}">
                            @if($dayNum > 0 && $dayNum <= 31)
                            <span class="text-[10px] font-medium {{ $isToday ? 'bg-primary text-primary-content w-5 h-5 flex items-center justify-center rounded-full' : 'opacity-60' }}">{{ $dayNum }}</span>
                            
                            @if($hasAppointment)
                            <div class="mt-1 space-y-1">
                                <div class="bg-primary/10 text-primary text-[8px] p-0.5 rounded truncate border border-primary/20">9:00 AM - Patient...</div>
                                @if($dayNum == 27)
                                <div class="bg-secondary/10 text-secondary text-[8px] p-0.5 rounded truncate border border-secondary/20">2:30 PM - Extraction</div>
                                @endif
                            </div>
                            @endif

                            <button class="absolute bottom-1 right-1 opacity-0 group-hover:opacity-100 btn btn-ghost btn-xs h-4 min-h-0 px-1 text-[8px]">+ Add</button>
                            @endif
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
