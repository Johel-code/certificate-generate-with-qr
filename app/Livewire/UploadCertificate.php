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

    public function save(){

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
