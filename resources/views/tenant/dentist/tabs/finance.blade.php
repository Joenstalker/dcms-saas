<div x-show="activeTab === 'finance'" class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold">Financial Overview</h2>
        <div class="join">
            <button class="btn btn-sm join-item">Day</button>
            <button class="btn btn-sm join-item btn-active">Week</button>
            <button class="btn btn-sm join-item">Month</button>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="stat-title text-xs font-bold uppercase">Income Today</div>
                        <div class="stat-value text-2xl text-primary">₱{{ number_format($stats['income_today'], 2) }}</div>
                    </div>
                    <div class="badge badge-success badge-sm text-[10px]">+12%</div>
                </div>
                <div class="text-[10px] opacity-50 mt-2">Vs. yesterday: ₱2,100.00</div>
            </div>
        </div>
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="stat-title text-xs font-bold uppercase">This Week</div>
                        <div class="stat-value text-2xl text-secondary">₱{{ number_format($stats['income_weekly'], 2) }}</div>
                    </div>
                    <div class="badge badge-info badge-sm text-[10px]">On Track</div>
                </div>
                <div class="text-[10px] opacity-50 mt-2">Target: ₱15,000.00</div>
            </div>
        </div>
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="stat-title text-xs font-bold uppercase">This Month</div>
                        <div class="stat-value text-2xl text-success">₱{{ number_format($stats['income_monthly'], 2) }}</div>
                    </div>
                    <div class="badge badge-warning badge-sm text-[10px]">High</div>
                </div>
                <div class="text-[10px] opacity-50 mt-2">Best month this year</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart Placeholder -->
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <h3 class="font-bold text-sm mb-4">Revenue Trend (Last 7 Days)</h3>
                <div class="h-48 bg-base-200/30 rounded flex items-end justify-around p-4 gap-2">
                    @foreach([40, 60, 35, 90, 55, 75, 80] as $height)
                    <div class="w-full bg-primary/40 rounded-t hover:bg-primary transition-all cursor-pointer group relative" style="height: {{ $height }}%">
                        <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-neutral text-neutral-content text-[8px] px-1 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">₱{{ $height * 100 }}</div>
                    </div>
                    @endforeach
                </div>
                <div class="flex justify-around mt-2">
                    @foreach(['M', 'T', 'W', 'T', 'F', 'S', 'S'] as $day)
                    <span class="text-[10px] opacity-50 font-bold">{{ $day }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body p-0">
                <div class="p-4 border-b border-base-200 flex justify-between items-center">
                    <h3 class="font-bold text-sm">Recent Transactions</h3>
                    <button class="btn btn-ghost btn-xs text-primary">View All</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-compact w-full text-xs">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Service</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>John Doe</td>
                                <td>Check-up</td>
                                <td class="font-bold text-success">₱500.00</td>
                            </tr>
                            <tr>
                                <td>Jane Smith</td>
                                <td>Extraction</td>
                                <td class="font-bold text-success">₱1,500.00</td>
                            </tr>
                            <tr>
                                <td>Robert Fox</td>
                                <td>Cleaning</td>
                                <td class="font-bold text-success">₱450.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
