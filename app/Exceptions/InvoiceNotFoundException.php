<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class InvoiceNotFoundException extends Exception
{
    public function report()
    {
        Log::warning($this->getMessage());
    }

    public function render()
    {
        return response()->json([
            'error' => 'Invoice not found.',
        ], 404);
    }
}
