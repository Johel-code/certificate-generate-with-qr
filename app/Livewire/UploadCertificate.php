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
                'textX' => 50,
                'textY' => 50,
            ];
        }
    }

    public function generateCertificate(){

        $this->validate([
            'image' => 'required|image|max:1024',
        ]);

        $imagePath = $this->image->store('certificates', 'public');

        foreach ($this->csvData as $row) {

            $data = [
                'image' => $imagePath,
                'qrCode' => base64_encode(QrCode::format('png')->size(300)->generate(route('certificate.view', ['user' => $row[0]]))),
            ];

            $updatedConfigurations = [];

            foreach ($this->csvHeaders as $key => $header) {
                // $cleanUserName = trim($row[$key], "\xEF\xBB\xBF");
                // $text = preg_replace('/[^A-Za-z0-9\-]/', '', $cleanUserName);
                $updatedConfigurations[$header] = [
                    'text' => $row[$key],
                    'textSize' => $this->fieldsConfigurations[$header]['textSize'],
                    'textColor' => $this->fieldsConfigurations[$header]['textColor'],
                    'fontFamily' => $this->fieldsConfigurations[$header]['fontFamily'],
                    'textX' => $this->fieldsConfigurations[$header]['textX'],
                    'textY' => $this->fieldsConfigurations[$header]['textY'],
                ];
            }

            $data['fieldsConfigurations'] = $updatedConfigurations;

            return redirect()->route('certificate.preview', ['data' => $data]);

            // // Guardar el PDF
            // $fileName = 'certificates/' . preg_replace('/[^A-Za-z0-9\-]/', '', $cleanUserName) . '.pdf';
            // Storage::disk('public')->put($fileName, $pdf->output());
        }

        session()->flash('message', 'Certificados generados con éxito.');
    }

    public function render()
    {
        return view('livewire.upload-certificate');
    }

}
