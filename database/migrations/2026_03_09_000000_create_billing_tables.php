<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Invoices Collection (Tenant DB)
        Schema::connection('mongodb')->create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index();
            $table->string('patient_id')->index();
            $table->string('appointment_id')->nullable()->index();
            $table->string('invoice_number')->unique();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2);
            $table->string('status')->default('Unpaid'); // Unpaid, Partial, Paid, Cancelled
            $table->text('notes')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Invoice Items Collection (Tenant DB)
        Schema::connection('mongodb')->create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index();
            $table->string('invoice_id')->index();
            $table->string('service_id')->nullable()->index();
            $table->string('service_name');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });

        // Patient Payments Collection (Tenant DB) - Re-using 'payments' collection name for the new model
        // We already deleted the old migration/model for central payments if we were using it for tenant,
        // but here we are explicitly defining it for the tenant connection.
        if (Schema::connection('mongodb')->hasTable('payments')) {
             // In case it exists from some other run
             Schema::connection('mongodb')->table('payments', function (Blueprint $table) {
                $table->string('invoice_id')->index()->change();
                $table->string('patient_id')->index()->change();
                $table->decimal('amount_paid', 10, 2)->change();
             });
        } else {
            Schema::connection('mongodb')->create('payments', function (Blueprint $table) {
                $table->id();
                $table->string('tenant_id')->index();
                $table->string('invoice_id')->index();
                $table->string('patient_id')->index();
                $table->decimal('amount_paid', 10, 2);
                $table->string('payment_method'); // Cash, GCash, Card, etc.
                $table->timestamp('transaction_date');
                $table->string('reference_number')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('invoices');
        Schema::connection('mongodb')->dropIfExists('invoice_items');
        Schema::connection('mongodb')->dropIfExists('payments');
    }
};
