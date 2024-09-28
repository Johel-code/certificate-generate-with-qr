<?php

namespace App\Livewire;

use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadCertificate extends Component
{

    use WithFileUploads;

    public $image; // Para almacenar la imagen subida
    public $text = "Texto de ejemplo"; // Texto inicial
    public $textColor = "#000000"; // Color del texto
    public $textSize = 20; // Tamaño del texto
    public $textX = 50; // Posición X del texto
    public $textY = 50; // Posición Y del texto

    public function generateCertificate(){
        // Validar que se haya subido una imagen
        $this->validate([
            'image' => 'required|image|max:1024', // 1MB máximo
        ]);

        // Almacenar la imagen subida temporalmente
        $imagePath = $this->image->store('certificates', 'public');

        // Preparar los datos para la vista del PDF
        $data = [
            'image' => $imagePath,
            'text' => $this->text,
            'textX' => $this->textX,
            'textY' => $this->textY,
            'textSize' => $this->textSize,
            'textColor' => $this->textColor,
        ];

        // $image = getimagesize(storage_path('app/public/'.$imagePath));
        // $pdfWidth = $image[0]; // Ancho en píxeles
        // $pdfHeight = $image[1]; // Alto en píxeles
        // $pdf = Pdf::loadView('pdf.certificate', $data)->setPaper([0, 0, $pdfWidth, $pdfHeight]);

        $pdf = Pdf::loadView('pdf.certificate', $data)
                    ->setPaper('A4', 'landscape')
                    ->setOption('margin', '0');

        // Descargar el PDF o almacenarlo
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'name.pdf');
    }

    public function render()
    {
        return view('livewire.upload-certificate');
    }

    public function updatedImage()
    {
        // Validación de la imagen
        $this->validate([
            'image' => 'image|max:1024', // 1MB máximo
        ]);
    }
}
