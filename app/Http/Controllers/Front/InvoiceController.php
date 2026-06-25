<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function download(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $pdf = Pdf::loadView(
            'invoice.pdf',
            compact('order')
        );

        return $pdf->download(
            $order->invoice_number . '.pdf'
        );
    }
}