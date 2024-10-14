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

    public $image;
    public $csvData = [];
    public $csvHeaders = [];
    public $selectedField;
    public $fieldsConfigurations = [];
    public $csv;
    public $textSize;
    public $textColor;
    public $fontFamily;
    public $textX;
    public $textY;
    public $qrSize;
    public $qrX;
    public $qrY;

    public function mount()
    {
        // Configuración inicial del QR
        $this->fieldsConfigurations['qrCode'] = [
            'qrSize' => 50,  // Tamaño del QR
            'qrX' => 50,  // Posición X del QR
            'qrY' => 50,  // Posición Y del QR
        ];
    }

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
        $this->qrSize = $this->fieldsConfigurations[$this->selectedField]['qrSize'] ?? null;
        $this->qrX = $this->fieldsConfigurations[$this->selectedField]['qrX'] ?? null;
        $this->qrY = $this->fieldsConfigurations[$this->selectedField]['qrY'] ?? null;
    }

    public function updatedCsv()
    {
        $this->validate([
            'csv' => 'required|mimes:csv,txt|max:1024',
        ]);

        $path = $this->csv->getRealPath();
        $data = array_map('str_getcsv', file($path));

        $this->csvHeaders = array_shift($data);

        $this->csvData = $data;
        foreach ($this->csvHeaders as $header) {
            $this->fieldsConfigurations[$header] = [
                'text' => $header,
                'textSize' => 16,
                'textColor' => '#000000',
                'fontFamily' => 'Arial',
                'textX' => 150,
                'textY' => 50,
            ];
        }
    }

    public function generateCertificate()
    {
        $this->validate([
            'image' => 'required|image|max:1024',
        ]);

        $imagePath = $this->image->store('certificates', 'public');
        $zipFileName = 'certificates/certificates_' . time() . '.zip';
        $zip = new \ZipArchive();
        $zip->open(storage_path('app/public/' . $zipFileName), \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $data = [
            'qrX' => $this->fieldsConfigurations['qrCode']['qrX'],
            'qrY' => $this->fieldsConfigurations['qrCode']['qrY'],
        ];

        foreach ($this->csvData as $row) {
            $updatedConfigurations = [];
            foreach ($this->csvHeaders as $key => $header) {
                $updatedConfigurations[$header] = [
                    'text' => $row[$key],
                    'textSize' => $this->fieldsConfigurations[$header]['textSize'],
                    'textColor' => $this->fieldsConfigurations[$header]['textColor'],
                    'fontFamily' => $this->fieldsConfigurations[$header]['fontFamily'],
                    'textX' => $this->fieldsConfigurations[$header]['textX'],
                    'textY' => $this->fieldsConfigurations[$header]['textY'],
                ];
            }

            // Preparar los datos para el certificado
            $data += [
                'image' => $imagePath,
                'qrCode' => base64_encode(QrCode::format('png')->size($this->fieldsConfigurations['qrCode']['qrSize'])->generate(route('certificate.view', ['user' => $row[0]]))),
                'fieldsConfigurations' => $updatedConfigurations,
            ];

            // Generar el PDF
            $pdf = Pdf::loadView('pdf.certificate', compact('data'))
                    ->setPaper('A4', 'landscape')
                    ->setOption('margin', '0');

            // Guardar cada PDF individual en un archivo
            $fileName = 'certificates/' . preg_replace('/[^A-Za-z0-9\-]/', '', $row[0]) . '.pdf';
            Storage::disk('public')->put($fileName, $pdf->output());

            // Agregar el PDF al archivo ZIP
            $zip->addFile(storage_path('app/public/' . $fileName), basename($fileName));
        }

        // Cerrar el archivo ZIP
        $zip->close();

        // Descargar el archivo ZIP
        return response()->download(storage_path('app/public/' . $zipFileName))->deleteFileAfterSend(true);

        session()->flash('message', 'Certificados generados con éxito.');
    }


    public function render()
    {
        $qrCode = base64_encode(QrCode::format('png')->generate('Vista Previa QR'));
        return view('livewire.upload-certificate', compact('qrCode'));
    }

}
