<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    private $skip = 0; // Skip the first row (header row)

    public function model(array $row)
    {
        // Skip the header row
        if ($this->skip === 0) {
            $this->skip++;
            return null;
        }

        $username = $row[0];
        $nm_user = $row[1];
        $dob_user = $row[2];
        $mobile_user = $row[3];
        $kd_departemen = $row[4];
        $hak_akses = $row[5];
        $kd_cabang = $row[6];

        return new User([
            'username' => $username,
            'nm_user' => $nm_user,
            'dob_user' => $dob_user,
            'mobile_user' => $mobile_user,
            'kd_departemen' => $kd_departemen,
            'hak_akses' => $hak_akses,
            'kd_cabang' => $kd_cabang,
        ]);
    }
}
