<?php

namespace App\Http\Controllers;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;

class OrderPdfController extends Controller
{
    
    public function __invoke(Order $order)
    {
        // Generate PDF content using the 'pdf.blade.php' view
        $pdf = PDF::loadView('pdf', ['record' => $order]);

        // Define the file name with the restaurant name, order number, and PDF extension
        $fileName = $order->order_number . '.pdf';

        // Return the PDF as a response with the defined file name
        return Response::make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }


}
