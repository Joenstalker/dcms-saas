<div class="flex items-center justify-between mb-6">
    <h3 class="text-lg font-bold flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Medical History & Clinical Notes
    </h3>
    <div class="flex items-center gap-3">
        <button onclick="update_medical_modal.showModal()" class="btn btn-primary btn-xs rounded-lg px-4 h-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Update Notes
        </button>
        <div class="flex flex-col items-end">
            <span class="badge badge-outline badge-sm text-[10px] opacity-70">Source: {{ $source ?? 'Face-to-Face' }}</span>
            <span class="text-[10px] opacity-50 font-bold mt-0.5">{{ $entry_date ?? now()->format('M d, Y') }}</span>
        </div>
    </div>
</div>

<div class="space-y-4">
    <div class="bg-base-200/50 p-6 rounded-2xl min-h-[150px] border border-base-200">
        <p class="text-sm leading-relaxed text-base-content/80">
            {{ $patient->medical_history ?? 'No clinical records found for this patient. Ensure all medical documentation is updated during the next consultation.' }}
        </p>
    </div>
    
    @if(isset($clinical_notes) && $clinical_notes->isNotEmpty())
    <div class="divider text-[10px] uppercase opacity-40 font-bold tracking-widest">Previous Entries</div>
    <div class="space-y-3">
        @foreach($clinical_notes as $note)
        <div class="p-4 rounded-xl border border-base-200 bg-base-50 flex flex-col gap-1">
            <div class="flex justify-between items-start">
                <span class="text-[10px] font-bold opacity-40 uppercase">{{ $note->created_at->format('M d, Y') }}</span>
                <span class="badge badge-ghost badge-xs">{{ $note->type ?? 'General' }}</span>
            </div>
            <p class="text-sm opacity-80">{{ $note->content }}</p>
        </div>
        @endforeach
    </div>
    @endif
</div>
