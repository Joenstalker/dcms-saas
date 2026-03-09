<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:owner,assistant']);
    }

    /**
     * Display a list of invoices.
     */
    public function index(Tenant $tenant): View
    {
        $invoices = Invoice::with('patient')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('tenant.billing.index', compact('tenant', 'invoices'));
    }

    /**
     * Show POS interface for a specific appointment or new invoice.
     */
    public function create(Request $request, Tenant $tenant): View
    {
        $appointment = null;
        if ($request->has('appointment_id')) {
            $appointment = Appointment::with('patient')->findOrFail($request->appointment_id);
        }

        $patients = Patient::orderBy('last_name')->get();
        $services = Service::where('is_active', true)->get();

        return view('tenant.billing.create', compact('tenant', 'appointment', 'patients', 'services'));
    }

    /**
     * Store a new invoice (Finalize Checkout).
     */
    public function store(Request $request, Tenant $tenant)
    {
        $request->validate([
            'patient_id' => 'required|string',
            'appointment_id' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|in:Cash,GCash,Card,Bank Transfer',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $invoiceNumber = $this->generateInvoiceNumber($tenant);

            $totalAmount = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $service = Service::findOrFail($item['service_id']);
                $subtotal = $service->amount * $item['quantity'];
                $totalAmount += $subtotal;

                $itemsData[] = [
                    'tenant_id' => $tenant->id,
                    'service_id' => $service->id,
                    'service_name' => $service->name,
                    'quantity' => (int) $item['quantity'],
                    'unit_price' => (float) $service->amount,
                    'subtotal' => (float) $subtotal,
                ];
            }

            $discount = (float) ($request->discount ?? 0);
            $grandTotal = $totalAmount - $discount;

            $invoice = Invoice::create([
                'tenant_id' => $tenant->id,
                'patient_id' => $request->patient_id,
                'appointment_id' => $request->appointment_id,
                'invoice_number' => $invoiceNumber,
                'total_amount' => $totalAmount,
                'discount_amount' => $discount,
                'grand_total' => $grandTotal,
                'status' => $request->payment_method ? 'Paid' : 'Unpaid',
                'paid_at' => $request->payment_method ? now() : null,
                'notes' => $request->notes,
            ]);

            foreach ($itemsData as $item) {
                $item['invoice_id'] = $invoice->id;
                InvoiceItem::create($item);
            }

            // Record payment if method provided
            if ($request->payment_method) {
                Payment::create([
                    'tenant_id' => $tenant->id,
                    'invoice_id' => $invoice->id,
                    'patient_id' => $request->patient_id,
                    'amount_paid' => $grandTotal,
                    'payment_method' => $request->payment_method,
                    'transaction_date' => now(),
                    'notes' => 'Automatic payment from POS creation.',
                ]);
            }

            // Update patient balance
            $invoice->patient->refresh();
            $invoice->patient->updateBalance();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice finalized' . ($request->payment_method ? ' and payment recorded ' : ' ') . 'successfully!',
                'invoice_id' => $invoice->id,
                'redirect' => route('tenant.billing.show', [$tenant->slug, $invoice->id])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Invoice creation failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => 'Failed to finalize invoice: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show invoice details (Printable View).
     */
    public function show(Tenant $tenant, Invoice $invoice): View
    {
        $invoice->load(['patient', 'items', 'payments']);
        return view('tenant.billing.invoice', compact('tenant', 'invoice'));
    }

    /**
     * Record a payment against an invoice.
     */
    public function recordPayment(Request $request, Tenant $tenant, Invoice $invoice)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|in:Cash,GCash,Card,Check,Bank Transfer',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $amountDue = $invoice->getAmountDue();
        if ($request->amount_paid > $amountDue + 0.01) { // 0.01 for floating point safety
             return back()->with('error', 'Payment amount cannot exceed the balance due.');
        }

        Payment::create([
            'tenant_id' => $tenant->id,
            'invoice_id' => $invoice->id,
            'patient_id' => $invoice->patient_id,
            'amount_paid' => $request->amount_paid,
            'payment_method' => $request->payment_method,
            'transaction_date' => now(),
            'reference_number' => $request->reference_number,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Payment recorded successfully!');
    }

    /**
     * Generate a unique invoice number.
     * CLINIC-YEAR-COUNT
     */
    protected function generateInvoiceNumber(Tenant $tenant): string
    {
        $year = date('Y');
        $prefix = strtoupper(str_replace(' ', '', $tenant->slug));
        
        $lastInvoice = Invoice::where('tenant_id', $tenant->id)
            ->where('invoice_number', 'like', "{$prefix}-{$year}-%")
            ->orderBy('created_at', 'desc')
            ->first();

        $count = 1;
        if ($lastInvoice) {
            $parts = explode('-', $lastInvoice->invoice_number);
            $count = (int) end($parts) + 1;
        }

        return "{$prefix}-{$year}-" . str_pad((string)$count, 4, '0', STR_PAD_LEFT);
    }
}
