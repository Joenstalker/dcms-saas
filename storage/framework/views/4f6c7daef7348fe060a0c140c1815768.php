<div id="recaptchaModal" data-turbo-permanent class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-sm w-full transform transition-all duration-500 scale-95 opacity-0 active-modal:scale-100 active-modal:opacity-100">
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-primary/10 rounded-full mb-4">
                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase italic">Security Check</h3>
            <p class="text-sm text-gray-500 mt-2">Please verify that you are not a robot to continue.</p>
        </div>

        <div class="flex justify-center mb-6">
            <div id="recaptcha-widget"></div>
        </div>

        <button type="button" onclick="closeRecaptchaModal()" class="btn btn-ghost btn-sm w-full text-gray-400 hover:text-gray-600 uppercase tracking-widest font-black text-[10px]">
            Cancel
        </button>
    </div>
</div>

<style>
    #recaptchaModal.active {
        display: flex;
    }
    #recaptchaModal.active > div {
        transform: scale(1);
        opacity: 1;
    }
</style>

<script>
    if (typeof window.recaptchaWidgetId === 'undefined') {
        window.recaptchaWidgetId = undefined;
    }

    if (typeof window.openRecaptchaModal === 'undefined') {
        window.openRecaptchaModal = function(callback) {
            const modal = document.getElementById('recaptchaModal');
            if (!modal) return;
            
            modal.classList.remove('hidden');
            setTimeout(() => modal.classList.add('active'), 10);

            if (typeof grecaptcha !== 'undefined') {
                if (window.recaptchaWidgetId === undefined) {
                    window.recaptchaWidgetId = grecaptcha.render('recaptcha-widget', {
                        'sitekey': '<?php echo e(config('services.recaptcha.site_key')); ?>',
                        'callback': (token) => {
                            window.closeRecaptchaModal();
                            callback(token);
                        }
                    });
                } else {
                    grecaptcha.reset(window.recaptchaWidgetId);
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'reCAPTCHA failed to load. Please check your internet connection.'
                });
                window.closeRecaptchaModal();
            }
        };
    }

    if (typeof window.closeRecaptchaModal === 'undefined') {
        window.closeRecaptchaModal = function() {
            const modal = document.getElementById('recaptchaModal');
            if (!modal) return;
            modal.classList.remove('active');
            setTimeout(() => modal.classList.add('hidden'), 300);
        };
    }
</script>
<?php /**PATH D:\dentistmng\dcms-saas\resources\views/components/recaptcha-modal.blade.php ENDPATH**/ ?>