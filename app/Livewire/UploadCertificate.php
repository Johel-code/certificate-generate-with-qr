<?php

namespace App\Livewire;

use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadCertificate extends Component
{

    use WithFileUploads;

    public $image; // Para almacenar la imagen subida
    public $text = "Texto de ejemplo"; // Texto inicial
    public $textColor = "#000000"; // Color del texto
    public $fontFamily = 'Arial';
    public $textSize = 20; // Tamaño del texto
    public $textX = 50; // Posición X del texto
    public $textY = 50; // Posición Y del texto
    public $csv;

    public function generateCertificate(){

        $this->validate([
            'image' => 'required|image|max:1024',
        ]);

        // Procesar el archivo CSV para obtener la lista de nombres de usuarios
        $csvPath = $this->csv->store('csv_files', 'public');
        $csvFile = fopen(storage_path('app/public/' . $csvPath), 'r');
        $users = [];

        while (($data = fgetcsv($csvFile, 1000, ',')) !== false) {
            $userName = trim($data[0]); // Eliminar espacios en blanco antes y después del nombre
            if (!empty($userName)) { // Solo agrega el nombre si no está vacío
                $users[] = $userName;
            }
        }

        fclose($csvFile);

        // Iterar sobre los nombres de los usuarios y generar un PDF para cada uno
        foreach ($users as $user) {
            $this->generateCertificateForUser($user);
        }

        session()->flash('message', 'Certificados generados con éxito.');

        // return response()->streamDownload(function () use ($pdf) {
        //     echo $pdf->stream();
        // }, 'name.pdf');
    }

    public function generateCertificateForUser($userName)
    {
        $cleanUserName = trim($userName, "\xEF\xBB\xBF");

        $imagePath = $this->image->store('certificates', 'public');

        $data = [
            'image' => $imagePath,
            'text' => $cleanUserName,
            'textX' => $this->textX,
            'textY' => $this->textY,
            'textSize' => $this->textSize,
            'textColor' => $this->textColor,
            'fontFamily' => $this->fontFamily
        ];

        $pdf = Pdf::loadView('pdf.certificate', $data)
                    ->setPaper('A4', 'landscape')
                    ->setOption('margin', '0');

        // Guardar cada PDF en una carpeta de certificados con el nombre del usuario

        $pdfOutput = $pdf->output();
        $fileName = 'certificates/' . preg_replace('/[^A-Za-z0-9\-]/', '', $cleanUserName) . '.pdf';
        Storage::disk('public')->put($fileName, $pdfOutput);
    }

    public function render()
    {
        return view('livewire.upload-certificate');
    }

}
