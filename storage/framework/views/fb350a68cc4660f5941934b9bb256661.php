

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-base-content">Point of Sale</h1>
            <p class="text-sm text-base-content/60">Generate a new invoice for a patient.</p>
        </div>
        <a href="<?php echo e(route('tenant.billing.index', $tenant->slug)); ?>" class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Invoices
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- POS Interface (Left Side) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Patient Selection -->
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body p-6">
                    <h2 class="text-lg font-semibold mb-4">1. Select Patient</h2>
                    <div class="form-control w-full">
                        <select id="patient_id" class="select select-bordered w-full select-lg font-medium">
                            <option value="" disabled <?php echo e(!isset($appointment) ? 'selected' : ''); ?>>Search or select a patient...</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($patient->id); ?>" <?php echo e((isset($appointment) && $appointment->patient_id == $patient->id) ? 'selected' : ''); ?>>
                                    <?php echo e($patient->last_name); ?>, <?php echo e($patient->first_name); ?> (<?php echo e($patient->phone ?? 'No Phone'); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($appointment)): ?>
                            <input type="hidden" id="appointment_id" value="<?php echo e($appointment->id); ?>">
                            <div class="mt-2 text-xs text-primary flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Linked to appointment on <?php echo e($appointment->scheduled_at->format('M d, Y')); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Service Selection -->
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body p-6">
                    <h2 class="text-lg font-semibold mb-4">2. Add Services/Procedures</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6" id="service-grid">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="service-card border border-base-200 rounded-xl p-4 hover:border-primary hover:bg-primary/5 cursor-pointer transition-all group" 
                             onclick="addItem('<?php echo e($service->id); ?>', '<?php echo e($service->name); ?>', <?php echo e($service->amount); ?>)">
                            <div class="font-bold text-sm mb-1 group-hover:text-primary transition-colors"><?php echo e($service->name); ?></div>
                            <div class="text-lg font-bold">₱<?php echo e(number_format($service->amount, 2)); ?></div>
                            <div class="text-[10px] uppercase tracking-wider text-base-content/50 mt-1"><?php echo e($service->category); ?></div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="divider">OR SEARCH</div>

                    <div class="form-control w-full">
                        <div class="join">
                            <select id="service_search" class="select select-bordered join-item w-full">
                                <option value="" disabled selected>Select service from list...</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($service->id); ?>" data-name="<?php echo e($service->name); ?>" data-price="<?php echo e($service->amount); ?>">
                                        <?php echo e($service->name); ?> - ₱<?php echo e(number_format($service->amount, 2)); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                            <button class="btn btn-primary join-item" onclick="addSelectedService()">Add Item</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkout Column (Right Side) -->
        <div class="lg:col-span-1">
            <div class="card bg-base-100 shadow-md border border-base-200 sticky top-6">
                <div class="card-body p-6">
                    <h2 class="text-lg font-bold mb-4 flex justify-between items-center">
                        Order Summary
                        <span class="badge badge-ghost font-normal" id="item-count">0 items</span>
                    </h2>

                    <div class="max-h-[400px] overflow-y-auto mb-6 pr-2" id="invoice-items">
                        <!-- Items will be injected here -->
                        <div class="text-center py-10 text-base-content/40 italic text-sm" id="empty-state">
                            No items added yet.
                        </div>
                    </div>

                    <div class="space-y-3 border-t pt-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-base-content/60">Subtotal</span>
                            <span class="font-bold" id="subtotal-display">₱0.00</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-base-content/60">Discount</span>
                            <div class="flex items-center gap-2">
                                <span class="text-error font-bold">-₱</span>
                                <input type="number" id="discount_input" class="input input-bordered input-xs w-20 text-right" value="0" onchange="calculateTotals()">
                            </div>
                        </div>
                        <div class="divider my-1"></div>
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold">Total</span>
                            <span class="text-2xl font-black text-primary" id="grand-total-display">₱0.00</span>
                        </div>
                    </div>

                    <div class="divider">3. Payment & Settlement</div>

                    <div class="form-control mb-4">
                        <label class="label p-1">
                            <span class="label-text-alt font-bold uppercase opacity-50">Payment Method</span>
                        </label>
                        <select id="payment_method" class="select select-bordered w-full font-bold">
                            <option value="Cash" selected>Cash</option>
                            <option value="GCash">GCash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Card">Card</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label p-1"><span class="label-text-alt font-bold opacity-50 uppercase">Internal Notes</span></label>
                        <textarea id="invoice_notes" class="textarea textarea-bordered h-20 text-sm" placeholder="Any special instructions..."></textarea>
                    </div>

                    <div class="card-actions mt-6">
                        <button class="btn btn-primary btn-block btn-lg" id="finalize-btn" onclick="finalizeInvoice()" disabled>
                            Finalize Invoice
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Item Template (Hidden) -->
<template id="item-template">
    <div class="item-row flex justify-between items-start py-3 border-b border-base-100 last:border-0 group">
        <div class="flex-1">
            <div class="font-bold text-sm item-name"></div>
            <div class="text-xs text-base-content/50 flex items-center gap-2 mt-1">
                <span class="item-price-label"></span> x 
                <div class="flex items-center bg-base-200 rounded px-1">
                    <button class="text-primary hover:scale-125 transition-transform" onclick="decrementQty(this)">-</button>
                    <span class="mx-2 font-bold item-qty">1</span>
                    <button class="text-primary hover:scale-125 transition-transform" onclick="incrementQty(this)">+</button>
                </div>
            </div>
        </div>
        <div class="text-right">
            <div class="font-bold text-sm item-subtotal"></div>
            <button class="text-error text-[10px] opacity-0 group-hover:opacity-100 transition-opacity uppercase font-bold mt-1" onclick="removeItem(this)">Remove</button>
        </div>
    </div>
</template>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    let cart = [];

    function addItem(id, name, price) {
        const existingItem = cart.find(item => item.service_id === id);
        if (existingItem) {
            existingItem.quantity++;
        } else {
            cart.push({
                service_id: id,
                name: name,
                unit_price: price,
                quantity: 1
            });
        }
        renderCart();
    }

    function addSelectedService() {
        const select = document.getElementById('service_search');
        if (!select.value) return;
        
        const option = select.options[select.selectedIndex];
        addItem(select.value, option.dataset.name, parseFloat(option.dataset.price));
        select.selectedIndex = 0;
    }

    function incrementQty(btn) {
        const index = getBtnItemIndex(btn);
        cart[index].quantity++;
        renderCart();
    }

    function decrementQty(btn) {
        const index = getBtnItemIndex(btn);
        if (cart[index].quantity > 1) {
            cart[index].quantity--;
            renderCart();
        }
    }

    function removeItem(btn) {
        const index = getBtnItemIndex(btn);
        cart.splice(index, 1);
        renderCart();
    }

    function getBtnItemIndex(btn) {
        const row = btn.closest('.item-row');
        return Array.from(document.getElementById('invoice-items').children).indexOf(row);
    }

    function renderCart() {
        const container = document.getElementById('invoice-items');
        const emptyState = document.getElementById('empty-state');
        const template = document.getElementById('item-template');
        
        container.innerHTML = '';
        
        if (cart.length === 0) {
            container.appendChild(emptyState);
            document.getElementById('finalize-btn').disabled = true;
        } else {
            cart.forEach(item => {
                const clone = template.content.cloneNode(true);
                clone.querySelector('.item-name').textContent = item.name;
                clone.querySelector('.item-price-label').textContent = '₱' + item.unit_price.toLocaleString(undefined, {minimumFractionDigits: 2});
                clone.querySelector('.item-qty').textContent = item.quantity;
                clone.querySelector('.item-subtotal').textContent = '₱' + (item.unit_price * item.quantity).toLocaleString(undefined, {minimumFractionDigits: 2});
                container.appendChild(clone);
            });
            document.getElementById('finalize-btn').disabled = false;
        }

        document.getElementById('item-count').textContent = cart.length + (cart.length === 1 ? ' item' : ' items');
        calculateTotals();
    }

    function calculateTotals() {
        const subtotal = cart.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0);
        const discount = parseFloat(document.getElementById('discount_input').value) || 0;
        const grandTotal = Math.max(0, subtotal - discount);

        document.getElementById('subtotal-display').textContent = '₱' + subtotal.toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById('grand-total-display').textContent = '₱' + grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2});
    }

    async function finalizeInvoice() {
        const paymentMethod = document.getElementById('payment_method').value;
        const discount = document.getElementById('discount_input').value;
        const notes = document.getElementById('invoice_notes').value;

        if (!patientId) {
            Swal.fire({ icon: 'error', title: 'Oops...', text: 'Please select a patient.' });
            return;
        }

        const result = await Swal.fire({
            title: 'Finalize & Record Payment?',
            text: `This will create an invoice for ₱${(cart.reduce((s,i)=>s+(i.unit_price*i.quantity),0) - discount).toLocaleString()} via ${paymentMethod}.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Finalize',
            confirmButtonColor: 'var(--p)'
        });

        if (!result.isConfirmed) return;

        // UI Feedback
        const btn = document.getElementById('finalize-btn');
        btn.disabled = true;
        btn.innerHTML = '<span class="loading loading-spinner"></span> Finalizing...';

        try {
            const response = await fetch("<?php echo e(route('tenant.billing.store', $tenant->slug)); ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
                },
                body: JSON.stringify({
                    patient_id: patientId,
                    appointment_id: appointmentId,
                    items: cart,
                    discount: discount,
                    payment_method: paymentMethod,
                    notes: notes
                })
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Invoice Created!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = data.redirect;
                });
            } else {
                throw new Error(data.error || 'Failed to finalize invoice.');
            }
        } catch (error) {
            console.error(error);
            Swal.fire({ icon: 'error', title: 'Error', text: error.message });
            btn.disabled = false;
            btn.textContent = 'Finalize Invoice';
        }
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.tenant', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\dentistmng\dcms-saas\resources\views/tenant/billing/create.blade.php ENDPATH**/ ?>