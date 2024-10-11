<?php

namespace App\Livewire;

use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UploadCertificate extends Component
{

    use WithFileUploads;

    public $image; // Para almacenar la imagen subida
    public $csvData = []; // Almacenar el contenido del CSV
    public $csvHeaders = []; // Almacenar los encabezados del CSV
    public $selectedField; // Campo seleccionado para configurar
    public $fieldsConfigurations = []; // Almacenar las configuraciones de cada campo
    public $csv;
    public $textSize;
    public $textColor;
    public $fontFamily;
    public $textX;
    public $textY;

    public function updateFieldConfiguration($fieldId, array $data)
    {
        if (!empty($this->selectedField)) {

            $this->fieldsConfigurations[$fieldId] = array_merge($this->fieldsConfigurations[$fieldId], $data);
        }
    }

    public function updatedSelectedField()
    {
        // Actualiza las propiedades locales de Alpine.js
        $this->textSize = $this->fieldsConfigurations[$this->selectedField]['textSize'] ?? null;
        $this->textColor = $this->fieldsConfigurations[$this->selectedField]['textColor'] ?? null;
        $this->fontFamily = $this->fieldsConfigurations[$this->selectedField]['fontFamily'] ?? null;
        $this->textX = $this->fieldsConfigurations[$this->selectedField]['textX'] ?? null;
        $this->textY = $this->fieldsConfigurations[$this->selectedField]['textY'] ?? null;
        // ... y así sucesivamente para las demás propiedades
    }

    // Cargar y procesar el archivo CSV
    public function updatedCsv()
    {
        $this->validate([
            'csv' => 'required|mimes:csv,txt|max:1024',
        ]);

        // Leer el archivo CSV
        $path = $this->csv->getRealPath();
        $data = array_map('str_getcsv', file($path));

        // Guardar los encabezados (primera fila del CSV)
        $this->csvHeaders = array_shift($data);

        // Guardar el resto del contenido del CSV (los valores)
        $this->csvData = $data;
        foreach ($this->csvHeaders as $header) {
            $this->fieldsConfigurations[$header] = [
                'text' => $header,  // Mostrar el texto del header
                'textSize' => 16,
                'textColor' => '#000000',
                'fontFamily' => 'Arial',
                'textX' => 50,
                'textY' => 50,
            ];
        }
        // dd($this->fieldsConfigurations);
    }

    // public function updateFieldConfiguration($config)
    // {
    //     // Asegúrate de que el campo seleccionado tiene una configuración independiente
    //     if ($this->selectedField) {
    //         $this->fieldsConfigurations[$this->selectedField] = $config;
    //         dd($config);
    //     }
    // }

    // public function generateCertificate(){

    //     $this->validate([
    //         'image' => 'required|image|max:1024',
    //     ]);

    //     // Procesar el archivo CSV para obtener la lista de nombres de usuarios
    //     $csvPath = $this->csv->store('csv_files', 'public');
    //     $csvFile = fopen(storage_path('app/public/' . $csvPath), 'r');
    //     $users = [];

    //     while (($data = fgetcsv($csvFile, 1000, ',')) !== false) {
    //         $userName = trim($data[0]); // Eliminar espacios en blanco antes y después del nombre
    //         if (!empty($userName)) { // Solo agrega el nombre si no está vacío
    //             $users[] = $userName;
    //         }
    //     }

    //     fclose($csvFile);

    //     // Iterar sobre los nombres de los usuarios y generar un PDF para cada uno
    //     foreach ($users as $user) {
    //         $this->generateCertificateForUser($user);
    //     }

    //     session()->flash('message', 'Certificados generados con éxito.');

    //     // return response()->streamDownload(function () use ($pdf) {
    //     //     echo $pdf->stream();
    //     // }, 'name.pdf');
    // }

    // public function generateCertificateForUser($userName)
    // {
    //     $cleanUserName = trim($userName, "\xEF\xBB\xBF");

    //     $imagePath = $this->image->store('certificates', 'public');

    //     // Generar la URL del certificado
    //     $certificateUrl = route('certificate.view', ['user' => $cleanUserName]);

    //     // Generar el QR Code con la URL
    //     $qrCode = base64_encode(QrCode::format('png')->size(300)->generate($certificateUrl));

    //     $data = [
    //         'image' => $imagePath,
    //         'text' => $cleanUserName,
    //         'textX' => $this->textX,
    //         'textY' => $this->textY,
    //         'textSize' => $this->textSize,
    //         'textColor' => $this->textColor,
    //         'fontFamily' => $this->fontFamily,
    //         'qrCode' => $qrCode
    //     ];

    //     $pdf = Pdf::loadView('pdf.certificate', $data)
    //                 ->setPaper('A4', 'landscape')
    //                 ->setOption('margin', '0');

    //     // Guardar cada PDF en una carpeta de certificados con el nombre del usuario

    //     $pdfOutput = $pdf->output();
    //     $fileName = 'certificates/' . preg_replace('/[^A-Za-z0-9\-]/', '', $cleanUserName) . '.pdf';
    //     Storage::disk('public')->put($fileName, $pdfOutput);


    // }

    public function render()
    {
        return view('livewire.upload-certificate');
    }

}
