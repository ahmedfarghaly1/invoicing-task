<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Http\Requests\CreateInvoiceRequest;
use App\Services\InvoiceService;

class InvoiceController extends Controller
{
    private InvoiceService $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * Create a new invoice.
     */
    public function create(CreateInvoiceRequest $request)
    {
        $validated = $request->validated();
        $result = $this->invoiceService->generateInvoice($validated);
        return response()->json($result['data'], $result['status']);
    }

    /**
     * Show an invoice's details.
     */
    public function show($id)
    {
        $invoice = Invoice::with('customer', 'items.user')->findOrFail($id);
        return new InvoiceResource($invoice);
    }
}
