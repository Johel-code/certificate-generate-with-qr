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
    public $customText= "";
    public $customTextWidth = 400;
    public $customTextHeight = 200;
    public $customTextX = 0;
    public $customTextY = 0;
    public $customTextSize = 22;
    public $customTextColor = '#000000';
    public $customFontFamily = 'Helvetica';
    public $alignment = 'center';
    public $opacity = 1;
    public $textAreaCounter = 1;

    public function mount()
    {
        // Configuración inicial del QR
        // $this->fieldsConfigurations['qrCode'] = [
        //     'qrSize' => 50,  // Tamaño del QR
        //     'qrX' => 50,  // Posición X del QR
        //     'qrY' => 50,  // Posición Y del QR
        // ];
        $this->fieldsConfigurations['textArea'] = [
            'type' => 'area',
            'text' => '',
            'textSize' => 50,  // Tamaño del QR
            'textColor' => 50,  // Posición X del QR
            'fontFamily' => 50,  // Posición Y del QR
            'textX' => 0,
            'textY' => 0,
            'width' => 400,
            'height' => 200,
            'alignment' => 'center',
            'label' => 'Área de texto'
        ];
    }

    public function addTextArea()
    {
        $fieldId = 'area_' . $this->textAreaCounter;

        // Configuración predeterminada para el área de texto
        $this->fieldsConfigurations[$fieldId] = [
            'type' => 'area',
            'text' => '',
            'textSize' => 16,
            'textColor' => '#000000',
            'fontFamily' => 'Arial',
            'textX' => 0,
            'textY' => 0,
            'width' => 200,   // Ancho predeterminado para el área de texto
            'height' => 100,  // Alto predeterminado para el área de texto
            'alignment' => 'center',
            'label' => 'Área de texto ' . $fieldId
        ];

        $this->textAreaCounter++;
        // Seleccionar automáticamente el nuevo campo para editar
        $this->selectedField = $fieldId;
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

        $this->customTextSize = $this->fieldsConfigurations[$this->selectedField]['textSize'] ?? null;
        $this->customTextColor = $this->fieldsConfigurations[$this->selectedField]['textColor'] ?? null;
        $this->customFontFamily = $this->fieldsConfigurations[$this->selectedField]['fontFamily'] ?? null;
        $this->alignment = $this->fieldsConfigurations[$this->selectedField]['alignment'] ?? null;
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
                'type' => 'text',
                'label' => $header
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

        foreach ($this->csvData as $row) {

            $templateText = nl2br(e(preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $this->customText)));

            $updatedConfigurations = [];
            foreach ($this->csvHeaders as $key => $header) {

                $templateText = str_replace("{{ $header }}", $row[$key], $templateText);

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
            $data = [
                'image' => $imagePath,
                'opacity' => $this->opacity,
                // 'qrCode' => base64_encode(QrCode::format('png')->size($this->fieldsConfigurations['qrCode']['qrSize'])->generate(route('certificate.view', ['user' => $row[0]]))),
                'fieldsConfigurations' => $updatedConfigurations,
                'customText' => $templateText,
                'alignment' => $this->alignment,
                'customTextWidth' => $this->customTextWidth,
                'customTextHeight' => $this->customTextHeight,
                'customTextX' => $this->customTextX,
                'customTextY' => $this->customTextY,
                'customTextSize' => $this->customTextSize,
                'customTextColor' => $this->customTextColor,
                'customFontFamily' => $this->customFontFamily,
                // 'qrX' => $this->fieldsConfigurations['qrCode']['qrX'],
                // 'qrY' => $this->fieldsConfigurations['qrCode']['qrY'],
            ];

            // Generar el PDF
            $pdf = Pdf::loadView('pdf.certificate', compact('data'))
                    ->setPaper('letter')
                    ->setOption('margin', '0')
                    ->setOption('dpi', 72);

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
