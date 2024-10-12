<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function preview(Request $request)
    {
        $data = $request->all();

        // Retornar la vista del certificado en el navegador
        $pdf = Pdf::loadView('pdf.certificate', compact('data'))
                  ->setPaper('A4', 'landscape')
                  ->setOption('margin', '0');

        return $pdf->stream('certificate.pdf'); // Para previsualizar el PDF en el navegador
    }
}
