<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('tenant_access_error')): ?>
<dialog id="security_alert_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box border-2 border-error/20 shadow-2xl">
        <div class="flex flex-col items-center text-center p-2">
            <!-- Warning Icon with Animation -->
            <div class="w-16 h-16 bg-error/10 text-error rounded-full flex items-center justify-center mb-4 animate-pulse">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            
            <h3 class="font-bold text-2xl text-error mb-2">Access Denied</h3>
            <p class="text-base-content/70 text-lg leading-relaxed">
                <?php echo e(session('tenant_access_error')); ?>

            </p>
            
            <div class="divider my-4"></div>
            
            <p class="text-sm opacity-50 italic">
                Your account is isolated to your registered clinic for security purposes.
            </p>

            <div class="modal-action w-full">
                <form method="dialog" class="w-full">
                    <button class="btn btn-error btn-outline w-full hover:scale-x-105 transition-transform">
                        Got it, thanks!
                    </button>
                </form>
            </div>
        </div>
    </div>
</dialog>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('security_alert_modal');
        if (modal) {
            modal.showModal();
        }
    });
</script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/tenant/components/security-modal.blade.php ENDPATH**/ ?>