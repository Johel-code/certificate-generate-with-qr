<?php

namespace App\Imports;

use App\Models\Recipient;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RecipientsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (empty($row['dni']) || empty($row['nombre'])) {
            return null;
        }

        $existingUser = Recipient::where('dni', $row['dni'])->first();

        if($existingUser) {
            return null;
        }

        return new Recipient([
            'name' => $row['nombre'],
            'dni' => $row['dni'],
        ]);
    }
}
