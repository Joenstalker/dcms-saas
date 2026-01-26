<div x-show="activeTab === 'concern'" class="space-y-6">
    <h2 class="text-xl font-bold">Secure Communication</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 h-[500px]">
        <div class="col-span-1 bg-base-100 rounded-lg border border-base-200 overflow-hidden flex flex-col">
            <div class="p-4 border-b border-base-200 font-bold bg-base-200/20 text-xs uppercase tracking-wider">Contacts</div>
            <div class="flex-1 overflow-y-auto p-2 space-y-1">
                <button class="btn btn-ghost btn-block justify-start text-left h-auto py-3 px-2 normal-case gap-2 bg-base-200/50 border-l-2 border-primary">
                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-primary-content text-xs font-bold">O</div>
                    <div class="flex-1 min-w-0">
                        <div class="font-bold text-sm text-base-content truncate">Clinic Owner</div>
                        <div class="text-[10px] opacity-60">Online</div>
                    </div>
                </button>
                <button class="btn btn-ghost btn-block justify-start text-left h-auto py-3 px-2 normal-case gap-2">
                    <div class="w-8 h-8 rounded-full bg-secondary flex items-center justify-center text-secondary-content text-xs font-bold">A</div>
                    <div class="flex-1 min-w-0">
                        <div class="font-bold text-sm text-base-content truncate">Assistant Team</div>
                        <div class="text-[10px] opacity-60">Away</div>
                    </div>
                </button>
            </div>
        </div>
        <div class="col-span-3 bg-base-100 rounded-lg border border-base-200 flex flex-col">
            <div class="p-4 border-b border-base-200 flex justify-between items-center bg-base-200/20">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-primary-content text-xs font-bold">O</div>
                    <div class="font-bold">Clinic Owner</div>
                </div>
                <button class="btn btn-ghost btn-xs">View Profile</button>
            </div>
            <div class="flex-1 overflow-y-auto p-6 space-y-4">
                <div class="flex justify-start">
                    <div class="bg-base-200 p-3 rounded-lg rounded-tl-none max-w-[80%]">
                        <p class="text-sm">Hi Dr. {{ auth()->user()->name }}, the patient records for the 2 PM appointment are ready.</p>
                        <span class="text-[9px] opacity-50 mt-1 block text-right">09:15 AM</span>
                    </div>
                </div>
                <div class="flex justify-end">
                    <div class="bg-primary text-primary-content p-3 rounded-lg rounded-tr-none max-w-[80%]">
                        <p class="text-sm">Great, thank you! Please make sure the X-rays are uploaded to the chart.</p>
                        <span class="text-[9px] opacity-70 mt-1 block text-right">09:20 AM</span>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-base-200">
                <div class="join w-full">
                    <input class="input input-bordered join-item flex-1" placeholder="Type your message..."/>
                    <button class="btn btn-primary join-item">Send</button>
                </div>
            </div>
        </div>
    </div>
</div>
