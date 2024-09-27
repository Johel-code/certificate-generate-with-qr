<?php

namespace App\Livewire;

use App\Imports\RecipientsImport;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Uploadusers extends Component
{
    use WithFileUploads;

    public $file;

    public function import()
    {
        // $this->validate([
        //     'file' => 'required|mines:csv,xlsx'
        // ]);

        Excel::import(new RecipientsImport, $this->file);

        session()->flash('message', 'Usuarios importados correctamente.');
    }

    public function render()
    {
        return view('livewire.uploadusers');
    }
}
