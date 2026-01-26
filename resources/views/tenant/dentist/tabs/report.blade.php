<div x-show="activeTab === 'report'" class="space-y-6">
    <h2 class="text-xl font-bold">Clinic Reports</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="card bg-base-100 shadow-sm border border-base-200 hover:shadow-md transition-shadow cursor-pointer">
            <div class="card-body">
                <div class="w-12 h-12 rounded-lg bg-primary/10 text-primary flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                </div>
                <h3 class="font-bold">Treatment Analytics</h3>
                <p class="text-xs opacity-60">Analyze common procedures and patient demographics.</p>
            </div>
        </div>
        <div class="card bg-base-100 shadow-sm border border-base-200 hover:shadow-md transition-shadow cursor-pointer">
            <div class="card-body">
                <div class="w-12 h-12 rounded-lg bg-secondary/10 text-secondary flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="font-bold">Financial Statements</h3>
                <p class="text-xs opacity-60">Detailed breakdown of income, expenses, and profit.</p>
            </div>
        </div>
        <div class="card bg-base-100 shadow-sm border border-base-200 hover:shadow-md transition-shadow cursor-pointer">
            <div class="card-body">
                <div class="w-12 h-12 rounded-lg bg-success/10 text-success flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
                <h3 class="font-bold">Appointment Reports</h3>
                <p class="text-xs opacity-60">Attendance rates, cancellation reasons, and wait times.</p>
            </div>
        </div>
    </div>
</div>
